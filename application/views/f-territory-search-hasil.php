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

.kosong{
        background-color: white;
}

.kuning{
        background-color: yellow;
}

.pagination { 
        text-align: right;
        padding: 20px 0 5px 0;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 10px;
}

.pagination a {
        margin: 0 5px 0 0;
        padding: 3px 6px;
}

.pagination a.number {
        border: 1px solid #ddd;
}

.pagination a.current {
        background-color: red;
        border-color: #459300 !important;
        color: #fff !important;
}

.pagination a.current:hover {
        text-decoration: underline;
}
</style>
<table width=100% style="border:solid 1px #aaaaaa;" cellpadding==0 cellspacing==0>
	<thead>
			<tr height="30px">
					<th width=30 class="t_header">No</th>
					<th class="t_header">Name</th>
					<th width=210 class="t_header">Maintenance By</th>
					<th width=210 class="t_header">Parent</th>
					<th width=160 class="t_header">Proses</th>
			</tr>
	</thead>
	<tbody>
		<?php
		#load data berdasarkan filter yang diisi
		$i=1;
		foreach($getData->result() as $r){
			echo'<tr height="30px">
				<td class="t_isi">'.$i.'</td>
				<td class="t_isi">'.$r->territory_name.'</td>
				<td class="t_isi">'.$this->mglobal->showdata('user_name','t_mtr_user',array('user_id'=>$r->user_id),'dblokal').'</td>
				<td class="t_isi">'.$this->mglobal->showdata('territory_name','t_mtr_territory',array('territory_id'=>$r->parent_id),'dblokal').'</td>
				<td class="t_isi" align=center><a href="'.base_url().'index.php/territory/tree/add/'.$r->territory_id.'" target="_blank">Add</a> | <a href="'.base_url().'index.php/territory/tree/update/'.$r->territory_id.'" target="_blank">Update</a> | Delete</td>
			</tr>';
			$i++;
		}
		?>
	</tbody>
</table>