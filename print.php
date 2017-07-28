<?php
session_start();
require_once '/var/gmcs_config/staff.conf';
require_once('tcpdf/tcpdf.php');
require_once 'common.php';

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

function write_text($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('courier','B',10);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->MultiCell($w, $h, $text , $border=0, $align='R', 
					$fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);	
}
function write_text_small($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('courier','B',7);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->MultiCell($w, $h, $text , $border=0, $align='R', 
					$fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);	
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


function information()
{
	
		$photo_id_proof='Photo and Name Identification proof: e.g UID, Driving Liscense';
		$dob_proof='Proof of Date of birth: e.g. School Leaving Certificate/Birth Certificate/SSC Passing Certificate';
		$bsc_proof='BSc Degree Certificate/ BSc Provisional Degree Certificate';
		$marks_proof='Final year BSc marks or grade sheet / 5th and 6th Semester marks or grade sheet';
		$attempt_proof='Attempt certificate from university';
		$catagory_proof='SC/ST/SEBC caste certificate and Non-creamy Layer certificate for SEBC valid on last date of application';
		$attachment='<ul><b>Required Documents:</b><li>'.$photo_id_proof.'</li><li>'.$dob_proof.'</li><li>'.$bsc_proof.'</li>
		<li>'.$marks_proof.'</li><li>'.$attempt_proof.'</li><li>'.$catagory_proof.'</li></ul>';


	return '

					<ul><b>Submission of physical application:</b>
						<li>Print Application</li>
						<li>Attach recent passport size photo</li>
						<li>Write Place, Date and Sign </li>
						<li>Donot make any correction by pen</li>
						<li>Attach self attested photocopies of required documents mentioned below</li>
					</ul>'.$attachment.'
					<ul><b>Important Dates:</b>
						<li>Last day for online application is DD-MM-YYYY</li>
						<li>Printed application must reach office of the Dean, GMC Surat befor 6.00 PM on DD-MM-YYYY</li>
						<li>Printed application, not reaching the office in time will ignored for preparing merit list</li>
					</ul>
					<ul><b>General:</b>
						<li>For any wrong information provided, the applicant will be removed from merit list on day of document varification and interview.</li>
						<li>Merit list will not be altered on provision of fresh evidence on day of document verification and interview</li>
					</ul>';
}

function print_application($link)
{
	$dt=get_raw($link,'select * from application where id=\''.$_SESSION['login'].'\'');
	class APP extends TCPDF 
	{
		public function Header() 	{	}	
		public function Footer() 	{	}	
	}

$pdf = new APP('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetMargins(30, 20, 30);
$pdf->AddPage();
//$pdf->Write(10,	print_r($dt,true));
//$pdf->Write(5,'Application, 30th Lab/X-Ray tech training course (2017-18)',
				//$link='', $fill=false, $align='C', $ln=true, $stretch=0, $firstline=false, 
				//$firstblock=false, $maxh=0, $wadj=0, $margin='');
//$pdf->Write(5,'Government Medical College Surat',
				//$link='', $fill=false, $align='C', $ln=true, $stretch=0, $firstline=false, 
				//$firstblock=false, $maxh=0, $wadj=0, $margin='');

$str='';
$str=$str.' <table width="100%">
	<tr>
		<td width="10%"><img src="college_logo.jpg" style="height:50;width:50;"></td>
			<td width="80%" align="center">
			<h3  align=center>Online application, 30th Lab/X-Ray technician training course (2017-18) <br>
			Government Medical College Surat</h3></td>
		<td width="10%"><img src="guj.jpg" style="height:50;width:50;"></td>
	</tr></table><br><br><br>';


$str=$str.'<table border="0.3" cellpadding="2">
				<tr>
					<td width="25%" align="center">Applicant ID</td>
					<td width="50%">'.$dt['id'].'</td>
					<td  rowspan="9" width="25%" align="center">Affix <br>recent <br>self-attested<br>passport-size<br>photo</td>
				</tr>';

$co=array('Laboratory Technician','X-Ray Technician','Laboratory Technician + X-Ray Technician');
$str= $str.'<tr><td align="center">Applied For</td><td>'.$co[$dt['course']-1].'</td><td></td></tr>';
$str= $str.'<tr><td align="center">Name</td><td >'.$dt['name'].'</td></tr>';
$str= $str.'<tr><td align="center">Sex</td><td>'.$dt['sex'].'</td></tr>';
$str= $str.'<tr><td align="center">Address</td><td>'.$dt['address'].'</td></tr>';
$str= $str.'<tr><td align="center">email</td><td>'.$dt['email'].'</td></tr>';
$str= $str.'<tr><td align="center">Mobile</td><td>'.$dt['mobile'].'</td></tr>';
$str= $str.'<tr><td align="center">Birth Date</td><td>'.mysql_to_india_date($dt['dob']).'</td></tr>';
$str= $str.'<tr><td align="center">Catagory</td><td>'.$dt['catagory'].'</td></tr>';
$str=$str.'</table>';

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

$mcell='';
foreach($mks as $value)
{
	if($dt[$value]>0)
	{
		$mcell=$mcell.$value.'='.$dt[$value].'<br>';
	}
}
//$mcell=$mcell.'attempt='.$dt['attempt'];

$str= $str.'
			<br><br><br><br><table border="0.3" cellpadding="2">
			<tr><td colspan="5" align="center"><b>Educational Qualification</b></td></tr>
			<tr>	<td width="8%" align="center"><b>Qual</b></td>
					<td width="15%" align="center"><b>Univ</b></td>
					<td width="12%" align="center"><b>Year<br> of <br>Passing</b></td>
					<td width="18%" align="center"><b>Subject</b></td>
					<td width="35%" align="center"><b>Marks/Grade</b></td>
					<td width="12%" align="center"><b>Attempt</b></td></tr>
			<tr><td align="center">BSc</td>
			<td align="center">'.$dt['university'].'</td>
			<td align="center">'.$dt['year'].'</td>
			<td align="center">'.$dt['bsc'].'</td>
			<td align="center">'.$mcell.'</td>
			<td align="center">'.$dt['attempt'].'</td></tr>
			</table>';
		
$str=$str.'<ol>
					<li>I hereby give undertaking to abide by all rules & regulations set by Government Of Gujarat for Lab/X-Ray Technician Training Course.</li>
					<li>I hereby abide to follow all institutional rules & regulations during entire period of training.</li>
					<li>All information provided by me are correct.I understand that I will be debarred from admission process and training, if found to provide incorrect information.</li>
					
					
					</ol>';
			
$str=$str.'<table><tr><td><br></td></tr><tr><td width="15%">Place:</td><td width="25%"></td>
<td width="60%" align="right">_______________________<br></td></tr>';
$str=$str.'<tr><td>Date:</td><td></td><td align="right">Signature of Applicant</td></tr></table>';

                $photo_id_proof='Photo and Name Identification proof: e.g UID, Driving Liscense';
                $dob_proof='Proof of Date of birth: e.g. School Leaving Certificate/Birth Certificate/SSC Passing Certificate';
                $bsc_proof='BSc Degree Certificate/ BSc Provisional Degree Certificate';
                $marks_proof='Final year BSc marks or grade sheet / 5th and 6th Semester marks or grade sheet';
                $attempt_proof='Attempt certificate from university';
                $catagory_proof='SC/ST/SEBC caste certificate, Physically handicapped and Non-creamy Layer certificate for SEBC valid on last date for application';
                $attachment='<ul><b>Required Documents:</b><li>'.$photo_id_proof.'</li><li>'.$dob_proof.'</li><li>'.$bsc_proof.'</li>
                <li>'.$marks_proof.'</li><li>'.$attempt_proof.'</li><li>'.$catagory_proof.'</li></ul>';
$str=$str.$attachment;

//$str=$str.information();

//$str=$str.print_r($dt,true);
$pdf->SetFont('dejavusans', '', 10);
$str1='<h3 align="center">Office Copy</h3>'.$str;
$pdf->writeHTML($str1, true, false, true, false, '');
$pdf->addPage();
$str2='<h3 align="center">Student Copy</h3>'.$str;
$pdf->writeHTML($str2, true, false, true, false, '');
$pdf->Output($_SESSION['login'].'_application.pdf', 'I');
}


/////////////////////////////////

print_application($link=connect());
?>
