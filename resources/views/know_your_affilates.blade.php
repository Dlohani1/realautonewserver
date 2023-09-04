<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="img/fab-i.png" type="image/x-icon" />
    <link rel="icon" href="img/fab-i.png" type="image/x-icon" />

    <!-- Bootstrap v4.0 CSS --> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/my_style.css">
    <link rel="stylesheet" href="css/fonts.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Know your Affiliates</title>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
   

  </head>
  <body> 
  
  <section class="table-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h4>Know your Affiliates</h4>
        </div>
        <div class="col-lg-6 col-md-10 col-sm-12 offset-lg-3 offset-md-1 text-center" style="display:none">
          <form class="form-inline">
              <div class="form-group mb-2">
                <label>Your Affiliates No.</label>
              </div>
              <div class="form-group mx-sm-3 mb-2">
                <input type="password" class="form-control" id="inputPassword2" placeholder="">
              </div>
              <button type="submit" class="btn btn-primary mb-2">Search</button>
           </form>
        </div>

        <div class="col-md-12">
           <div class="table-responsive mt-5">

	<?php
		if(count($data) > 0 ) { 
	 ?>
            <table class="table table-bordered">
              <tr>
                <th>Sl.no</th>
                <th>User</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>April</th>
                <th>May</th>
                <th>Jun</th>
                <th>Jly</th>
                <th>Aug</th>
                <th>Sept</th>
                <th>Oct</th>
                <th>Nov</th>
                <th>Dec</th>
              </tr>
              <?php
              $sl = 0;
              foreach($data as $key => $value) {
                echo "<tr>";
                echo "<td>".++$sl."</td>";
                echo "<td>".$value['name']."</td>";
               

                for($i=1;$i<=12;$i++) {
                  
                  if(isset($value['payment_month'][$i])) {
                  
                      echo "<td><span class='label label-success'>&nbsp; &nbsp;</span></td>";
                  } else {
                    

                     echo "<td><span class='label label-default'>&nbsp; &nbsp;</span></td>";
                  } 
                 
                }
                echo "</tr>";
              }
              ?>
              
            </table>
<?php } else { echo "No record Found !!";} ?>

           </div>
        </div>
      </div>
    </div>
  </section>
  



 




    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap 4.0 JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <!--<script src="js/jquery-3.2.1.slim.min.js"></script>-->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>  
  </body>
</html>
