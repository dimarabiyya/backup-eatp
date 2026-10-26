<?php
/*
  Document   : email_test_page
  Created on : Feb 4, 2016 10:24:13 AM
  Author     : tumbas
  Description: Purpose of the PHP File follows.
 */

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Email Server Test Page</title>
  </head>
  <body style="background-color: #8e0404; margin: 5px 200px;">
    <div style="background-color: whitesmoke; padding: 1px 10px; border-radius: 2px">
      <h1 style="text-align: center"><img src="<?php echo base_url('file/images/logosmartfren.png'); ?>" style="vertical-align: middle; width: 50px"> Email Server Test Page</h1>
      <p>The Email Configuration is :</p>
      <table>
        <tr>
          <td>Host</td>
          <td> : <?php echo $host; ?></td>
        </tr>
        <tr>
          <td>Port</td>
          <td> : <?php echo $port; ?></td>
        </tr>
        <tr>
          <td>Email Address</td>
          <td> : <?php echo $email_address; ?></td>
        </tr>
        <tr>
          <td>Password</td>
          <td> : *****</td>
        </tr>
      </table>
      <hr>
      <p>
        You can access SiMS through this link : <a href="http://sims.smartfren.com">http://sims.smartfren.com</a>
      </p>
    </div>
  </body>
</html>