<style type="text/css">
    B {
        font-size: 18px;
    }

    td.border_bottom {
        border-bottom:1pt solid black;
    }
</style>
<!-- Cover -->
<p>
    <img width="150"
        src="<?php echo "http://10.0.5.68/";?>assets/images/logo_xl_smart.png"
    />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img width="110" height="45"
        src="<?php echo "https://privaterepositorynas.alita.id/";?>assets/images/logo_alita.png" align="right"
    />
</p>
<p align="center" style="font-size: 16px">
    <strong>PT XL AXIATA</strong>
    <br>
    <strong>Acceptance Test Document</strong>
</p>
<p></p>

<table border="0" cellspacing="0" cellpadding="0" width="612">
    <tbody>
    <tr>
            <td width="187" valign="top">
            </td>
            <td width="19" valign="top">
            </td>
            <td width="325" valign="top">
                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong> CIVIL WORKS & INSTALLATION</strong>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
            </td>
            <td width="19" valign="top">
            </td>
            <td width="325" valign="top">
                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong> RADIO BASE STATION</strong>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
            </td>
            <td width="19" valign="top">
            </td>
            <td width="325" valign="top">
                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong> RECTIFIER</strong>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
            </td>
            <td width="19" valign="top">
            </td>
            <td width="325" valign="top">
                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <strong> TRANSMISSION</strong>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                &nbsp;
            </td>
            <td width="19" valign="top">
                &nbsp;
            </td>
            <td width="325" valign="top">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                VENDOR NAME
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                PT. ALITA PRAYA MITRA
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                PROJECT NAME
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo htmlspecialchars(@$po_description) ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                SITE ID / SITE NAME
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo $site_id_ne.' / '.$site_name ?>
            </td>
        </tr>
        <!-- <tr>
            <td width="187" valign="top">
                OPPOSITE ID / SITE NAME
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo $site_id_fe.' / '.$opposite_site_name ?>
            </td>
        </tr> -->
        <tr>
            <td width="187" valign="top">
                DATE OF ACCEPTANCE
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo $acceptance_date ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                ACCEPTANCE STATUS
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="325" valign="top">
                <?php if($acceptance_status == 1){ ?>
                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <strong> PASS</strong>
                <?php }else{ ?>
                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong> PASS</strong>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
            </td>
            <td width="19" valign="top">
            </td>
            <td width="325" valign="top">
                <?php if($acceptance_status == 2){ ?>
                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <strong> PASS WITH PENDING ITEMS</strong>
                <?php }else{ ?>
                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong> PASS WITH PENDING ITEMS</strong>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
            </td>
            <td width="19" valign="top">
            </td>
            <td width="325" valign="top">
                <?php if($acceptance_status == 3){ ?>
                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <strong> REJECTED</strong>
                <?php }else{ ?>
                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong> REJECTED</strong>
                <?php } ?>
            </td>
        </tr>
    </tbody>
</table>
<p></p>
<p></p>
<p>
    <strong>ACCEPTANCE BY :</strong>
</p>
<p></p>
<p>
    <strong>VENDOR</strong>
</p>
<p></p>
<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td colspan="2"><p> <img align="left" width="150" height="50" src="<?php echo "http://10.0.5.68/".''.$ttd_pic_alita ?>"/></p></td>
        </tr>
        <tr>
            <td width="20" valign="top">    1. </td>
            <td class="border_bottom" width="550" valign="top">    <?php echo $pic_alita_name." / / ".$pic_alita_number ?></td>
        </tr>
    </tbody>
</table>
<p></p>
<p>
    <strong>PT XL AXIATA Tbk.</strong>
</p>
<p></p>
<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td colspan="2"><p> <img align="left" width="150" height="50" src="<?php echo "http://eatp.alita.id/".''.$ttd_fop_hs ?>"/></p></td>
        </tr>
        <tr>
            <td width="20" valign="top">    1. </td>
            <td class="border_bottom" width="550" valign="top">    <?php echo $hs_name." / ".$hs_date." / ".$hs_contact ?></td>
        </tr>
        <tr>
            <td colspan="2"><br><p></p><p> <img align="left" width="150" height="50" src="<?php echo "http://eatp.alita.id/".''.$ttd_spv ?>"/></p></td>
        </tr>
        <tr>
            <td width="20" valign="top">    2.  </td>
            <td class="border_bottom" width="550" valign="top">    <?php echo $spv_username." / ".$spv_created_on." / ".$mobile_number ?></td>
        </tr>
    </tbody>
</table>
<!-- Cover -->

<!-- Page -->
<br pagebreak="true"/>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img align="center"
        width="150"
        src="<?php echo "http://10.0.5.68/";?>assets/images/logo_xl_smart.png"
    />
</p>
<p align="center">
    <strong><u>BERITA ACARA SERAH TERIMA</u></strong>
</p>
<table border="0" cellspacing="0" cellpadding="0" width="612">
    <tbody>
        <tr>
            <td width="187" valign="top">
                Vendor / Contractor Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                PT. ALITA PRAYA MITRA
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                Project Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo htmlspecialchars(@$po_description) ?>
            </td>
        </tr>
        <?php if ($visit_type == "110" || $visit_type == "120") { ?>
            <tr>
            <td width="187" valign="top">
                Opposite Site ID/Site Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo $site_id_fe.' / '.$opposite_site_name ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td width="187" valign="top">
                Site ID / Site Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo $site_id_ne.' / '.$site_name ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                Site Coordinate*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="38" valign="top">
                E/W;
            </td>
            <td class="border_bottom" width="125" valign="top">
                <?php echo $latitude ?>
            </td>
            <td width="37" valign="top">
                S/N;
            </td>
            <td class="border_bottom" width="125" valign="top">
                <?php echo $longitude ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                Area*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo $area_name ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                No. EWO/NBWO*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo $ewo_no ?>
            </td>
        </tr>
    </tbody>
</table>
<p></p>
<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td width="90" valign="top" align="left">&nbsp;&nbsp;Pada hari ini, </td>
            <td class="border_bottom" width="100" valign="top" align="center">
			    <?php 
				//start edit by hadi 20181016
				//$hari = date('l', strtotime($ID3739));
				$hari = !empty($hs_date) ? date('l', strtotime($hs_date)) : '';
                    if($hari == "Sunday"){ echo "Minggu"; }
                    elseif($hari == "Monday"){ echo "Senin"; }
                    elseif($hari == "Tuesday"){ echo "Selasa"; }
                    elseif($hari == "Wednesday"){ echo "Rabu"; }
                    elseif($hari == "Thursday"){ echo "Kamis"; }
                    elseif($hari == "Friday"){ echo "Jumat"; }
                    elseif($hari == "Saturday"){ echo "Sabtu"; }
                ?>
            </td>
            <td width="50" valign="top"> tanggal </td>
            <td class="border_bottom" width="70" valign="top" align="center">
                <?php 
				//echo (date('d', strtotime($ID3739)))
				echo (!empty($hs_date) ? date('d', strtotime($hs_date)) : '')
				?>
            </td>
            <td width="45" valign="top"> bulan </td>
            <td class="border_bottom" width="70" valign="top" align="center">
                <?php 
				//echo (date('m', strtotime($ID3739))) 
				echo (!empty($hs_date) ? date('m', strtotime($hs_date)) : '') 
				?>
            </td>
            <td width="45" valign="top"> tahun </td>
            <td class="border_bottom" width="70" valign="top" align="center">
                <?php 
				//echo (date('Y', strtotime($ID3739))) 
				echo (!empty($hs_date) ? date('Y', strtotime($hs_date)) : '') 
				//end edit by hadi 20181016
				?>
            </td>
            <td width="10" valign="top">,</td>
        </tr>
        <tr>
            <td width="500" valign="top" align="left">
            kami yang bertanda tangan di bawah ini, menyatakan bahwa pekerjaan, yang meliputi :
            </td>
        </tr>
    </tbody>
</table>

<p align="center">
    <strong><?php echo $visit_type_label . (isset($checklist_item['3735_Radio']) ? $checklist_item['3735_Radio']['pm_result'] : '') . (isset($checklist_item['3736_Ghz']) ? ', <u>' . $checklist_item['3736_Ghz']['pm_result'] . '</u> Ghz' : '') . (isset($checklist_item['3737_Mbps']) ? ', <u>' . $checklist_item['3737_Mbps']['pm_result'] . '</u> Mbps' : '') ?></strong>
</p>
<p align="justify">
    pada lokasi tersebut di atas
    <em>
        <u>
            telah selesai dilaksanakan dan diterima sesuai dengan ruang lingkup
            pekerjaan yang diberikan,
        </u>
    </em>
    dengan :
</p>
<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td width="210" valign="top">
                Durasi waktu pengerjaan*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td  class="border_bottom" width="175" valign="top">
                <?php echo isset($checklist_item['3740_Durasi Waktu pengerjaan']) ? $checklist_item['3740_Durasi Waktu pengerjaan']['pm_result'] : '' ?>
            </td>
            <td width="186" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="210" valign="top">
                Tanggal pelaksanaan*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td  class="border_bottom" width="175" valign="top">
                <?php echo isset($checklist_item['3741_Tanggal pelaksanaan']) ? $checklist_item['3741_Tanggal pelaksanaan']['pm_result'] : '' ?>
            </td>
            <td width="186" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="210" valign="top">
                Tanggal penyelesaian*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td  class="border_bottom" width="175" valign="top">
                <?php echo isset($checklist_item['3742_Tanggal penyelesaian']) ? $checklist_item['3742_Tanggal penyelesaian']['pm_result'] : '' ?>
            </td>
            <td width="186" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="210" valign="top">
                Tanggal aktual penyelesaian*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td  class="border_bottom" width="175" valign="top">
                <?php echo isset($checklist_item['3743_Tanggal aktual penyelesaian']) ? $checklist_item['3743_Tanggal aktual penyelesaian']['pm_result'] : '' ?>
            </td>
            <td width="186" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="210" valign="top">
                Delay pengerjaan*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td  class="border_bottom" width="175" valign="top">
                <?php echo isset($checklist_item['3744_Delay pengerjaan']) ? $checklist_item['3744_Delay pengerjaan']['pm_result'] : '' ?>
            </td>
            <td width="186" valign="top">
                hari kalender
            </td>
        </tr>
    </tbody>
</table>

<p>
    Alasan delay pengerjaan*:
</p>
<!-- Alasan Delay pengerjaan*: -->
<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <?php
         $row = 1;
         for ($i=3745; $i <= 3752 ; $i++) { ?> 
            <tr>
                <td width="20" valign="top">
                    <?php echo $row ?>.
                </td>
                <td  class="border_bottom" width="400" valign="top">
                    <?php echo isset($checklist_item[$i.'_Alasan delay #'. $row]) ? $checklist_item[$i.'_Alasan delay #'. $row]['pm_result'] : ''; ?>
                </td>
                <td width="15" valign="top">
                    :
                </td>
                <td  class="border_bottom" width="50" valign="top" align="center">
                    <?php 
                    //Add 1 in $i to get id Lama delay data 
                    $i++;
                    echo isset($checklist_item[$i.'_Lama delay #'. $row]) ? $checklist_item[$i.'_Lama delay #'. $row]['pm_result'] : '';
                    //Add 1 in $row to get new row index
                    $row++;
                    ?>
                </td>
                <td width="80" valign="top">
                    hari kalender
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<p>
    Demikian Berita Acara ini dibuat dengan sebenar-benarnya dan dapat
    dipergunakan sebagaimana mestinya.
</p>
<p></p>
<table>
	<!-- start edit by hadi, 2018-11-06 -->
    <tbody>
        <tr>
            <td width="180" align="center"><p> PT. XL Axiata Tbk.</p></td>
            <td width="250"></td>
            <td width="180" align="center"><p> PT. Alita Praya Mitra</p></td>
        </tr>
        <tr>
            <td width="180" align="center"><p> </p></td>
            <td width="250"></td>
            <td width="180" align="center"><p> </p></td>
        </tr>
        <tr>
            <td width="180" align="center"><p> <img align="right" width="150" height="50" src="<?php echo "http://10.0.5.85:5050/".''.$ttd_fop_hs ?>"/></p></td>
            <td width="250"></td>
            <td width="180" align="center"><p> <img align="right" width="150" height="50" src="<?php echo "http://10.0.5.85:5050/".''.$ttd_pic_alita ?>"/></p></td>
        </tr>
        <tr>
            <td width="180" align="center" class="border_bottom"><p> <?php echo $hs_name ?> </p></td>
            <td width="250"></td>
            <td width="180" align="center" class="border_bottom"><p> <?php echo $pic_alita_name ?></p></td>
        </tr>
    </tbody>
	<!-- end edit by hadi, 2018-11-06 -->
</table>
<p>
    <strong><em>*Note : Wajib diisi</em></strong>
    <strong><em></em></strong>
</p>
<!-- Page -->

<!-- Page 2: -->
<br pagebreak="true"/>
<p>
    <img width="150"
        src="<?php echo "http://10.0.5.68/";?>assets/images/logo_xl_smart.png"
    />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img width="110" height="45"
        src="<?php echo "http://eatp.alita.id/";?>assets/images/logo_alita.png" align="right"
    />
</p>

<p align="center">
    <strong><u>CHECK LIST - <?php echo $visit_type_label ?></u></strong>
</p>
<table border="0" cellspacing="0" cellpadding="0" width="612">
    <tbody>
        <tr>
            <td width="187" valign="top">
                Vendor Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="325" valign="top">
                PT. ALITA PRAYA MITRA
            </td>
        </tr>
        <tr>
            <td valign="top">
                Project Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="325" valign="top">
                <?php echo htmlspecialchars(@$po_description) ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                Site ID / Site Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="325" valign="top">
                <?php echo $site_id_ne.' / '.$site_name ?>
            </td>
        </tr>
        <?php if ($visit_type == "110" || $visit_type == "120") { ?>
        <tr>
            <td width="187" valign="top">
                Opposite Site ID/Site Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="325" valign="top">
                <?php echo $site_id_fe.' / '.$opposite_site_name ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td width="187" valign="top">
                Area*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="325" valign="top">
                <?php echo $area_name ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                No. EWO/NBWO*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="325" valign="top">
                <?php echo $ewo_no ?>
            </td>
        </tr>
        <tr>
            <td width="187" valign="top">
                Date of Installation*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td width="325" valign="top">
                <?php echo $visit_date ?>
            </td>
        </tr>
    </tbody>
</table>
<p></p>

<!-- Group checklist -->
<table style='margin:auto' border="1" cellspacing="1" cellpadding="1" width="100%">
    <tbody>
        <tr bgcolor="#A9A9A9">
        <td width="400" align="center">
                    <strong>Jenis Kegiatan</strong>
            </td>
            <td width="100" align="center">
                    <strong>Status</strong>
            </td>
            <td width="147" align="center">
                    <strong>Remarks </strong>
            </td>
        </tr>

        <?php
                $approval_data = [
                    'approval' => @$approval,
                    'approval_remarks' => @$approval_remarks,
                ];
                //var_dump($checklistsgrouping);
                  function render($properties, $remarks, $approval_data,$user_id="",$endian, $all_header = '') {
                    function sub_render($parent, $remarks, $approval_data,$iterasi,$user_id="",$endian, $all_header, $pos = 0, $spacing = "") {
                        $header = '<tr bgcolor="#DDDDDD" id="checklist-group-header">';
                        $header .= '<td colspan="3"><strong> '.$spacing.@$parent['group_name'];
                        
                          $tmp = substr(@$parent['group_name'], -2);
                          switch ($tmp) {
                            case 'NE':
                              $endian = 'NE';
                              break;
                            case 'FE':
                              $endian = 'FE';
                              break;
                            default:
                              if (strtoupper(@$parent['group_name']) == 'NEAR END') {
                                  $endian = 'NE';
                              } elseif (strtoupper(@$parent['group_name']) == 'FAR END') {
                                  $endian = 'FE';
                              }
                              break;
                          }
    
                        $header .= '</strong></td>';
                        $header .= '</tr>';
    
                        if( isset($parent['sub_group']) ) {
                          $_2nd = 1;
                          $loop_key = 0;
                          foreach ($parent['sub_group'] as $gg) {
                            if ($gg['group_name'] != 'BAST') {
                                if ($loop_key == 0 && $pos == 0) {
                                    $all_header .= $header;
                                }else{
                                    $all_header = $header;
                                }
                                sub_render($gg, $remarks, $approval_data, false,$user_id,$endian, $all_header, $loop_key, $spacing.'&nbsp;&nbsp;');
                                $loop_key++;
                            }
                          }
                        }
                        if(isset($parent['checklists'])) {
                          $i=1;
                          if ($pos == 0) {
                            echo $all_header;
                          }
                          echo $header;
                          foreach ($parent['checklists'] as $cc) {
                            //echo $cc['checklist_name']."<br/>";
                            echo generate_view($cc,$i,$user_id,$endian,$spacing, $remarks, $approval_data);
                            $i++;
                          }
                          $all_header = '';
                        }
                    }
                    
                    $_1st = 1;
                    foreach ($properties as $key => $gg) {
                        if ($gg['group_name'] != 'BAST') {
                            sub_render($gg, $remarks, $approval_data, $_1st++,$user_id,$endian, $all_header, $key);
                        }
                    }
                  }

                 render($checklistsgrouping, $remarks, $approval_data,$this->session->userdata('user_id'),null);
        ?>

<?php

function generate_view($data,$no,$user_id="",$endian=null, $spacing, $remarks, $approval_data)
  {
    $s = '<tr id="list-checklist" nobr="true">
            <td> '.$spacing.'&nbsp;&nbsp;'.$no.'. '.@$data["checklist_name"].'</td>';
            if (!empty(@$acceptance_status) && $acceptance_status != 3) {
                $s .= '<td align="center"> Not OK </td>
                <td>'.$remarks.'</td>';
            }else{
                $s .= '<td align="center"> OK </td>
                <td> </td>';
            }
	$s .= '</tr>';
    return $s;
  }
?>
    </tbody>
</table>
<p><br/></p>
<!-- Page -->

<!-- Page -->
<?php if ($visit_type == "110" || $visit_type == "120" || $visit_type == "112") { ?>
    <br pagebreak="true"/>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img align="center"
        width="150"
        src="<?php echo "http://10.0.5.68/";?>assets/images/logo_xl_smart.png"
    />
</p>
<p align="center">
    <strong><u>DELIVERY NOTE</u></strong>
</p>
<p align="center">
    <strong><u>GOODS ARRIVAL CERTIFICATE (GAC)</u></strong>
</p>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="0">
<tbody>
  <tr height="37">
    <td height="37">Delivery to</td>
    <td colspan="2">: PT. XL AXIATA</td>
    <td></td>
    <td width="92"></td>
    <td></td>
    <td width="64"></td>
  </tr>
  <tr height="37">
    <td height="37">Delivery from</td>
    <td colspan="4">: PT. ALITA PRAYA MITRA - JAKARTA</td>
    <td></td>
    <td></td>
  </tr>
  <tr height="37">
    <td height="37">Project Name</td>
    <td colspan="4">: <?php echo htmlspecialchars(@$po_description) ?></td>
    <td></td>
    <td></td>
  </tr>
  <tr height="37">
    <td height="37">PIC HS</td>
    <td colspan="4">: <?php echo $hs_name ?></td>
    <td></td>
    <td></td>
  </tr>
  <tr height="37">
    <td height="37">Site Name</td>
    <td colspan="4">: <?php echo $site_id_ne.' / '.$site_name ?></td>
    <td></td>
    <td></td>
  </tr>
  <tr height="37">
    <td height="37">Delivery Date</td>
    <td colspan="4">: <?php echo $alita_qc_date ?></td>
    <td></td>
    <td></td>
  </tr>
  <tr height="37">
    <td height="37">Delivery goods</td>
    <td colspan="4">: As follows</td>
    <td></td>
    <td></td>
  </tr>
	</tbody>
</table>
<p>&nbsp;</p>
<?php $table_gac = '<div class="accordion-group checklist-group-header"><div class="accordion-heading"></div><div id="group_id_260_1" class="accordion-body in collapse"><div class="accordion-inner"><table class="table table-striped table-bordered list-checklist" border="1">
						<tr align="center">
							  <th style="width:15%">Purch.Doc.</th>
							  <th style="width:5%">Item</th>
							  <th style="width:20%">Material Code</th>
							  <th style="width:30%">Short Text</th>
							  <th style="width:10%">PO Qty</th>
							  <th style="width:10%">Region</th>
							  <th style="width:10%">EWO</th>
						  </tr>';
						echo $table_gac;
						//echo $coba_cabi;
						//print_r($data_gac);
						foreach ($data_gac as $_gac) {
							//echo $_gac->purch_doc;
							echo '<tr align="center">
							  <td>'.$_gac->purch_doc.'</td>
							  <td>'.$_gac->item.'</td>
							  <td>'.$_gac->material_code.'</td>
							  <td>'.$_gac->short_text.'</td>
							  <td>'.$_gac->po_qty.'</td>
							  <td>'.$_gac->region.'</td>
							  <td>'.$_gac->ewo.'</td>
						  </tr>';
						}
						echo '</table></div></div></div>';
						?>
<!-- end update by hadi case: upload GAC - 2019.07.06 -->
<p>&nbsp;</p>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
	<!-- start edit by hadi 2018-10-23 -->
    <tbody>
        <tr align="center">
            <td height="130">
                <strong>Tim Leader Instalasi</strong><br>
                <p> <img align="left" width="150" height="50" src="<?php echo "http://10.0.5.85:5050/".''.$ttd_fop_alita ?>"/></p>
            </td>
            <td height="130">
                <strong>Coordinator APM</strong><br>
                <p> <img align="left" width="150" height="50" src="<?php echo "http://10.0.5.85:5050/".''.$ttd_qc_alita ?>"/></p>
            </td>
            <td height="130">
                <strong>Engineer XL</strong><br>
                <p> <img align="left" width="150" height="50" src="<?php echo "http://10.0.5.85:5050/".''.$ttd_fop_hs ?>"/></p>
            </td>
        </tr>
        <tr align="center">
            <td height="20">
                <?php echo $alita_name." / ".$alita_date." / ".$alita_contact ?>
            </td>
            <td height="20">
                <?php echo $alita_qc_name." / ".$alita_qc_date." / ".$alita_qc_contact ?>
            </td>
            <td height="20">
                <?php echo $hs_name." / ".$hs_date." / ".$hs_contact ?>
            </td>
        </tr>
    </tbody>
	<!-- end -->
</table>

<!-- Page -->
<br pagebreak="true"/>

<p>
    <strong><u>Penting untuk diingat :</u></strong>
</p>

 <table>
    <tbody>
        <tr>
            <td width="15"><strong>1. </strong></td>
            <td width="630"><strong>Semua material utama yang terpasang dan label harap disertakan foto/softcopy.
            </strong></td>
        </tr>
        <tr>
            <td width="15"><strong>2. </strong></td>
            <td width="630" align="justify"><strong>Sebelum koneksi PNMT harap lepaskan kabel power Laptop/notebook dari input AC, baru koneksikan ke IDU.
            </strong></td>
        </tr>
        <tr>
            <td width="15"><strong>3. </strong></td>
            <td width="630"><strong>Dilarang melepas kabel IF dari IDU/ ODU dalam keadaan ON.
            </strong></td>
        </tr>
        <tr>
            <td width="15"><strong>4. </strong></td>
            <td width="630" align="justify"><strong>Pasolink NEO menggunakan tegangan input -48Volt DC. Perangkat Pasolink lama (V4 dll) yang menggunakan sistem tegangan floating -15V s/d -60V DC <em>tidak diperbolehkan</em> untuk digunakan untuk Pasolink NEO.
            </strong></td>
        </tr>
        <tr>
            <td width="15"><strong>5. </strong></td>
            <td width="630"><strong>Rentang waktu antara ON dan OFF IDU minimal 1 menit.
            </strong></td>
        </tr>
        <tr>
            <td width="15"><strong>6. </strong></td>
            <td width="630"><strong>OFF IDU untuk sistem 1+1 dimulai dengan sistem yang standby terlebih dahulu baru sisi aktif.
            </strong></td>
        </tr>
        <tr>
            <td width="15"><strong>7. </strong></td>
            <td width="630"><strong>ODU selain tipe NEO CPV dan NHG tidak bisa dipasangkan dengan IDU NEO/C.
            </strong></td>
        </tr>
        <tr>
            <td width="15"><strong>8. </strong></td>
            <td width="630"><strong>Lebeling menggunakan Label Print Casio.
            </strong></td>
        </tr>
    </tbody>
</table>
<p></p>
<p>
    <strong><em>*Note : Wajib diisi</em></strong>
</p>
<!-- Page -->
<?php } ?>
<!-- Page -->

<!-- Page Group -->
<?php if ($visit_type == "110" || $visit_type == "120" || $visit_type == "112") { ?>
<?php 
$berita_acara_groups = searchGroup('618', $checklistsgrouping); 
if (!empty($berita_acara_groups)) {
    echo '<br pagebreak="true"/>';

    //Sort Group Berita Acara By group_id
    $col = array_column($berita_acara_groups['sub_group'], 'group_id');
    array_multisort($col, SORT_ASC, $berita_acara_groups['sub_group']);

    $visual_check_groups    = searchGroup('56', @$berita_acara_groups['sub_group']);
    $for_nec_ipaso_400_groups   = searchGroup('65', @$berita_acara_groups['sub_group']);
    $qos_setting_groups     = searchGroup('62', @$berita_acara_groups['sub_group']);
    $dcn_configuration_groups = searchGroup('61', @$berita_acara_groups['sub_group']);
    $interference_check_groups = searchGroup('60', @$berita_acara_groups['sub_group']);
    $protection_switching_groups = searchGroup('59', @$berita_acara_groups['sub_group']);
    $receiver_power_groups = searchGroup('58', @$berita_acara_groups['sub_group']);
    $configuration_check_groups = searchGroup('57', @$berita_acara_groups['sub_group']);
    $test_and_measurement   = searchGroup('75', @$berita_acara_groups['sub_group']);

    foreach ($berita_acara_groups['sub_group'] as $key => $ba_group) {
        if ($ba_group['group_name'] != 'BAST') {
            if ($ba_group['group_name'] == 'Visual Check' && !empty($visual_check_groups)) { //Visual Check?>
                <strong>Visual Check</strong>

                <?php 
                    //Serial Number
                    $serial_number_group = searchGroup('327', @$visual_check_groups['sub_group']);
                    if (!empty($serial_number_group)) { ?>
                        <p align="left">Serial Number</p>
                        <table>
                            <tbody>
                                <tr>
                                    <td width="120"><strong> Site ID / Site Name</strong></td>
                                    <td width="18">:</td>
                                    <td class="border_bottom" valign="top" width="300"><?php echo $site_id_ne.' / '.$site_name ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <p></p>
                        <table border="1" cellspacing="0" cellpadding="0" width="562">
                            <tbody>
                                <tr align="center" bgcolor="#A9A9A9">
                                    <td width="156" valign="top">
                                        <strong>UNIT</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong>Type</strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong>Serial No</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>IDU</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['116_Site 1 IDU Type']['pm_result']  ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['117_Site 1 IDU Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>ODU 1</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['118_Site 1 ODU 1 Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['119_Site 1 ODU 1 Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>ODU 2(*)</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['120_Site 1 ODU 2 (*)Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['121_Site 1 ODU 2 (*) Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>Hybrid(*)</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['122_Site 1 Hybrid (*) Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['123_Site 1 Hybrid (*) Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>Antenna 1</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['124_Site 1 Antenna 1 Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['125_Site 1 Antenna 1 Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>Antenna 2(*)</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['126_Site 1 Antenna 2 (*) Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['127_Site 1 Antenna 2 (*) Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                            (*) Only for applicable configuration
                        <p></p>
                        <table>
                            <tbody>
                                <tr>
                                    <td width="150"><strong> Opposite ID / Site Name</strong></td>
                                    <td width="18">:</td>
                                    <td class="border_bottom" valign="top" width="300"><?php echo $site_id_fe.' / '.$opposite_site_name ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <p></p>
                        <table border="1" cellspacing="0" cellpadding="0" width="562">
                            <tbody>
                                <tr align="center" bgcolor="#A9A9A9">
                                    <td width="156" valign="top">
                                        <strong>UNIT</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong>Type</strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong>Serial No</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>IDU</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['128_Site 2 IDU Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['129_Site 2 IDU Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>ODU 1</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['130_Site 2 ODU 1 Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['131_Site 2 ODU 1 Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>ODU 2(*)</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['132_Site 2 ODU 2 (*)Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['133_Site 2 ODU 2 (*) Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>Hybrid(*)</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['134_Site 2 Hybrid (*) Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['135_Site 2 Hybrid (*) Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>Antenna</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['136_Site 2 Antenna 1 Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['137_Site 2 Antenna 1 Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top">
                                        <strong>Antenna 2(*)</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong><?php echo @$checklist_item['138_Site 2 Antenna 2 (*) Type']['pm_result'] ?></strong>
                                    </td>
                                    <td width="217" valign="top">
                                        <strong><?php echo @$checklist_item['139_Site 2 Antenna 2 (*) Serial No']['pm_result'] ?></strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    (*) Only for applicable configuration
                    <?php }

                    //Antenna
                    $antenna_group = searchGroup('328', @$visual_check_groups['sub_group']);
                    if (!empty($antenna_group)) { ?>
                        <p>Antenna</p>
                        <table border="1" cellspacing="0" cellpadding="0" width="562">
                            <tbody>
                                <tr align="center" bgcolor="#A9A9A9">
                                    <td width="156" valign="top">
                                        <strong>Antenna</strong>
                                    </td>
                                    <td width="142" valign="top">
                                        <strong>Diameter (M)</strong>
                                    </td>
                                    <td width="142" valign="top">
                                        <strong>Height (M)</strong>
                                    </td>
                                    <td width="123" valign="top">
                                        <strong>Polarization</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="156" valign="top" height="50px">
                                        <strong>Site 1 : <?php echo @$checklist_item['140_Site 1 Antenna NE']['pm_result'] ?></strong>
                                    </td>
                                    <td width="142" valign="top" height="50px">
                                        <?php echo @$checklist_item['141_Site 1 Diameter (M) NE']['pm_result'] ?>
                                    </td>
                                    <td width="142" valign="top" height="50px">
                                        <?php echo @$checklist_item['142_Site 1 Height (M) NE']['pm_result'] ?>
                                    </td>
                                    <td width="123" valign="top" height="50px">
                                        <?php echo @$checklist_item['143_Site 1 Polarization (M) NE']['pm_result'] ?>
                                    </td>
                                </tr>
                                <tr>
                                <td width="156" valign="top" height="50px">
                                        <strong>Site 2 : <?php echo @$checklist_item['144_Site 2 Antenna FE']['pm_result'] ?></strong>
                                    </td>
                                    <td width="142" valign="top" height="50px">
                                        <?php echo @$checklist_item['145_Site 2 Diameter (M) FE']['pm_result'] ?>
                                    </td>
                                    <td width="142" valign="top" height="50px">
                                        <?php echo @$checklist_item['146_Site 2 Height (M) FE']['pm_result'] ?>
                                    </td>
                                    <td width="123" valign="top" height="50px">
                                        <?php echo @$checklist_item['147_Site 2 Polarization (M) FE']['pm_result'] ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p></p>
                        <table border="1" cellspacing="0" cellpadding="0" width="562">
                            <tbody>
                                <tr align="center"  bgcolor="#A9A9A9">
                                    <td width="37" valign="top">
                                    </td>
                                    <td width="404" valign="top">
                                        <strong>Item Check</strong>
                                    </td>
                                    <td width="62" valign="top">
                                        <strong>OK</strong>
                                    </td>
                                    <td width="58" valign="top">
                                        <strong>NOK</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="37" align="center">
                                        3)
                                    </td>
                                    <td width="404">
                                        Check the physical out-door installation.
                                    </td>
                                    <td width="62" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" />
                                    </td>
                                    <td width="58" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />
                                    </td>
                                </tr>
                                <tr>
                                    <td width="37" align="center">
                                        4)
                                    </td>
                                    <td width="404">
                                        Check the radio indoor equipment installation.
                                    </td>
                                    <td width="62" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" />
                                    </td>
                                    <td width="58" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />
                                    </td>
                                </tr>
                                <tr>
                                    <td width="37" align="center">
                                        5)
                                    </td>
                                    <td width="404">
                                        Indoor cable rack and structure installation.
                                    </td>
                                    <td width="62" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" />
                                    </td>
                                    <td width="58" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />
                                    </td>
                                </tr>
                                <tr>
                                    <td width="37" align="center">
                                        6)
                                    </td>
                                    <td width="404">
                                        Labeling.
                                    </td>
                                    <td width="62" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" />
                                    </td>
                                    <td width="58" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />
                                    </td>
                                </tr>
                                <tr>
                                    <td width="37" align="center">
                                        7)
                                    </td>
                                    <td width="404">
                                        Grounding cable installation.
                                    </td>
                                    <td width="62" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" />
                                    </td>
                                    <td width="58" align="center">
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p></p>
                    <?php }
                ?>
                    <br pagebreak="true"/>
            <?php }elseif ($ba_group['group_name'] == 'Configuration Check' && !empty($configuration_check_groups)) { //Configuration Check ?>
                <p align="left">
                    <strong>Configuration Check </strong>
                </p>
                <table border="1" cellspacing="0" cellpadding="0" width="558">
                    <tbody>
                        <tr align="center" bgcolor="#A9A9A9">
                            <td width="264">
                                <strong>Site 1 Set-Up</strong>
                            </td>
                            <td width="142">
                                <strong>Channel 1</strong>
                            </td>
                            <td width="151">
                                <strong>Channel 2 (*)</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Sub Band
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo @$checklist_item['153_Sub Band Channel 1']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo @$checklist_item['154_Sub Band Channel 2 (*)']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Transmitted Frequency
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['155_Transmitted Frequency (MHz) Channel 1']) ? $checklist_item['155_Transmitted Frequency (MHz) Channel 1']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['156_Transmitted Frequency (MHz) Channel 2 (*)']) ? $checklist_item['156_Transmitted Frequency (MHz) Channel 2 (*)']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Received Frequency
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['157_Received Frequency (MHz) Channel 1']) ? $checklist_item['157_Received Frequency (MHz) Channel 1']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['158_Received Frequency (MHz) Channel 2 (*)']) ? $checklist_item['158_Received Frequency (MHz) Channel 2 (*)']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Channel Band Width
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['159_Channel Band Width (MHz) Channel 1']) ? $checklist_item['159_Channel Band Width (MHz) Channel 1']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['160_Channel Band Width (MHz) Channel 2 (*)']) ? $checklist_item['160_Channel Band Width (MHz) Channel 2 (*)']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                    Transmitted Power
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['161_Transmitted Power (dBm) Channel 1']) ? $checklist_item['161_Transmitted Power (dBm) Channel 1']['pm_result'] . " dBm" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['162_Transmitted Power (dBm) Channel 2 (*)']) ? $checklist_item['162_Transmitted Power (dBm) Channel 2 (*)']['pm_result'] . " dBm" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Modulation Setting
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo @$checklist_item['163_Modulation Setting Channel 1']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo @$checklist_item['164_Modulation Setting Channel 2 (*)']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Number of E1
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo @$checklist_item['165_Number of E1 Channel 1']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo @$checklist_item['166_Number of E1 Channel 2 (*)']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Ethernet Bandwidth
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['167_Ethernet Bandwidth (MBPS) Channel 1']) ?  $checklist_item['167_Ethernet Bandwidth (MBPS) Channel 1']['pm_result'] : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['168_Ethernet Bandwidth (MBPS) Channel 2 (*)']) ?  $checklist_item['168_Ethernet Bandwidth (MBPS) Channel 2 (*)']['pm_result'] : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p></p>
                <table border="1" cellspacing="0" cellpadding="0" width="558">
                    <tbody>
                        <tr align="center" bgcolor="#A9A9A9">
                            <td width="264">
                                <strong>Site 2 Set-Up</strong>
                            </td>
                            <td width="142">
                                <strong>Channel 1</strong>
                            </td>
                            <td width="151">
                                <strong>Channel 2 (*)</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Sub Band
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo @$checklist_item['169_Sub Band Channel 1']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo @$checklist_item['170_Sub Band Channel 2 (*)']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Transmitted Frequency
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['171_Transmitted Frequency (MHz) Channel 1']) ? $checklist_item['171_Transmitted Frequency (MHz) Channel 1']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['172_Transmitted Frequency (MHz) Channel 2 (*)']) ? $checklist_item['172_Transmitted Frequency (MHz) Channel 2 (*)']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Received Frequency
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['173_Received Frequency (MHz) Channel 1']) ? $checklist_item['173_Received Frequency (MHz) Channel 1']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['174_Received Frequency (MHz) Channel 2 (*)']) ? $checklist_item['174_Received Frequency (MHz) Channel 2 (*)']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Channel Band Width
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['175_Channel Band Width (MHz) Channel 1']) ? $checklist_item['175_Channel Band Width (MHz) Channel 1']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['176_Channel Band Width (MHz) Channel 2 (*)']) ? $checklist_item['176_Channel Band Width (MHz) Channel 2 (*)']['pm_result'] . " (MHz)" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                    Transmitted Power
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['177_Transmitted Power (dBm) Channel 1']) ? $checklist_item['177_Transmitted Power (dBm) Channel 1']['pm_result'] . " dBm" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['178_Transmitted Power (dBm) Channel 2 (*)']) ? $checklist_item['178_Transmitted Power (dBm) Channel 2 (*)']['pm_result'] . " dBm" : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Modulation Setting
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo @$checklist_item['179_Modulation Setting Channel 1']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo @$checklist_item['180_Modulation Setting Channel 2 (*)']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Number of E1
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo @$checklist_item['181_Number of E1 Channel 1']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo @$checklist_item['182_Number of E1 Channel 2 (*)']['pm_result'] ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td width="264" valign="top">
                                Ethernet Bandwidth
                            </td>
                            <td width="142" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['167_Ethernet Bandwidth (MBPS) Channel 1']) ?  $checklist_item['167_Ethernet Bandwidth (MBPS) Channel 1']['pm_result'] . '(MBPS)' : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                            <td width="151" valign="top" align="right">
                                <?php echo !empty(@$checklist_item['168_Ethernet Bandwidth (MBPS) Channel 2 (*)']) ?  $checklist_item['168_Ethernet Bandwidth (MBPS) Channel 2 (*)']['pm_result'] . '(MBPS)' : '' ?> &nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p>(*) only if applicable</p>
            <?php }elseif ($ba_group['group_name'] == 'Receive Power Level Result' && !empty($receiver_power_groups)) { //Receive Power Level Result ?>
                <p></p>
                <p>
                    <strong>Receive Power Level Result</strong>
                </p>
                <table border="1" cellspacing="0" cellpadding="0" width="558">
                    <tbody>
                        <tr align="center"  bgcolor="#A9A9A9">
                            <td width="207" rowspan="2">
                                <strong>Item</strong>
                            </td>
                            <td width="181" colspan="2" valign="top">
                                <strong>Station A</strong>
                            </td>
                            <td width="170" colspan="2" valign="top">
                                <strong>Station B</strong>
                            </td>
                        </tr>
                        <tr align="center">
                            <td width="97" valign="top">
                                <strong>ODU 1</strong>
                            </td>
                            <td width="84" valign="top">
                                <strong>ODU 2 (**)</strong>
                            </td>
                            <td width="86" valign="top">
                                <strong>ODU 1</strong>
                            </td>
                            <td width="83" valign="top">
                                <strong>ODU 2 (**)</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="207" valign="top" align="left">
                                Rx level in link budget
                            </td>
                            <td width="97" valign="top" align="center">
                                <?php echo !empty($checklist_item['183_Rx level in link budget (dBm) Station A ODU 1']) ? $checklist_item['183_Rx level in link budget (dBm) Station A ODU 1']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="84" valign="top" align="center">
                                <?php echo !empty($checklist_item['184_Rx level in link budget (dBm) Station A ODU 2 (**)']) ? $checklist_item['184_Rx level in link budget (dBm) Station A ODU 2 (**)']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="86" valign="top" align="center">
                                <?php echo !empty($checklist_item['185_Rx level in link budget (dBm) Station B ODU 1']) ? $checklist_item['185_Rx level in link budget (dBm) Station B ODU 1']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="83" valign="top" align="center">
                                <?php echo !empty($checklist_item['186_Rx level in link budget (dBm) Station B ODU 2 (**)']) ? $checklist_item['186_Rx level in link budget (dBm) Station B ODU 2 (**)']['pm_result'] . ' dBm' : '' ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="207" valign="top" align="left">
                                Rx Level
                            </td>
                            <td width="97" valign="top" align="center">
                                <?php echo !empty($checklist_item['187_Rx level (dBm) Station A ODU 1']) ? $checklist_item['187_Rx level (dBm) Station A ODU 1']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="84" valign="top" align="center">
                                <?php echo !empty($checklist_item['188_Rx level (dBm) Station A ODU 2 (**)']) ? $checklist_item['188_Rx level (dBm) Station A ODU 2 (**)']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="86" valign="top" align="center">
                                <?php echo !empty($checklist_item['189_Rx level (dBm) Station B ODU 1']) ? $checklist_item['189_Rx level (dBm) Station B ODU 1']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="83" valign="top" align="center">
                                <?php echo !empty($checklist_item['190_Rx level (dBm) Station B ODU 2 (**)']) ? $checklist_item['190_Rx level (dBm) Station B ODU 2 (**)']['pm_result'] . ' dBm' : '' ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="207" valign="top" align="left">
                                    Tx Power
                            </td>
                            <td width="97" valign="top" align="center">
                                <?php echo !empty($checklist_item['191_Tx Power (dBm) Station A ODU 1']) ? $checklist_item['191_Tx Power (dBm) Station A ODU 1']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="84" valign="top" align="center">
                                <?php echo !empty($checklist_item['192_Tx Power (dBm) Station A ODU 2 (**)']) ? $checklist_item['192_Tx Power (dBm) Station A ODU 2 (**)']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="86" valign="top" align="center">
                                <?php echo !empty($checklist_item['193_Tx Power (dBm) Station B ODU 1']) ? $checklist_item['193_Tx Power (dBm) Station B ODU 1']['pm_result'] . ' dBm' : '' ?>
                            </td>
                            <td width="83" valign="top" align="center">
                                <?php echo !empty($checklist_item['194_Tx Power (dBm) Station B ODU 2 (**)']) ? $checklist_item['194_Tx Power (dBm) Station B ODU 2 (**)']['pm_result'] . ' dBm' : '' ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p>(**) if Applicable</p>
            <?php }elseif ($ba_group['group_name'] == 'Protection Switching Test' && !empty($protection_switching_groups)) { //Protection Switching Test ?>
                <p></p>
                <p>
                    <strong>Protection Switching Test</strong>
                </p>
                <p>
                    <strong>System Configuration : </strong>
                    <?php if(@$checklist_item['195_System Configuration']['pm_result'] == '1+1HSB'){ ?>
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /><strong> 1+1HSB &nbsp;&nbsp;&nbsp;
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> 1+1FD &nbsp;&nbsp;&nbsp;
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> 1+1SD</strong>
                    <?php } elseif (@$checklist_item['195_System Configuration']['pm_result'] == '1+1FD') { ?>
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> 1+1HSB &nbsp;&nbsp;&nbsp;
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> 1+1FD &nbsp;&nbsp;&nbsp;
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> 1+1SD</strong>
                    <?php } elseif (@$checklist_item['195_System Configuration']['pm_result'] == '1+1SD') { ?>
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> 1+1HSB &nbsp;&nbsp;&nbsp;
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> 1+1FD &nbsp;&nbsp;&nbsp;
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> 1+1SD</strong>
                    <?php }else{ ?>
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> 1+1HSB &nbsp;&nbsp;&nbsp;
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> 1+1FD &nbsp;&nbsp;&nbsp;
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> 1+1SD</strong>
                    <?php } ?>
                </p>
                <table border="1" cellspacing="0" cellpadding="0" width="558">
                    <tbody>
                        <tr align="center" bgcolor="#A9A9A9">
                            <td width="246" valign="top">
                                <strong>Site</strong>
                            </td>
                            <td width="312" valign="top">
                                <strong>Switching Test Result </strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="246" valign="top" align="left">
                                    <strong>Site 1</strong>
                            </td>
                            <td width="312" valign="top" align="center">
                                <?php if(@$checklist_item['196_Site 1 Switching Test Result']['pm_result'] == 'OK'){ ?>
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" />    OK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />  NOK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />  NA(*)
                                <?php } elseif (@$checklist_item['196_Site 1 Switching Test Result']['pm_result'] == 'NOK') { ?>
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />  OK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" />    NOK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />  NA(*)
                                <?php } else { ?>
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />  OK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" />  NOK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" />    NA(*)
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="246" valign="top" align="left">
                                    <strong>Site 2</strong>
                            </td>
                            <td width="312" valign="top" align="center">
                                <?php if(@$checklist_item['197_Site 2 Switching Test Result']['pm_result'] == 'OK'){ ?>
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> OK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> NOK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> NA(*)
                                <?php } elseif (@$checklist_item['197_Site 2 Switching Test Result']['pm_result'] == 'NOK') { ?>
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> OK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> NOK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> NA(*)
                                <?php } else { ?>
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> OK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> NOK &nbsp;&nbsp;&nbsp;
                                    <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> NA(*)
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p> (*) if the configuration only 1+0</p>

                <br pagebreak="true"/>
            <?php }elseif ($ba_group['group_name'] == 'Interference Check' && !empty($interference_check_groups)) { //Interference Check ?>
                <p><strong>Interference Check</strong></p>
                <table border="1" cellspacing="0" cellpadding="0">
                    <tbody align="center">
                        <tr bgcolor="#A9A9A9">
                            <td width="216" rowspan="2">
                                <strong>Item</strong>
                            </td>
                            <td width="178" colspan="2" valign="top">
                                <strong>Site 1 (<em>Tx Off on Site 2</em>)</strong>
                            </td>
                            <td width="163" colspan="2" valign="top">
                                <strong>Site 2 (<em>Tx Off on Site 1</em>)</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="89" valign="top">
                                <strong>ODU 1</strong>
                            </td>
                            <td width="89" valign="top">
                                <strong>ODU 2 (**)</strong>
                            </td>
                            <td width="89" valign="top">
                                <strong>ODU 1</strong>
                            </td>
                            <td width="74" valign="top">
                                <strong>ODU 2 (**)</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="216" valign="top">   Receive level</td>
                            <td width="89" valign="top">    <?php echo @$checklist_item['198_Receive Level Site 1 (Tx Off on Site 2) ODU 1']['pm_result'] ?></td>
                            <td width="89" valign="top">    <?php echo @$checklist_item['199_Receive Level Site 1 (Tx Off on Site 2) ODU 2 (**)']['pm_result'] ?></td>
                            <td width="89" valign="top">    <?php echo @$checklist_item['200_Receive Level Site 2 (Tx Off on Site 2) ODU 1']['pm_result'] ?></td>
                            <td width="74" valign="top">    <?php echo @$checklist_item['201_Receive Level Site 2 (Tx Off on Site 2) ODU 2 (**)']['pm_result'] ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php }elseif ($ba_group['group_name'] == 'DCN Configuration' && !empty($dcn_configuration_groups)) { //DCN Configuration ?>
                <p></p>
                <p><strong>DCN Configuration</strong></p>
                <table border="1" cellspacing="0" cellpadding="0" width="558">
                    <tbody>
                        <tr align="center" bgcolor="#A9A9A9">
                            <td width="216" rowspan="2"><strong>Station Name</strong></td>
                            <td width="181" valign="top"><strong>Site 1</strong></td>
                            <td width="161" valign="top"><strong>Site 2</strong></td>
                        </tr>
                        <tr bgcolor="#A9A9A9">
                            <td width="181" valign="top" align="left"><strong>  Name : <?php echo @$checklist_item['202_Site Name']['pm_result'] ?></strong></td>
                            <td width="161" valign="top" align="left"><strong>  Name : <?php echo @$checklist_item['207_Site Name']['pm_result'] ?></strong></td>
                        </tr>
                        <tr>
                            <td width="216" valign="top">   NE ID</td>
                            <td width="181" valign="top">   <?php echo @$checklist_item['203_NE ID Site 1']['pm_result'] ?></td>
                            <td width="161" valign="top">   <?php echo @$checklist_item['208_NE ID Site 2']['pm_result'] ?></td>
                        </tr>
                        <tr>
                            <td width="216" valign="top">   IP Address</td>
                            <td width="181" valign="top">   <?php echo @$checklist_item['204_IP Address Site 1']['pm_result'] ?></td>
                            <td width="161" valign="top">   <?php echo @$checklist_item['209_IP Address Site 2']['pm_result'] ?></td>
                        </tr>
                        <tr>
                            <td width="216" valign="top">   Net Mask</td>
                            <td width="181" valign="top">   <?php echo @$checklist_item['205_Net Mask Site 1']['pm_result'] ?></td>
                            <td width="161" valign="top">   <?php echo @$checklist_item['210_Net Mask Site 2']['pm_result'] ?></td>
                        </tr>
                        <tr>
                            <td width="216" valign="top">   Gateway</td>
                            <td width="181" valign="top">   <?php echo @$checklist_item['206_Gateway Site 1']['pm_result'] ?></td>
                            <td width="161" valign="top">   <?php echo @$checklist_item['211_Gateway Site 2']['pm_result'] ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php }elseif ($ba_group['group_name'] == 'QoS Setting' && !empty($qos_setting_groups)) { //QoS Setting ?>
                <p></p>
                <p><strong>QoS Setting</strong></p>
                <table border="1" cellspacing="0" cellpadding="0" width="558">
                    <tbody>
                        <tr align="center" bgcolor="#A9A9A9">
                            <td width="186" valign="top"><strong>Item</strong></td>
                            <td width="154" valign="top"> <strong>Set/Default</strong></td>
                            <td width="217" valign="top"><strong>Remarks</strong> </td>
                        </tr>
                        <tr>
                            <td width="186" valign="top">   P-Bit </td>
                            <td width="154" valign="top" align="center">
                                <?php if(@$checklist_item['212_P-Bit']['pm_result'] == 'Set'){ ?>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /><strong> Set &nbsp;&nbsp;&nbsp;</strong>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> Default</strong>
                                <?php }else{ ?>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> Set &nbsp;&nbsp;&nbsp;</strong>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /><strong> Default</strong>
                                <?php } ?>
                            </td>
                            <td width="217" valign="top">   Shall be set as attached document </td>
                        </tr>
                        <tr>
                            <td width="186" valign="top">   DSCP </td>
                            <td width="154" valign="top" align="center">
                                <?php if(@$checklist_item['213_DSCP']['pm_result'] == 'Set'){ ?>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /><strong> Set &nbsp;&nbsp;&nbsp;</strong>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> Default</strong>
                                <?php }else{ ?>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> Set &nbsp;&nbsp;&nbsp;</strong>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /><strong> Default</strong>
                                <?php } ?>
                            </td>
                            <td width="217" valign="top">   Shall be set as attached document </td>
                        </tr>
                        <tr>
                            <td width="186" valign="top">   Queque Size </td>
                            <td width="154" valign="top" align="center">
                                <?php if(@$checklist_item['214_Queque Size']['pm_result'] == 'Set'){ ?>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /><strong> Set &nbsp;&nbsp;&nbsp;</strong>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> Default</strong>
                                <?php }else{ ?>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /><strong> Set &nbsp;&nbsp;&nbsp;</strong>
                                <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /><strong> Default</strong>
                                <?php } ?>
                            </td>
                            <td width="217" valign="top">   Shall be set as attached document </td>
                        </tr>
                    </tbody>
                </table>
            <?php }elseif ($ba_group['group_name'] == 'Test & Measurement' && !empty($test_and_measurement)) {//Test & Measurement ?>
                <p></p>
                <p><strong>Test &amp; Measurement</strong></p>

                <?php 
                    $ber_test_group = searchGroup('374', @$test_and_measurement['sub_group']);
                    if (!empty($ber_test_group)){ ?>
                        <p><strong>a) BER Test</strong></p>
                        <table border="1" cellspacing="0" cellpadding="0" width="558" align="center">
                            <tbody>
                                <tr bgcolor="#A9A9A9">
                                    <td width="217" valign="top">
                                        <strong>SETUP</strong>
                                    </td>
                                    <td width="189" valign="top">
                                        <strong>Radio 1</strong>
                                    </td>
                                    <td width="151" valign="top">
                                        <strong>Radio 2</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="217" valign="top">
                                        <strong>Bit Error Rate</strong>
                                    </td>
                                    <td width="189" valign="OK">
                                        <?php if(@$checklist_item['215_Bit Error Rate Radio 1']['pm_result'] == 'Set'){ ?>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <strong>OK &nbsp;&nbsp;&nbsp;</strong>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong>NOK</strong>
                                        <?php }else{ ?>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong>OK &nbsp;&nbsp;&nbsp;</strong>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <strong>NOK</strong>
                                        <?php } ?>
                                    </td>
                                    <td width="151" valign="top">
                                        <?php if(@$checklist_item['216_Bit Error Rate Radio 2']['pm_result'] == 'Set'){ ?>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <strong>OK &nbsp;&nbsp;&nbsp;</strong>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong>NOK</strong>
                                        <?php }else{ ?>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <strong>OK &nbsp;&nbsp;&nbsp;</strong>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <strong>NOK</strong>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php }
                ?>

                <?php 
                    $ethernet_test_group = searchGroup('375', @$test_and_measurement['sub_group']);
                    if (!empty($ethernet_test_group)){ ?>
                        <p></p>
                        <p><strong>b) Ethernet Test</strong></p>
                        <table border="1" cellspacing="0" cellpadding="0" width="500" align="center">
                            <tbody>
                                <tr bgcolor="#A9A9A9">
                                    <td width="87"><strong>Item Test</strong></td>
                                    <td width="178"><strong>Specification</strong></td>
                                    <td width="166"><strong>Measurement</strong></td>
                                    <td width="129" colspan="6" valign="center"><strong>Result</strong></td>
                                </tr>
                                <tr>
                                    <td width="87" height="60">    LAN <br> Interface </td>
                                    <td width="178" height="60">    Connection Test <br>(ping test FE/GE to FE/GE <br> one Hop – IuB &amp; OAM) </td>
                                    <td width="166" height="60">    <?php echo @$checklist_item['218_LAN Interface Measurement']['pm_result'] ?> </td>
                                    <td width="129" height="60" align="left">
                                        <?php if(@$checklist_item['219_LAN Interface Result']['pm_result'] == 'OK'){ ?>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> OK <br>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> NOK <br>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> N/A
                                        <?php }elseif(@$checklist_item['219_LAN Interface Result']['pm_result'] == 'NOK'){ ?>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> OK <br>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> NOK <br>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> N/A
                                        <?php }else{ ?>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> OK <br>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> NOK <br>
                                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> N/A
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p></p>
                    <?php }
                ?>
            <?php }elseif ($ba_group['group_name'] == 'For NEC iPaso 400 & iPaso 1000 ' && !empty($for_nec_ipaso_400_groups)) {//For NEC iPaso 400 & iPaso 1000 ?>
                <!-- <br pagebreak="true"/>
                <p align="center"  bgcolor="#DBEBEE">
                    <pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
                    <table>
                        <tbody>
                            <tr>
                                <td width="118"></td>
                                <td style="background-image: url(<?php echo "http://eatp.alita.id/";?>assets/images/bg-report.png)" width="400" bgcolor="#FFFFFF">
                                    <H1>For NEC iPaso 400 &amp; iPaso 1000</H1>
                                </td>
                                <td width="117"></td>
                            </tr>
                        </tbody>
                    </table>

                    <pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
                </p> -->

                <br pagebreak="true"/>
                <p></p>
                <p><strong><u>For NEC iPaso 400 &amp; iPaso 1000 :</u></strong></p>
                <table border="1" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr align="center" bgcolor="#A9A9A9" >
                            <td valign="top"><strong>Universal Slot Number</strong></td>
                            <td valign="top"><strong>MODULE NAME</strong></td>
                            <td valign="top"><strong>SERIAL NUMBER</strong></td>
                            <td valign="top"><strong>DESTINATION</strong></td>
                        </tr>
                        <tr>
                            <td valign="top">   <?php echo @$checklist_item['236_Universal Slot Number #1']['pm_result'] ?></td>
                            <td valign="top">   <?php echo @$checklist_item['237_Universal Slot Number Module Name']['pm_result'] ?></td>
                            <td valign="top">   <?php echo @$checklist_item['254_Universal Slot Number Serial Number']['pm_result'] ?></td>
                            <td valign="top">   <?php echo @$checklist_item['428_Universal Slot Number Destination']['pm_result'] ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php }
        }
    }
} ?>
<?php } ?>
<!-- Page Group -->

