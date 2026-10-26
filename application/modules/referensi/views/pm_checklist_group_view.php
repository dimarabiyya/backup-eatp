<link href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css" type="text/css" rel="stylesheet"></link>
<link href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css" type="text/css" rel="stylesheet"></link>
<script src="<?php echo base_url();?>assets/scripts/jeasyui/jquery.easyui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js" type="text/javascript"></script>

<style>
.tree-file2 {
/*  background: url('<?php echo base_url();?>assets/scripts/jeasyui/images/tree_icons.png') no-repeat -240px 0;*/
    background: url('<?php echo base_url();?>assets/images/interface-android.png') no-repeat;

}
.tree-file-android {
  background: url('<?php echo base_url();?>assets/images/interface-android.png') no-repeat -4px 0 0 4px;
}
.tree-file3 {
  background: url('<?php echo base_url();?>assets/images/interface-desktop.png') no-repeat;
}
</style>
<script>
var Document = {
	        param: {
                id: 0,
                tree_type: '',
                tree_name:''
        },
        deleteData: function(id,tree_type,tree_name) {
                var checkstr =  confirm('Are you sure want to delete (' + tree_name + ') ?');
                if(checkstr == true){
                        this.param.id = id; 
                        this.param.tree_type = tree_type; 
                        $.ajax({
                                url: '<?php echo base_url()?>referensi/pm_checklist_group/deleteData',
                                type: 'POST',
                                dataType: 'json',
                                data: jQuery.param(Document.param),
                                success: function() {
                                        window.location.href = "<?php echo base_url();?>referensi/pm_checklist_group/index";
                                }
                        });
                                
                        //console.log(Document.param);		
                }
                return false;
        }
        
}


function closeFancybox(){
$.fancybox.close();	
}

var addChecklistGroup = function(elem1,elem2) { // elem1: shown element, elem2: hidden element

	var url = '<?php echo site_url('referensi/pm_checklist_group/addeditdata/') ?>';

	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { id: elem1, tree_type: elem2 } }, // e: it used for selected element
		width: 680,
		height: 495,
		scrolling: false,
		autoSize: false,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade'
	});
};

var addChecklistGroup2 = function(elem1) {
	var url = '<?php echo site_url('referensi/pm_checklist_group/addData/') ?>';
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { id: elem1} }, // e: it used for selected element
		width: 680,
		height: 495,
		scrolling: false,
		autoSize: false,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade'
	});
};
</script>

<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">

		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i>PM Checklist Group Definitions</h4>
            <div class="buttons pull-right">
               <button type="button" class="btn btn-primary btn-heading btn-icon glyphicons circle_plus"  onclick="addChecklistGroup2()"><i></i>Add Checklist Group</button>
            </div>
		</div>

		<div class="widget-body">
						
						<table id="dg" class="easyui-treegrid"
						data-options="
							iconCls: 'icon-save',
							rownumbers: false,
							pagination: true,
							fitColumns: true,
							url: '<?php echo base_url().'referensi/pm_checklist_group/treeGrid';?>',
							animate: true,
							toolbar:'#tb',
							pageSize: 10,
							height:'auto',
							idField: 'tree_id',
							treeField: 'tree_name',
							onBeforeLoad: function(row,param){
								if (!row) {    // load top level rows
									param.id = 0;    // set id=0, indicate to load new page rows
		
								}
							}
						">



           <thead>
                                      <tr>
                                          <?php
                                              $arr=array('tree_name'=>'Checklist Name','tree_type'=>'Type','action'=>'Action');
                                              $width=array('800','100','100');
                                              $i=0;
                                              foreach ($arr as $key=>$value) {                                
                                                      echo '<th field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
                                                  $i++;
                                              }
                                          ?>
                                      </tr>
            </thead>
                        </table>
                        
						<hr>
                       
			
		</div>
	</div>
</div>

 