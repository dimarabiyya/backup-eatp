<?php

$visit_id = $this->input->get('visit_id');
$ecrf = $this->db->from('t_trx_ecrf')->where('visit_id', $visit_id)->get()->result();
$approval = $this->db->from('t_trx_ecrf_approval')->where('visit_id', $visit_id)->get()->row();
if (!empty($approval)) {
    $status_api = $approval->status_send_api == 1 ? 'Done' : 'Not Send';
    $text_approval = '<div>
                        <p>
                            Visit ID : ' . $approval->visit_id . '
                        </p>
                        <p>
                            Status : ' . $approval->status_approve . '
                        </p>
                        <p>
                            Date : ' . date('Y-m-d H:i', strtotime($approval->created_at)) . '
                        </p>
                        <p>
                            Remarks : ' . $approval->remarks . '
                        </p>
                        <p>
                            Status Send API : ' . $status_api . '
                        </p>
                    </div>';
    $status_button = '';
} else {
    $text_approval = '<div>
                        <p>
                            Visit ID : ' . $visit_id . '
                        </p>
                        <p>
                            Status : -
                        </p>
                        <p>
                            Date : -
                        </p>
                        <p>
                            Remarks : -
                        </p>
                    </div>';
    $status_button = 'disabled';
}
$table = '<div class="widget">
	                <div class="widget-head">
		                <h4 class="heading">ECRF List</h4>
	                </div>

	                <div class="widget-body">
                    ' . $text_approval . '
                    <div class="form-actions">
                        <button type="submit" ' . $status_button . ' id="submit-approve" class="submit btn btn-icon btn-success glyphicons circle_ok"><i></i>Approve</button>
                        <button type="button" ' . $status_button . ' id="reject-button" class="btn btn-icon btn-primary glyphicons circle_remove"><i></i>Reject</button>
                    </div>
                    <div class="control-group mb-3" id="form-reject">
                         <label class="control-label" for="remarks"><b>Remarks Reject</b></label>
                        <div class="controls">
                            <textarea name="remarks" id="remarks" style="width:90%;height: 50px;"></textarea>
                        </div>
                        <button type="button" id="submit-reject" class="submit btn btn-icon btn-primary glyphicons circle_ok"><i></i>Submit</button>
                        <div class="separator bottom"></div>
                    </div>
                        <table class="table-striped table-bordered" border="1" width="1000" style="font-size:14px">
                            <tr>
                                <th>ECRF Number</th>
                                <th>Tower Index</th>
                                <th>ECRF Type</th>
                                <th>Condition</th>
                                <th>Detail</th>
                                <th width="300">Photo</th>
                            </tr>';
