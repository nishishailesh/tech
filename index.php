<?php
//exit(0);
//echo ' Last date over. This  application is closed.';
//exit(0);
echo '<p class="text-center">Online Registration and Application is open on only between 2022-07-10 at 11:00 AM to 2022-07-18 17:00</p>';
//echo '<h1 style="color:red">If you apply before prescribed time, your application will be deleted on 2022-07-10 11:00 AM</h1>';

$date_now = new DateTime();
$date2 = new DateTime("2022-07-10 11:00");
$date3 = new DateTime("2022-07-18 17:00");
//print_r($date_now);
//print_r($date2);
if($date_now>=$date2 and $date_now<=$date3)
{
	//echo 'Application open';
}
else
{
	echo '<div class="col-sm-8 mx-auto">
              	<div class="text-info text-center">
                	<h3><b>Online application for 35th Lab/X-Ray Technician Training course(2022-23)<br>Government Medical College Surat</b></h3>
                </div>';

	echo 'Online Registration and Application is open on only between 2022-07-10 at 11:00 AM to 2022-07-18 17:00';
	//echo '<h1 style="color:red">If you apply before prescribed time, your application will be deleted on 2022-07-10 11:00 AM</h1>';
	exit(0);
}

if(isset($_SESSION))
{
	session_unset();
	session_destroy();
}
session_start();
unset($_SESSION['login']);
unset($_SESSION['password']);

require_once 'config.php';
require_once 'common_table_function.php';

head(); 
echo "<div class='row'>
			<div class='col-sm-2 mx-auto'>
			    <div><img src='college_logo.jpg' alt='college logo'></div>
			</div>
			<div class='col-sm-8 mx-auto'>
				<div class='text-info text-center'>
				             <h3><b>Online application for
                                    35th Lab/X-Ray Technician
                                    Training course(2022-23)<br>
                                    Government Medical College Surat</b></h3>
                 </div>
             </div>
             <div class='col-sm-2 mx-auto'>
                 <div><img src='gujarat.jpg' alt='gujarat logo'></div>
			</div>
		</div>";
$message='';
if(isset($_GET['message']))
{
        $message=$message.$_GET['message'];
}

        echo "<div class='row'>
                        <div class='col-*-6 mx-auto jumbotron'>
                                <div class='text-danger text-center'><h1>".$message."</h1></div>
                        </div>
                </div>"; 

echo'<div class="row">
			<div class="col-sm-4 bg-light mx-auto">';
					
		echo'<div class="row">
					<div class="col-sm-12 bg-light mx-auto bordered bordered-dark">
						<form method=post action=start.php>
							<div class="form-group">
								<h4 class="text-info text-center  bg-dark">Login</h4>
							</div>
							<div class="form-group">
								<label for=user>Login ID</label>
								<input  class="form-control" id=user type=text name=login placeholder=Username>
							</div>
							<div class="form-group">						
								<label for=password>Password</label>
								<input  class="form-control" id=password type=password name=password placeholder=Password>
							</div>
							<div class="form-group">						
								<button class="btn btn-info btn-block" type=submit name=action value=Login>Login</button>
							</div>
							<h5 class="text-info text-center  bg-dark">'.$GLOBALS['login_message'].'</h5>
						</form>
					</div>
				</div>';

echo '</div>';

/*
echo '<div class="col-sm-4 bg-dark mx-auto">';
		echo'<div class="row">
			<div class="col-sm-12 mx-auto">
				<form method=post action=register.php>
					<div class="form-group">
						<h2 class="text-info text-center  bg-dark">Register/Forgot Password</h2>
					</div>
					<div class="form-group">
						<label for=mobile class="text-light" >Mobile Number</label>
						<input  class="form-control" id=mobile type=text name=mobile placeholder=Mobile Number>
					</div>
					<div class="form-group">						
						<button class="btn btn-info btn-block" type=submit name=action value=register>Send Password by SMS</button>
					</div>
					<h5 class="text-info text-center  bg-dark">Mobile number will be your login ID</h5>
					<h5 class="text-info text-center  bg-dark">Password will be sent to your mobile number</h5>
					<span class="text-danger bg-dark">For login problem/SMS not received call 0261-2208715 (ServerRoom)</span><br>
					<span class="text-warning bg-dark">For course related inquiry call 0261-2208349 (Establishment Section)</span>
				</form>
			</div>
		</div>';
echo '</div>';

*/

echo '<div class="col-sm-4 bg-dark mx-auto">';
		echo'<div class="row">
			<div class="col-sm-12 mx-auto">
				<form method=post action=register_email.php>
					<div class="form-group">
						<h4 class="text-info text-center  bg-dark">Register/Forgot Password</h4>
					</div>
					<div class="form-group">
						<label for=email class="text-light" >email ID</label>
						<input  class="form-control" id=email type=text name=email placeholder=email>
					</div>
					<div class="form-group">
						<button class="btn btn-info btn-block" type=submit name=action value=register_email>Send Password by email</button>
					</div>
					<h4 class="text-info bg-dark">email will be your login ID</h4>
					<h4 class="text-info bg-dark">Password will be sent to your email</h4>
					<h4><span class="text-danger bg-dark">For login problem/email not received call 0261-2208715 (ServerRoom)</span><br>
					<span class="text-warning bg-dark">For course related inquiry call 0261-2208349 (Establishment Section)</span></h4>
				</form>
			</div>
		</div>';
echo '</div>';

echo '</div>';

		echo'<div class="row">
			<div class="col-sm-12 mx-auto">';
			echo '<table class="table table-bordered table-light table-striped">';
			echo '<tr><td class="text-center bg-danger"colspan=2><h4><b>Important Dates/Details</b></h4></td></tr>';
			echo '<tr><th>Last Date of Online Application</th><td>18/07/2022 5:00 PM </td> </tr>';
			echo '<tr><th>Last Date of Submission of Physical Application,self attested copies of documents and verification for original documents in person. 
			<span class="text-danger">Application will not be accepted by courier/Post.</span></th><td>From 13/07/2022 to 20/07/2022 On Government working days between 11:00 AM to 5:00 PM. Note: on 16-07-2022 Saturday office is closed for Verification</td></tr>';
			echo '<tr><th>Place of submission of Physical Application and verification or original documents</th><td>EST Section of GMC Surat</td></tr>';
		echo '</table>';

		echo '</div>
				</div>';


echo '			</div>
		</div>	
';
tail();
?>
