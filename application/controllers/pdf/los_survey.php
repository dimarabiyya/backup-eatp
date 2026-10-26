<?php
require_once APPPATH . 'libraries/tcpdf/tcpdf.php';

class Los_survey extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->mglobal->is_logged_in();
	}

	public function index()
	{
		$this->load->library('parser');
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nicola Asuni');
		$pdf->SetTitle('Engineering Form');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		
		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING);
		//$pdf->setFooterData(array(0,64,0), array(0,64,128));
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// add/remove default header/footer
		$pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		//$pdf->SetFont('dejavusans', '', 10, '', true);
		$pdf->SetFont('helvetica', 'BU', 16);
		
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage('P', 'A4');
		
		// set text shadow effect
		//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
		
		// $pdf->Write(0, 'Commisioning Control Quality', '', 0, 'C', 1, 0, false, false, 0);
		 // $pdf->Ln(10);
		
		$pdf->SetFont('helvetica', '', 10);
		
		// Set some content to print
		/*$html = <<<EOD
		<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
		<i>This is the first example of TCPDF library.</i>
		<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
		<p>Please check the source code documentation and other examples for further information.</p>
		<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
		EOD;*/
		
		

		$html = '
		<style>
		table{padding:2px; border-spacing:0px;}
		table th{font-weight:bold;}
		</style>
		';
		
		$sql = "
			SELECT
				pm_checklist.*,
				checklist.checklist_name
			FROM
				t_trx_pm_checklist_result pm_checklist
			JOIN t_mtr_checklist checklist ON checklist.checklist_id = pm_checklist.checklist_id
			WHERE pm_checklist.visit_id = 3
			LIMIT 8;
		";
		
		$data = $this->db->query($sql)->result_array();

		// print_r($data[0]['visit_id']); die();
		//$data = "test";
		
		$html .= $this->parser->parse('pdf/index', $data);
		//$html.='<div>abc</div>';

		// Print text using writeHTMLCell()
		//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
		//$pdf->AddSpotColor('My TCPDF Dark Green', 100, 50, 80, 45);
		//$pdf->SetFillSpotColor('My TCPDF Dark Green', 100);
		////$pdf->Rect(15, 140, 180, 20);
		//print_r($html); die();
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('Engineering_Form.pdf', 'I');
	}
}

// extend TCPF with custom functions
class MYPDF extends TCPDF {
	
	public function Header()
	{
		// // Logo
		// $image_file = K_PATH_IMAGES.'logo_example.jpg';
		// $this->Image($image_file, '', '', 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// // Set font
		// $this->SetFont('helvetica', '', 9);
		// // Title
		// $this->Cell(0, 15, 'Engineering Form: Conformance/Non Conformance of Commisioning', 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}
