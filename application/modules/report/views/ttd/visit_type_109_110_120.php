<br pagebreak="true"/>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img align="center"
        width="70" height="45"
        src="<?php echo base_url();?>assets/images/logo_xl.png"
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
                <?php echo $po_description ?>
            </td>
        </tr>
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
                Opposite Site ID/Site Name*
            </td>
            <td width="19" valign="top">
                :
            </td>
            <td class="border_bottom" width="325" valign="top">
                <?php echo $site_id_fe.' / '.$opposite_site_name ?>
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
				//$hari = date('l', strtotime($ID3757));
				$hari = date('l', strtotime($hs_date));
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
				//echo (date('d', strtotime($ID3757))) 
				echo (date('d', strtotime($hs_date))) 
				?>
            </td>
            <td width="45" valign="top"> bulan </td>
            <td class="border_bottom" width="70" valign="top" align="center">
                <?php 
				//echo (date('m', strtotime($ID3757))) 
				echo (date('m', strtotime($hs_date))) 
				?>
            </td>
            <td width="45" valign="top"> tahun </td>
            <td class="border_bottom" width="70" valign="top" align="center">
                <?php 
				//echo (date('Y', strtotime($ID3757))) 
				echo (date('Y', strtotime($hs_date))) 
				//start edit by hadi 20181016
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
    <strong>Installasi dan Pengetesan Radio iPaso <?php echo $ID3753 ?>, <u><?php echo $ID3754 ?></u> Ghz, <u><?php echo $ID3755 ?></u>  Mbps</strong>
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
                <?php echo $ID3758 ?>
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
                <?php echo $ID3759 ?>
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
                <?php echo $ID3760 ?>
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
                <?php echo $ID3761 ?>
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
                <?php echo $ID3762 ?>
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
        <tr>
            <td width="20" valign="top">
                e.
            </td>
            <td  class="border_bottom" width="400" valign="top">
                <?php echo $ID3763 ?>
            </td>
            <td width="15" valign="top">
                :
            </td>
            <td  class="border_bottom" width="50" valign="top" align="center">
                <?php echo $ID3764 ?>
            </td>
            <td width="80" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="20" valign="top">
                f.
            </td>
            <td  class="border_bottom" width="400" valign="top">
                <?php echo $ID3765 ?>
            </td>
            <td width="15" valign="top">
                :
            </td>
            <td  class="border_bottom" width="50" valign="top" align="center">
                <?php echo $ID3766 ?>
            </td>
            <td width="80" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="20" valign="top">
                g.
            </td>
            <td  class="border_bottom" width="400" valign="top">
                <?php echo $ID3767 ?>
            </td>
            <td width="15" valign="top">
                :
            </td>
            <td  class="border_bottom" width="50" valign="top" align="center">
                <?php echo $ID3768 ?>
            </td>
            <td width="80" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="20" valign="top">
                h.
            </td>
            <td  class="border_bottom" width="400" valign="top">
                <?php echo $ID3769 ?>
            </td>
            <td width="15" valign="top">
                :
            </td>
            <td  class="border_bottom" width="50" valign="top" align="center">
                <?php echo $ID3770 ?>
            </td>
            <td width="80" valign="top">
                hari kalender
            </td>
        </tr>
    </tbody>
</table>

<p>
    Demikian Berita Acara ini dibuat dengan sebenar-benarnya dan dapat
    dipergunakan sebagaimana mestinya.
</p>
<p></p>
<table>
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
            <td width="180" align="center"><p> <img align="right" width="150" height="50" src="<?php echo base_url().''.$ttd_fop_hs ?>"/></p></td>
            <td width="250"></td>
            <td width="180" align="center"><p> <img align="right" width="150" height="50" src="<?php echo base_url().''.$ttd_fop_alita ?>"/></p></td>
        </tr>
        <tr>
            <td width="180" align="center" class="border_bottom"><p> <?php echo $hs_name ?> </p></td>
            <td width="250"></td>
            <td width="180" align="center" class="border_bottom"><p> <?php echo $alita_name ?></p></td>
        </tr>
    </tbody>
</table>
<p>
    <strong><em>*Note : Wajib diisi</em></strong>
</p>

<br pagebreak="true"/>
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
            <td width="558" valign="top"><?php echo $notes ?></td>
        </tr>
    </tbody>
</table>
<p></p>
<table border="1" cellspacing="0" cellpadding="0" width="558">
	<!-- start edit by hadi 2018-10-23 -->
    <tbody>
        <tr>
            <td width="283" rowspan="2">
                Acceptance by :<br>
                VENDOR<br>
                <img align="left" width="150" height="50" src="<?php echo base_url().''.$ttd_fop_alita ?>"/>
                <br>
                    _______________________________
                <br>
                Name : <?php echo $alita_name ?> Date : <?php echo $alita_date ?>
            </td>
            <td width="274" valign="top" hidden="hidden">
                Verified and Approved by :<br>
                ENGINEER<br>
                <img align="left" width="150" height="50" src="<?php echo base_url().''.$ttd_fop_hs ?>"/>
                <br>
                Name : <?php echo $hs_name ?> Date : <?php echo $hs_date ?>
            </td>
        </tr>
        <tr>
            <td width="274" valign="top">
                SUPERVISOR<br>
                <img align="left" width="150" height="50" src="<?php echo base_url().''.$ttd_spv ?>"/>
                <br>
                Name : <?php echo $spv_username." / ".$spv_created_on ?>
            </td>
        </tr>
    </tbody>
	<!-- end edit by hadi 2018-10-23 -->
</table>