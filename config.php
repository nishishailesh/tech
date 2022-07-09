<?php
$GLOBALS['login_message']='Application for Lab/X-Ray Tech Training';
$GLOBALS['user_database']='tech';
$GLOBALS['user_table']='user';
$GLOBALS['user_id']='id';
$GLOBALS['user_pass']='password';
$GLOBALS['expiry_period']='+ 6 months';
$GLOBALS['nojunk']=false;
$GLOBALS['expirydate_field']='expirydate';

$GLOBALS['textarea_size']=120;	//for input vs textarea
$GLOBALS['limit']=10;			//for show all
$GLOBALS['search_limit']=10;	//for search

$GLOBALS['menu']=array
					(
						'Application'=>array(
											//'Search'=>array('search|tech|application','main.php',''),
											//'Show All'=>array('show_all_rows|tech|application','main.php',''),
											//'Show All (Table)'=>array('show_horizontal_all|tech|application','main.php',''),
											'Fill'=>array('edit|tech|application','main.php',''),				
										'Print'=>array('print_pdf|tech|application','main.php','formtarget=_blank'),
										//'Print All(pdf)'=>array('print_pdf|tech|application','main.php','formtarget=_blank'),
									//'Print label'=>array('print_label|tech|application','main.php','')
										),
										
						'Instructions'=>array(
										'General'=>array('help|tech|application','main.php','formtarget=_blank')
									)
					);

if(isset($_SESSION['login']))
{
	$GLOBALS['default']=array('id'=>$_SESSION['login']);
	//$GLOBALS['default']=array();
}

$GLOBALS['send_email']=1;
//$GLOBALS['email_database_server']='11.207.1.2';
$GLOBALS['email_database_server']='127.0.0.1';
?>
