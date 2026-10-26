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
					<th width=160 class="t_header">Username</th>
					<th width=190 class="t_header">Email</th>
					<th width=160 class="t_header">Mobile Phone</th>
					<th width=120 class="t_header">Proses</th>
			</tr>
	</thead>
	<tbody>
		<?php
		#load data berdasarkan filter yang diisi
		$i=1;
		foreach($getData as $r){
			echo'<tr height="30px">
				<td class="t_isi">'.$i.'</td>
				<td class="t_isi">'.$r->user_name.'</td>
				<td class="t_isi">'.$r->user_id.'</td>
				<td class="t_isi">'.$r->email.'</td>
				<td class="t_isi">'.$r->mobile_number.'</td>
				<td class="t_isi" align=center>';
				
				// if($r->created_by <> 'ois'){
					echo'<a href="'.base_url().'index.php/user_management/index/update/'.$r->id_encoded.'" target=_blank>Update</a> |'; //edited: alfin.akhret@gmail.com
				// }
					echo'<span id="'.$r->user_id.'" class="d_keluarkan" style="font-color:blue;cursor:pointer;">Keluarkan</span>
				</td>
			</tr>';
				
			$i++;
		}
		?>
	</tbody>
</table>