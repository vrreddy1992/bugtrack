<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Storage;

/**
 * Manage all file upload operations here
 *
 * @author Anil
 */
trait FileUploadTrait {

	/**
	 * Get storage path
	 *
	 * @access protected
	 * @author Anil
	 * @return void
	 */
	protected function traitGetStoragePath() {
		return config('custom.APP_STORAGE_PATH');
	}

	/**
	 * Get company directory
	 *
	 * @access protected
	 * @author Anil
	 * @return void
	 */
	protected function traitGetCompanyDirectory($company_id='') {
		if (empty($company_id)) {
			if (!empty($this->request->company_id)) {
				$company_id = $this->request->company_id;
			} else {
				$company_id = auth()->user()->company_id;
			}
		}
		$company_dir = config('custom.COMPANY_DIR_PREFIX').$company_id;

		// if company directory isn't created create now.
		createCompanyDirectory($company_id);

		return $company_dir;
	}

	/**
	 * validate file upload requirements
	 *
	 * @access protected
	 * @author Anil
	 * @return void
	 */
	protected function validateUploadRequirements() {
		if (empty($this->trait_upload_key)) {
			return $this->sendUploadFailResponse('File Atributes not defined.');
		}

		if (!empty($this->trait_upload_folder)) {
			$upload_folder = $this->trait_upload_folder_relative_path = $this->traitGetStoragePath(). $this->traitGetCompanyDirectory() . "/". $this->trait_upload_folder;
			$isDirCreated = Storage::makeDirectory($upload_folder);
		}

		if (empty($isDirCreated)) {
			return $this->sendUploadFailResponse('Unable to create directory.');
		}

		return true;
	}

	/**
	 * Upload file
	 *
	 * @access protected
	 * @author Anil
	 * @return void
	 */
	public function uploadFiles(Request $request) {
		$this->request = $request;
		if (empty($this->setAttributesFromCntrl)) {
			$this->setFileUploadAttributes();
		}

		$validateRequirements = $this->validateUploadRequirements();
		if ($validateRequirements != true) {
			return $validateRequirements;
		}

		$upload_key = $this->trait_upload_key;

		$uploadedFileDetailsArr = [];
		if ($request->hasFile($upload_key)) {
			$requestFileDetails = $request->file($upload_key);

			if (!is_array($requestFileDetails)) {
				$requestFileDetails = [$requestFileDetails];
			}

			foreach ($requestFileDetails as $key => $uploadFileObj) {
				$uploadResponse = $this->moveUploadedFileToTargerDir($uploadFileObj);

				if (!empty($uploadResponse['success'])) {
					$uploadedFileDetailsArr['success'][] = $uploadResponse;
				} else {
					$uploadedFileDetailsArr['fail'][] = $uploadResponse;
				}
			}
		} else {
			return array(
				'no_files_found' => true,
				'success' => 1
			);
		}

		$uploadedFileDetailsArr['upload_success'] = false;
		if (!empty($uploadedFileDetailsArr['success'])) {
			$uploadedFileDetailsArr['upload_success'] = true;
		} else {
			$uploadedFileDetailsArr['upload_error'] = 'Unable to upload files, This may be due to inavlid extension or file size exceeds the upload limit.';
		}

		return $uploadedFileDetailsArr;
	}

	/**
	 * Move uploaded file to target directory
	 *
	 * @access protected
	 * @author Anil
	 * @return Array
	 */
	protected function moveUploadedFileToTargerDir($uploadFileObj) {
		$upload_folder_path = $this->trait_upload_folder_relative_path;

		$extension = $uploadFileObj->extension();
		$mimetype = $uploadFileObj->getMimeType();
		$filesize = $uploadFileObj->getClientSize();

		$file_details = [];
		if ($uploadFileObj->isValid()) {
			if (!($this->traitIsValidExtension($extension))) {
				return $this->sendUploadFailResponse("File extension $extension not supported.");
			}

			if ($this->traitIsExceedingUploadLimit($filesize)) {
				return $this->sendUploadFailResponse('File exceeds the maximum upload limit.');
			}

			/*if (!($this->traitIsValidMimeType($mimetype))) {
				return $this->sendUploadFailResponse("Mimetype $mimetype isn't supported.");
			}*/

			$file_details['success'] = 1;
			$file_details['file_name'] = $uploadFileObj->getClientOriginalName();
			$file_details['hash_name'] = $file_details['unique_name'] = $uploadFileObj->hashname();

			if (!empty($this->includeMonthAndYearInDir)) {
				$year_month = date('Y_m_');

				$file_details['unique_name'] = $year_month.$file_details['unique_name'];
				$upload_folder_path = $this->createYearAndMonthDirectory($upload_folder_path, $year_month);
			}

			$uploadFileObj->storeAs($upload_folder_path, $file_details['hash_name']);
		} else {
			return $this->sendUploadFailResponse($uploadFileObj->getError());
		}

		return $file_details;
	}