<!-- Page -->
<!-- <br pagebreak="true"/>
<p>
    <strong>PENDING ITEMS</strong>
</p>
<p><strong><em>Please list down all pending items and its timeline to be completed</em></strong>
</p>
<table border="1" cellspacing="0" cellpadding="0" width="558">
    <tbody>
        <tr align="center" bgcolor="#A9A9A9">
            <td width="36" valign="top"><strong>NO</strong></td>
            <td width="180" valign="top"><strong>ITEM</strong></td>
            <td width="120" valign="top"><strong>TARGET COMPLETION</strong></td>
            <td width="100" valign="top"><strong>PIC</strong></td>
            <td width="100" valign="top"><strong>CONTACT NO.</strong></td>
            <td width="110" valign="top"><strong>STATUS COMPLETION</strong></td>
        </tr>
        <?php $i = 1;
              foreach ($pending as $data) { ?>
        <tr>
            <td width="36" valign="top"> <?php echo $i ?></td>
            <td width="180" valign="top"> <?php echo $data['item_descr'] ?></td>
            <td width="120" valign="top"> <?php echo $data['target_completion'] ?></td>
            <td width="100" valign="top"> <?php echo $data['pic'] ?></td>
            <td width="100" valign="top"> <?php echo $data['contact_no'] ?></td>
            <?php if ($data['is_completed'] == 1) { ?>
                <td width="110" valign="top"> COMPLETED ?></td>
            <?php }else{ ?>
                <td width="110" valign="top"> NOT YET </td>
            <?php } ?>
        </tr>
        <?php $i++;
              } ?>
    </tbody>