foreach ($ecrf as $value) {
    $photo_result = $this->db->select('t_mtr_checklist.checklist_id, t_mtr_checklist.checklist_name, t_trx_checklist_result_photo.result_photo_id,t_trx_checklist_result_photo.photo_name,t_trx_checklist_result_photo.created_by,t_trx_checklist_result_photo.created_on,t_trx_checklist_result_photo.updated_by,t_trx_checklist_result_photo.updated_on,t_trx_checklist_result_photo.visit_id')->from('t_mtr_checklist')->join('t_trx_checklist_result_photo', 't_mtr_checklist.checklist_id = t_trx_checklist_result_photo.checklist_id AND t_trx_checklist_result_photo.visit_id = ' .  $visit_id, 'left');
    $table_photo = '';
    switch ($value->condition) {
        case 'dismantle':
            if ($value->site_type == 'ne') {
                $data_photo = $photo_result->where_in('t_mtr_checklist.checklist_id', ['488', '497', '500'])->order_by('t_mtr_checklist.checklist_id', 'asc')->get()->result();
            } else {
                $data_photo = $photo_result->where_in('t_mtr_checklist.checklist_id', ['506', '515', '516'])->order_by('t_mtr_checklist.checklist_id', 'asc')->get()->result();
            }
            break;
        case 'add':
            if ($value->site_type == 'ne') {
                $data_photo = $photo_result->where('t_mtr_checklist.checklist_id', '286')->get()->result();
            } else {
                $data_photo = $photo_result->where('t_mtr_checklist.checklist_id', '471')->get()->result();
            }
            break;

        default:
            $data_photo = $photo_result->where_in('t_mtr_checklist.checklist_id', ['488', '506', '497', '515', '500', '516', '286', '471'])->get()->result();
            break;
    }
    foreach ($data_photo as $key => $show) {
        $title = $this->db
            ->select('ck.checklist_id, ck.checklist_name, cgk.checklist_group_id, cgk.group_name, cgk.parent_id, mcgk.group_name AS parent_name, mcgk.parent_id AS master_id')
            ->from('t_mtr_checklist ck')
            ->join('t_mtr_checklist_group AS cgk', 'ck.checklist_group = cgk.checklist_group_id')
            ->join('t_mtr_checklist_group AS mcgk', 'cgk.parent_id = mcgk.checklist_group_id')
            ->where('ck.checklist_id', $show->checklist_id)
            ->get()->row();
        if (empty($approval->status_approve) || $approval->status_approve == 'reject' || $approval->status_send_api == 1) {
            $button_status = 'disabled';
        } else {
            $button_status = '';
        }
        $status_photo = '';
        if (empty($show->photo_name)) {
            $status_photo = '<span class="label label-primary"> No Photo </span>';
        }
        $table_photo .= '<div style="margin-bottom:12px">
                                    <p>
                                    <b>' . $title->parent_name . '</b><br><b>' . $title->group_name . '</b><br>' . $title->checklist_name . '
                                    </p>
                                    <img src="https://privaterepositorynas.alita.id/' . $show->photo_name . '">
                                    ' . $status_photo . '
                                <br>
                                <br>
                                <button type="submit"' . $button_status . ' class="submit btn btn-icon btn-success glyphicons circle_ok" data-photo="' . $value->id . '" onclick="postUnitApiFoto(' . $visit_id . ',' . $value->id . ',' . $show->result_photo_id . ',' . $key . ')"><i></i>Send Image</button>
                                </div>';
    }


    $table .=
        '
                <tr>
			        <td align="center">' . $value->ecrf_number . '</td>
			        <td align="center">' . $value->tower_index . '</td>
                    <td align="center">' . strtoupper($value->ecrf_type) . '</td>
                    <td align="center">' . strtoupper($value->condition) . '</td>
                    <td >
                    Height : ' . $value->height . ' <br>
                    Diameter : ' . $value->diameter . ' <br>
                    Quantity : ' . $value->quantity . ' <br>
                    </td>
                    <td>' . $table_photo . '</td>
		        </tr>
                ';
}
if (empty($approval->status_approve) || $approval->status_approve == 'reject' || $approval->status_send_api == 1) {
    $button_status_bottom = 'disabled';
} else {
    $button_status_bottom = '';
}
$table .= '</table>
            <div class="form-actions">
                <button type="submit"' . $button_status_bottom . ' class="submit btn btn-icon btn-success glyphicons circle_ok" onclick="postApiFoto(' . $visit_id . ')"><i></i>Send <img id="loadbar" src="' . base_url('file/images/loading-snake.GIF') . '" width="16px"> </button>
                <button type="button" class="cancel btn btn-icon btn-close btn-default" onclick="$.fancybox.close()">Cancel</button>
            </div>
        </div>
        ';

