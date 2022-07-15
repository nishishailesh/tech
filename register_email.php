<?php
session_start();
require_once 'common_table_function.php';
require_once 'config.php';
require_once '/var/gmcs_config/staff.conf';

if(!isset($_POST['action'])){exit(0);}
head();

//my_print_r($_POST);
if($_POST['action']=='register_email')
{
	$text=rand(11111,99999);
	//echo $text;
	$x=save_email($_POST['email'],$text);

	if($x===true)
	{
		$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
		
		
		$sql='insert into user (id,password) 
							values(\''.$_POST['email'].'\',
							\''.password_hash($text,PASSWORD_BCRYPT).'\')
							on duplicate key update
							password=\''.password_hash($text,PASSWORD_BCRYPT).'\'
							';
		$result=run_query($link,'tech',$sql);
		if(!$result){echo '<h3 class="bg-danger text-warning">registration failed</h3>';}
		else
		{
			$ar=array('id'=>$_POST['email']);
			$sql_app='insert into application (id,email) 
							values(\''.$_POST['email'].'\',\''.$_POST['email'].'\')
							on duplicate key update
							email=\''.$_POST['email'].'\'';			
			//echo $sql_app;
			$result=run_query($link,'tech',$sql_app);

			echo '<h4 class="text-danger text-success">Registration successful<br>Initial Password Sent to email. <br>email will be username.<br>Note: Password change required on first login</h4>';
			echo '<form method=post action=index.php>';
			echo '<button class="btn btn-info" type=submit name=action value=nothing>';
					echo '<span class="badge badge-danger">Login</span>';
			echo '</button>';
			echo '</form>';
		}
	}
	else
	{
		echo '<h3 class="bg-danger text-warning">Could not send email</h3>';
		echo '<h5 class="bg-important text-danger">'.$x.'</h5>';
		echo '<form method=post action=index.php>';
		echo '<button class="btn btn-info" type=submit name=action value=nothing>';
				echo '<span class="badge badge-danger">Home</span>';
		echo '</button>';
		echo '</form>';
		
	}
}
tail();



function save_email($emailid,$comment,$sms=0)
{
	if($GLOBALS['send_email']!=1){return;}
	else
	{
		echo 'trying to send email....';
	}
	$main_server_link=get_remote_link($GLOBALS['email_database_server'],$GLOBALS['main_server_main_user'],$GLOBALS['main_server_main_pass']);
	if(!$main_server_link){echo 'can not connect to email server'; return false;}
	$sql='INSERT INTO email(`to`,`subject`,`content`,`sent`,sms,sms_sent)
	 	VALUES (\''.$emailid.'\',\'password from gmcsurat\',\''.
	 	my_safe_string($main_server_link,$comment).'\',0,\''.$sms.'\',0)';
	//echo $sql;
	if(!run_query($main_server_link,'email',$sql))
	{
		echo '<span class="text-danger">save_email():email not sent</span><br>';
		return false;
	}
	else
	{
		return true;
	}
}


function get_remote_link($ip,$u,$p)
{
	$link=mysqli_connect($ip,$u,$p);
	if(!$link)
	{
		echo 'error1:'.mysqli_error($link); 
		return false;
	}
	return $link;
}


function my_safe_string($link,$str)
{
	return mysqli_real_escape_string($link,$str);
} 

?>