</table>
<p></p>
<p></p>
<table border="1" cellspacing="0" cellpadding="0" width="558">
    <tbody>
        <tr bgcolor="#A9A9A9">
            <td width="558" valign="top"><strong>   ADDITIONAL NOTES :</strong></td>
        </tr>
        <tr>
            <td width="558" valign="top"><?php echo htmlspecialchars($notes) ?></td>
        </tr>
    </tbody>
</table>
<p></p>
<table border="1" cellspacing="0" cellpadding="0" width="558">
	<tbody>
        <tr>
            <td width="283" rowspan="2">
                Acceptance by :<br>
                VENDOR<br>
                <img align="left" width="150" height="50" src="<?php echo "http://10.0.5.85:5050/".''.$ttd_fop_alita ?>"/>
                <br>
                    _______________________________
                <br>
                Name : <?php echo $alita_name ?> Date : <?php echo $alita_date ?>
            </td>
            <td width="274" valign="top" hidden="hidden">
                Verified and Approved by :<br>
                ENGINEER<br>
                <img align="left" width="150" height="50" src="<?php echo "http://10.0.5.85:5050/".''.$ttd_fop_hs ?>"/>
                <br>
                Name : <?php echo $hs_name ?> Date : <?php echo $hs_date ?>
            </td>
        </tr>
        <tr>
            <td width="274" valign="top">
                SUPERVISOR<br>
                <img align="left" width="150" height="50" src="<?php echo "http://10.0.5.85:5050/".''.$ttd_spv ?>"/>
                <br>
                Name : <?php echo $spv_username." / ".$spv_created_on ?>
            </td>
        </tr>
    </tbody>
