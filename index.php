<?php
session_start();
require_once '/var/gmcs_config/staff.conf';
require_once 'common_js.php';
require_once('tcpdf/tcpdf.php');
require_once 'common.php';


$GLOBALS['last_date']='2017-07-10 23:59';

//echo '
//<script type="text/javascript" src="date/datepicker.js"></script>
//<link rel="stylesheet" type="text/css" href="date/datepicker.css" /> 
//';

?>

<script>

function show_mk()
{
	ys=document.getElementById('ys').value;
	mg=document.getElementById('mg').value;
	//alert(ys+mg);
	x=ys.charAt(0);
	y=mg.charAt(0);
	if(x=='1' && y=='1')
	{
		document.getElementById('mk').innerHTML=
		'<table align=center style="background-color:lightgray;">\
		<tr>\
		<th>Final Year Marks Obtained</th>\
		<td><input type=text name=final_marks_obtained id=final_marks_obtained></td>\
		<th>Final Year Maximum Marks</th>\
		<td><input type=text name=final_marks_max id=final_marks_max></td>\
		</tr>\
		<tr>\
		<td colspan=4>Write only final year marks. For semester system or GPA system, choose appropriately at  <b style="color:red;">(X)</b> and  <b style="color:red;">(Y)</b> above</td>\
		</tr>\
		<tr>\
		<td colspan=4><b>document required:</b>Final year marksheet(s)</td>\
		</tr>\
		</table>';
	}
	else if(x=='1' && y=='2')
	{
		document.getElementById('mk').innerHTML=
		'<table align=center style="background-color:lightgray;">\
		<tr>\
		<th>Final year SGPA Obtained</th>\
		<td><input type=text name=final_year_SGPA id=final_year_SGPA></td>\
		</tr>\
		<tr>\
		<td colspan=4>Write only Final year SGPA. For semester result system or marks system, choose appropriately at  <b style="color:red;">(X)</b> and  <b style="color:red;">(Y)</b> above</td>\
		</tr>\
		<tr>\
		<td colspan=4><b>document required:</b>Final year grade sheet(s)</td>\
		</tr>\
		</table>';		
		
	}
	else if(x=='2' && y=='1')
	{
		//alert('sem + marks');
		document.getElementById('mk').innerHTML=
		'<table align=center style="background-color:lightgray;">\
		<tr>\
		<th>5th semester Marks Obtained</th>\
		<td><input type=text name=5th_sem_marks id=5th_sem_marks></td>\
		<th>5th semester Maximum Marks</th>\
		<td><input type=text name=5th_sem_marks_max id=5th_sem_marks_max></td>\
		</tr>\
		<th>6th semester Marks Obtained</th>\
		<td><input type=text name=6th_sem_marks id=6th_sem_marks></td>\
		<th>6th semester Maximum Marks</th>\
		<td><input type=text name=6th_sem_marks_max id=6th_sem_marks_max></td>\
		</tr>\
		<tr>\
		<td colspan=4>Write only 5th and 6th sem year marks. For yearly result system or GPA system, choose appropriately at  <b style="color:red;">(X)</b> and  <b style="color:red;">(Y)</b> above</td>\
		</tr>\
		<tr>\
		<td colspan=4><b>document required:</b>5th and 6th semester marksheets</td>\
		</tr>\
		</table>';
	}
	else if(x=='2' && y=='2')
	{
		//alert('sem + gpa');
		document.getElementById('mk').innerHTML=
		'<table align=center style="background-color:lightgray;">\
		<tr>\
		<th>5th semester SGPA</th>\
		<td><input type=text name=5th_sem_SGPA id=5th_sem_SGPA></td>\
		</tr>\
		<th>6th semester SGPA</th>\
		<td><input type=text name=6th_sem_SGPA id=6th_sem_SGPA></td>\
		</tr>\
		<tr>\
		<td colspan=4>Write only 5th and 6th sem year SGPA. For yearly result system or marks system, choose appropriately at  <b style="color:red;">(X)</b> and  <b style="color:red;">(Y)</b> above</td>\
		</tr>\
		<tr>\
		<td colspan=4><b>document required:</b>5th and 6th year SGPA grade sheets</td>\
		</tr>\
		</table>';
	}
}

