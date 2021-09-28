<?php
session_start();
require_once 'common_table_function.php';
require_once 'config.php';
require_once '/var/gmcs_config/staff.conf';

if(!isset($_POST['action'])){exit(0);}
head();

//my_print_r($_POST);
if($_POST['action']=='register')
{
	$text=rand(11111,99999);
	//echo $text;
	$x=send_sms('Login ID='.$_POST['mobile'].'  Password='.$text.' GMC Surat: Lab/Xray Tech Application , Change of password required',$_POST['mobile']);
	echo $x;
	if(substr($x,0,4)=='100=')
	//if(strlen($x)>0)
	{
		$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
		
		
		$sql='insert into user (id,password) 
							values(\''.$_POST['mobile'].'\',
							\''.password_hash($text,PASSWORD_BCRYPT).'\')
							on duplicate key update
							password=\''.password_hash($text,PASSWORD_BCRYPT).'\'
							';
		$result=run_query($link,'tech',$sql);
		if(!$result){echo '<h3 class="bg-danger text-warning">registration failed</h3>';}
		else
		{
			$ar=array('id'=>$_POST['mobile']);
			$sql_app='insert into application (id,mobile) 
							values(\''.$_POST['mobile'].'\',\''.$_POST['mobile'].'\')
							on duplicate key update
							mobile=\''.$_POST['mobile'].'\'';			
			$result=run_query($link,'tech',$sql_app);

			echo '<h3 class="bg-danger text-success">SMS Sent. Registration Successful.</h3>';
			echo '<form method=post action=index.php>';
			echo '<button class="btn btn-info" type=submit name=action value=nothing>';
					echo '<span class="badge badge-danger">Home</span>';
			echo '</button>';
			echo '</form>';
		}
	}
	else
	{
		echo '<h3 class="bg-danger text-warning">Could not send SMS</h3>';
		echo '<h5 class="bg-important text-danger">'.$x.'</h5>';
		echo '<form method=post action=index.php>';
		echo '<button class="btn btn-info" type=submit name=action value=nothing>';
				echo '<span class="badge badge-danger">Home</span>';
		echo '</button>';
		echo '</form>';
		
	}
}
tail();
?>