</table> -->
<!-- Page -->

<!-- Image Page Function -->
<?php
function generate_view_image($data,$no,$user_id="",$endian=null, $spacing, $remarks)
{
    $r="";
    try {
        if (@$data['has_photo'] == 1) {
            $r = '<p>'.$spacing.'&nbsp;&nbsp;'.$data['checklist_name'].'</p>';
            if(!empty(@$data['photo_name'])){
                //* UPDATE 20-09-2023 (Merubah image jadi base64)
                $image = "https://privaterepositorynas.alita.id/".$data['photo_name'];
                $mimeType = finfo_buffer(finfo_open(), file_get_contents($image), FILEINFO_MIME_TYPE);
                $base64_image = base64_encode(file_get_contents($image));
                $r .= $spacing.'&nbsp;&nbsp;'.'<table><tr><td width="542.4" height="399.36"><img src="data:'.$mimeType.';base64,'.$base64_image.'"></td></tr></table>';
                //*
                //   $r='<img width="542.4" height="399.36" src="'.base_url('').$data['photo_name'].'">';
                // $r .= $spacing.'&nbsp;&nbsp;'.'<table><tr><td width="542.4" height="399.36"><img src="https://privaterepositorynas.alita.id/'.$data['photo_name'].'"></td></tr></table>';
            }else{
                $r .= $spacing.'&nbsp;&nbsp;'.'<img width="542.4" height="399.36" src="http://eatp.alita.id/assets/images/noimage.jpg">';
            }
        }
    } catch (\Exception $e) {
        $r = '<p>'.$spacing.'&nbsp;&nbsp;'.$data['checklist_name'].'</p>';
        $r .= $spacing.'<img width="542.4" height="399.36" src="http://eatp.alita.id/assets/images/noimage.jpg">';
    }

    return $r;
}

