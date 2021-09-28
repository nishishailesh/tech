<?php
//exit(0);
//echo ' Last date over. This  application is closed.';
//exit(0);
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
                                    33rd Lab/X-Ray Technician
                                    Training course(2020-21)<br>
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
                        <div class='col-*-6 mx-auto'>
                                <div class='text-danger text-center'><h3>".$message."</h3></div>
                        </div>
                </div>"; 
echo'<div class="row">
			<div class="col-sm-6 bg-light mx-auto">';
					
		echo'<div class="row">
					<div class="col-sm-12 bg-light mx-auto bordered bordered-dark">
						<form method=post action=start.php>
							<div class="form-group">
								<h2 class="text-info text-center  bg-dark">Login</h2>
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


echo '<div class="col-sm-6 bg-dark mx-auto">';
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
				</form>
			</div>
		</div>	
';
echo '</div></div>';

		echo'<div class="row">
			<div class="col-sm-12 mx-auto">';
			echo '<table class="table table-bordered table-light table-striped">';
			echo '<tr><td class="text-center bg-danger"colspan=2><h4><b>Important Dates/Details</b></h4></td></tr>';
			echo '<tr><th>Last Date of Online Application</th><td>05/12/2020 5:00 PM </td> </tr>';
			echo '<tr><th>Last Date of Submission of Physical Application,self attested copies of documents and verification for original documents in person. 
			<span class="text-danger">Application will not be accepted by courier/Post.</span></th><td>From 01/12/2020 to 11/12/2020 On Government working days between 11:00 AM to 5:00 PM</td></tr>';
			echo '<tr><th>Place of submission of Physical Application and verification or original documents</th><td>EST Section of GMC Surat</td></tr>';
		   echo'<tr><th>Candidates are requested to visit Website and Notice Board of Government Medical College,Surat for list of selected candidates.It will be displayed after approximately one week from last date of physical verification of application/documents.</th><td>website: www.gmcsurat.edu.in</td></tr>';
		echo '</table>';

		echo '</div>
				</div>';


echo '			</div>
		</div>	
';
tail();
?>
