<br pagebreak="true"/>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img align="center"
        width="70"
        height="45"
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
<p><br/></p>
<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td width="90" valign="top" align="left">&nbsp;&nbsp;Pada hari ini, </td>
            <td class="border_bottom" width="100" valign="top" align="center">
                <?php $hari = date('l', strtotime($ID3586));
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
                <?php echo (date('d', strtotime($ID3586))) ?>
            </td>
            <td width="45" valign="top"> bulan </td>
            <td class="border_bottom" width="70" valign="top" align="center">
                <?php echo (date('m', strtotime($ID3586))) ?>
            </td>
            <td width="45" valign="top"> tahun </td>
            <td class="border_bottom" width="70" valign="top" align="center">
                <?php echo (date('Y', strtotime($ID3586))) ?>
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
    <strong>Los Survey</strong>
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
                <?php echo $ID3587 ?>
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
                <?php echo $ID3588 ?>
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
                <?php echo $ID3589 ?>
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
                <?php echo $ID3590 ?>
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
                <?php echo $ID3591 ?>
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
                a.
            </td>
            <td  class="border_bottom" width="400" valign="top">
                <?php echo $ID3592 ?>
            </td>
            <td width="15" valign="top">
                :
            </td>
            <td  class="border_bottom" width="50" valign="top" align="center">
                <?php echo $ID3593 ?>
            </td>
            <td width="80" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="20" valign="top">
                b.
            </td>
            <td  class="border_bottom" width="400" valign="top">
                <?php echo $ID3594 ?>
            </td>
            <td width="15" valign="top">
                :
            </td>
            <td  class="border_bottom" width="50" valign="top" align="center">
                <?php echo $ID3595 ?>
            </td>
            <td width="80" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="20" valign="top">
                c.
            </td>
            <td  class="border_bottom" width="400" valign="top">
                <?php echo $ID3596 ?>
            </td>
            <td width="15" valign="top">
                :
            </td>
            <td  class="border_bottom" width="50" valign="top" align="center">
                <?php echo $ID3597 ?>
            </td>
            <td width="80" valign="top">
                hari kalender
            </td>
        </tr>
        <tr>
            <td width="20" valign="top">
                d.
            </td>
            <td  class="border_bottom" width="400" valign="top">
                <?php echo $ID3598 ?>
            </td>
            <td width="15" valign="top">
                :
            </td>
            <td  class="border_bottom" width="50" valign="top" align="center">
                <?php echo $ID3599 ?>
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