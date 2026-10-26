<style>
.t_judul{
        
        padding:12px;
        font-size:16px;
        font-weight:bold;
        margin-bottom: 10px;
        padding-bottom: 20px;
}

.t_header{
        background:#dddddd;
        vertical-align: middle;
        padding:3px;
        text-align: center;
        border: solid 1px;
        font-weight: bold;
}

.t_isi{
        vertical-align: middle;
        padding:3px;
        border: solid 1px;
        font-weight: bold;
}


	
</style>

<script>
$(document).ready(function() {
    
    $('.d_menu').click(function() {
        var groupid        = $(this).attr('groupid');
        var menuid          = $(this).attr('menuid');
        var nilai      = $(this).is(':checked');
        
        $.post(
            '<?php echo base_url()?>index.php/grouprole/saveUserRole', 
            {groupid:groupid,menuid:menuid,nilai:nilai},
            function(data) {
                if(data.status == 'OK') {
                    $("#messageBox").removeClass().addClass("errorbox").html(data.pesan).fadeIn(1000).fadeOut(1000);
                }
            },
            "json"
        ).error(function() {
            alert("-");
        });
    });
	
	$('.saveAction').click(function() {
        var groupid        = $(this).attr('groupid');
        var menuid          = $(this).attr('menuid');
		var actid          = $(this).attr('actid');
        var nilai      = $(this).is(':checked');
        
        $.post(
            '<?php echo base_url()?>index.php/grouprole/saveUserRoleAction', 
            {groupid:groupid,menuid:menuid,nilai:nilai,actid:actid},
            function(data) {
                if(data.status == 'OK') {
                    $("#messageBox").removeClass().addClass("errorbox").html(data.pesan).fadeIn(1000).fadeOut(1000);
                }
            },
            "json"
        ).error(function() {
            alert("-");
        });
    });
    
});

function jump_url(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

$(document).ready(function() {
	$("#messageBox").addClass("messagebox");setTimeout(function(){
		$("#messageBox").fadeOut("slow")}, 3000);
	});
function messagebox(){
	$("#messageBox").removeClass().addClass("confirmbox").html("Item has been saved").fadeIn(2000).fadeOut(6000);
}
function alertbox(){
	$("#messageBox").removeClass().addClass("errorbox").html("Oops, there was an error!").fadeIn(2000).fadeOut(6000);
}

</script>

<style type="text/css" media="screen">
<!--
.container {min-width: 170px; margin: 0px auto;position: fixed; right: 0px; top: 0px;}
.messagebox {background-color: #F5F5F5;padding:0px;margin:0px;border: 1px solid #DBDBDB;}
.errorbox {color:#000;background-color:#f96d07;padding:5px;margin:10px 0px;border:1px solid #f27c7c;font-weight:bold;color:white;}
.confirmbox {background-color:#F2FFDB;color:#151515;border:1px solid #9C6;margin:10px 0px;padding:5px;}
-->
</style>
<div class="container">
<div class="messagebox" id="messageBox"></div>
</div>
<?php
function monthTH($name="month", $selected=null)
{
       $dd = '';

        $months = array(
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December');
        /*** the current month ***/
        $selected = is_null($selected) ? date('n', time()) : $selected;

        for ($i = 1; $i <= 12; $i++)
        {
                $dd .= '<th class="t_header"';
                if ($i == $selected)
                {
                        $dd .= 'style="background-color:#FFDFBF;"';
                }
                /*** get the month ***/
                $dd .= '>'.$months[$i].'</th>';
        }
		$dd .= '';
        return $dd;
}
function dateMonth($name="month", $selected=null,$no)
{
		$dd = '';
		
         $months = array(
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9,
                10,
                11,
                12);
        /*** the current month ***/
        $selected = is_null($selected) ? date('n', time()) : $selected;

        for ($i = 1; $i <= 12; $i++)
        {
                $dd .= '<td align="center"';
                if ($i == $selected)
                {
                        $dd .= 'style="background-color:#FFDFBF;"';
                }
                /*** get the month ***/
                $dd .= '><input type="text" name="EventDate"';
				if ( $i < $selected)
				{
					$dd .= 'readonly ';
				}else {
					$dd .= ' id="DateTaken'.$no.''.$i.'" ';
				}

				$dd .='style="width:70px"> <a href="#" >LINK LIST</a></td>
				 <script type="text/javascript">
    $(document).ready(function () {
        $("#DateTaken'.$no.''.$i.'").datepicker({
			minDate: new Date(2013,'.$i.' - 1, 1),
			maxDate:  new Date(2013,'.$i.' - 1,31),
            dateFormat: "dd-mm-yy"
        });
    });
</script>';
        }
		$dd .= '';
        return $dd;
}
?>
<div class="box" style="min-height:900px;">
        <!-- box / title -->
        <div class="title">
                <h5>Journey Cycle</h5>
        </div>
        <!-- end box / title -->
        
        <form id="form" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <div class="form">
                        <div class="fields">
                                
                                
                                <div class="field">
                                        <div class="label">
                                                <label for="name">Years:</label>
                                        </div>
                                        <div class="input">
                                                <select name="group" onchange="jump_url('parent',this,0)" >>
                                                <option value="<?php echo  base_url()?>index.php/grouprole/">Choice</option>
                                                <?php 
					for ($i=$yearss;$i<=$yearse;$i++){ 
						echo "<option value=".base_url()."index.php/journey/choice/".$i.">".$i."</option>n";     
					} 
				
                                                echo'</select>';
                                                ?>
                                                
                                        </div>
                                </div>
                        </div>
                </div>
        </form><br><br>&nbsp;
		<div class="table"> <?php if($id <> "") { ?>
          <table width="100%" style="border:solid 1px #aaaaaa;">
    <thead>
      <tr>
		 <th class="t_header">No</th>
        <th class="t_header">Name</th>

        <?php
			$name = 'bulan';
			$month = date("n");
			echo monthTH($name, $month); 
?>
      </tr>
     
    </thead>
	<tbody>
	 <tr>
	 <?php
	 if ($users->num_rows() > 0)
	{
	$no = 1;
	  foreach ($users->result() as $row){
	  ?><tr>
        <td><?php echo $row->site_id?></td>
        <td><?php echo $row->site_name?></td>
        <?php $name = 'bulan';
			$month = date("n"); echo dateMonth($name, $month,$no); ?>
		
		<?php
		$no++;
        }
		 }else{
	  echo"<tr><td colspan=14>data kosong</td></tr>";
	  }
		?>
     
      </tr>
	</tbody>
  </table><?php } ?>
     </div>
</div>

<script>
		$(function() {
		<?php
		for($k=1;$k<=$i;$k++){
			echo'$( "#formats'.$k.'" ).buttonset();';
		}
		?>
	});
</script>

