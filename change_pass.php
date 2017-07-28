<?php
session_start();
require_once 'common.php';
require_once '/var/gmcs_config/staff.conf';
require_once 'common_js.php';
echo '<html>';
echo '<head>';

echo '</head>';
echo '<body>';
function read_password()
{
	echo '<table border=1 class="style2"><form method=post>';
	echo '<tr><th colspan=2 class="head">Change Password for Lab/X-Ray Online application user</th></tr>';
	echo '<tr><td>Login ID</td>	<td><input readonly=yes type=text name=id value=\''.$_SESSION['login'].'\'></td></tr>'; 
	echo '<tr><td>Old Password</td>	<td><input type=password name=old_password></td></tr>';
	echo '<tr><td>New Password</td>	<td><input type=password name=password_1></td></tr>';
	echo '<tr><td>Repeat New Password</td>	<td><input type=password name=password_2></td></tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value=change_password>Change Password</button></td></tr>';
	echo '</form></table>';
}

function check_old_password($link,$user,$password)
{
	$sql='select * from  user where id=\''.$user.'\' ';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql))
	{
		//echo mysql_error();
		return FALSE;
	}
	if(mysqli_num_rows($result)<=0)
	{
		//echo 'No such user';
		echo '<h3>wrong username/password</h3>';
		return false;
	}
	$array=mysqli_fetch_assoc($result);
	if(MD5($password)==$array['password'])
	{
	  	//echo 'You have supplied correct username and password';
		return true;
	}
	else
	{
		//echo 'You have suppled wrong password for correct user';
                echo '<h3>wrong username/password</h3>';
		return false;
	}
}


function update_password($link,$user,$new_password)
{
        $sql='update user set password=MD5(\''.$new_password.'\') where id=\''.$user.'\' ';
        //echo $sql;
        if(!$result=mysqli_query($link,$sql))
        {
                echo mysqli_error($link);
                return FALSE;
        }

        if(mysqli_affected_rows($link)==1)
        {
		unset($_SESSION['login']);
                echo '<h3>Update successful. Close browser and restart it again. then go to <u>gmcsurat.edu.in/tech</u></h3>';
		//echo '<h3>Or go to:<a href="http://gmcsurat.edu.in/tech">Main Page</a><h3>';
		//session_destroy();
        }
		elseif(mysqli_affected_rows($link)==0)
		{
					echo '<h3>Old and new Passwords same. Nothing is changed.</h3>';
					return true;
		}
}


/////////////Start  of Script///////////////

$link=connect();

if(isset($_POST['action']))
{
	if($_POST['action']=='change_password')
	{
		if($_POST['password_1']==$_POST['password_2'])
		{
			//echo 'OK.  New passwords matches';
			if(check_old_password($link,$_POST['id'],$_POST['old_password']))
			{
				update_password($link,$_POST['id'],$_POST['password_1']);
			}
		}
		else
		{
			echo '<h3>New passwords supplied do not match</h3>';
		}
	}
	elseif($_POST['action']=='change_pass')
	{
		read_password();	
	}
}

?>
