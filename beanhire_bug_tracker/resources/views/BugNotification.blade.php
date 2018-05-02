<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <center>
            <table width="600px" cellpadding="0" cellspacing="0" border="0">
               <tr>
                   <td style="width:100%;font-family:Arial,Helvetica,sans-serif;padding:25px 0; border: 1px solid #cfcfcf; background:#e3e3e3; text-align:left; padding-left:20px;">
                       <img src="{{ asset('public/images/logo.png') }}" width="150px" alt="Maxo-Bugtracker">
                   </td>
               </tr>
                <tr align="left">
                   <td style="background:#ffffff; font-family:Arial,Helvetica,sans-serif;font-size:14px; padding:15px 10px; border-left: 1px solid #cfcfcf; border-right: 1px solid #cfcfcf; align:cenetr">
                       <table width="100%" cellpadding="0" cellspacing="0" border="0">
                           <tr align="left">
                              <td style="font-family:Arial,Helvetica,sans-serif; text-align: left; font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr align="left">
                                           <td style="width:100%; text-align:center;padding:10px; padding-bottom:5px; font-size:14px; text-align: left;">Hi <span style="color:#3097d1;"> {{$bugData['assigned_to']}}</span></td>
                                       </tr>
                                       <tr align="left">
                                        @if($bugData['old_status'] != null && $bugData['new_status'] != null)
                                          <td style="width:100%; text-align:center;padding:10px; padding-top:5px; text-align: left;">BG-{{$bugData['bug_code']}} status has been changed from {{$bugData['old_status']}} to {{$bugData['new_status']}}. Below are the bug details.</td>
                                        @else
                                          <td style="width:100%; text-align:center;padding:10px; padding-top:5px; text-align: left;">BG-{{$bugData['bug_code']}} has been assigned to you by    {{$bugData['assigned_by']}}. Below are the bug details.</td>
                                        @endif
                                       </tr>
                                   </table>
                               </td>                               
                           </tr>
                       </table>
                   </td>
                </tr>
                <tr align="center">
                   <td style="background:#ffffff; font-family:Arial,Helvetica,sans-serif;font-size:14px; padding:10px; border-left: 1px solid #cfcfcf; border-right: 1px solid #cfcfcf; align:cenetr">
                       <table width="400px" cellpadding="0" cellspacing="0" border="0">
                           <tr>
                               <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Bug-ID</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">BG-{{$bugData['bug_code']}}</td>
                                       </tr>
                                   </table>
                               </td>
                           </tr>
                           <tr>
                              <td style="font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Title</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">{{$bugData['title']}}</td>
                                       </tr>
                                   </table>
                               </td>
                               
                           </tr>
                           <tr>
                              <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Project</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">{{$bugData['project']}}</td>
                                       </tr>
                                   </table>
                               </td>
                               
                           </tr>
                           <tr>
                              <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Sprint</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">{{$bugData['sprint']}}</td>
                                       </tr>
                                   </table>
                               </td>
                               
                           </tr>
                           <tr>
                              <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Severity</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">{{$bugData['severity']}}</td>
                                       </tr>
                                   </table>
                               </td>
                               
                           </tr>
                           <tr>
                              <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Status</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">{{$bugData['status']}}</td>
                                       </tr>
                                   </table>
                               </td>                              
                           </tr>
                           <tr>
                              <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Reported By</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">{{$bugData['assigned_by']}}</td>
                                       </tr>
                                   </table>
                               </td>                              
                           </tr>
                           <tr>
                              <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">URL</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">
                                             <a href="{{url('/viewBug')}}/{{$bugData['bug_id']}}">Click Here</a>
                                           </td>
                                       </tr>
                                   <!-- <a href="{{url('/add_testcase')}}/@{{sub_module.id}}"><i class="mdi mdi-plus"></i></a>                  -->

                                   </table>
                               </td>                              
                           </tr>

<!--                            <tr>
                              <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Assigned by</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">{{$bugData['assigned_by']}}</td>
                                       </tr>
                                   </table>
                               </td>                              
                           </tr> -->
                           <tr>
<!--                               <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:50%;border:1px solid #cfcfcf; padding:10px;">Created Date</td>
                                           <td style="width:50%;border:1px solid #cfcfcf; font-size: 14px; padding:10px;">28/11/2017</td>
                                       </tr>
                                   </table>
                               </td> -->
                              
                           </tr>
                       </table>
                   </td>
                </tr>
                <tr>
                   <td style="font-family:Arial,Helvetica,sans-serif;box-sizing:border-box;padding:15px 0; background:#e3e3e3; border-left: 1px solid #cfcfcf; border-right: 1px solid #cfcfcf; border-bottom: 1px solid #cfcfcf;text-align:center">
                       <span style="font-family:Arial,Helvetica,sans-serif;box-sizing:border-box;color:#bbbfc3;font-size:12px;font-weight:bold;text-decoration:none; color: #666666;" target="_blank">© 2017 SanTrack. All rights reserved.</span>
                   </td>
               </tr>
            </table>
        </center>
    </body>
</html>
