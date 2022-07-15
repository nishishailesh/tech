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
}
if($action=='save')
{

	save($link,$d,$t,$_POST,$_FILES);
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
	$sql_app='insert into application (id,mobile)
							values(\''.$ar['id'].'\',\''.$ar['id'].'\')
							on duplicate key update
							mobile=\''.$ar['id'].'\'';
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




function check_verification_status($link,$application_id)
{

}

tail();
?>

