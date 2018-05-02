<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>SanTrack</title>       
        <script type="text/javascript">
            var root_url = "{{ url('/') }}"+"/";
        </script>
    </head>
    <body>
        <center>
            <table width="600px" cellpadding="0" cellspacing="0" border="0">
               <tr>
                   <td style="width:100%;font-family:Arial,Helvetica,sans-serif;padding:25px 0; border: 1px solid #cfcfcf; background:#e3e3e3; text-align:left; padding-left:20px;">
                       <img src="{{ asset('public/images/logo.png') }}" alt="Maxo-Bugtracker" style="max-width:100%">
                   </td>
               </tr>
                <tr align="left">
                   <td style="background:#ffffff; font-family:Arial,Helvetica,sans-serif;font-size:16px; padding:15px 10px; border-left: 1px solid #cfcfcf; border-right: 1px solid #cfcfcf; align:cenetr">
                       <table width="100%" cellpadding="0" cellspacing="0" border="0">
                           <tr align="left">
                              <td style="font-family:Arial,Helvetica,sans-serif; text-align: left; font-size:16px; color:#74787e;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr align="left">
                                           <td style="width:100%; text-align:center;padding:10px; padding-bottom:5px; font-size:16px; text-align: left;">Hi <span style="color:#3097d1;">{{$userData['name']}}</span></td>
                                       </tr>
                                       <tr align="left">
                                           <td style="width:100%; text-align:center;padding:10px; padding-top:5px; text-align: left;">Please Click on the below button to create your password.</td>
                                       </tr>
                                   </table>
                               </td>                               
                           </tr>
                           <tr align="center">
                              <td style="width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px; color:#74787e; text-align: center;">
                                   <table width='100%' cellpadding="0" cellspacing="0" border="0">
                                       <tr>
                                           <td style="width:100%; padding:10px; text-align:center;">
                                           <a href="{{ url('/activateUser/')."/".$userData['userID'].'?token='.$userData['token']}}" style="font-family:Arial,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#3097d1;border-top:10px solid #3097d1;border-right:18px solid #3097d1;border-bottom:10px solid #3097d1;border-left:18px solid #3097d1; text-align:center;">Create Password</a>
                                           </td>
                                       </tr>
                                   </table>
                               </td>                               
                           </tr>
                       </table>
                   </td>
                </tr>
                <tr>
                   <td style="font-family:Arial,Helvetica,sans-serif;box-sizing:border-box;padding:15px 0; background:#e3e3e3; border-left: 1px solid #cfcfcf; border-right: 1px solid #cfcfcf; border-bottom: 1px solid #cfcfcf;text-align:center">
                       <span style="font-family:Arial,Helvetica,sans-serif;box-sizing:border-box;color:#bbbfc3;font-size:12px;font-weight:bold;text-decoration:none; color: #6666;" target="_blank">© 2017 SanTrack. All rights reserved.</span>
                   </td>
               </tr>
            </table>
        </center>
        
        <h2></h2>
        <h3></h3>
    </body>
</html>