function validate()
{
	var err;
	err='';
	if(document.getElementById('course').value=='')
	{
		err=err+'Choose course\n';
	}

	if(document.getElementById('name').value=='')
	{
		err=err+'Name can not be empty\n';
	}

	if(document.getElementById('address').value=='')
	{
		err=err+'Address can not be empty\n';
	}		
	
	if(document.getElementById('email').value=='')
	{
		err=err+'Email can not be empty\n';
	}

	if(document.getElementById('mobile').value=='')
	{
		err=err+'mobile can not be empty\n';
	}

	if(document.getElementById('dob').value=='')
	{
		err=err+'Date of Birth is required\n';
	}

	if(document.getElementById('catagory').value=='')
	{
		err=err+'Select one catagory\n';
	}	

	if(document.getElementById('sex').value=='')
	{
		err=err+'Sex not indicated\n';
	}

	if(document.getElementById('bsc').value=='')
	{
		err=err+'Choose BSc main subject\n';
	}
	
	if(document.getElementById('university').value=='')
	{
		err=err+'University can not be empty\n';
	}	

	if(document.getElementById('year').value=='')
	{
		err=err+'Year of passing BSc must be mentioned\n';
	}	

	if(document.getElementById('ys').value=='')
	{
		err=err+'Is your BSc yearwise or semesterwise. (X)\n';
	}
	
	if(document.getElementById('mg').value=='')
	{
		err=err+'Does your BSc resultsheet mention marks or GPA. (Y)\n';
	}
	if(document.getElementById('attempt').value=='')
	{
		err=err+'Indicate correct number of attempts.\n';
	}	
	var m = ["final_marks_obtained" , 
"final_marks_max" , 
"final_year_SGPA" , 
"5th_sem_marks" , 
"5th_sem_marks_max" , 
"6th_sem_marks" , 
"6th_sem_marks_max" , 
"5th_sem_SGPA" , 
"6th_sem_SGPA" ];

 len = m.length;
	for (i=0; i<len; ++i) 
	{
	  if(document.getElementById(m[i])!=null)
	  {
		  res=document.getElementById(m[i]).value;
		  if(res=='' || isNaN(res) )
		  {
			  err=err+ m[i] + ' is empty / accepts only numbers \n';
		  }
	  }
	}
/*
"final_marks_obtained" , 
"final_marks_max" , 
"final_year_SGPA" , 
"5th_sem_marks_obtained" , 
"5th_sem_marks_max" , 
"6th_sem_marks_obtained" , 
"6th_sem_marks_max" , 
"5th_sem_SGPA" , 
"6th_sem_SGPA" , 
  */
            
								
	if(err==''){err='No error.\nCongradulation!'; alert(err); return true;}
	else
	{
		alert(err);
		return false;
	}
}

function lockk()
{
	v=validate();
	if(v==false)
	{
		return false;
		alert('incomplete application can not be saved');
	}
	else
	{
		return confirm("Locked application can not be changed\nIf you wish to make any change in future, donot lock, press cancel.\nApplication can be printed only after locking");
	}
}
</script>

<?php

/*
Register:
	1) mobile as username, password sent to mobile
	2) email as username, password sent to email
	3) sms email/mobile to ronakk, password will be generated and sent to same email/mobile in 24 hours

change password
forgot password
  -send sms/email
  -sms/email ronakk
  -generate and send back
  -if next login with new, disable old
  -if next login with old, disable new
  
Save
Lock
Print

*/

function get_mobile_reg_info()
{
	echo '<form method=post>';
	echo '<table border=5  align=center style="background-color:#BFBFBF">';
	echo '<tr><th colspan=3 style="background-color:lightgray">Registration via Mobile</th></tr>';
	echo '<tr><th colspan=3>Mobile number will be your username.<br>
	To generate password enter your mobile number below <br>
	And Click on "Send SMS" Button<br>
	Password will be sent to your mobile number</th></tr>';
	
	echo '<tr><td>Mobile No.</td><td>';
	echo '<input type=text placeholder="Write mobile number" name=mobile>';
	echo '</td>';
	echo '<td>';
	echo '<button type=submit  name=action value=send_sms><h3>Send SMS</h3></button>';	
	echo '</td></tr>';
	echo '</table>';
	echo '</form>';

}


function get_email_reg_info()
{
	echo '<form method=post>';
	echo '<table border=5 style="background-color:lightgreen"  align=center>';
	echo '<tr><th colspan=3 style="background-color:lightgray">Registration Method 2</th></tr>';
	echo '<tr><th colspan=3>email will be your username</th></tr>';
	echo '<tr><th colspan=3>Password will be sent to your email<th></tr>';
	echo '<tr><td>email</td><td>';
	echo '<input type=text placeholder="Write email id" name=email>';
	echo '</td>';
	echo '<td>';
	echo '<input type=submit  name=action value=send_email>';	
	echo '</td></tr>';
	echo '</table>';
	echo '</form>';

}

function manual_reg()
{
	echo '<form method=post>';
	echo '<table border=5 style="background-color:#BFBFBF""  align=center>';
	echo '<tr><th colspan=3 style="background-color:lightgray">Manual Registration</th></tr>';
	echo '<tr><th colspan=3>To generate password send SMS "TECH" to +91 9067108646</th></tr>';
	//echo '<tr><th colspan=3>Or</th></tr>';
	//echo '<tr><th colspan=3>write "TECH" as subject to email: serverroor@gmail.com<th></tr>';
	echo '<tr><th>password will be sent in 24 hours</th></tr>';
	echo '<tr><th>Call 0261-2208349 for any problem(During 10:00 to 18:00 hr)</th></tr>';
	echo '</table>';
	echo '</form>';

}

function login()
{
	echo '<form method=post>';
	echo '<table border=10 style="background-color:#F49797" align=center>';
	//echo '<tr><th colspan=3 style="background-color:lightgray">Registered users</th></tr>';
	echo '<tr><td>login id</td><td>';
	echo '<input type=text placeholder="mobile number" name=login>';
	echo '</td>';
	echo '<tr><td>password</td><td>';
	echo '<input type=password placeholder="Password" name=password>';
	echo '</td></tr><tr>';
	echo '<th colspan=2>';
	echo '<button type=submit  name=action value=login><h3>LOGIN</h3></button>';	
	echo '</th></tr>';
	echo '</table>';
	echo '</form>';
}

