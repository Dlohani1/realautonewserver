<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Realauto</title>
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
                <table>
                    <thead border="1">
                        <tr>
                            <th width="150" style="border: 1px solid #ececec; text-align: left; padding: 5px;">Name</th>
                            <th width="400" style="border: 1px solid #ececec; text-align: left; padding: 5px;">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="150" style="border: 1px solid #ececec; text-align: left; padding: 5px;">Name:</td>
                            <td width="400" style="border: 1px solid #ececec; text-align: left; padding: 5px;"><?php echo $name; ?></td>
                        </tr>

                        <tr>
                            <td width="150" style="border: 1px solid #ececec; text-align: left; padding: 5px;">Phone No:</td>
                            <td width="400" style="border: 1px solid #ececec; text-align: left; padding: 5px;"><?php echo $phone; ?></td>
                        </tr>

                        <tr>
                            <td width="150" style="border: 1px solid #ececec; text-align: left; padding: 5px;">Email ID:</td>
                            <td width="400" style="border: 1px solid #ececec; text-align: left; padding: 5px;"><?php echo $email; ?></td>
                        </tr>

                        <tr>
                            <td width="150" style="border: 1px solid #ececec; text-align: left; padding: 5px;">Company Name:</td>
                            <td width="400" style="border: 1px solid #ececec; text-align: left; padding: 5px;"><?php echo $company_name; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin: 0px 20px; text-align: center;">
                <p style="color: #bbbbbb; font-size: 12px;">Copyright &copy; <?php echo date('Y'); ?> Realauto || All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
