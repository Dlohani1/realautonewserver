<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>RealAuto :: Reset Password</title>
</head>
<body style="width: 600px; margin: 0px auto; padding: 0px;">
    <div style="background-color: #fff;">
        <div style="border-bottom-right-radius: 15px; border-bottom-left-radius: 15px; margin: 0px 15px;">
            <div style="text-align: center; padding: 25px 0px 25px 0px;">
                <img src="{{ asset('/assets/home/img/logo.png') }}" alt="logo" style="width: 200px;" />
            </div>
            <div style="clear: both;"></div>
        </div>
        <div style="margin: 25px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" style="background:#FFF;color:#232f3e;font-family:helvetica,arial,sans-serif;font-size:15px;line-height:24px;margin:20px auto 0;width: 600px;">
                <tbody>
                <tr>
                    <td>
                        <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding: 10px;">
                            <tbody>
                            <tr>
                                <td>
                                    <h4 style="margin-top: 0px;">
                                        You are receiving this email because someone (hopefully you) has indicated that you have forgotten your password. If this is correct, please click on the below link to reset your password:
                                    </h4>
                                    <h4 style="margin-top: 0px;">
                                        <a href="<?php echo url('/reset-password/'.$userid.'/'.md5($email)); ?>"> <?php echo url('/reset-password/'.$userid.'/'.md5($email)); ?> </a>
                                    </h4>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="margin: 0px 20px; text-align: center;">
            <p style="color: #bbbbbb; font-size: 12px;">Copyright &copy; <?php echo date('Y'); ?> RealAuto || All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