	/**
	 * Create and year and month directory
	 *
	 * @access protected
	 * @author Anil
	 * @return void
	 */
	protected function createYearAndMonthDirectory($upload_folder_path = null, $year_month = null) {
		$year_month = str_replace('_', '/', $year_month);
		$upload_folder_path_inc_year = $upload_folder_path. '/' . $year_month;

		if (Storage::makeDirectory($upload_folder_path_inc_year)) {
			return $upload_folder_path_inc_year;
		}
	}

	/**
	 * Send upload failed response based on error message
	 *
	 * @access protected
	 * @author Anil
	 * @return Array
	 */
	protected function sendUploadFailResponse($error = null) {
		if (empty($error)) {
			return array();
		}

		return array(
			'success' => 0,
			'upload_success' => false,
			'error' => $error
		);
	}

	/**
	 * Check file is exceeding upload limit or not
	 *
	 * @access protected
	 * @author Anil
	 * @return Boolean true on success false on failure
	 */
	protected function traitIsExceedingUploadLimit($size = null) {
		if (!empty($this->triatMaxUploadLimit)) {
			$this->triatMaxUploadLimit = $this->getReadableBytes($this->triatMaxUploadLimit);
		} else {
			$this->triatMaxUploadLimit = env('MAX_FILE_UPLOAD_LIMIT');
		}

		// it's good for float comparisions
		return $size - $this->triatMaxUploadLimit > 0.0001 ? true : false;
	}

	/**
	 * Get Readable file bytes if size given like 25MB
	 *
	 * @access protected
	 * @return Integer
	 */
	protected function getReadableBytes($upload_limit = null) {
		if (is_numeric($upload_limit)) {
			return $upload_limit;
		}

		$upload_limit = strtoupper(trim($upload_limit));
		$byte_str = preg_replace("/[^A-Za-z]/", '', $upload_limit);
		$number = preg_replace("/[^0-9]/", '', $upload_limit);

		switch ($byte_str) {
			case "KB":
			case "K":
				return $number * 1024;
			case "MB":
			case "M":
				return $number * pow(1024,2);
			case "GB":
			case "G":
				return $number * pow(1024,3);
			case "TB":
			case "T":
				return $number * pow(1024,4);
			case "PB":
			case "P":
				return $number * pow(1024,5);
			default:
				return $upload_limit;
		}
	}

	/**
	 * Check the file extension is allowable file types or not
	 *
	 * @access protected
	 * @author Anil
	 * @return Boolean true on success false on failure
	 */
	protected function traitIsValidExtension($extension = null) {
		if (empty($extension)) {
			return false;
		}

		$extension = strtolower($extension);

		if (!empty($this->onlyAllowableExtensions)) {
			$allowable_extensions = $this->traitGetAllowableExtensions($this->onlyAllowableExtensions);
		} else {
			$allowable_extensions = $this->traitGetAllowableExtensions();
		}

		return in_array($extension, $allowable_extensions) ? true : false;
	}

	/**
	 * Check the file mimetype is allowable file mimetypes or not
	 *
	 * @access protected
	 * @author Anil
	 * @return Boolean true on success false on failure
	 */
	protected function traitIsValidMimeType($mimetype = null) {
		if (empty($mimetype)) {
			return false;
		}

		$mimetype = strtolower($mimetype);

		$allowable_mimetypes = $this->traitGetAllowableMimetypes();
		if (!empty($this->onlyAllowableMimetypes)) {
			$allowable_mimetypes = $this->onlyAllowableMimetypes;
		}

		return in_array($mimetype, $allowable_mimetypes) ? true : false;
	}

	/**
	 * Get allowable extensions
	 *
	 * @access protected
	 * @author Anil
	 * @return Array
	 */
	protected function traitGetAllowableExtensions($ext_str = null) {
		if (empty($ext_str)) {
			$allowable_file_types = env('ALLOWABLE_FILE_TYPES', '');
		} else {
			$allowable_file_types = $ext_str;
		}

		if (empty($allowable_file_types)) {
			return array();
		}

		$allowable_file_types = array_map(function($ext) {
			$ext = str_replace('.', '', $ext);
			return strtolower(trim($ext));
		}, explode(',', $allowable_file_types));

		return $allowable_file_types;
	}

	/**
	 * Get allowable mime types
	 *
	 * @access protected
	 * @author Anil
	 * @return void
	 */
	protected function traitGetAllowableMimetypes($mimetypes = null) {
		if (empty($mimetypes)) {
			$allowable_mime_types = env('ALLOWABLE_MIME_TYPES', arary());
		} else {
			$allowable_mime_types = $mimetypes;
		}

		if (empty($allowable_mime_types)) {
			return array();
		}

		$allowable_mime_types = array_map(function($mimetype) {
			return strtolower(trim($mimetype));
		}, explode(',', $allowable_mime_types));

		return $allowable_mime_types;
	}