function sub_render_image($parent,$pagebreak="",$remarks,$iterasi,$user_id="",$endian, $all_header = "", $pos = 0, $spacing = "") {
    $header = '<p><strong>'.$spacing.$parent['group_name'].'</strong></p>';

    if( isset($parent['sub_group']) ) {
      $_2nd = 1;
      $loop_key = 0;
      foreach ($parent['sub_group'] as $gg) {
        // if ($gg['group_name'] != 'ODU ') {
            if ($loop_key == 0 && $pos == 0) {
                $all_header .= $header;
            }else{
                $all_header = $header;
            }
            sub_render_image($gg, $pagebreak, $remarks, false,$user_id,$endian, $all_header, $loop_key, $spacing.'&nbsp;&nbsp;');
            $loop_key++;
        // }
      }
    }
    if(isset($parent['checklists'])) {
      $i=1;
      $checklists = "";
      if (@$parent['checklists'][0]['has_photo'] == "2") {
        $checklists = '<table style="margin:auto" border="1" cellspacing="1" cellpadding="1" width="100%">
                    <tbody>';
        //Header
        $checklists .= '<tr bgcolor="#A9A9A9">';
        foreach ($parent['checklists'] as $tbheader) {
            $checklists .= '<td align="center">
                                <strong>'.$tbheader['checklist_name'].'</strong>
                            </td>';
        }
        $checklists .= '</tr>';
        //Body
        $checklists .= '<tr>';
        foreach ($parent['checklists'] as $tbbody) {
            $checklists .= '<td align="center">'.@$tbbody['pm_result'].'</td>';
        }
        $checklists .= '</tr>';
        $checklists .= '</tbody></table>';
      }else{
        foreach ($parent['checklists'] as $cc) {
            //echo $cc['checklist_name']."<br/>";
            $checklists .= generate_view_image($cc,$i,$user_id,$endian,$spacing, $remarks);
            $i++;
        }
      }

      if (!empty($checklists)) {
          if ($pos == 0) {
            // echo $pagebreak;
            echo $all_header;
            
            $pagebreak = '';
          }
          echo $header;
        //   echo '<div nobr="true">';
          echo $checklists;
        //   echo '</div>';
      }
      $all_header = '';
    }
}
?>
<!-- Image Page Function -->