echo $table;
?>
<script>
    function reload(visit_id) {
        $.fancybox.close();
        var url = "<?php echo site_url('/visitsummary/get_ECRF_approval') ?>";
        $.fancybox({
            href: url,
            type: 'ajax',
            ajax: {
                data: {
                    visit_id: visit_id
                }
            },
            width: 1000,
            scrolling: true,
            autoSize: true,
            fitToView: true,
            openEffect: 'fade',
            closeEffect: 'fade',
            afterShow: function() {
                $('.fancybox-wrap').draggable({
                    handle: ".widget-head"
                });

            }
        });
    };

    function postApiFoto(visit_id) {
        var post_url = "<?php echo site_url('/schedule/send_ECRF_img?visit_id=') ?>" + visit_id;
        $('#loadbar').show();
        $.ajax({
            type: "GET",
            url: post_url,
            dataType: "json",
            success: function(response) {
                $('#loadbar').hide();
                var resp_length = response.length;
                var is_success = 0;
                $.each(response, function(index, value) {
                    var result = JSON.parse(value.send_document);
                    if (result['status'] == 200) {
                        is_success++
                    }
                    notyfy({
                        layout: 'center',
                        text: '<b style="font-size:16px; line-height:2">Status : ' + result['status'] + '</b><br>' + result['message'],
                        type: 'alert', // alert, success, error, warning, information, confirm
                        dismissQueue: false,
                        modal: true,
                        showEffect: false,
                        hideEffect: false,
                        buttons: [{
                            addClass: 'btn btn-notyfy-ok',
                            text: '<i class="icon-ok"></i> OK',
                            onClick: function(notyfy) {
                                notyfy.close();

                                if (typeof functionAfterOKClicked != 'undefined' && typeof functionAfterOKClicked == 'function') {
                                    functionAfterOKClicked();
                                }
                            }
                        }],
                        events: {
                            shown: function() {
                                document.getElementsByClassName('notyfy_buttons')[0].style.textAlign = 'center';
                                document.getElementsByClassName('btn-notyfy-ok')[0].focus();
                            }
                        }
                    });
                });
                console.log('success=', is_success, 'length=', resp_length);
                if (is_success == resp_length) {
                    $.ajax({
                        type: "GET",
                        url: "<?php echo site_url('/schedule/send_status_api') ?>",
                        data: {
                            visit_id: visit_id
                        },
                        dataType: "JSON",
                        success: function(response) {
                            reload(visit_id);
                        }
                    });
                }
                reload(visit_id);
            }
        });
    };

    function postUnitApiFoto(visit_id, trx_ecrf_id, photo_id, index_photo) {
        var post_urls = "<?php echo site_url('/schedule/send_unit_ECRF_img?visit_id=') ?>" + visit_id + '&trx_ecrf_id=' + trx_ecrf_id + '&photo_id=' + photo_id + '&index_photo=' + index_photo;
        $.get(post_urls, "",
            function(data, textStatus, jqXHR) {
                var obj_msg = data.send_document;
                console.log(obj_msg)
                if (obj_msg == "success") {
                    coolNotification('<ul style="text-align:left">' + obj_msg + '</ul>', 'success');

                } else {
                    coolNotification('<ul style="text-align:left">' + obj_msg + '</ul>', 'error');
                }
            },
            "json"
        );
    };

    $(document).ready(function() {
        $('#form-reject').hide();
        $('#loadbar').hide();
        $('#reject-button').click(function(e) {
            e.preventDefault();
            $('#form-reject').toggle('show');
        });

        $('#submit-reject').click(function(e) {
            e.preventDefault();
            var visit_id = "<?php echo $visit_id; ?>";
            var remarks = $('#remarks').val();
            coolConfirmation('Are you sure want to reject?', function() {
                $.ajax({
                    url: '<?php echo base_url('schedule/ecrf_approval_reject'); ?>',
                    data: {
                        visit_id: visit_id,
                        remarks: remarks
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
                        coolNotification('' + data.message + '', 'success');
                        reload(visit_id);
                    },
                    error: function(xhr, status, message) {
                        coolNotification('An error occured, please contact the administrator.', 'error');
                        reload(visit_id);
                    }
                });
            });
        });
        $('#submit-approve').click(function(e) {
            e.preventDefault();
            var visit_id = "<?php echo $visit_id; ?>";
            coolConfirmation('Are you sure want to Approve?', function() {
                $.ajax({
                    url: '<?php echo base_url('schedule/ecrf_approval_approve'); ?>',
                    data: {
                        visit_id: visit_id,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
                        coolNotification('' + data.message + '', 'success');
                        reload(visit_id);
                    },
                    error: function(xhr, status, message) {
                        coolNotification('An error occured, please contact the administrator.', 'error');
                        reload(visit_id);
                    }
                });
            });
        });
    });
</script>