	/**
	 * Every function which derives this class must implement this function
	 *
	 * Following properties can be set,
	 *
	 * includeMonthAndYearInDir => true or false, if you want to include month and year structure for directory.
	 * trait_upload_key => required
	 * trait_upload_folder => upload folder name
	 * onlyAllowableExtensions
	 * onlyAllowableMimetypes
	 *
	 * @access public
	 * @author Anil
	 * @return void
	 */
	abstract public function setFileUploadAttributes();

	/**
	 * Clone files
	 *
	 * @access protected
	 * @author Asheesh
	 * @return void
	 */

	public function cloneFiles($filesArray) {
		if (empty($this->setAttributesFromCntrl)) {
			$this->setFileUploadAttributes();
		}

		/*$validateRequirements = $this->validateUploadRequirements();
		if ($validateRequirements != true) {
			return $validateRequirements;
		}*/

		$uploadedFileDetailsArr = [];
		foreach ($filesArray as $key => $unique_name) {
			$uploadResponse = $this->copyFileToTargetDir($key, $unique_name);

			if (!empty($uploadResponse['success'])) {
				$uploadedFileDetailsArr['success'][] = $uploadResponse;
			} else {
				$uploadedFileDetailsArr['fail'][] = $uploadResponse;
			}
		}

		$uploadedFileDetailsArr['upload_success'] = false;
		if (!empty($uploadedFileDetailsArr['success'])) {
			$uploadedFileDetailsArr['upload_success'] = true;
		} else {
			$uploadedFileDetailsArr['upload_error'] = 'Unable to upload files, This may be due to inavlid extension or file size exceeds the upload limit.';
		}

		return $uploadedFileDetailsArr;
	}

	/**
	 * Copy file to target directory
	 *
	 * @access protected
	 * @author Asheesh
	 * @return Array
	 */
	protected function copyFileToTargetDir($doc_id, $unique_name) {
		$name_arr = explode('.', $unique_name);
		$ext = end($name_arr);
		$file_name = $name_arr[count($name_arr)-2];

		$changed_unique_name = str_replace('.'.$ext, '', $unique_name);
		$changed_unique_name = $changed_unique_name.time().'.'.$ext;

		$source_folder_path = getUploadedFileRelativePath($unique_name);
		$destination_folder_path = getUploadedFileRelativePath($changed_unique_name);

		//$destination_folder_path = str_replace('.'.$ext, '', $destination_folder_path);
		//$destination_folder_path = $destination_folder_path.time().'.'.$ext;


		$upload_folder = $this->traitGetStoragePath(). $this->traitGetCompanyDirectory() . "/". $this->trait_upload_folder;


		$source_folder_path = $upload_folder.'/'.$source_folder_path;
		$destination_folder_path = $upload_folder.'/'.$destination_folder_path;
		//print_r($source_folder_path);exit;
		$file_details = [];
		$file_details['success'] = 0;

		$job_doc_data  = \App\Models\JobDocument::select(['original_name'])->where('id',$doc_id)->first();

		$file_details['file_name'] = $job_doc_data['original_name'];
		$file_details['hash_name'] = $file_details['unique_name'] = $changed_unique_name;
		try{
			Storage::copy($source_folder_path, $destination_folder_path);
			$file_details['success'] = 1;
		} catch(Exception $e) {

		}

		return $file_details;
	}

	/**
	 * Upload Base64 Encoded files
	 *
	 * @access protected
	 * @author Asheesh
	 * @return void
	 */
	public function uploadEncodedFiles($files_to_parse, $to_path, $include_month_year=false) {
		$year_month = '';
		if($include_month_year) {
			$year_month = date('Y_m_');

			$to_path = $this->createYearAndMonthDirectory($to_path, $year_month);
		}
		$uploadedFileDetailsArr = [];
		if(!empty($files_to_parse)) {
	        foreach ($files_to_parse as $key => $file_to_parse) {
	            $file_contents = base64_decode($file_to_parse['data']);
	            $file_name = $file_to_parse['name'];
	            if(empty($file_to_parse['extension'])) {
	                $arr_file_ext = explode('.', $file_to_parse['name']);
	                $file_ext = end($arr_file_ext);
	            } else $file_ext = $file_to_parse['extension'];
	            $file_ext = strtolower($file_ext);

	            $unique_name = str_random(40).'.'.$file_ext;
	            $to_path = "$to_path/$unique_name";

	            try {
					Storage::put($to_path, $file_contents);
					$uploadedFileDetailsArr['success'][] = array('file_name'=>$file_name, 'unique_name'=>$year_month.$unique_name);
	            } catch (\Exception $e) {
	            	$uploadedFileDetailsArr['fail'][] = array('error'=>'Failed to save.');
	            }
	        }
	    } else{
	    	return array(
				'no_files_found' => true,
				'success' => 1
			);
	    }

	    $uploadedFileDetailsArr['upload_success'] = false;
		if (!empty($uploadedFileDetailsArr['success'])) {
			$uploadedFileDetailsArr['upload_success'] = true;
		} else {
			$uploadedFileDetailsArr['upload_error'] = 'Unable to upload files, This may be due to inavlid extension or file size exceeds the upload limit.';
		}

		return $uploadedFileDetailsArr;
	}
}
