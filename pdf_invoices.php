<?php
//PDF USING MULTIPLE PAGES
//CREATED BY: Carlos Vasquez S.
//E-MAIL: cvasquez@cvs.cl
//CVS TECNOLOGIA E INNOVACION
//SANTIAGO, CHILE

require('pdf_main.php');

//Connect to your database
include("common.php");

//Create new pdf file
$pdf=new FPDF();

//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//Add first page
$pdf->AddPage();

//set the margin
$pdf->SetMargins('0.5', '0.5', $right='5.5');

//set the display mode
$pdf->SetDisplayMode('fullpage', $layout='continuous');


//set initial y axis position per page
$y_axis_initial = 25;
$y_axis=25;
//print column titles
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->SetY($y_axis_initial);
$pdf->SetX(25);
$pdf->Cell(30,6,'ID',1,0,'L',1);
$pdf->Cell(60,6,'DATE',1,0,'L',1);
$pdf->Cell(60,6,'COUSTOMER',1,0,'L',1);
$pdf->Cell(30,6,'STORE CODE',1,0,'L',1);
$pdf->Cell(30,6,'PROFIT',1,0,'L',1);
$pdf->Cell(30,6,'TOTAL',1,0,'L',1);
$pdf->Cell(20,6,'STATUS',1,0,'L',1);
//Set Row Height
$row_height = 6;
$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$result=mysql_query('select * from `Invoice Dimension` ORDER BY `Invoice Date` DESC');

//initialize counter
$i = 0;

//Set maximum rows per page
$max = 50;



while($row = mysql_fetch_array($result))
{
	//If the current row is the last one, create new page and print column title
	if ($i == $max)
	{
		$pdf->AddPage();

		//print column titles for the current page
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(2500);
		$pdf->Cell(30,6,'ID',1,0,'L',1);
		$pdf->Cell(60,6,'DATE',1,0,'L',1);
		$pdf->Cell(60,6,'CUSTOMER',1,0,'L',1);
		$pdf->Cell(30,6,'STORE CODE',1,0,'L',1);
		$pdf->Cell(30,6,'PROFIT',1,0,'L',1);
		$pdf->Cell(30,6,'TOTAL',1,0,'L',1);
		$pdf->Cell(20,6,'STATUS',1,0,'L',1);		
		//Go to next row
		$y_axis = $y_axis + $row_height;
		
		//Set $i variable to 0 (first row)
		$i = 0;
	}

	$id = $row['Invoice Public ID'];	
	$date = $row['Invoice Date'];
	$coustomer_name = $row['Invoice Customer Name'];
	$store_code = $row['Invoice Store Code'];
	$profit = $row['Invoice Total Profit'];	
	$total = $row['Invoice Total Amount'];
	$status = $row['Invoice Paid'];	


	$pdf->SetY($y_axis);
	$pdf->SetX(25);
	$pdf->Cell(30,6,$id,1,0,'L',1);
	$pdf->Cell(60,6,$date,1,0,'L',1);
	$pdf->Cell(60,6,$coustomer_name,1,0,'L',1);
	$pdf->Cell(30,6,$store_code,1,0,'L',1);
	$pdf->Cell(30,6,$profit,1,0,'L',1);	
	$pdf->Cell(30,6,$total,1,0,'L',1);
	$pdf->Cell(20,6,$status,1,0,'L',1);
	

	//Go to next row
	$y_axis = $y_axis + $row_height;
	$i = $i + 1;
}



//Send file
$pdf->Output();
?>
