<?php
session_start();
require_once 'config.php';
error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED);

require_once '/var/gmcs_config/staff.conf';
require_once 'common_table_function.php';
require_once('tcpdf/tcpdf.php'); //if in /usr/share/php folder

//my_print_r($GLOBALS);
//RVogdc4R!

$ex=explode('|',$_POST['action']);
//print_r($ex);
if(count($ex)==3)
{
	$d=$ex[1];
	$t=$ex[2];
	$action=$ex[0];
}
else
{
	$d=$_POST['^database'];
	$t=$_POST['^table'];
	$action=$_POST['action'];	
}

if(isset($action))
{
	if($_POST['action']=='download' || $action=='print_pdf' || $action=='generate_pdf')
	{
		$GLOBALS['nojunk']=TRUE;
	}
}

if(isset($_POST['offset']))
{
	$offset=$_POST['offset'];
}
else
{
	$offset=0;
}

$link=set_session();

$dk=get_dependant_table($link,$d,$t);

//my_print_r($dk);

//if primary key is to be made readonly, it must be autoincrement
//autoincrement and default are readonly
$pk=get_primary_key($link,$d,$t);
$pka=array();
$pka_value=false;

foreach($pk as $pk_key)
{
	if(isset($_POST[$pk_key['Field']]))
	{
		$pka[$pk_key['Field']]=$_POST[$pk_key['Field']];
		$pka_value=true;
	}
	else
	{
		$pka[$pk_key['Field']]='';
	}
}

if($action=='download')
{
	download($link,$d,$t,$_POST['blob_field'],$pka);
	exit(0);
}

if(isset($_POST['offset']))
{
	$offset=$_POST['offset'];
}
else
{
	$offset=0;
}
	
head();
if($action!='help')
{
menu();
}

//help();
if($action!='print_pdf')
{
echo '<span class="badge badge-danger"><h5>Note:-</h5></span><h5 class="text-danger border border-primary rounded">For Laboratory technician course, BSc in Chemistry, Biochemistry, Microbiology or Biotechnology is essential. 
Applicant who have done BSc in any other subject are not eligible for Laboratory Technician Course.
For XRay technician course, BSc in any subject is acceptable, however, applicant with BSc in Physics are given preference</h5>'; 
echo '<span class="bg-warning"><h5 class="bg-warning">Read Instructions->General carefully before filling application. For filling Marks/SGPA read instruction 13.</h5></span>';
}
if($action=='save')
{
	//if(!is_application_verified($link,$d,$_POST['id']))
	//{

$mrk=array("final_year_marks_obtained",
"final_year_marks_max",
"final_year_SGPA",
"5th_sem_marks_obtained",
"5th_sem_marks_max",
"6th_sem_marks_obtained",
"6th_sem_marks_max",
"5th_sem_SGPA",
"6th_sem_SGPA"
);

foreach($mrk as $m)
{
	if(strlen($_POST[$m])==0){$_POST[$m]=0;}
}
		save($link,$d,$t,$_POST,$_FILES);
	//}
	//else
	//{
	//	echo '<h3>Application verified. Can not be changed. Contact office if required</h3>';
	//}
	edit($link,$d,$t,$GLOBALS['default'],$GLOBALS['default']);	
}
elseif($action=='insert')
{
	insert($link,$d,$t,$_POST,$_FILES);
}
elseif($action=='show_single_by_pk')
{
	show_search_rows_by_pka_full($link,$d,$t,$pka);
}
elseif($action=='edit')
{
	$ar=array('id'=>$_SESSION['login']);
	//$sql_app='insert into application (id,mobile) 
	//						values(\''.$ar['id'].'\',\''.$ar['id'].'\')
	//						on duplicate key update
	//						mobile=\''.$ar['id'].'\'';
	//$result=run_query($link,'tech',$sql_app);
	edit($link,$d,$t,$GLOBALS['default'],$GLOBALS['default']);
}
elseif($action=='delete')
{
	delete($link,$d,$t,$pka);
	$ar=array('id'=>$_SESSION['login']);
	$sql_app='insert into application (id,email)
							values(\''.$ar['id'].'\',\''.$ar['id'].'\')
							on duplicate key update
							email=\''.$ar['id'].'\'';
	$result=run_query($link,'tech',$sql_app);
	edit($link,$d,$t,$GLOBALS['default'],$GLOBALS['default']);
}

elseif($action=='print_pdf')
{
	if(validate($link,$d,$t,$GLOBALS['default']))
	{
		//echo'<table><tr><td width="66%">';
		print_pdf_v($link,$d,$t,mk_select_sql_from_default($link,$d,$t,$GLOBALS['default']));
		//echo'</td><td width="34%" border="1"></td></tr></table>';
		exit(0);
	}
	else
	{
		echo 'validataion failed. Complate application before printing';
	}
}
elseif($action=='new')
{
	add($link,$d,$t,$GLOBALS['default'],'display:block;');
}
elseif($action=='show_horizontal_all')
{
	show_horizontal_all($link,$d,$t,$offset,$GLOBALS['limit'],$GLOBALS['default']);
}
elseif($action=='search')
{
	search($link,$d,$t,$GLOBALS['default']);
}
elseif($action=='show_all_rows')
{
	show_all_rows($link,$d,$t,$offset,$GLOBALS['limit'],$GLOBALS['default']);
}
elseif($action=='show_search_details')
{
	show_search_rows_by_pka($link,$d,$t,$pka);
	show_dependent_rows($link,$d,$t,$pka);
	add_dependent_rows($link,$d,$t,$pka);
}
elseif($action=='show_search_rows')
{
	show_search_rows($link,$d,$t,$_POST);
}

/////PDF labels////////
elseif($action=='generate_pdf')
{
		if($_POST['from']<= $_POST['to'])
		{
			$pdf=initialize_pdf();
			print_lable_general($link,$d,$pdf, $_POST['from'], $_POST['to']);
			$pdf->Output($_SESSION['login'].'pdf', 'I');
		}
}
elseif($action=='help')
{
		help();
}
elseif($action=='print_label')
{
		get_items($d,$t);
}




function is_application_verified($link,$d,$application_id)
{

return false;

	$sql='select * from verification where id=\''.$application_id.'\'';
	$result=run_query($link,$d,$sql);
	$ar=get_single_row($result);
	if($ar['serial_number']>0){return true;}
	else{return false;}
}

tail();
?>