<?php if ($visit_type == "120" || $visit_type == "112") { ?>
<!-- Page -->
<?php
    $mw_radio_system_ne_groups = searchGroup('3', $checklistsgrouping);
    if (!empty(@$mw_radio_system_ne_groups['sub_group'])) { ?>
        <br pagebreak="true"/>
        <p align="center"  bgcolor="#DBEBEE">
        <pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
        <table>
            <tbody>
                <tr>
                    <td width="118"></td>
                    <td style="background-image: url(<?php echo "http://eatp.alita.id/";?>assets/images/bg-report.png)" width="400" bgcolor="#FFFFFF">
                        <H1>ACCEPTANCE TEST DOCUMENT<br/>NEAR END</H1>
                    </td>
                    <td width="117"></td>
                </tr>
            </tbody>
        </table>

        <pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
        </p>

         <!-- MW Radio System NE -->
         <br pagebreak="true"/>
        <?php 
            $all_header = "";
            $_1st = 1;
            foreach ($mw_radio_system_ne_groups['sub_group'] as $key => $gg) {
                sub_render_image($gg, "", $remarks, $_1st++, $this->session->userdata('user_id'), null, $all_header, $key);
            }
        ?>
    <?php }
?>
<!-- Page -->

<!-- Page -->

<?php
    $mw_radio_system_fe_groups = searchGroup('4', $checklistsgrouping);
    if (!empty(@$mw_radio_system_fe_groups['sub_group']) && $visit_type == "112") { ?>
        <br pagebreak="true"/>
        <p align="center"  bgcolor="#DBEBEE">
        <pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
        <table>
            <tbody>
                <tr>
                    <td width="118"></td>
                    <td style="background-image: url(<?php echo "http://eatp.alita.id/";?>assets/images/bg-report.png)" width="400" bgcolor="#FFFFFF">
                        <H1>ACCEPTANCE TEST DOCUMENT<br/>FAR END</H1>
                    </td>
                    <td width="117"></td>
                </tr>
            </tbody>
        </table>

        <pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
        </p>

         <!-- MW Radio System FE -->
         <br pagebreak="true"/>
        <?php 
            $all_header = "";
            $_1st = 1;
            foreach ($mw_radio_system_fe_groups['sub_group'] as $key => $gg) {
                sub_render_image($gg, "", $remarks, $_1st++, $this->session->userdata('user_id'), null, $all_header, $key);
            }
        ?>
    <?php }
?>
<!-- Page -->
<?php } ?>
<!-- Page -->

