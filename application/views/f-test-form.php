<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	<div class="buttons pull-right">
		<a href="" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add page</a>
	</div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i> Manage products</h4>
		</div>
		<div class="widget-body">
			<div class="form-inline separator bottom small">
				Total products: 26
				<span class="pull-right">
					<label class="strong">Sort by:</label>
					<select class="selectpicker" data-style="btn-default btn-small">
						<option>Option</option>
						<option>Option</option>
						<option>Option</option>
					</select>
				</span>
			</div>
			
			<div class="filter-bar">
				<form>
					<div>
						<label>From:</label>
						<div class="input-append">
							<input type="text" name="from" id="dateRangeFrom" class="input-mini" value="08/05/13" style="width: 53px;" />
							<span class="add-on glyphicons calendar"><i></i></span>
						</div>
					</div>
					<div>
						<label>To:</label>
						<div class="input-append">
							<input type="text" name="to" id="dateRangeTo" class="input-mini" value="08/18/13" style="width: 53px;" />
							<span class="add-on glyphicons calendar"><i></i></span>
						</div>
					</div>
					<div>
						<label>Min:</label>
						<div class="input-append">
							<input type="text" name="from" class="input-mini" style="width: 30px;" value="100" />
							<span class="add-on glyphicons euro"><i></i></span>
						</div>
					</div>
					<div>
						<label>Max:</label>
						<div class="input-append">
							<input type="text" name="from" class="input-mini" style="width: 30px;" value="500" />
							<span class="add-on glyphicons euro"><i></i></span>
						</div>
					</div>
					<div>
						<label>Select:</label>
						<select name="from" style="width: 80px;">
							<option>Some option</option>
							<option>Other option</option>
							<option>Some other option</option>
						</select>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
			
			<table class="table table-bordered table-condensed table-striped table-primary table-vertical-center checkboxs js-table-sortable">
				<thead>
					<tr>
						<th style="width: 1%;" class="uniformjs"><input type="checkbox" /></th>
						<th style="width: 1%;">No.</th>
						<th>Title</th>
						<th style="width: 1%;" class="col_drag">Drag</th>
						<th class="center">Preview</th>
						<th class="center">Stock</th>
						<th class="center">Price</th>
						<th class="center" style="width: 60px;">Actions</th>
					</tr>
				</thead>
				<tbody>
                                    <?php
                                    for($i=1;$i<10;$i++){ ?>
				    <tr class="selectable">
						<td class="center uniformjs"><input type="checkbox" /></td>
						<td><?php echo $i?></td>
						<td><b>Lorem Dolor Ipsum</b></td>
						<td class="col_drag"><span class="glyphicons btn-action single move" style="margin-right: 0;"><i></i></span></td>
						<td class="center" id="col_photo"><span class="glyphicons btn-action single picture" style="margin-right: 0;"><i></i></span>2 photos</td>
						<td class="center form-inline small">
							<input type="text" style="width: 30px;" value="1" />
						</td>
						<td class="center">&euro;30</td>
						<td class="center">
							<a href="product_edit.html?lang=en" class="btn-action glyphicons pencil btn-success"><i></i></a>
							<a href="#" class="btn-action glyphicons remove_2 btn-danger"><i></i></a>
						</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
			</table>
			<div class="separator top form-inline small">
				<div class="pull-left checkboxs_actions hide">
					<label class="strong">With selected:</label>
					<select class="selectpicker dropup" data-style="btn-default btn-small">
						<option>Action</option>
						<option>Action</option>
						<option>Action</option>
					</select>
				</div>
				<div class="pagination pagination-small pull-right" style="margin: 0;">
					<ul>
						<li class="disabled"><a href="#">&laquo;</a></li>
						<li class="active"><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">&raquo;</a></li>
					</ul>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
</div>		