<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>RealAuto</title>
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
				
				Hey <?php echo $name; ?>,<br/>

				You have received this email because you signed up for the STARTER PACK of our product. <br/>
				<?php 
				if (isset($welcome_mail) && $welcome_mail == "1") {?>
				Your 3 day trial period will start <?php echo $startDate; ?> and end on <?php echo $endDate; ?>.<br/>

				Please find your login details below: <br/>

				Login Url : https://realauto.in/login <br/>
				Email     : <?php echo $email; ?> <br/>
				Password  : <?php echo $password; ?><br/>

				<strong>Note : </strong>Please change your password after you login.<br/>
				<?php 
				} else {?>
				
				

				<?php } ?>
				Thank You<br/>
				RealAuto Support Team
				
                
            </div>
            <div style="margin: 0px 20px; text-align: center;">
                <p style="color: #bbbbbb; font-size: 12px;">Copyright &copy; <?php echo date('Y'); ?> RealAuto || All Rights Reserved.</p>
            </div>
        </div>
    </body>
</html>