<!-- Page -->
<br pagebreak="true"/>
<p align="center"  bgcolor="#DBEBEE">
<pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
<table>
    <tbody>
        <tr>
            <td width="118"></td>
            <td style="background-image: url('."http://eatp.alita.id/".'assets/images/bg-report.png)" width="400" bgcolor="#FFFFFF">
            <?php if ($visit_type == "120" || $visit_type == "112") { ?>
                <H1>Attachment</H1>
            <?php }else{ ?>
                <H1>Acceptence Checklist Document</H1>
            <?php } ?>
            </td>
            <td width="117"></td>
        </tr>
    </tbody>
</table>

<pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
</p>
<br pagebreak="true"/>
<?php 
    $all_header = "";
    $_1st = 1;
    foreach (@$checklistsgrouping as $key => $gg) {
        if (
            $gg['group_id'] != '618' && //Berita Acara
            $gg['group_id'] != '3' && //MW Radio System NE
            $gg['group_id'] != '4' //MW Radio System FE
        ) {
            sub_render_image($gg, "", $remarks, $_1st++, $this->session->userdata('user_id'), null, $all_header, $key);
        }
    }
?>
<!-- Page -->

<!-- Page -->
<p></p>
<br pagebreak="true"/>
<p align="center"  bgcolor="#DBEBEE">
<pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
<table>
    <tbody>
        <tr>
            <td width="118"></td>
            <td style="background-image: url(<?php echo "http://eatp.alita.id/";?>assets/images/bg-report.png)" width="400" bgcolor="#FFFFFF">
                <H1>CERTIFICATE OF COMPLETION</H1>
            </td>
            <td width="117"></td>
        </tr>
    </tbody>
