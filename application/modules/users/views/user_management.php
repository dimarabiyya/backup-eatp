
<div class="left-sidebar">
<div class="row-fluid">
              <div class="span12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                      <a id="dynamicTable"><?php echo lang('text_heading_title_user_management') ?></a>
                    </div>
                    <span class="tools">
                      <a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a>
                    </span>
                  </div>
                  <div class="widget-body">
                    <div id="dt_example" class="example_alt_pagination">
                      <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">

                        <thead>
                          <tr>
                            <th style="width:20%">
                              Name
                            </th>
                            <th style="width:20%">
                              NIK (Username)
                            </th>
                            <th style="width:20%">
                            Email
                            </th>
                            <th style="width:10%" class="hidden-phone">
                              Mobile Phone
                            </th>
                            <th style="width:16%" class="hidden-phone">
                              Group
                            </th>
                            <th style="width:6%" class="hidden-phone">
                              Status
                            </th>
                            <th style="width:16%" class="hidden-phone">
                              Action
                            </th>
                          </tr>
                        </thead>
                        <tbody>
						<?php foreach ($users as $user) {
		$link = '&nbsp;';
		if($user['created_by'] <> 'ois') {
			$link="<a href='". base_url('users/edit_user/'. get_instance()->core->encode($user['user_id'])) ."'>Edit</a>";
		}
	?>
                          <tr class="gradeX ">
                            <td>
                              <?php echo $user['user_name'] ?>
                            </td>
                            <td>
                             <?php echo $user['nik'] ?>
                            </td>
                            <td>
                            <?php echo $user['email'] ?>
                            </td>
                            <td class="hidden-phone">
                              <?php echo $user['mobile_number'] ?>
                            </td>
                            <td class="hidden-phone">
                              <?php echo $user['user_group_name'] ?>
                            </td>
							<td class="hidden-phone">
                              <?php echo $user['istatus'] ?>
                            </td>
                            <td class="hidden-phone">
							<div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-mini dropdown-toggle">
                                Action 
                                <span class="caret">
                                </span>
                              </button>
                              <ul class="dropdown-menu">
                                <li>                                 
                                    <?php echo $link ?>
                                </li>
                                <li>
                                  <a href="#">
                                    Delete
                                  </a>
                                </li>
                              </ul>
                            </div>
                             
                            </td>
                          </tr>
                          <?php } ?>
                          
                        </tbody>
                      </table>
                      <div class="clearfix">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
</div>