function send_sms($sms,$num)
{
	//$str='http://mobi1.blogdns.com/httpmsgid/SMSSenders.aspx';
	$str=$GLOBALS['sms_site'];
	$getdata = http_build_query
		(
		array(
			'UserID' => $GLOBALS['sms_UserID'],
			'UserPass' => $GLOBALS['sms_UserPass'],
			'Message'=>$sms,
			'MobileNo'=>$num,
			'GSMID'=>$GLOBALS['sms_GSMID']
			)
		);
								
	$hdr = "Content-Type: application/x-www-form-urlencoded";
                    
	$opts = array('http' =>
					array(
						'method'  => 'GET',
						'content' => $getdata,
						'header'  => $hdr
						)
				);

	$context  = stream_context_create($opts);
	//echo $str;
	$ret=file_get_contents($str,false,$context);
	return $ret;
}



function create_user($link,$u,$p)
{
	$sql='insert into user (id,password) values(\''.$u.'\',\''.MD5($p).'\')';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql))
	{
		echo 'user creation failed:'.mysqli_error($link);
		return FALSE;
	}
}

function date_diff_grand($from_dt,$to_dt)
{
	$from_dtt=date_create($from_dt);
	$to_dtt=date_create($to_dt);
	
	$diff=date_diff($from_dtt,$to_dtt);
	
	//echo '<pre>';
	//print_r($diff);
	//echo '</pre>';
	
	return $diff;

}

function get_date_diff_as_ymd($from_dt,$to_dt)
{
	$diff=date_diff_grand($from_dt,$to_dt);
	return date_interval_format($diff,'%r%Y y,%r%M m,%r%D d');
}

function check_last_date()
{
	$to_time = strtotime($GLOBALS['last_date']);
	$from_time = time();
	//echo $to_time - $from_time;
	if($to_time - $from_time>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}


function ask_login()
{
	echo '<table align=center>';
	echo '<tr><td valign=top width="25%">';
	echo '<h3 align=center style="background-color:lightgreen">Log In</h3>';
	login();
	echo '</td><td width="25%"></td>';
	//$diff=date_diff_grand(strftime('%Y-%m-%d'),$GLOBALS['last_date']);
	//print_r($diff);
	//if(date_interval_format($diff,'%r%Y y,%r%M m,%r%D d')


if(check_last_date())
{

	echo '<td width="50%">';
	echo '<h3 align=center style="background-color:lightgreen">New User Sign Up</h3>';
	get_mobile_reg_info();
	//get_email_reg_info();
	manual_reg();	
	echo '</td>';
}
else
{
	echo '<td width="50%">';

	echo '</td>';	
}

	echo '</tr></table>';
}


function user_exist($link,$u)
{
	$sql='select * from  user where id=\''.$u.'\' ';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql))
	{
		//echo mysql_error();
		return FALSE;
	}
	if(mysqli_num_rows($result)<=0)
	{
		return false;
	}
	else
	{
		return true;
	}
}

	//echo '<table>';
	//echo '<tr>';
	//echo '<td>';
	//echo '</td>';
	//echo '</tr>';
	//echo '</table>';
	
function read_field($name,$value='',$help='',$size=30,$readonly='')
{
	echo '<tr>';
		echo '<td>';
			echo $name;
		echo '</td>';
		echo '<td>';
			echo '<input '.$readonly.' size=\''.$size.'\' type=text name=\''.$name.'\' id=\''.$name.'\' value=\''.$value.'\'>';
		echo '</td>';
		echo '<td>';
			echo $help;
		echo '</td>';
	echo '</tr>';
}	

function read_date($key,$value=' ',$help='',$readonly=' ')
{
	//if($readonly=='readonly')
	//{
		//echo		'<tr><td>'.$key.'</td><td><input  readonly
							//value=\''.$value.'\' 
							//id=\''.$key.'\' 
							//size="10" 
							//name=\''.$key.'\' /></td><td>'.$help.'</td></tr>
				//';		
	//}
	//else
	//{
		echo		'<tr><td>'.$key.'</td><td><input  readonly
							value=\''.$value.'\' 
							id=\''.$key.'\' 
							class="datepicker" 
							size="10" 
							name=\''.$key.'\' />
							</td><td>'.$help.'</td></tr>
				';
	//}
}


