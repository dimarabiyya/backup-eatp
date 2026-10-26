<?php $grand_total = json_decode($grand_total); ?>
<table width="85%" class="table table-bordered"  border="0" align="right">
  <tr>
    <th colspan="6"><?php echo $title?></th>
    <th colspan="4" style="text-align:center;">Project Implementation</th>
  </tr>
  <tr>
    <?php foreach($data_header as $item):?>
        <th class="merge-center"><?php echo $item; ?></th>
    <?php endforeach;?>
  </tr>
  <tbody>
      <?php foreach($summary as $item):?>
      <tr>
        <td rowspan="2" class="merge-center"><?php echo $item->region;?></td>
        <td>Installation</td>
        <td style="text-align: right;"><?php echo @$item->installation->po;?></td>
        <td style="text-align: right;"><?php echo @$item->installation->din;?></td>
        <td style="text-align: right;"><?php echo @$item->installation->pi_closed;?></td>                                        
        <td style="text-align: right;"><?php echo @$item->installation->ess;?></td>                                        
        <td style="text-align: right;"><?php echo @$item->installation->pi_done;?></td>                                        
        <td style="text-align: right;"><?php echo @$item->installation->pi_ongoing;?></td>                                        
        <td style="text-align: right;"><?php echo @$item->installation->pi_hold;?></td>      
        <td style="text-align: right;"><?php echo @$item->installation->pi_notstart;?></td>                                        
      </tr>
      <tr>
        <td>Add Service</td>
        <td style="text-align: right;"><?php echo @$item->addservice->po;?></td>
        <td style="text-align: right;"><?php echo @$item->addservice->din;?></td>
        <td style="text-align: right;"><?php echo @$item->addservice->pi_closed;?></td>                                        
        <td style="text-align: right;"><?php echo @$item->addservice->ess;?></td>                                        
        <td style="text-align: right;"><?php echo @$item->addservice->pi_done;?></td>                                        
        <td style="text-align: right;"><?php echo @$item->addservice->pi_ongoing;?></td>                                        
        <td style="text-align: right;"><?php echo @$item->addservice->pi_hold;?></td>      
        <td style="text-align: right;"><?php echo @$item->addservice->pi_notstart;?></td>  
      </tr>
      <tr>
        <td colspan="2" class="sub-total">Total</td>
        <td class="sub-total"><?php echo @$item->total_po;?></td>
        <td class="sub-total"><?php echo @$item->total_din;?></td>
        <td class="sub-total"><?php echo @$item->total_atp;?></td>                                        
        <td class="sub-total"><?php echo @$item->total_ess;?></td>                                        
        <td class="sub-total"><?php echo @$item->total_done;?></td>                                        
        <td class="sub-total"><?php echo @$item->total_ongoing;?></td>                                       
        <td class="sub-total"><?php echo @$item->total_hold;?></td>      
        <td class="sub-total"><?php echo @$item->total_notstart;?></td>  
      </tr>
    <?php endforeach;?>
    <tr>
      <td colspan="2" class="grand-total">Grand Total</td>
      <td class="grand-total"><?php echo @$grand_total->po;?></td>
      <td class="grand-total"><?php echo @$grand_total->din;?></td>
      <td class="grand-total"><?php echo @$grand_total->atp;?></td>
      <td class="grand-total"><?php echo @$grand_total->ess;?></td>
      <td class="grand-total"><?php echo @$grand_total->done;?></td>
      <td class="grand-total"><?php echo @$grand_total->ongoing;?></td>
      <td class="grand-total"><?php echo @$grand_total->hold;?></td>
      <td class="grand-total"><?php echo @$grand_total->notstart;?></td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td class="sub-total"><?php echo (($grand_total->din ==0)?'0':round((@$grand_total->din/@$grand_total->po)*100))?>%</td>
      <td class="sub-total"><?php echo (($grand_total->din ==0)?'0':round((@$grand_total->atp/@$grand_total->po)*100))?>%</td>
      <td class="sub-total"><?php echo (($grand_total->din ==0)?'0':round((@$grand_total->ess/@$grand_total->po)*100))?>%</td>
      <td colspan="3"></td>
    </tr>
  </tbody>
</table>