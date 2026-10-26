<?php

/**
* @author Manullang
*/
class Export extends CI_Controller
{
	public function __construct()
       {
              parent::__construct();
              $this->mglobal->is_logged_in();
              $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
              //$this->load->library('PHPExcel', NULL, 'excel');
       }

       public function index()
       {
              //get visit_id visit_type dari url
              $data['id'] =  $this->core->decode($this->uri->segment(4));
              $data['type'] =  $this->core->decode($this->uri->segment(5));
              $data['content_id'] = 'export_view';
                //echo $data['id'];
                //echo "<br>";
                //echo $data['type'];
              $this->load->view('template/smashing', $data);
       }

       public function return_export($visit_id)
       {
              $data = $this->load->model('export_model')->form_material($visit_id);
              $current_date = date('d-m-Y');
              
              $objPHPExcel = IOFactory::load("./file/template/export/Return_Form.xlsx");
              $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('AK11', $current_date )
                            //->setCellValue('S15', $data['ID5'] )
                            ->setCellValue('L20', $data['ewo_no'] )
                            //->setCellValue('L22', $data['ID5'] ) //?
                            //->setCellValue('L24', $data['ID5'] ) //kosong
                            ->setCellValue('AK28', $data['site_id_ne'] )
                            ->setCellValue('I28', $data['ID521'] )
                           ->setCellValue('I30', $data['ID522'] )
                           ->setCellValue('I32', $data['ID523'] )
                           ->setCellValue('I34', $data['ID524'] )
                           ->setCellValue('I36', $data['ID525'] )
                           ->setCellValue('AH36', $data['ID526'] )
                           ->setCellValue('E43', $data['ID527'] )
                           ->setCellValue('N43', $data['ID528'] )
                           ->setCellValue('AE43', $data['ID529'] )
                           ->setCellValue('AO43', $data['ID530'] )
                           ->setCellValue('AR43', $data['ID531'] )
                           ->setCellValue('AU43', $data['ID532'] )
                           ->setCellValue('AY43', $data['ID533'] )
                           ->setCellValue('E45', $data['ID534'] )
                           ->setCellValue('N45', $data['ID535'] )
                           ->setCellValue('AE45', $data['ID536'] )
                           ->setCellValue('AO45', $data['ID537'] )
                           ->setCellValue('AR45', $data['ID538'] )
                           ->setCellValue('AU45', $data['ID539'] )
                           ->setCellValue('AY45', $data['ID540'] )
                           ->setCellValue('E47', $data['ID541'] )
                           ->setCellValue('N47', $data['ID542'] )
                           ->setCellValue('AE47', $data['ID543'] )
                           ->setCellValue('AO47', $data['ID544'] )
                           ->setCellValue('AR47', $data['ID545'] )
                           ->setCellValue('AU47', $data['ID546'] )
                           ->setCellValue('AY47', $data['ID547'] )
                           ->setCellValue('E49', $data['ID548'] )
                           ->setCellValue('N49', $data['ID549'] )
                           ->setCellValue('AE49', $data['ID550'] )
                           ->setCellValue('AO49', $data['ID551'] )
                           ->setCellValue('AR49', $data['ID552'] )
                           ->setCellValue('AU49', $data['ID553'] )
                           ->setCellValue('AY49', $data['ID554'] )
                           ->setCellValue('E51', $data['ID555'] )
                           ->setCellValue('N51', $data['ID556'] )
                           ->setCellValue('AE51', $data['ID557'] )
                           ->setCellValue('AO51', $data['ID558'] )
                           ->setCellValue('AR51', $data['ID559'] )
                           ->setCellValue('AU51', $data['ID560'] )
                           ->setCellValue('AY51', $data['ID561'] )
                           ->setCellValue('E53', $data['ID562'] )
                           ->setCellValue('N53', $data['ID563'] )
                           ->setCellValue('AE53', $data['ID564'] )
                           ->setCellValue('AO53', $data['ID565'] )
                           ->setCellValue('AR53', $data['ID566'] )
                           ->setCellValue('AU53', $data['ID567'] )
                           ->setCellValue('AY53', $data['ID568'] )
                           ->setCellValue('E55', $data['ID569'] )
                           ->setCellValue('N55', $data['ID570'] )
                           ->setCellValue('AE55', $data['ID571'] )
                           ->setCellValue('AO55', $data['ID572'] )
                           ->setCellValue('AR55', $data['ID573'] )
                           ->setCellValue('AU55', $data['ID574'] )
                           ->setCellValue('AY55', $data['ID575'] )
                           ->setCellValue('E57', $data['ID576'] )
                           ->setCellValue('N57', $data['ID577'] )
                           ->setCellValue('AE57', $data['ID578'] )
                           ->setCellValue('AO57', $data['ID579'] )
                           ->setCellValue('AR57', $data['ID580'] )
                           ->setCellValue('AU57', $data['ID581'] )
                           ->setCellValue('AY57', $data['ID582'] )
                           ->setCellValue('E59', $data['ID583'] )
                           ->setCellValue('N59', $data['ID584'] )
                           ->setCellValue('AE59', $data['ID585'] )
                           ->setCellValue('AO59', $data['ID586'] )
                           ->setCellValue('AR59', $data['ID587'] )
                           ->setCellValue('AU59', $data['ID588'] )
                           ->setCellValue('AY59', $data['ID589'] )
                           ->setCellValue('E61', $data['ID590'] )
                           ->setCellValue('N61', $data['ID591'] )
                           ->setCellValue('AE61', $data['ID592'] )
                           ->setCellValue('AO61', $data['ID593'] )
                           ->setCellValue('AR61', $data['ID594'] )
                           ->setCellValue('AU61', $data['ID595'] )
                           ->setCellValue('AY61', $data['ID596'] )
                           ->setCellValue('E63', $data['ID597'] )
                           ->setCellValue('N63', $data['ID598'] )
                           ->setCellValue('AE63', $data['ID599'] )
                           ->setCellValue('AO63', $data['ID600'] )
                           ->setCellValue('AR63', $data['ID601'] )
                           ->setCellValue('AU63', $data['ID602'] )
                           ->setCellValue('AY63', $data['ID603'] )
                           ->setCellValue('E65', $data['ID604'] )
                           ->setCellValue('N65', $data['ID605'] )
                           ->setCellValue('AE65', $data['ID606'] )
                           ->setCellValue('AO65', $data['ID607'] )
                           ->setCellValue('AR65', $data['ID608'] )
                           ->setCellValue('AU65', $data['ID609'] )
                           ->setCellValue('AY65', $data['ID610'] )
                           ->setCellValue('H69', $data['ID611'] )
                           ->setCellValue('G78', $data['hs_name'] )
                           ->setCellValue('I79', 'PT. XL Axiata' )
                           ->setCellValue('F80', $data['hs_date'] )
                           ->setCellValue('Q78', $data['alita_name'] )
                           ->setCellValue('T79', 'PT. Alita' )
                           ->setCellValue('Q80', $data['alita_date'] )
                           ->setCellValue('AF78', $data['hs_name'] )
                           ->setCellValue('AH79', 'PT. XL Axiata' )
                           ->setCellValue('AE80', $data['hs_date'] )
                           ->setCellValue('AS78', $data['alita_name'] )
                           ->setCellValue('AT79', 'PT. Alita' )
                           ->setCellValue('AR80', $data['alita_date'] )
                     ;
              //order_type 1=new=nbwo, 2=Change=ewo
              if($data['order_type'] == 1)
              {
                $nbwo = new PHPExcel_Worksheet_Drawing();
                $nbwo->setWorksheet($objPHPExcel->getActiveSheet());
                $nbwo->setName("New:");
                $nbwo->setPath('./assets/images/centang.png' );
                $nbwo->setHeight(15);
                $nbwo->setCoordinates('C15');

                $objPHPExcel->getActiveSheet()->setCellValue('G15', '');
              }
              else
              {
                $objPHPExcel->getActiveSheet()->setCellValue('C15', '');

                $ewo = new PHPExcel_Worksheet_Drawing();
                $ewo->setWorksheet($objPHPExcel->getActiveSheet());
                $ewo->setName("Change:");
                $ewo->setPath('./assets/images/centang.png' );
                $ewo->setHeight(15);
                $ewo->setCoordinates('G15');
              }
              
              //Tandatangan objDrawingPType
              $objSent = new PHPExcel_Worksheet_Drawing();
              $objSent->setWorksheet($objPHPExcel->getActiveSheet());
              $objSent->setName("Sent By:");
              $objSent->setPath('.'.$data['ttd_fop_hs'] );
              $objSent->setHeight(30);
              $objSent->setCoordinates('F76');

              $objApproved = new PHPExcel_Worksheet_Drawing();
              $objApproved->setWorksheet($objPHPExcel->getActiveSheet());
              $objApproved->setName("Approved By:");
              $objApproved->setPath('.'.$data['ttd_fop_alita'] );
              $objApproved->setHeight(30);
              $objApproved->setCoordinates('Q76');

              $objDelivered = new PHPExcel_Worksheet_Drawing();
              $objDelivered->setWorksheet($objPHPExcel->getActiveSheet());
              $objDelivered->setName("Delivered By:");
              $objDelivered->setPath('.'.$data['ttd_fop_hs'] );
              $objDelivered->setHeight(30);
              $objDelivered->setCoordinates('AE76');

              $objReceived = new PHPExcel_Worksheet_Drawing();
              $objReceived->setWorksheet($objPHPExcel->getActiveSheet());
              $objReceived->setName("Received By:");
              $objReceived->setPath('.'.$data['ttd_fop_alita'] );
              $objReceived->setHeight(30);
              $objReceived->setCoordinates('AR76');


              // We'll be outputting an excel file
              //header('Content-type: application/vnd.ms-excel');
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Return_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function reloc_export($visit_id)
       {
              $data = $this->load->model('export_model')->form_material($visit_id);
              $current_date = date('d-m-Y');
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/Reloc_Form.xlsx");
              $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('AK11', $current_date )
                            //->setCellValue('S15', $data['ID6'] )
                            ->setCellValue('L20', $data['ewo_no'] )
                            //->setCellValue('L22', $data['ID6'] ) //?
                            //->setCellValue('L24', $data['ID6'] ) //kosong
                            ->setCellValue('AK28', $data['site_id_ne'] )
                            ->setCellValue('AK30', $data['site_id_fe'] )
                            ->setCellValue('I28', $data['ID628'] )
                           ->setCellValue('I30', $data['ID629'] )
                           ->setCellValue('I32', $data['ID630'] )
                           ->setCellValue('I34', $data['ID631'] )
                           ->setCellValue('I36', $data['ID632'] )
                           ->setCellValue('AH36', $data['ID633'] )
                           ->setCellValue('E43', $data['ID634'] )
                           ->setCellValue('N43', $data['ID635'] )
                           ->setCellValue('AE43', $data['ID636'] )
                           ->setCellValue('AO43', $data['ID637'] )
                           ->setCellValue('AR43', $data['ID638'] )
                           ->setCellValue('AU43', $data['ID639'] )
                           ->setCellValue('AY43', $data['ID640'] )
                           ->setCellValue('E45', $data['ID641'] )
                           ->setCellValue('N45', $data['ID642'] )
                           ->setCellValue('AE45', $data['ID643'] )
                           ->setCellValue('AO45', $data['ID644'] )
                           ->setCellValue('AR45', $data['ID645'] )
                           ->setCellValue('AU45', $data['ID646'] )
                           ->setCellValue('AY45', $data['ID647'] )
                           ->setCellValue('E47', $data['ID648'] )
                           ->setCellValue('N47', $data['ID649'] )
                           ->setCellValue('AE47', $data['ID650'] )
                           ->setCellValue('AO47', $data['ID651'] )
                           ->setCellValue('AR47', $data['ID652'] )
                           ->setCellValue('AU47', $data['ID653'] )
                           ->setCellValue('AY47', $data['ID654'] )
                           ->setCellValue('E49', $data['ID655'] )
                           ->setCellValue('N49', $data['ID656'] )
                           ->setCellValue('AE49', $data['ID657'] )
                           ->setCellValue('AO49', $data['ID658'] )
                           ->setCellValue('AR49', $data['ID659'] )
                           ->setCellValue('AU49', $data['ID660'] )
                           ->setCellValue('AY49', $data['ID661'] )
                           ->setCellValue('E51', $data['ID662'] )
                           ->setCellValue('N51', $data['ID663'] )
                           ->setCellValue('AE51', $data['ID664'] )
                           ->setCellValue('AO51', $data['ID665'] )
                           ->setCellValue('AR51', $data['ID666'] )
                           ->setCellValue('AU51', $data['ID667'] )
                           ->setCellValue('AY51', $data['ID668'] )
                           ->setCellValue('E53', $data['ID669'] )
                           ->setCellValue('N53', $data['ID670'] )
                           ->setCellValue('AE53', $data['ID671'] )
                           ->setCellValue('AO53', $data['ID672'] )
                           ->setCellValue('AR53', $data['ID673'] )
                           ->setCellValue('AU53', $data['ID674'] )
                           ->setCellValue('AY53', $data['ID675'] )
                           ->setCellValue('E55', $data['ID676'] )
                           ->setCellValue('N55', $data['ID677'] )
                           ->setCellValue('AE55', $data['ID678'] )
                           ->setCellValue('AO55', $data['ID679'] )
                           ->setCellValue('AR55', $data['ID680'] )
                           ->setCellValue('AU55', $data['ID681'] )
                           ->setCellValue('AY55', $data['ID682'] )
                           ->setCellValue('E57', $data['ID683'] )
                           ->setCellValue('N57', $data['ID684'] )
                           ->setCellValue('AE57', $data['ID685'] )
                           ->setCellValue('AO57', $data['ID686'] )
                           ->setCellValue('AR57', $data['ID687'] )
                           ->setCellValue('AU57', $data['ID688'] )
                           ->setCellValue('AY57', $data['ID689'] )
                           ->setCellValue('E59', $data['ID690'] )
                           ->setCellValue('N59', $data['ID691'] )
                           ->setCellValue('AE59', $data['ID692'] )
                           ->setCellValue('AO59', $data['ID693'] )
                           ->setCellValue('AR59', $data['ID694'] )
                           ->setCellValue('AU59', $data['ID695'] )
                           ->setCellValue('AY59', $data['ID696'] )
                           ->setCellValue('E61', $data['ID697'] )
                           ->setCellValue('N61', $data['ID698'] )
                           ->setCellValue('AE61', $data['ID699'] )
                           ->setCellValue('AO61', $data['ID700'] )
                           ->setCellValue('AR61', $data['ID701'] )
                           ->setCellValue('AU61', $data['ID702'] )
                           ->setCellValue('AY61', $data['ID703'] )
                           ->setCellValue('E63', $data['ID704'] )
                           ->setCellValue('N63', $data['ID705'] )
                           ->setCellValue('AE63', $data['ID706'] )
                           ->setCellValue('AO63', $data['ID707'] )
                           ->setCellValue('AR63', $data['ID708'] )
                           ->setCellValue('AU63', $data['ID709'] )
                           ->setCellValue('AY63', $data['ID710'] )
                           ->setCellValue('E65', $data['ID711'] )
                           ->setCellValue('N65', $data['ID712'] )
                           ->setCellValue('AE65', $data['ID713'] )
                           ->setCellValue('AO65', $data['ID714'] )
                           ->setCellValue('AR65', $data['ID715'] )
                           ->setCellValue('AU65', $data['ID716'] )
                           ->setCellValue('AY65', $data['ID717'] )
                           ->setCellValue('H69', $data['ID718'] )
                          // ->setCellValue('F77', $data['ID719'] )
                           ->setCellValue('F78', $data['hs_name'] )
                           ->setCellValue('H79', 'PT. XL Axiata' )
                           ->setCellValue('F80', $data['hs_date'] )
                           //->setCellValue('Q77', $data['hs_name'] )
                           ->setCellValue('Q78', $data['alita_name'] )
                           ->setCellValue('T79', 'PT. Alita' )
                           ->setCellValue('Q80', $data['alita_date'] )
                           //->setCellValue('AE77', $data['ID620'] )
                           ->setCellValue('AF78', $data['hs_name'] )
                           ->setCellValue('AH79', 'PT. XL Axiata' )
                           ->setCellValue('AE80', $data['hs_date'] )
                           //->setCellValue('AR77', $data['ID624'] )
                           ->setCellValue('AS78', $data['alita_name'] )
                           ->setCellValue('AT79', 'PT. Alita' )
                           ->setCellValue('AR80', $data['alita_date'] )
                     ;

              //order_type 1=new=nbwo, 2=Change=ewo
              if($data['order_type'] == 1)
              {
                $nbwo = new PHPExcel_Worksheet_Drawing();
                $nbwo->setWorksheet($objPHPExcel->getActiveSheet());
                $nbwo->setName("New:");
                $nbwo->setPath('./assets/images/centang.png' );
                $nbwo->setHeight(15);
                $nbwo->setCoordinates('C15');

                $objPHPExcel->getActiveSheet()->setCellValue('G15', '');
              }
              else
              {
                $objPHPExcel->getActiveSheet()->setCellValue('C15', '');

                $ewo = new PHPExcel_Worksheet_Drawing();
                $ewo->setWorksheet($objPHPExcel->getActiveSheet());
                $ewo->setName("Change:");
                $ewo->setPath('./assets/images/centang.png' );
                $ewo->setHeight(15);
                $ewo->setCoordinates('G15');
              }
               
              //Tandatangan objDrawingPType
              $objSent = new PHPExcel_Worksheet_Drawing();
              $objSent->setWorksheet($objPHPExcel->getActiveSheet());
              $objSent->setName("Sent By:");
              $objSent->setPath('.'.$data['ttd_fop_hs'] );
              $objSent->setHeight(30);
              $objSent->setCoordinates('F76');

              $objApproved = new PHPExcel_Worksheet_Drawing();
              $objApproved->setWorksheet($objPHPExcel->getActiveSheet());
              $objApproved->setName("Approved By:");
              $objApproved->setPath('.'.$data['ttd_fop_alita'] );
              $objApproved->setHeight(30);
              $objApproved->setCoordinates('Q76');

              $objDelivered = new PHPExcel_Worksheet_Drawing();
              $objDelivered->setWorksheet($objPHPExcel->getActiveSheet());
              $objDelivered->setName("Delivered By:");
              $objDelivered->setPath('.'.$data['ttd_fop_hs'] );
              $objDelivered->setHeight(30);
              $objDelivered->setCoordinates('AE76');

              $objReceived = new PHPExcel_Worksheet_Drawing();
              $objReceived->setWorksheet($objPHPExcel->getActiveSheet());
              $objReceived->setName("Received By:");
              $objReceived->setPath('.'.$data['ttd_fop_alita'] );
              $objReceived->setHeight(30);
              $objReceived->setCoordinates('AR76');

               // We'll be outputting an excel file
              //header('Content-type: application/vnd.ms-excel');
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Reloc_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
       }

       public function scrap_export($visit_id)
       {
              $data = $this->load->model('export_model')->form_material($visit_id);
              $current_date = date('d-m-Y');
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/Scrap_Form.xlsx");
              $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('AK11', $current_date )
                            //->setCellValue('S15', $data['ID7'] )
                            ->setCellValue('L20', $data['ewo_no'] )
                            //->setCellValue('L22', $data['ID7'] ) //?
                            //->setCellValue('L24', $data['ID7'] ) //kosong
                            ->setCellValue('AK28', $data['site_id_ne'] )
                            ->setCellValue('I28', $data['735'] )
                           ->setCellValue('I30', $data['ID736'] )
                           ->setCellValue('I32', $data['ID737'] )
                           ->setCellValue('I34', $data['ID738'] )
                           ->setCellValue('I36', $data['ID739'] )
                           ->setCellValue('AH36', $data['ID740'] )
                           ->setCellValue('E43', $data['ID741'] )
                           ->setCellValue('N43', $data['ID742'] )
                           ->setCellValue('AE43', $data['ID743'] )
                           ->setCellValue('AO43', $data['ID744'] )
                           ->setCellValue('AR43', $data['ID745'] )
                           ->setCellValue('AU43', $data['ID746'] )
                           ->setCellValue('AY43', $data['ID747'] )
                           ->setCellValue('E45', $data['ID748'] )
                           ->setCellValue('N45', $data['ID749'] )
                           ->setCellValue('AE45', $data['ID750'] )
                           ->setCellValue('AO45', $data['ID751'] )
                           ->setCellValue('AR45', $data['ID752'] )
                           ->setCellValue('AU45', $data['ID753'] )
                           ->setCellValue('AY45', $data['ID754'] )
                           ->setCellValue('E47', $data['ID755'] )
                           ->setCellValue('N47', $data['ID756'] )
                           ->setCellValue('AE47', $data['ID757'] )
                           ->setCellValue('AO47', $data['ID758'] )
                           ->setCellValue('AR47', $data['ID759'] )
                           ->setCellValue('AU47', $data['ID760'] )
                           ->setCellValue('AY47', $data['ID761'] )
                           ->setCellValue('E49', $data['ID762'] )
                           ->setCellValue('N49', $data['ID763'] )
                           ->setCellValue('AE49', $data['ID764'] )
                           ->setCellValue('AO49', $data['ID765'] )
                           ->setCellValue('AR49', $data['ID766'] )
                           ->setCellValue('AU49', $data['ID767'] )
                           ->setCellValue('AY49', $data['ID768'] )
                           ->setCellValue('E51', $data['ID769'] )
                           ->setCellValue('N51', $data['ID770'] )
                           ->setCellValue('AE51', $data['ID771'] )
                           ->setCellValue('AO51', $data['ID772'] )
                           ->setCellValue('AR51', $data['ID773'] )
                           ->setCellValue('AU51', $data['ID774'] )
                           ->setCellValue('AY51', $data['ID775'] )
                           ->setCellValue('E53', $data['ID776'] )
                           ->setCellValue('N53', $data['ID777'] )
                           ->setCellValue('AE53', $data['ID778'] )
                           ->setCellValue('AO53', $data['ID779'] )
                           ->setCellValue('AR53', $data['ID780'] )
                           ->setCellValue('AU53', $data['ID781'] )
                           ->setCellValue('AY53', $data['ID782'] )
                           ->setCellValue('E55', $data['ID783'] )
                           ->setCellValue('N55', $data['ID784'] )
                           ->setCellValue('AE55', $data['ID785'] )
                           ->setCellValue('AO55', $data['ID786'] )
                           ->setCellValue('AR55', $data['ID787'] )
                           ->setCellValue('AU55', $data['ID788'] )
                           ->setCellValue('AY55', $data['ID789'] )
                           ->setCellValue('E57', $data['ID790'] )
                           ->setCellValue('N57', $data['ID791'] )
                           ->setCellValue('AE57', $data['ID792'] )
                           ->setCellValue('AO57', $data['ID793'] )
                           ->setCellValue('AR57', $data['ID794'] )
                           ->setCellValue('AU57', $data['ID795'] )
                           ->setCellValue('AY57', $data['ID797'] )
                           ->setCellValue('E59', $data['ID798'] )
                           ->setCellValue('N59', $data['ID799'] )
                           ->setCellValue('AE59', $data['ID800'] )
                           ->setCellValue('AO59', $data['ID801'] )
                           ->setCellValue('AR59', $data['ID802'] )
                           ->setCellValue('AU59', $data['ID803'] )
                           ->setCellValue('AY59', $data['ID804'] )
                           ->setCellValue('E61', $data['ID805'] )
                           ->setCellValue('N61', $data['ID806'] )
                           ->setCellValue('AE61', $data['ID807'] )
                           ->setCellValue('AO61', $data['ID808'] )
                           ->setCellValue('AR61', $data['ID809'] )
                           ->setCellValue('AU61', $data['ID810'] )
                           ->setCellValue('AY61', $data['ID811'] )
                           ->setCellValue('E63', $data['ID812'] )
                           ->setCellValue('N63', $data['ID813'] )
                           ->setCellValue('AE63', $data['ID814'] )
                           ->setCellValue('AO63', $data['ID815'] )
                           ->setCellValue('AR63', $data['ID816'] )
                           ->setCellValue('AU63', $data['ID817'] )
                           ->setCellValue('AY63', $data['ID818'] )
                           ->setCellValue('E65', $data['ID819'] )
                           ->setCellValue('N65', $data['ID820'] )
                           ->setCellValue('AE65', $data['ID821'] )
                           ->setCellValue('AO65', $data['ID822'] )
                           ->setCellValue('AR65', $data['ID823'] )
                           ->setCellValue('AU65', $data['ID824'] )
                           ->setCellValue('AY65', $data['ID825'] )
                           ->setCellValue('H69', $data['ID826'] )
                           ->setCellValue('K78', $data['hs_name'] )
                           ->setCellValue('K79', 'PT. XL Axiata' )
                           ->setCellValue('K80', $data['hs_date'] )
                           ->setCellValue('AR78', $data['alita_name'] )
                           ->setCellValue('AR79', 'PT. Alita' )
                           ->setCellValue('AR80', $data['alita_date'] )
                     ;

              //order_type 1=new=nbwo, 2=Change=ewo
              if($data['order_type'] == 1)
              {
                $nbwo = new PHPExcel_Worksheet_Drawing();
                $nbwo->setWorksheet($objPHPExcel->getActiveSheet());
                $nbwo->setName("New:");
                $nbwo->setPath('./assets/images/centang.png' );
                $nbwo->setHeight(15);
                $nbwo->setCoordinates('C15');

                $objPHPExcel->getActiveSheet()->setCellValue('G15', '');
              }
              else
              {
                $objPHPExcel->getActiveSheet()->setCellValue('C15', '');

                $ewo = new PHPExcel_Worksheet_Drawing();
                $ewo->setWorksheet($objPHPExcel->getActiveSheet());
                $ewo->setName("Change:");
                $ewo->setPath('./assets/images/centang.png' );
                $ewo->setHeight(15);
                $ewo->setCoordinates('G15');
              }
               
              //Tandatangan objDrawingPType
              $objSent = new PHPExcel_Worksheet_Drawing();
              $objSent->setWorksheet($objPHPExcel->getActiveSheet());
              $objSent->setName("Sent By:");
              $objSent->setPath('.'.$data['ttd_fop_hs'] );
              $objSent->setHeight(30);
              $objSent->setCoordinates('K76');

              $objApproved = new PHPExcel_Worksheet_Drawing();
              $objApproved->setWorksheet($objPHPExcel->getActiveSheet());
              $objApproved->setName("Approved By:");
              $objApproved->setPath('.'.$data['ttd_fop_alita'] );
              $objApproved->setHeight(30);
              $objApproved->setCoordinates('AR76');

              //header('Content-type: application/vnd.ms-excel');
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Scrap_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
       }

       public function data($visit_id)
       { 
              $objPHPExcel = IOFactory::load("./file/template/export/Data.xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 835 AND 1484) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=835; $i <= 1484; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 1;
              $row = 3;
              foreach ($all_data as $key => $value) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                  if($col == 13){
                     $col = 0;
                     $row++;
                  }
                  $col++;
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="DATA_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function list_mltn($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/List_MLTN.xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 1485 AND 1844) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=1485; $i <= 1844; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 1;
              $row = 5;
              $next = 5;
              foreach ($all_data as $key => $value) {
                  if($row==25 || $row==46 || $row==67 || $row==88 || $row==109 || $row==130 || $row==151 || $row==172){
                     $row++;
                  }
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);

                  if($col == 2){
                     $col = 1;
                     $row++;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="MLTN_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function list_mle($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/List_MLE.xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 1845 AND 2204) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=1845; $i <= 2204; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 1;
              $row = 5;
              foreach ($all_data as $key => $value) {
                  if($row==25 || $row==46 || $row==67 || $row==88 || $row==109 || $row==130 || $row==151 || $row==172){
                     $row++;
                  }
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);

                  if($col == 3){
                     $col = 1;
                     $row++;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="MLE_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function simf_instalasi($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/SIMF (Instalasi).xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 2205 AND 2514) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=2205; $i <= 2514; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 1;
              $row = 3;
              foreach ($all_data as $key => $value) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);

                  if($col == 31){
                     $col = 1;
                     $row++;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Instalasi_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function simf_dismantle($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/SIMF (Dismantle).xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 2515 AND 2814) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=2515; $i <= 2814; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 1;
              $row = 3;
              foreach ($all_data as $key => $value) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);

                  if($col == 30){
                     $col = 1;
                     $row++;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Dismantle_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function simf_antenna($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/IMS (Antenna).xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 2815 AND 3034) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=2815; $i <= 3034; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 0;
              $row = 3;
              foreach ($all_data as $key => $value) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);

                  if($col == 21){
                     $col = 0;
                     $row++;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Antenna_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function ims_idu($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/IMS (IDU).xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 3035 AND 3204) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=3035; $i <= 3204; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 0;
              $row = 3;
              foreach ($all_data as $key => $value) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $key);

                  if($col == 16){
                     $col = 0;
                     $row++;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="IDU_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function ims_odu($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/IMS (ODU).xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 3205 AND 3414) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=3205; $i <= 3414; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 0;
              $row = 4;
              foreach ($all_data as $key => $value) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);

                  if($col == 20){
                     $col = 0;
                     $row++;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="ODU_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function ims_connection($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/IMS (Connection).xlsx");
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 3415 AND 3484) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=3415; $i <= 3484; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 0;
              $row = 3;
              foreach ($all_data as $key => $value) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);

                  if($col == 6){
                     $col = 0;
                     $row++;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Connection_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function los_survey($visit_id)
       {
              //load Excel template file 
              $objPHPExcel = IOFactory::load("./file/template/export/Los Survey.xlsx"); // ./file/template/export/Los Survey.xlsx
              
              $objPHPExcel->setActiveSheetIndex(0);

              $sql = '
                     SELECT checklist_id, checklist_result
                     FROM t_trx_checklist_result
                     WHERE (checklist_id BETWEEN 3564 AND 3585) AND visit_id = '.$visit_id.'
              ';
              $query_sql = $this->db->query($sql);
              $not_null = $query_sql->result_array();

              $result_checklistID = array();
              if(!empty($not_null)){
                     foreach ($not_null as $data) {
                            $result_checklistID[] = $data['checklist_id'];
                     }
              }
              
              $checklist_id = array();
              for ($i=3564; $i <= 3585; $i++) { 
                     $all_checklistID[] = $i;
              }

              $null = array(); //Jika tidak ada di tabel t_checklist_result di set string kosong ''
              $check_issetID = array_diff($all_checklistID, $result_checklistID);
              foreach ($check_issetID as $key => $value) {
                     $null[] = array('checklist_id' => $value, 'checklist_result' => '');
              }

              $data_array = array_merge($not_null, $null);
              $id = array();
              $result = array();
              foreach ($data_array as $rs) {
                     $id[] = $rs['checklist_id'];
                     $result[] = $rs['checklist_result'];
              }

              $all_data = array_combine($id, $result);
              $col = 1;
              $row = 8;
              foreach ($all_data as $key => $value) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);

                  if($col == 14){
                     $col = 1;
                     $row = 14;
                  }else{
                     $col++;
                  }
              }
              
              //prepare download
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Los Survey_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       public function pre_survey($visit_id)
       {
              $data = $this->load->model('export_model')->pre_survey($visit_id);
              $current_date = date('d-m-Y');

              $objPHPExcel = IOFactory::load("./file/template/export/Pre Survey.xlsx"); // ./file/template/export/Pre Survey.xlsx
              $objPHPExcel->setActiveSheetIndex(0);

              $objPHPExcel->getActiveSheet()->setCellValue('E8', $data['site_id_ne']);
              $objPHPExcel->getActiveSheet()->setCellValue('E9', $data['config']);
              $objPHPExcel->getActiveSheet()->setCellValue('N9', $current_date);
              if($data['ID3654'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K12', $data['ID3654']);
              }elseif($data['ID3654'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K12', $data['ID3654']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('L12', $data['ID3654']);
              }
              if($data['ID3655'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K13', $data['ID3655']);
              }elseif($data['ID3655'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L13', $data['ID3655']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M13', $data['ID3655']);
              }
              if($data['ID3656'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K17', $data['ID3656']);
              }elseif($data['ID3656'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L17', $data['ID3656']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M17', $data['ID3656']);
              }
              if($data['ID3657'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K18', $data['ID3657']);
              }elseif($data['ID3657'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L18', $data['ID3657']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M18', $data['ID3657']);
              }
              if($data['ID3658'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K19', $data['ID3658']);
              }elseif($data['ID3658'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L19', $data['ID3658']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M19', $data['ID3658']);
              }
              if($data['ID3659'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K23', $data['ID3659']);
              }elseif($data['ID3659'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L23', $data['ID3659']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M23', $data['ID3659']);
              }
              if($data['ID3660'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K24', $data['ID3660']);
              }elseif($data['ID3660'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L24', $data['ID3660']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M24', $data['ID3660']);
              }
              if($data['ID3661'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K31', $data['ID3661']);
              }elseif($data['ID3661'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L31', $data['ID3661']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M31', $data['ID3661']);
              }
              if($data['ID3662'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K32', $data['ID3662']);
              }elseif($data['ID3662'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L32', $data['ID3662']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M32', $data['ID3662']);
              }
              if($data['ID3663'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K33', $data['ID3663']);
              }elseif($data['ID3663'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L33', $data['ID3663']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M33', $data['ID3663']);
              }
              if($data['ID3664'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K34', $data['ID3664']);
              }elseif($data['ID3664'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L34', $data['ID3664']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M34', $data['ID3664']);
              }
              if($data['ID3665'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K37', $data['ID3665']);
              }elseif($data['ID3665'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L37', $data['ID3665']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M37', $data['ID3665']);
              }
              if($data['ID3666'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K38', $data['ID3666']);
              }elseif($data['ID3666'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L38', $data['ID3666']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M38', $data['ID3666']);
              }
              if($data['ID3667'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K39', $data['ID3667']);
              }elseif($data['ID3667'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L39', $data['ID3667']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M39', $data['ID3667']);
              }
              if($data['ID3668'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K40', $data['ID3668']);
              }elseif($data['ID3668'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L40', $data['ID3668']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M40', $data['ID3668']);
              }
              if($data['ID3669'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K41', $data['ID3669']);
              }elseif($data['ID3669'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L41', $data['ID3669']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M41', $data['ID3669']);
              }
              if($data['ID3670'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K42', $data['ID3670']);
              }elseif($data['ID3670'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L42', $data['ID3670']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M42', $data['ID3670']);
              }
              if($data['ID3671'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K45', $data['ID3671']);
              }elseif($data['ID3671'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L45', $data['ID3671']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M45', $data['ID3671']);
              }
              if($data['ID3672'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K46', $data['ID3672']);
              }elseif($data['ID3672'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L46', $data['ID3672']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M46', $data['ID3672']);
              }
              if($data['ID3673'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K47', $data['ID3673']);
              }elseif($data['ID3673'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L47', $data['ID3673']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M47', $data['ID3673']);
              }
              if($data['ID3674'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K48', $data['ID3674']);
              }elseif($data['ID3674'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L48', $data['ID3674']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M48', $data['ID3674']);
              }
              if($data['ID3675'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K49', $data['ID3675']);
              }elseif($data['ID3675'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L49', $data['ID3675']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M49', $data['ID3675']);
              }

              //Tandatangan objDrawingPType
              $objPole1 = new PHPExcel_Worksheet_Drawing();
              $objPole1->setWorksheet($objPHPExcel->getActiveSheet());
              $objPole1->setName("Pareto By Type");
              $objPole1->setPath('.'.$data['ID3676'] );
              $objPole1->setHeight(227);
              $objPole1->setCoordinates('D54');

              $objRack1 = new PHPExcel_Worksheet_Drawing();
              $objRack1->setWorksheet($objPHPExcel->getActiveSheet());
              $objRack1->setName("Pareto By Type");
              $objRack1->setPath('.'.$data['ID3677'] );
              $objRack1->setHeight(227);
              $objRack1->setCoordinates('D69');

              $objShelter1 = new PHPExcel_Worksheet_Drawing();
              $objShelter1->setWorksheet($objPHPExcel->getActiveSheet());
              $objShelter1->setName("Pareto By Type");
              $objShelter1->setPath('.'.$data['ID3678'] );
              $objShelter1->setHeight(227);
              $objShelter1->setCoordinates('J54');

              $objCabinet1 = new PHPExcel_Worksheet_Drawing();
              $objCabinet1->setWorksheet($objPHPExcel->getActiveSheet());
              $objCabinet1->setName("Pareto By Type");
              $objCabinet1->setPath('.'.$data['ID3679'] );
              $objCabinet1->setHeight(227);
              $objCabinet1->setCoordinates('J69');

              if($data['ID3680'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K89', $data['ID3680']);
              }elseif($data['ID3680'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L89', $data['ID3680']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M89', $data['ID3680']);
              }
              if($data['ID3681'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K90', $data['ID3681']);
              }elseif($data['ID3681'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L90', $data['ID3681']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M90', $data['ID3681']);
              }
              if($data['ID3682'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K91', $data['ID3682']);
              }elseif($data['ID3682'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L91', $data['ID3682']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M91', $data['ID3682']);
              }
              if($data['ID3683'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K92', $data['ID3683']);
              }elseif($data['ID3683'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L92', $data['ID3683']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M92', $data['ID3683']);
              }
              if($data['ID3684'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K95', $data['ID3684']);
              }elseif($data['ID3684'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L95', $data['ID3684']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M95', $data['ID3684']);
              }
              if($data['ID3685'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K96', $data['ID3685']);
              }elseif($data['ID3685'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L96', $data['ID3685']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M96', $data['ID3685']);
              }
              if($data['ID3686'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K97', $data['ID3686']);
              }elseif($data['ID3686'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L97', $data['ID3686']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M97', $data['ID3686']);
              }
              if($data['ID3687'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K98', $data['ID3687']);
              }elseif($data['ID3687'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L98', $data['ID3687']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M98', $data['ID3687']);
              }
              if($data['ID3688'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K99', $data['ID3688']);
              }elseif($data['ID3688'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L99', $data['ID3688']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M99', $data['ID3688']);
              }
              if($data['ID3689'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K102', $data['ID3689']);
              }elseif($data['ID3689'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L102', $data['ID3689']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M102', $data['ID3689']);
              }
              if($data['ID3690'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K103', $data['ID3690']);
              }elseif($data['ID3690'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L103', $data['ID3690']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M103', $data['ID3690']);
              }
              
              $objPole2 = new PHPExcel_Worksheet_Drawing();
              $objPole2->setWorksheet($objPHPExcel->getActiveSheet());
              $objPole2->setName("Pareto By Type");
              $objPole2->setPath('.'.$data['ID3691'] );
              $objPole2->setHeight(227);
              $objPole2->setCoordinates('D108');

              $objRack2 = new PHPExcel_Worksheet_Drawing();
              $objRack2->setWorksheet($objPHPExcel->getActiveSheet());
              $objRack2->setName("Pareto By Type");
              $objRack2->setPath('.'.$data['ID3692'] );
              $objRack2->setHeight(227);
              $objRack2->setCoordinates('D121');

              $objShelter2 = new PHPExcel_Worksheet_Drawing();
              $objShelter2->setWorksheet($objPHPExcel->getActiveSheet());
              $objShelter2->setName("Pareto By Type");
              $objShelter2->setPath('.'.$data['ID3693'] );
              $objShelter2->setHeight(227);
              $objShelter2->setCoordinates('J108');

              $objCabinet2 = new PHPExcel_Worksheet_Drawing();
              $objCabinet2->setWorksheet($objPHPExcel->getActiveSheet());
              $objCabinet2->setName("Pareto By Type");
              $objCabinet2->setPath('.'.$data['ID3694'] );
              $objCabinet2->setHeight(227);
              $objCabinet2->setCoordinates('J121');

              if($data['ID3695'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K136', $data['ID3695']);
              }elseif($data['ID3695'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L136', $data['ID3695']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M136', $data['ID3695']);
              }
              if($data['ID3696'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K137', $data['ID3696']);
              }elseif($data['ID3696'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L137', $data['ID3696']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M137', $data['ID3696']);
              }
              if($data['ID3697'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K138', $data['ID3697']);
              }elseif($data['ID3697'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L138', $data['ID3697']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M138', $data['ID3697']);
              }
              if($data['ID3698'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K139', $data['ID3698']);
              }elseif($data['ID3698'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L139', $data['ID3698']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M139', $data['ID3698']);
              }
              if($data['ID3699'] == 'Yes'){
                     $objPHPExcel->getActiveSheet()->setCellValue('K140', $data['ID3699']);
              }elseif($data['ID3699'] == 'No'){
                     $objPHPExcel->getActiveSheet()->setCellValue('L140', $data['ID3699']);
              }else{
                     $objPHPExcel->getActiveSheet()->setCellValue('M140', $data['ID3699']);
              }

              //$objPHPExcel->getActiveSheet()->setCellValue('C146', $data['ID3699']);
              $objPHPExcel->getActiveSheet()->setCellValue('D151', $data['hs_name']);
              $objPHPExcel->getActiveSheet()->setCellValue('D152', $data['hs_date']);
              //$objPHPExcel->getActiveSheet()->setCellValue('M146', $data['ID3699']);
              $objPHPExcel->getActiveSheet()->setCellValue('N151', $data['alita_name']);
              $objPHPExcel->getActiveSheet()->setCellValue('N152', $data['alita_date']);

              $objAlita = new PHPExcel_Worksheet_Drawing();
              $objAlita->setWorksheet($objPHPExcel->getActiveSheet());
              $objAlita->setName("Sent By:");
              $objAlita->setPath('.'.$data['ttd_fop_hs'] );
              $objAlita->setHeight(30);
              $objAlita->setCoordinates('C146');

              $objXl = new PHPExcel_Worksheet_Drawing();
              $objXl->setWorksheet($objPHPExcel->getActiveSheet());
              $objXl->setName("Approved By:");
              $objXl->setPath('.'.$data['ttd_fop_alita'] );
              $objXl->setHeight(30);
              $objXl->setCoordinates('M146');

              // We'll be outputting an excel file
              //header('Content-type: application/vnd.ms-excel');
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment; filename="Pre Survey_Export_'.date('Y-m-d-H:i:s').'.xlsx"');
              $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('php://output');
              exit();
       }

       //END
}

?>