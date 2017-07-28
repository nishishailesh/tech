<?php
function login_varify()
{
	return mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
}



/////////////////////////////////
function select_database($link)
{
	return mysqli_select_db($link,'tech');
}


function check_user($link,$u,$p)
{
	$sql='select * from user where id=\''.$u.'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$result_array=mysqli_fetch_assoc($result);
	if(md5($p)==$result_array['password'])
	{
		return true;
	}
	else
	{
		return false;
	}
}



function logout()
{
	//session_start();
	unset($_SESSION['login']);
	session_destroy(); //Destroy it! So we are logged out now
	//header("location:index.php"); //configure absolute path of this file for access from anywhere
}
///////////////////////////////////
function connect()
{
	if(!isset($_SESSION['login'])){return false;}
	if(!$link=login_varify())
	{
		echo 'database login could not be verified<br>';
		return false;
		//logout();
	
		//exit();
	}


	if(!select_database($link))
	{
		echo 'database could not be selected<br>';
		return false;

		//logout();
	
		//exit();
	}
	
	if(!check_user($link,$_SESSION['login'],$_SESSION['password']))
	{
		echo 'application user could not be varified<br>';
		return false;

		//logout();
		//exit();
	}
	
return $link;
}
?>