</table>

<pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre><pre></pre>
</p>

<br pagebreak="true"/>
<table border="1" width="100%">
<tbody>
    <tr>
        <td style="font-size: 12pt; font-weight: bold">
            On this date <span><?php echo date('Y-m-d', strtotime($acceptance_date)) ?></span> we the undersigned hereby certify that the Site at the above location 
            is done for <span style="text-decoration: underline"><?php echo $visit_type_label ?></span> according to the attached documentations.
        </td>
    </tr>
    <tr>
        <td>
            <table style="padding: 10px 0; font-weight: bold">
                <tr>
                    <td><span style="text-decoration:underline">On behalf of ALITA</span></td>
                </tr>
                <tr>
                    <td>Name : <?php echo $pic_alita_name ?></td>
                </tr>
                <tr>
                    <td>Position : PIC Alita</td>
                </tr>
                <tr>
                    <td><span style="text-decoration:underline">On behalf of XL AXIATA</span></td>
                </tr>
                <tr>
                    <td>Name : <?php echo $hs_name ?></td>
                </tr>
                <tr>
                    <td>Date Pass : <span style="text-decoration:underline"><?php echo date('Y-m-d', strtotime($acceptance_date)) ?></span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" style="font-size: 12pt; font-weight: bold; background-color: #00CCFF">
            ATP Result
        </td>
    </tr>
    <tr>
        <td align="center" style="font-size: 12pt; font-weight: bold;">
            <table>
                <tr>
                    <?php if($acceptance_status == 1 || $acceptance_status == 2){ ?>
                    <td>
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <i> Accepted</i>
                    </td>
                    <td>
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <i> Reject</i>
                    </td>
                    <?php }else{ ?>    
                    <td>
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/uncheck.png" /> <i> Accepted</i>
                    </td>
                    <td>
                        <img width="12" height="10" src="<?php echo "http://eatp.alita.id/";?>assets/images/check.png" /> <i> Reject</i>
                    </td>
                    <?php } ?>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <span style="font-size: 12pt; font-weight: bold">Remarks :</span>
            <br>
            <?php if (!empty($pending)) { ?>
                <table border="1" width="100%">
                    <tbody>
                        <tr align="center" bgcolor="#A9A9A9">
                            <td width="36" valign="top"><strong>NO</strong></td>
                            <td width="170" valign="top"><strong>ITEM</strong></td>
                            <td valign="top"><strong>TARGET COMPLETION</strong></td>
                            <td valign="top"><strong>PIC</strong></td>
                            <td valign="top"><strong>CONTACT NO.</strong></td>
                            <td valign="top"><strong>STATUS COMPLETION</strong></td>
                        </tr>
                        <?php $i = 1;
                            foreach ($pending as $data) { ?>
                        <tr>
                            <td width="36" valign="top"> <?php echo $i ?></td>
                            <td width="170" valign="top"> <?php echo $data['item_descr'] ?></td>
                            <td valign="top"> <?php echo $data['target_completion'] ?></td>
                            <td valign="top"> <?php echo $data['pic'] ?></td>
                            <td valign="top"> <?php echo $data['contact_no'] ?></td>
                            <?php if ($data['is_completed'] == 1) { ?>
                                <td valign="top"> COMPLETED ?></td>
                            <?php }else{ ?>
                                <td valign="top"> NOT YET </td>
                            <?php } ?>
                        </tr>
                        <?php $i++;
                            } ?>
                    </tbody>
                </table>
            <?php } ?>
        </td>
    </tr>
</tbody>
</table>
<!-- Page -->


<!-- Search Group Function -->
<?php 
    function searchGroup($group_id, $group_array)
    {
        $result = array();
        //Get Index Array
        $index = array_search($group_id, array_column($group_array, 'group_id'));

        if (isset($index)) {
            $result = $group_array[$index];
        }

        return $result;
    }
?>
<!-- Search Group Function -->