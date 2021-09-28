<?php 

require_once('tcpdf/tcpdf.php');


function print_lable_general($link,$d,$pdf, $from,$to)
{
	$sql='select * from biblio,items 
									where biblio.biblionumber=items.biblionumber and 
									barcode between 
										convert(\''.$from.'\',UNSIGNED) and 
										convert(\''.$to.'\',UNSIGNED)';
	//echo $sql;
	//return;
	$result=run_query($link,$d,$sql);

	$pdf->SetFont('helveticaB', '', 9);
	while($ar=get_single_row($result))
	{
		$ln1=$ar['barcode'];
		$ln2=substr($ar['title'],0,30);
		$ln3=substr($ar['author'],0,15);
		$ln4=$ar['itemcallnumber'];
		
		$pdf->AddPage();
		//3 mm allside
		//height 25-6=19 for 4 lines 5 mm avilable
		//50-6=44 widht avilable
	
		$pdf->write1DBarcode($ln1, 'C128', 3, 3 , 33, 5 , 0.4, array(), 'N');

		$pdf->SetXY(3,8);
		$pdf->Cell (44,5,$ln1,$border=0, $ln=1, $align='', $fill=false, 
			$link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');	

		$pdf->SetXY(3,13);
		$pdf->Cell (44,5,$ln2,$border=0, $ln=1, $align='', $fill=false, 
			$link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');	

		$pdf->SetXY(3,18);
		$pdf->Cell (44,5,$ln3.'  '.$ln4,$border=0, $ln=1, $align='', $fill=false, 
			$link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');			
	}
}

class MYPDF_BARCODE extends TCPDF 
{
	public function Header() 
	{

	}

	// Page footer
	public function Footer() 
	{

	}
}

function initialize_pdf()
{
	// for barcode
	$style = array(
		'position' => '',
		'align' => 'C',
		'stretch' => false,
		'fitwidth' => true,
		'cellfitalign' => '',
		'border' => false,
		'hpadding' => 'auto',
		'vpadding' => '0',
		'fgcolor' => array(0,0,0),
		'bgcolor' => false, //array(255,255,255),
		'text' => true,
		'font' => 'helvetica',
		'fontsize' => 10,
		'stretchtext' => 4
	);


	$pdf = new MYPDF_BARCODE('', 'mm', array("50","25"), true, 'UTF-8', false);
	
	$pdf->SetMargins(0,0, $right=-1, $keepmargins=true);
	$pdf->setPrintFooter(false);
	$pdf->setPrintHeader(false);
	$pdf->SetAutoPageBreak(TRUE, 0);
	$pdf->setCellPaddings(0,0,0,0);

	return $pdf;

	//minimum 2 mm margin
	//25-4=21 available Y
	//50-5=46 available X
	//5 line 1, 10 barcode, 5 line 3
}

function get_items($d,$t)
{
	echo 
	'<form method=post target=_blank>
		<table class="table table-bordered bg-light">
		<tr><th class="text-center bg-warning" colspan=4>Print Barcode between two Accession numbers</th></tr>
				<input type=hidden name=^database readonly value=\''.$d.'\'>
				<input type=hidden name=^table readonly value=\''.$t.'\'>
			<tr><th>From Barcode</th><td><input type=number name=from placeholder="From Barcode"></td></tr>
			<tr><th>To Barcode</th><td><input type=number name=to placeholder="To Barcode"></td></tr>
			<tr><th colspan=4><button class="btn btn-info" type=submit name=action value=generate_pdf>Generate PDF Lables</button></th></tr>
		</table>
	</form>';
	
}

?>
