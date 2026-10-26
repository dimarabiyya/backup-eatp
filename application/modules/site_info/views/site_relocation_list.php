<?php

/*
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

<div class="innerLR">
	
	<div class="widget">

		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons list"><i></i> Site Relocation Data</h4>
			<div class="btn-heading-group">
                            <?php //if ($this->mmenu->cekPermission($menu, 'menu_add')) { ?>
                                    <a href="<?php echo base_url('/site_info/site_relocation/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add New</a>
                                    <?php //} ?>
                            <a id="btn-export" href="<?php echo site_url('/export/site_info/site_relocation') ?>" class="btn btn-warning btn-mini btn-heading btn-icon glyphicons inbox_out pull-right"><i></i> Export</a>
                        </div>
		</div>
		
		<div class="widget-body">
                    <div id="tb-sites" style="padding: 10px 10px 0px 10px; height:auto">
                        <div class="input-append ">
                          <?php echo form_input('query', '', 'id="query" class="input-medium search-query" placeholder="Search.."') ?> 
                          <button class="btn" >Search</button>
                        </div>
                    </div>
                    <table id="grid-sites-reloc" class="jgrid"></table>
		</div>
	</div>
	
</div>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<style>
.datagrid-header-rownumber { width:50px; }
.datagrid-cell-rownumber { width:50px; }
select { width:auto; }
</style>

<script>
$(document).ready(function (){
        $('#grid-sites-reloc').datagrid({
            url         : '<?php echo base_url('site_info/site_relocation/get_data');?>',
            method      : 'get',
            pageSize    : 15,
            pageList    : [10, 15, 20, 30, 100, 200, 500],
            striped     : true,
            pagination  : true,
            singleSelect: true,
            fitColumns  : true,
            rownumbers  : true,
            height      : 'auto',
            toolbar     : '#tb-sites',
            columns     : [[
                {field:'vendor_id',title:'Vendor ID',width:100},
                {field:'relocation_type',title:'Relocation Type',width:100},
                {field:'dismantle_start_date',title:'Start Date',width:75},
                {field:'dismantle_end_date',title:'Finish Date',width:75},
                {field:'site_destination',title:'Site Destination',width:100},
                {field:'cause',title:'Cause',width:150},
                {field:'doc_name',title:'Doc.',width:50, align:'center', formatter: function (value, row, index){
                    if (value!=null) {
                        var doc = '<a download href="<?php echo base_url('/file/upload/site_relocation');?>/' + value + '" " title="Download Document" class="btn-action glyphicons download btn-success"><i></i></a>';
                        return doc;
                    } else {
                        return ' - ';
                    } 
                    
                }},
                {field:'remark',title:'Remark',width:150},
                {field:'action',title:'Action',width:75, align:'center', formatter: function (value, row, index){
                    var edit   = '<a href="<?php echo base_url('/site_info/site_relocation/edit') ?>/' + row.site_relocation_id + '" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>';
                    var del = '<a href="<?php echo base_url('/site_info/site_relocation/delete') ?>/' + row.site_relocation_id + '" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>';
                        return edit + ' ' + del;
                }}
            ]]
        });
});
</script>