function mk_select_from_array($ar,$form_name,$readonly='',$default='', $onchange='')
{

		echo '<select  onchange="'.$onchange.'" '.$readonly.' name='.$form_name.' id='.$form_name.'>';
		foreach($ar as $value)
		{
			if($value==$default)
		{
			echo '<option selected  > '.$value.' </option>';
		}
		else
			{
				echo '<option  > '.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function read_catagory($default='',$readonly='')
{
	$ar=array('','open','SC','ST','SEBC','PH');
	echo '<tr>';
		echo '<td>';
			echo 'catagory';
		echo '</td>';
		echo '<td>';
			mk_select_from_array($ar,'catagory',$readonly,$default);
		echo '</td>';
		echo '<td>';
			echo '<b>document required:</b>Catagory certificate (and Non-creamy layer certificate for SEBC), PH=Physically handicaped';
		echo '</td>';
	echo '</tr>';
}

function read_attempt($default='',$readonly='')
{
	$ar=array('',1,2,3,4,5,6,7,8,9,10);
	echo '<tr>';
		echo '<td>';
			echo 'Attempt';
		echo '</td>';
		echo '<td>';
			mk_select_from_array($ar,'attempt',$readonly,$default);
		echo '</td>';
		echo '<td>';
			echo '<b>document required:</b>Attempt certificate (if >10 attempt,choose 10)';
		echo '</td>';
	echo '</tr>';
}

function read_sex($default='',$readonly='')
{
	$ar=array('','M','F','O');
	echo '<tr>';
		echo '<td>';
			echo 'sex';
		echo '</td>';
		echo '<td>';
			mk_select_from_array($ar,'sex',$readonly,$default);
		echo '</td>';
		echo '<td>';
			echo 'Select your sex';
		echo '</td>';
	echo '</tr>';
		
}

function read_degree($default='',$readonly='')
{
	$ar=array('','Microbiology','Biochemistry','Physics','Other');
	echo '<tr>';
		echo '<td>';
			echo 'BSc main subject';
		echo '</td>';
		echo '<td>';
			mk_select_from_array($ar,'bsc',$readonly,$default);
		echo '</td>';
		echo '<td>';
			echo '<b>document required:</b>BSc degree certificate. Choose "Others" if your main subject is not listed';
		echo '</td>';
	echo '</tr>';
		
}

function read_course($default='',$readonly='')
{
	$ar=array('','1.Laboratory Technician','2.X Ray Technician','3.Both Lab and X-Ray Technician');
	echo '<tr>';
		echo '<td>';
			echo 'Course(s) applying for ';
		echo '</td>';
		echo '<td>';
			mk_select_from_array($ar,'course',$readonly,$default);
		echo '</td>';
		echo '<td>';
			echo 'You can apply for both courses in single application';
		echo '</td>';
	echo '</tr>';
		
}

function read_result_type($default='',$readonly='')
{
	//echo $default;
	$ar=array('','1.yearly result','2.six monthly semester result');
	$dstr=check_in_ar($default,$ar);	
	//echo $dstr;

	echo '<tr>';
		echo '<td>';
			echo 'Yearwise/Semesterwise<b  style="color:red;">(X)</b>';
		echo '</td>';
		echo '<td>';
			mk_select_from_array($ar,'ys',$readonly,$dstr,'show_mk()');
		echo '</td>';
		echo '<td>';
			echo 'Select weather your result is yearly or semesterwise';
		echo '</td>';
	echo '</tr>';		
}

function read_marks_type($default='',$readonly='')
{
	$ar=array('','1.marks','2.GPA');
	$dstr=check_in_ar($default,$ar);
	//echo $dstr;
	echo '<tr>';
		echo '<td>';
			echo 'marks/GPA<b style="color:red;">(Y)</b>';
		echo '</td>';
		echo '<td>';
			mk_select_from_array($ar,'mg',$readonly,$dstr,'show_mk()');
		echo '</td>';
		echo '<td>';
			echo 'Select weather your result is in marks or GPA';
		echo '</td>';
	echo '</tr>';		
}


function read_year($name,$default='',$readonly='')
{
		echo '<tr>';
		echo '<td>';
			echo 'Year of passing BSc</b>';
		echo '</td>';
		echo '<td>';
	
		$y=strftime('%Y');

	echo '<select '.$readonly.' name=\''.$name.'\' id=\''.$name.'\'>';

	if($default==''){$selected='selected';}else{ $selected='';}	
	echo '<option '.$selected.' ></option>';
	
	for($i=$y;$i>($y-100);$i--)
	{
			if($default==$i){$selected='selected';}else{ $selected='';}
			echo '<option '.$selected.' >'.$i.'</option>';
	}
	echo '</select>';
	echo '</td>';
		echo '<td>';
			echo 'Year of passing BSc';
		echo '</td>';
	echo '</tr>';	
}

function get_raw($link,$sql)
{
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	if(mysqli_num_rows($result)!=1){echo mysqli_error($link);return false;}
	else
	{
		return mysqli_fetch_assoc($result);
	}
}

function check_in_ar($string,$ar) 
{
    foreach($ar as $a) 
	{
		if(isset($a[0]))
		{
			if($a[0] == $string) 
			{
				//echo $a;
				return $a;
			} 
		}
	}
    return false;
}


function find_marks_type($dt)
{
	$mks=array(
	'final_marks_obtained'=>array(1,1),
	'final_year_SGPA'=>array(1,2),
	'5th_sem_marks'=>array(2,1),
	'5th_sem_SGPA'=>array(2,2),
			);

	foreach($mks as $key=>$value)
	{
		if($dt[$key]!='' && $dt[$key]!=0)
		{
			//print_r($value);
			return $value;
		}
	}
}
function work($link,$status)
{
	
	if($status==1){$readonly='disabled';}else{$readonly='';}
	if(check_last_date()){}else{$readonly='disabled';}
	
	$dt=get_raw($link,'select * from application where id=\''.$_SESSION['login'].'\'');
		$ar=array('','1.Laboratory Technician','2.X Ray Technician','3.Both Lab and X-Ray Technician');
	//print_r($dt);
	echo '<form method=post>';
	echo '<table class=border align=center style="background-color:#F3DFBB;">';
	
	read_course(check_in_ar($dt['course'].'.',$ar),$readonly);
	read_field('name',$dt['name'],'<b>document required:</b>Identity Card. The name provided must match name in identity card',30,$readonly);
	
	read_field('address',$dt['address'],'Write address where communications can be sent',50,$readonly);
	read_field('email',$dt['email'],'You must be able to access this email',30,$readonly);
	read_field('mobile',
					$_SESSION['login'],
					'You may change mobile number for communication, but login will always be '.$_SESSION['login'],20,$readonly);
	read_date('dob',mysql_to_india_date($dt['dob']),'<b>document required:</b>School leaving, SSC passing or Birth certificate',$readonly);
	read_catagory($dt['catagory'],$readonly);
	read_sex($dt['sex'],$readonly);
	read_degree($dt['bsc'],$readonly);
	read_field('university',$dt['university'],'Write name of university where BSc was studied',30,$readonly);
	read_year('year',$dt['year'],$readonly);
	
	$mtype=find_marks_type($dt);
	
	read_result_type($mtype[0],$readonly);
	read_marks_type($mtype[1],$readonly);
	read_attempt($dt['attempt'],$readonly);
	//print_r($mtype);
	if($mtype[0]==1 && $mtype[1]==1)
	{
		$mk_str=		'<table align=center style="background-color:lightgray;">
		<tr>
		<th>Final Year Marks Obtained</th>
		<td><input '.$readonly.' type=text name=final_marks_obtained id=final_marks_obtained value=\''.$dt['final_marks_obtained'].'\'></td>
		<th>Final Year Maximum Marks</th>
		<td><input '.$readonly.' type=text name=final_marks_max id=final_marks_max value=\''.$dt['final_marks_max'].'\'></td>
		</tr>
		<tr>
		<td colspan=4>Write only final year marks. For semester system or GPA system, choose appropriately at  <b style="color:red;">(X)</b> and  <b style="color:red;">(Y)</b> above</td>
		</tr>
		<tr>
		<td colspan=4><b>document required:</b>Final year marksheet(s)</td>
		</tr>
		</table>';
	}
	elseif($mtype[0]==1 && $mtype[1]==2)
	{
		$mk_str='<table align=center style="background-color:lightgray;">
		<tr>
		<th>Final year SGPA Obtained</th>
		<td><input '.$readonly.' type=text name=final_year_SGPA id=final_year_SGPA value=\''.$dt['final_year_SGPA'].'\'></td>
		</tr>
		<tr>
		<td colspan=4>Write only Final year SGPA. For semester result system or marks system, choose appropriately at  <b style="color:red;">(X)</b> and  <b style="color:red;">(Y)</b> above</td>
		</tr>
		<tr>
		<td colspan=4><b>document required:</b>Final year grade sheet(s)</td>
		</tr>
		</table>';		
	}
	elseif($mtype[0]==2 && $mtype[1]==1)
	{
		$mk_str='<table align=center style="background-color:lightgray;">
		<tr>
		<th>5th semester Marks Obtained</th>
		<td><input '.$readonly.' type=text name=5th_sem_marks id=5th_sem_marks value=\''.$dt['5th_sem_marks'].'\'></td>
		<th>5th semester Maximum Marks</th>
		<td><input '.$readonly.' type=text name=5th_sem_marks_max id=5th_sem_marks_max value=\''.$dt['5th_sem_marks_max'].'\'></td>
		</tr>
		<th>6th semester Marks Obtained</th>
		<td><input '.$readonly.' type=text name=6th_sem_marks id=6th_sem_marks value=\''.$dt['6th_sem_marks'].'\'></td>
		<th>6th semester Maximum Marks</th>
		<td><input '.$readonly.' type=text name=6th_sem_marks_max id=6th_sem_marks_max value=\''.$dt['6th_sem_marks_max'].'\'></td>
		</tr>
		<tr>
		<td colspan=4>Write only 5th and 6th sem year marks. For yearly result system or GPA system, choose appropriately at  <b style="color:red;">(X)</b> and  <b style="color:red;">(Y)</b> above</td>
		</tr>
		<tr>
		<td colspan=4><b>document required:</b>5th and 6th semester marksheets</td>
		</tr>
		</table>';		
	}
	
	elseif($mtype[0]==2 && $mtype[1]==2)
	{
		$mk_str='<table align=center style="background-color:lightgray;">
		<tr>
		<th>5th semester SGPA</th>
		<td><input '.$readonly.' type=text name=5th_sem_SGPA id=5th_sem_SGPA value=\''.$dt['5th_sem_SGPA'].'\'></td>
		</tr>
		<th>6th semester SGPA</th>
		<td><input '.$readonly.' type=text name=6th_sem_SGPA id=6th_sem_SGPA value=\''.$dt['6th_sem_SGPA'].'\'></td>
		</tr>
		<tr>
		<td colspan=4>Write only 5th and 6th sem year SGPA. For yearly result system or marks system, choose appropriately at  <b style="color:red;">(X)</b> and  <b style="color:red;">(Y)</b> above</td>
		</tr>
		<tr>
		<td colspan=4><b>document required:</b>5th and 6th year SGPA grade sheets</td>
		</tr>
		</table>';	
	}
	else
	{
		$mk_str='Select (X) and (Y) to enter marks';
	}
		
	echo '<tr><td></td><td colspan=2 ><div id=mk>'.$mk_str.'</div></td></tr>';
	

	if($status==1)
	{
	echo '<tr>
		<td><h3>Click on Print Button<br> to Download & Print Application ----- ></h3></td>
		<td>
		<button formaction=print.php formtarget=_blank 
		style="background-color:green;color:red;" type=submit id=print  name=action value=print><h2>Print</h2></button>
		</td>
		<td>Print in 2 copies, Attach Photo, Sign, attach required photocopies</td>
		</tr>';	
	
	}
	else
	{
		if(check_last_date())
		{
		echo '<tr>
			<td>Save frequently</td>
			<td>
			<button type=submit id=save onclick="return validate();" name=action value=save><h3>Save</h3></button>
			</td>
			<td>Incomplete details can not be saved. Saved application can be retrived and edited afterwords.</td>
			</tr>';


		echo '<tr>
			<td>Donot lock until filled correctly <br>Lock is required to Download & Print Application </td>
			<td>
			<button type=submit id=lock onclick="return lockk();" name=action value=lock><h3>Lock</h3></button>
			</td>
			<td>After locking, application can not be changed</td>
			</tr>';
		}
	}
	echo '</table>';
	echo '</form>';
}


function india_to_mysql_date($ddmmyyyy)
{
	$ex=explode('-',$ddmmyyyy);
	if(count($ex)==3)
	{
		return $ex[2].'-'.$ex[1].'-'.$ex[0];
	}
	else
	{
		return false;
	}
}

function mysql_to_india_date($yyyymmdd)
{
	$ex=explode('-',$yyyymmdd);
	if(count($ex)==3)
	{
		return $ex[2].'-'.$ex[1].'-'.$ex[0];
	}
	else
	{
		return false;
	}
}

function update_one_field($link,$table,$pk_field,$pk_value,$update_field,$update_value)
{
	$sql='update `'.$table.'` set  `'.$update_field.'`=\''.$update_value.'\' where
									`'.$pk_field.'`=\''.$pk_value.'\'';
 
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	return true;
}

function save($link)
{

$mks=array(
'final_marks_obtained',
'final_marks_max',
'final_year_SGPA',
'5th_sem_marks',
'5th_sem_marks_max',
'6th_sem_marks',
'6th_sem_marks_max',
'5th_sem_SGPA',
'6th_sem_SGPA');

foreach($mks as $value)
{
	if(!isset($_POST[$value]))
	{
		$_POST[$value]='';
	}
}
	$sql='insert into application 
	(
	 id,
	 course,
	 name,
	 address,
	 
	 email,
	 mobile,
	 dob,
	 catagory,
	 
	 sex,
	 bsc,
	 university,
	 year,
	 
	 final_marks_obtained,
	 final_marks_max,
	 final_year_SGPA,
	 5th_sem_marks,
	 
	 5th_sem_marks_max,
	 6th_sem_marks,
	 6th_sem_marks_max,
	 5th_sem_SGPA,
	 
	 6th_sem_SGPA,
	 attempt
	)
	values
	(
		\''.$_SESSION['login'].'\',
		\''.$_POST['course'][0].'\',
		\''.$_POST['name'].'\',
		\''.$_POST['address'].'\',
	
		\''.$_POST['email'].'\',
		\''.$_POST['mobile'].'\',
		\''.india_to_mysql_date($_POST['dob']).'\',
		\''.$_POST['catagory'].'\',
	
		\''.$_POST['sex'].'\',
		\''.$_POST['bsc'].'\',
		\''.$_POST['university'].'\',
		\''.$_POST['year'].'\',
	
		\''.$_POST['final_marks_obtained'].'\',
		\''.$_POST['final_marks_max'].'\',
		\''.$_POST['final_year_SGPA'].'\',
		\''.$_POST['5th_sem_marks'].'\',
	
		\''.$_POST['5th_sem_marks_max'].'\',
		\''.$_POST['6th_sem_marks'].'\',
		\''.$_POST['6th_sem_marks_max'].'\',
		\''.$_POST['5th_sem_SGPA'].'\',
	
		\''.$_POST['6th_sem_SGPA'].'\' ,
		\''.$_POST['attempt'].'\'
	)
	
	ON DUPLICATE KEY UPDATE    
 course=\''.$_POST['course'][0].'\',
 name=\''.$_POST['name'].'\',
 address=\''.$_POST['address'].'\',
 email=\''.$_POST['email'].'\',
 mobile=\''.$_POST['mobile'].'\',
 dob=\''.india_to_mysql_date($_POST['dob']).'\',
 catagory=\''.$_POST['catagory'].'\',
 sex=\''.$_POST['sex'].'\',
 bsc=\''.$_POST['bsc'].'\',
 university=\''.$_POST['university'].'\',
 year=\''.$_POST['year'].'\',
 final_marks_obtained=\''.$_POST['final_marks_obtained'].'\',
 final_marks_max=\''.$_POST['final_marks_max'].'\',
 final_year_SGPA=\''.$_POST['final_year_SGPA'].'\',
 5th_sem_marks=\''.$_POST['5th_sem_marks'].'\',
 5th_sem_marks_max=\''.$_POST['5th_sem_marks_max'].'\',
 6th_sem_marks=\''.$_POST['6th_sem_marks'].'\',
 6th_sem_marks_max=\''.$_POST['6th_sem_marks_max'].'\',
 5th_sem_SGPA=\''.$_POST['5th_sem_SGPA'].'\',
 6th_sem_SGPA=\''.$_POST['6th_sem_SGPA'].'\',							
 attempt=\''.$_POST['attempt'].'\'							
							
	';
	
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}

}



function information()
{
	
		$photo_id_proof='Photo and Name Identification proof: e.g UID, Driving Liscense';
		$dob_proof='Proof of Date of birth: e.g. School Leaving Certificate/Birth Certificate/SSC Passing Certificate';
		$bsc_proof='BSc Degree Certificate/ BSc Provisional Degree Certificate';
		$marks_proof='Final year BSc marks or grade sheet / 5th and 6th Semester marks or grade sheet';
		$attempt_proof='Attempt certificate from university';
		$catagory_proof='SC/ST/SEBC caste certificate, Physically handicaped and Non-creamy Layer certificate for SEBC valid on last date for application';
		$attachment='<ul><b>Required Documents:</b><li>'.$photo_id_proof.'</li><li>'.$dob_proof.'</li><li>'.$bsc_proof.'</li>
		<li>'.$marks_proof.'</li><li>'.$attempt_proof.'</li><li>'.$catagory_proof.'</li></ul>';


	return '

					<ul><b>Submission of physical application:</b>
						<li>Save, Lock only when complate, Print</li>
						<li>Print Application (two copies, one copy to be sent to this office, second copy for your record)</li>
						<li>Attach recent passport size photo and self-attest it</li>
						<li>Write Place, Date and Sign</li>
						<li>Donot make any correction by pen</li>
						<li>Attach self attested photocopies of required documents mentioned below</li>
						<li>Put completed application with attachment in a cover</li>
						<li>Write "Application for 30th Lab/X-Ray Technician Course" on upper left side of cover</li>
						<li>Send the cover to following address preferably by speedpost, registered post or courier</li>
						<li>
							<div style="background-color:#D6E0F2;border: 1px solid green ;width:25%;">
							<u>Address:</u><br>
							Office of the Dean<br>
							Governemnt Medical College<br>
							Majura Gate<br>
							Surat-395001<br>
							Gujarat<br>
				
							</div>
						</li>
						<li>Applications are also accepted by hand delivery at <u>INWARD SECTION</u> at above address during office time</l>
					</ul>'.$attachment.'
					<ul><b>Important Dates:</b>
						<li>Last day for online application is 10-07-2017 upto 24.00 </li>
						<li>Printed application must reach office of the Dean, GMC Surat before 18.00  on 15-07-2017</li>
						<li>Online appilcation is rejected if printed application donot reach in time</li>
						<li>Date of interview will be notified by at least one of following: SMS/email/newspaper/website</li>
					</ul>
					<ul><b>General:</b>
						<li>For any wrong information provided, the applicant will be removed from merit list on day of document varification and interview.</li>
						<li>Merit list will not be altered on provision of fresh evidence on day of document verification and interview</li>
					</ul>';
}

/*
function information()
{
	return '<ul>
					<ul><b>Important Dates:</b>
						<li>Last day for online application is DD-MM-YYYY</li>
						<li>Printed application must reach office of the Dean, GMC Surat befor 6.00 PM on DD-MM-YYYY</li>
						<li>Printed application, not reaching the office in time will ignored for preparing merit list</li>
					</ul>

					<ul><b>General:</b>
						<li>For any wrong information provided, the applicant will be removed from merit list on day of document varification and interview.</li>
						<li>Merit list will not be altered on provision of fresh evidence on day of document verification and interview</li>
					</ul>
		</ul>';
	
}
*/

////////////////Start/////////

echo '<html><head></head><body>';
if(isset($_POST['login']))
{
	unset(	$_SESSION['login']);
	unset(	$_SESSION['password']);
	$_SESSION['login']=$_POST['login'];
	$_SESSION['password']=$_POST['password'];
	if(!connect())
	{
	        unset(  $_SESSION['login']);
        	unset(  $_SESSION['password']);
	}
}

//echo '<pre>';print_r($GLOBALS);echo '</pre>';

if(isset($_SESSION['login']))
{
	$logout='
	
	<form method=post action=logout.php><button type=submit name=action value=logout><h3>Logout</h3></button></form>
	<form method=post action=change_pass.php><button type=submit name=action value=change_pass>
	<h3>Change Password</h3></button></form>
	
	';
}
else
{
	$logout='';
}
echo ' <table width="100%" border=5>
	<tr>
		<td align="center"><img src="college_logo.jpg" style="height:100;width:100;"></td>
		<td>'.$logout.'</td>
		<td>
			<h3  align=center>Online application, 30th Lab/X-Ray technician training course (2017-18) <br>
			Government Medical College Surat</h3></td>
		<td align="center"><img src="guj.jpg" style="height:100;width:100;"></td>
	</tr></table>';


if(isset($_POST['action']))
{
		
	if($_POST['action']=='logout')
	{
		logout();
		ask_login();
	}
	elseif($_POST['action']=='save')
	{
		$link=connect();
		save($link);
		$status=get_raw($link,'select * from application where id=\''.$_SESSION['login'].'\'');
		work($link,$status['locked']);
	}

	elseif($_POST['action']=='lock')
	{
		$link=connect();			

		if(update_one_field($link,'application','id',$_SESSION['login'],'locked','1')==true)
		{
			$status=get_raw($link,'select * from application where id=\''.$_SESSION['login'].'\'');
			work($link,$status['locked']);
		}
		else
		{
			echo '<h3>Not locked</h3>';
		}
	}
		
	elseif($_POST['action']=='login')
	{
		if(!$link=connect())
		{
			echo '<h3  align=center style="background-color:red">Login failed</h3>';		
			ask_login();
		}
		else
		{
			$link=connect();
			$status=get_raw($link,'select * from application where id=\''.$_SESSION['login'].'\'');
			//print_r($status);
			work($link,$status['locked']);
		}
	}
	elseif($_POST['action']=='send_sms')
	{
		if($link=login_varify())
		{
			select_database($link);
		}
		
		if(!user_exist($link,$_POST['mobile']))
		{
			$text=rand(11111,99999);
			//echo $text;
			$x=send_sms('password:'.$text,$_POST['mobile']);
			//echo 'result:'.$x;
			if(strlen($x)>0)
			{
				if(!create_user($link,$_POST['mobile'],$text))
				{
					echo '<h5 align=center style="background-color:lightgreen">login: '.$_POST['mobile'].' </h5>';
					echo '<h5 align=center style="background-color:lightgreen">Password: SMS sent to above mobile number. </h5>';
				}				
			}
			else 
			{
				echo '<h3 align=center style="background-color:red">SMS sending failed</h5>';
			}
		}
		else
		{
			echo '<h3 align=center style="background-color:red"><blink>User already exist</blink></h5>';
		}
		ask_login();
	}
	if($_POST['action']=='print')
	{
		print_application($link=connect());
	}
}
else
{
	ask_login();
}

echo information();
echo '</body></html>';

	/*
	
	

Online application

	        [course] => 1.Laboratory Technician
            [name] => shailesh
            [address] => sdf
            [email] => bio@g.com
            [mobile] => 9426328832
            [dob] => 02-06-2017
            [catagory] => SC
            [sex] => F
            [bsc] => Microbiology
            [university] => vnsgu
            [year] => 1934
            [ys] => 1.yearly result
            [mg] => 1.marks
            [final_marks_obtained] => 234
            [final_marks_max] => 450
            [action] => save
            
  `id` int(11) NOT NULL,
 `name` varchar(100) NOT NULL,
 `address` varchar(300) NOT NULL,
 `email` varchar(100) NOT NULL,
 `mobile` bigint(20) NOT NULL,
 `dob` date NOT NULL,
 `catagory` varchar(10) NOT NULL,
 `sex` varchar(1) NOT NULL,
 `bsc` varchar(30) NOT NULL,
 `university` varchar(100) NOT NULL,
 `year` int(11) NOT NULL,
 `final_marks_obtained` int(11) NOT NULL,
 `final_marks_max` int(11) NOT NULL,
 `final_year_SGPA` float NOT NULL,
 `5th_sem_marks` int(11) NOT NULL,
 `5th_sem_marks_max` int(11) NOT NULL,
 `6th_sem_marks` int(11) NOT NULL,
 `6th_sem_marks_max` int(11) NOT NULL,
 `5th_sem_SGPA` float NOT NULL,
 `6th_sem_SGPA` float NOT NULL,
 `locked` int(11) NOT NULL,
 `verify` int(11) NOT NULL,
 `merit` int(11) NOT NULL,
 `selected` int(11) NOT NULL,
 `remark` varchar(200) NOT NULL,
 
  id,
 name,
 address,
 email,
 mobile,
 dob,
 catagory,
 sex,
 bsc,
 university,
 year,
 final_marks_obtained,
 final_marks_max,
 final_year_SGPA,
 5th_sem_marks,
 5th_sem_marks_max,
 6th_sem_marks,
 6th_sem_marks_max,
 5th_sem_SGPA,
 6th_sem_SGPA,
 locked,
 verify,
 merit,
 selected,
 remark,
 
 $_POST['id'],
$_POST['name'],
$_POST['address'],
$_POST['email'],
$_POST['mobile'],
$_POST['dob'],
$_POST['catagory'],
$_POST['sex'],
$_POST['bsc'],
$_POST['university'],
$_POST['year'],
$_POST['final_marks_obtained'],
$_POST['final_marks_max'],
$_POST['final_year_SGPA'],
$_POST['5th_sem_marks'],
$_POST['5th_sem_marks_max'],
$_POST['6th_sem_marks'],
$_POST['6th_sem_marks_max'],
$_POST['5th_sem_SGPA'],
$_POST['6th_sem_SGPA'],
$_POST['locked'],
$_POST['verify'],
$_POST['merit'],
$_POST['selected'],
$_POST['remark'],
	
	*/
?>
