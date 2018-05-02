<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // public function sendResetLinkEmail(Request $request)
    // {
    //     $this->validateEmail($request);
    //     $count = App\Models\User::where('email', $request->email)->count();
    //     if ($count == 0) {
    //         return $this->sendResetLinkFailedResponse($request, 'passwords.user');
    //     }

    //     /*$query = \App\Models\GlobalUser::query();

    //     $arrUsers = $query->join('companies', 'companies.id', '=', 'global_users.company_id')
    //         ->where('companies.status', '=', 1)
    //         ->where('global_users.email', '=', $request->email)
    //         ->select('global_users.*', 'companies.name as company_name')->get();

    //     // Check mail exists
    //     if($arrUsers->count() == 0)*/

    //     // Set random token
    //     $token = str_random(64);
    //     \App\Models\PasswordReset::updateOrCreate(['email' => $request->email], [
    //         'email' => $request->email,
    //         'token' => $token
    //     ]);

    //     // Check password reset mail is sent before or not
    //     // $is_reset_before = \App\Models\PasswordReset::where('email', 'LIKE', $request->email)->count();

    //     // If yse update the record
    //     /*if ($is_reset_before)
    //         \App\Models\PasswordReset::where('email', 'LIKE', $request->email)->update([
    //             'token' => $token
    //             ]);
    //     else
    //         \App\Models\PasswordReset::create([
    //             'email' => $request->email,
    //             'token' => $token
    //         ]);*/

    //     // Set mail data
    //     $mailData = [
    //         'template_url' =>  'emails.users.forgot_password_mail_to_user', 
    //         'template_name' => 'Reset Password',
    //         'subject' => 'Reset Password',
    //         'template_data' => ['reset_link' => url('/password/reset/'.$token)]
    //     ];

    //     // Send reset password link mail to user
    //     Mail::to($request->email)->send(new SendStaticMail($mailData));

    //     return $this->sendResetLinkResponse();                    
    // }
}
