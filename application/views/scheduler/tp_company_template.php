<?php

/*
 * tp_company_template.php, Mar 13, 2015 9:48:17 AM    
 * 
 * Andedi Tarmadi
 * 
 * 
 * ----------------
 * Tumbas Wiralodra
 * Email : andedi.foss@gmail.com
 * 087829986468
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<p>Dear Tower Provider, <br>
Below is list of sites that will be expired with incoming expired ageing. Please be ware to prepare the renewal if needed. Thank you very much for your attention.<p>

<table border=1 cellspacing=0 cellpadding=3 style="font-size: 13px;">
    <tr>
        <th>No</th><th>Region</th><th>Site ID</th><th>Vendor ID</th><th>Site Name</th><th>TP Name</th><th>Lease End Date</th><th>Expired Status</th><th>Ageing Category</th><th>Ageing (Days)</th>
    </tr>
    
    <?php
    $j = 1;
    foreach ($sites as $site) {
        echo "<tr>
            <td align='center'>{$j}</td><td>{$site->region}</td><td>{$site->siteid}</td><td>{$site->vendorid}</td><td>{$site->sitename}</td><td>{$site->tpname}</td><td align='center'>{$site->leaseenddate}</td><td>{$site->expiredstatus}</td><td>{$site->ageingcategory}</td><td align='right'>{$site->ageingdays}</td>
            </tr>";
        $j++;    
    }
    ?>
</table>