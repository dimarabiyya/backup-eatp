<?php

class Mgis extends CI_Model {

    function Mgis() {
        parent::__construct();
        $this->idnya = "";
        $this->result = "";
        $this->result_array = array();
        $this->user_group_arr = array();
        $this->pathTer = "";
    }

    public function getTerritory() {
        $this->db->distinct();
        $this->db->select('territory_id');
        $this->db->order_by('territory_id', 'asc');
        $query = $this->db->get('t_mtr_territory_border');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    public function getBoundaryTerritory($ter_id, $tipe = 1) {
        $this->db->where(array('territory_id' => $ter_id,
            'territory_type' => $tipe,
            'active' => 1));
        $this->db->order_by('territory_border_id', 'asc');
        $query = $this->db->get('t_mtr_territory_border');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }
    
    public function getRegionBoundary($ter_id, $type) { // {^_^) andedi 20141217
        if ($ter_id!=0 && $type!=0) $where = " AND tb.territory_id={$ter_id} ";
        
        $sql    = "SELECT tb.territory_id, tb.lat, tb.lon, rg.region_name, cl.cluster_name 
                FROM t_mtr_territory_border tb
                LEFT JOIN t_mtr_region rg ON rg.region_id = tb.territory_id
                LEFT JOIN t_mtr_cluster_ops cl ON cl.cluster_ops_id = tb.territory_id
                WHERE tb.territory_type=1 AND tb.active=1 {$where}
                ORDER BY tb.territory_border_id";
        $query  = $this->db->query($sql);
        return $query->result();
    }
    
    public function getClusterBoundary($ter_id, $type) { // {^_^) andedi 20141217
        $arr_cluster = '';
        $where = '';
        if ($type!=0) {
            if ($type==1) {
                $arr_cluster = "SELECT cluster_ops_id FROM t_mtr_cluster_ops WHERE region_id={$ter_id} AND status=1";
                $type = 2;
            } else {
                $arr_cluster = $ter_id;
            }
            $where = " AND tb.territory_id IN ({$arr_cluster}) ";
        }
            
        $sql    = "SELECT tb.territory_id, tb.lat, tb.lon, rg.region_name, cl.cluster_name 
                FROM t_mtr_territory_border tb
                LEFT JOIN t_mtr_cluster_ops cl ON cl.cluster_ops_id = tb.territory_id
                INNER JOIN t_mtr_region rg ON rg.region_id = cl.region_id
                WHERE tb.territory_type=2 AND tb.active=1 {$where}
                ORDER BY tb.territory_border_id";
        $query  = $this->db->query($sql);
        return $query->result();
    }

    function getParentIdByUser() {
        $user = $this->session->userdata('user_id');
        $user_grup_id = $this->session->userdata('user_group_id');

        if ($user_grup_id = 10) {
            return 0;
        } else {
            $sql = "select parent_id from t_mtr_territory where user_id = '$user' order by parent_id limit 1";
            $query = $this->db->query($sql);
            $row = $query->row();
            return $row->parent_id;
        }
    }

    function getTerritoryTree($id) {
        $query = $this->getTerritoryDody($id);
        $k = 0;
        $jum = $query->num_rows();
        if ($query->num_rows() > 0) {
            $this->result .='[';
            foreach ($query->result() as $r) {
                $this->result .='{"id":' . $r->territory_id . ',
								"text":"' . $r->territory_name . '",
								"state":"open"';
                #cek punya children gak ?
                $cek = $this->getTerritoryDody($r->territory_id);
                $k++;
                if ($cek->num_rows() > 0) {
                    $this->result .=',"children":';
                    $this->getTerritoryTree($r->territory_id);
                    $this->result .='}';
                    if ($k != $jum)
                        $this->result .=',';
                }else {
                    $this->result .='}';
                    if ($k != $jum)
                        $this->result .=',';
                    $this->getTerritoryTree($r->territory_id);
                }
            }
            $this->result .="]";
        }
        return $this->result;
    }

    public function getTerritoryMember($id) {
        $this->result_array[] = $id;
        $query = $this->getTerritoryDody($id);
        $k = 0;
        $jum = $query->num_rows();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $r) {
                $this->result_array[] = $r->territory_id;
            }
        }
        return array_unique($this->result_array);
    }

    public function getTerritoryMemberNew($id = "") {
        //get region/cluster di bawahnya

        $sql = "";
        if ($id == "") {
            $sql = "SELECT region_id as terr_id, '1' as tipe, 'd01414' as fill_color FROM t_mtr_region WHERE status = 1
				union 
				select cluster_ops_id as terr_id, '2' as tipe, '0a33cb' as fill_color from t_mtr_cluster_ops ";
        } else {
            //tipe region/cluster
            $tr = explode('-', $id);
            if ($tr[1] == "reg") {
                $sql = "SELECT region_id as terr_id, '1' as tipe, 'd01414' as fill_color FROM t_mtr_region WHERE status = 1 and region_id='$tr[0]'
					union 
					select cluster_ops_id as terr_id, '2' as tipe, '0a33cb' as fill_color from t_mtr_cluster_ops where region_id='$tr[0]'";
            } else {
                $sql = "select cluster_ops_id as terr_id, '2' as tipe, '0a33cb' as fill_color from t_mtr_cluster_ops where cluster_ops_id='$tr[0]'";
            }
        }

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    function getTerritoryNameNew($terr_id, $tipe = 1) {

        $hasil = "";
        if ($tipe == 1) {
            $hasil = $this->mglobal->showdata("region_name", "t_mtr_region", array('region_id' => $terr_id), "dblokal");
        } else {
            $idreg = $this->mglobal->showdata("region_id", "t_mtr_cluster_ops", array('cluster_ops_id' => $terr_id), "dblokal");
            $hasil = $this->mglobal->showdata("region_name", "t_mtr_region", array('region_id' => $idreg), "dblokal");
            $hasil .= " > " . $this->mglobal->showdata("cluster_name", "t_mtr_cluster_ops", array('cluster_ops_id' => $terr_id), "dblokal");
        }
        return $hasil;
    }

    function getTerritoryByUserLogin() {
        $user = $this->session->userdata('user_id');
        $sql = "select * t_mtr_user_territory where user_id = '$user'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
            //return $query->result_array();  
        } else {
            return array();
        }
    }

    function getTerritoryDody($pid) {
        $cluster_id = $this->session->userdata('cluster_id');

        $dblokal = $this->load->database("default", TRUE);
        $dblokal->order_by("ordering", "ASC");
        $where = "parent_id = '$pid' and (territory_type_id=1 or territory_type_id=2 or territory_type_id=3) ";

        $dblokal->where($where);
        $query = $dblokal->get("t_mtr_territory");
        return $query;
    }

    function getTerritoryDonny($pid) {
        $dblokal = $this->load->database("default", TRUE);
        $dblokal->order_by("ordering", "ASC");
        $where = "parent_id = '$pid' and territory_type_id in (1,2,3,4,5) ";

        $dblokal->where($where);
        $query = $dblokal->get("t_mtr_territory");
        return $query;
    }

    public function getParentTerritory($id) {

        $sql = "select * from t_mtr_territory where territory_id = '$id'";
        $query = $this->db->query($sql);
        $i = 0;

        if ($query->num_rows() > 0) {
            $this->pathTer .= "";
            $row = $query->row();

            $this->pathTer .= $row->territory_name . " > ";

            if ($id <> $row->parent_id) {
                $this->mgis->getParentTerritory($row->parent_id);
            }
        } else {
            $this->pathTer .= "";
        }

        return $this->pathTer;
    }

    // public function getTerritory() {
    // $query = $this->db->query("select a.* from t_mtr_territory a order by territory_id,parent_id asc");
    // //$query = $this->db->query("");
    // if( $query->num_rows() > 0 ) {
    // return $query->result();  
    // //return $query->result_array();  
    // } else {
    // return array();
    // }
    // }

    public function getTerritoryById($territory_id) {


        $query = $this->db->query("select * from t_mtr_territory where territory_id = '$territory_id'");
        //$query = $this->db->query("");

        if ($query->num_rows() > 0) {
            //return $query->result();  
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getMapTrackingIwa($user_id, $date) {
        $query = $this->db->query("select id, jc_id,type_id,waktu,latitude, longitude, created_by, date(created_on) as tracking_date from t_trx_pm_tracking
									WHERE created_by='" . $user_id . "' and date(created_on) = '" . $date . "' 
									order by id asc");
        if ($query->num_rows() > 0) {
            return $query->result_array();
            // return $query->row_array();  
        } else {
            return array();
        }
    }

    public function getMapTrackingIwaSI($user_id, $date) {
        $query = $this->db->query("select id, jc_id,type_id,waktu,latitude, longitude, created_by, date(created_on) as tracking_date from t_trx_site_inspection_tracking
									WHERE created_by='" . $user_id . "' and date(created_on) = '" . $date . "' 
									order by id asc");
        if ($query->num_rows() > 0) {
            return $query->result_array();
            // return $query->row_array();  
        } else {
            return array();
        }
    }

    public function getPic($ugroup = 11) {
        $query = $this->db->query("select muser.user_id, muser.user_name
									from t_mtr_user_multi_role urole
									left join t_mtr_user muser on muser.user_id=urole.user_id 
									where urole.user_group_id=$ugroup OR urole.user_group_id=55
									order by muser.user_name asc");
        if ($query->num_rows() > 0) {
            return $query->result_array();
            // return $query->row_array();  
        } else {
            return array();
        }
    }

    // dody@6dec
    public function getSiteAll() {
        $this->db->select('*');
        $query = $this->db->get('t_mtr_site');
        return $query->result();
    }

    public function updateSiteRegion($site_id, $terr_id, $tipe) {
        //cek teritory yg dipilih region / cluster
        //jika cluster, update region & cluster
        //ter type id =3 => cluster , 2 region
        //parent id = 1=>region, else cluster
        $parent_id = $this->mglobal->showdata("region_id", "t_mtr_cluster_ops", array('cluster_ops_id' => $terr_id), "dblokal");
//        die($tipe.'sk');
        $data = array();
        if ($tipe == 1) {
            $data['region_id'] = $terr_id;
        } else {
            $data['region_id'] = $parent_id;
            $data['cluster_ops_id'] = $terr_id;
        }

        $this->db->where('site_id', $site_id);
        $this->db->update('t_mtr_site', $data);
    }

    function getTerritoryTreeNew() {
        $query = $this->db->query("SELECT * FROM t_mtr_region WHERE status = 1 ORDER BY region_id");
        $k = 0;
        $jum = $query->num_rows();
        if ($query->num_rows() > 0) {
            $this->result .='[';
            foreach ($query->result() as $r) {
                $this->result .='{"id":"' . $r->region_id . '-reg",
								"text":"' . $r->region_name . '",
								"state":"open"';
                #cek punya children gak ?
                $cek = $this->db->query("SELECT * FROM t_mtr_cluster_ops WHERE status = 1 AND region_id='$r->region_id' ORDER BY cluster_name");
                $k++;
                if ($cek->num_rows() > 0) {
                    $l = 0;
                    $this->result .=',"children":[';
                    foreach ($cek->result() as $s) {
                        $l++;
                        $this->result .='{"id":"' . $s->cluster_ops_id . '-cls",
							"text":"' . $s->cluster_name . '",
							"state":"open"}';
                        if ($l != $cek->num_rows())
                            $this->result .=',';
                    }
                    $this->result .=']}';


                    if ($k != $jum)
                        $this->result .=',';
                }else {
                    $this->result .='}';
                    if ($k != $jum)
                        $this->result .=',';
                }
            }
            $this->result .="]";
        }
        return $this->result;
    }

    /*
      public function putBoundary() {
      $data = array();

      if($this->input->post("border") <> "") {

      $this->db->delete( 't_mtr_territory_border', array( 'territory_id' => $this->input->post("territory") ) );

      $geo = explode(";",$this->input->post("border"));
      $sum_array = count($geo);
      $x = 0;
      if($sum_array > 0) {
      foreach($geo as $v => $x) {
      if($x <> "") {
      $latlng = explode(",",$x);

      $data[$x] = array(
      "territory_id" => $this->input->post("territory"),
      "lat" => $latlng[0],
      "lon" => $latlng[1]
      );
      $x++;
      }
      }
      }
      }

      $this->db->insert_batch( 't_mtr_territory_border', $data );
      return $this->db->insert_id();
      }

      public function updateColor() {
      $color_array = array("000000","ff1800","001eff","fff000","06ff00","ff00fc","ff6c00","00fcff","004e72","ab2ff2","aeff00","8f347b","bcbcbc");
      $fill_color = array_rand($color_array,1);
      $border_color = array_rand($color_array,1);
      $data = array(
      'fill_color' => $fill_color,
      'border_color' => $border_color
      );

      $this->db->update( 't_mtr_territory', $data, array( 'territory_id' => $this->input->post( 'territory', true ) ) );
      }

      public function updateColor2() {
      $user = $this->session->userdata('user_id');
      $sql = "SELECT f_get_region_color(f_get_region('$user')) as fill_color";

      $query = $this->db->query($sql);
      //$query = $this->db->query("");

      if( $query->num_rows() > 0 ) {

      $row = $query->row();
      $fill_color = $row->fill_color;

      //return $query->result_array();
      } else {
      $fill_color =  "000000";
      }
      $border_color = "000000";

      $data = array(
      'fill_color' => $fill_color,
      'border_color' => $border_color
      );

      $this->db->update( 't_mtr_territory', $data, array( 'territory_id' => $this->input->post( 'territory', true ) ) );
      }


      function updateoutletlonlat($channel,$lon,$lat){
      $user = $this->session->userdata('user_id');
      $sql = "select f_set_partner_longlat('$channel','$lon','$lat','$user') ";
      $query = $this->db->query($sql);
      if($query->num_rows()>0){
      return $query->row()->f_set_partner_longlat;
      }else{
      return 0;
      }


      return  $this->db->update( 't_mtr_outlet', array("lon"=>$lon,"lat"=>$lat), array( 'outlet_id' => $channel ) );
      }
     */
    /* 	
      public function getMapTracking($user_id, $tanggal ) {


      //SQL Patched By RAA
      $sql="select y.track_id, y.lon, y.lat, y.created_on, y.journey_id,y.accuracy,j.channel_id,coalesce(coalesce(o.outlet_name,ag.agent_name),NULL) as partner_name, j.type, concat(coalesce(o.address,'null'),',',coalesce(o.city,'null'),'<br>Outlet LonLat:',coalesce(o.lon,'null'),',',coalesce(o.lat,'null')) as address from (select DISTINCT ON (lon,lat) lon, lat, track_id, created_on, journey_id,accuracy
      from t_trx_track where user_id = '$user_id' and track_date = '$tanggal' and lon <> '0.0' and lat <> '0.0'
      order by lon,lat,journey_id asc) as y
      left join t_trx_journey j on j.journey_id=y.journey_id
      left join t_mtr_outlet o on o.outlet_id =j.channel_id
      left join t_mtr_agent ag on ag.agent_id=j.channel_id
      order by track_id";
      $query = $this->db->query($sql);

      if( $query->num_rows() > 0 ) {
      return $query->result();
      //return $query->result_array();
      } else {
      return array();
      }
      }

      public function getMapOutlet($outlet_id){
      $sql = "select outlet_id, outlet_name, concat_ws(', ',address, city), lon, lat
      from t_mtr_outlet ott
      join t_mtr_territory tt
      on ott.territory_id = tt.territory_id
      and tt.parent_id = 58
      and ott.outlet_id = '$outlet_id'
      and ott.lon is not NULL
      and ott.lon != '0'
      and ott.lon != '0.0'
      and ott.lon != ''
      and ott.lat is not null
      and ott.lat != '0'
      and ott.lat != '0.0'
      and ott.lat != '';";
      $query = $this->db->query($sql);
      if ($query->num_row() > 0){
      return $query->result();
      } else {
      return array();
      }

      }

      public function getFirstTrack($lon,$lat,$user_id,$tanggal) {
      $sql = "select created_on as mydate from t_trx_track where lon = '$lon' and lat = '$lat' and user_id = '$user_id' and track_date = '$tanggal' order by mydate asc limit 1";
      $query = $this->db->query($sql);
      $row = $query->row();

      return $row->mydate;

      }
      public function getLastTrack($lon,$lat,$user_id,$tanggal) {
      $sql = "select created_on as mydate from t_trx_track where lon = '$lon' and lat = '$lat' and user_id = '$user_id' and track_date = '$tanggal' order by mydate desc limit 1";
      $query = $this->db->query($sql);
      $row = $query->row();

      return $row->mydate;

      }

      public function getMinute($his) {
      $str = explode(':',$his);
      $min = (int)$str[0] * 60;
      return $min + $str[1];

      }
      public function getDurationTracking($lon,$lat,$user_id,$tanggal) {
      $firstTrack = $this->getFirstTrack($lon,$lat,$user_id,$tanggal);
      $lastTrack = $this->getLastTrack($lon,$lat,$user_id,$tanggal);

      $datetime1 = new DateTime($firstTrack);
      $datetime2 = new DateTime($lastTrack);
      $interval = $datetime1->diff($datetime2);
      //return $interval->format('%H:%i:%s');
      $int = $interval->format('%H:%i:%s');
      return $this->getMinute($int);

      //return $firstTrack ." ".$lastTrack;

      }

      public function getUserName($user) {
      $sql = "select user_name from t_mtr_user where user_id = '$user'";
      $query = $this->db->query($sql);
      $row = $query->row();
      return $row->user_name;
      }

      public function getgroupname($user) {
      $sql = "select t_mtr_user_group.user_group_name from t_mtr_user join t_mtr_user_group on t_mtr_user.user_group_id = t_mtr_user_group.user_group_id where user_id = '$user'";
      $query = $this->db->query($sql);
      $row = $query->row();
      return $row->user_group_name;
      }

      public function getUser89() {
      $user = $this->session->userdata('user_id');
      //edited Alfin Akhret <alfin.akhret@gmail.com>
      // optimasi query
      //
      $cluster = $this->db->query("select * from f_get_cluster('".$user."')");
      $cluster = $cluster->result();
      $cluster = $cluster[0]->f_get_cluster;
      // echo $cluster;die();

      $sql = "select t_mtr_user.user_id, initcap(t_mtr_user.user_name) as user_name, t_mtr_user_group.user_group_name from t_mtr_user join t_mtr_user_group on t_mtr_user.user_group_id = t_mtr_user_group.user_group_id where f_get_cluster(user_id) = '$cluster' ".
      "and t_mtr_user.user_group_id in (4,6,8,9,14,34,36,37) and t_mtr_user.istatus = 1 ORDER BY user_name ASC";
      $query = $this->db->query($sql);

      if( $query->num_rows() > 0 ) {
      return $query;
      //return $query->result_array();
      } else {
      return "";
      }
      }
      function getUser_json()
      {
      $k=0;
      $this->idnya ++;
      $getUser = $this->getUser89();
      $this->result .='[';
      foreach($getUser->result() as $r){
      $k++;
      $this->result .='{"tid":"'. $r->user_id .'",
      "id":"'. $r->user_id .'",
      "group_name":"'. $r->user_group_name .'",
      "name":"'. $r->user_name .'"}';
      if($k!=$getUser->num_rows) $this->result .=',';
      }
      $this->result .="]";
      return $this->result;
      }


      public function getOutlet() {


      $query = $this->db->query("select * from t_mtr_outlet");
      //$query = $this->db->query("");

      if( $query->num_rows() > 0 ) {
      return $query->result();
      //return $query->result_array();
      } else {
      return array();
      }
      }

      public function getOutletByTerritory($territory_id, $outlet_id, $outlet_name) {
      $cluster_id = $this->session->userdata('cluster_id');

      $sql = "select *
      from t_mtr_outlet as tot
      join t_mtr_territory as tt on tot.territory_id = tt.territory_id
      where tt.parent_id = $cluster_id
      and tot.outlet_id like '%$outlet_id%'
      and tot.outlet_name like '%$outlet_name%'
      and tt.territory_type_id=5
      and tot.lon is not NULL
      and tot.lon != '0'
      and tot.lon != '0.0'
      and tot.lon != ''
      and tot.lat is not null
      and tot.lat != '0'
      and tot.lat != '0.0'
      and tot.lat != '';";

      //return $q;
      $query = $this->db->query($sql);
      //$query = $this->db->query("");

      if( $query->num_rows() > 0 ) {
      return $query->result();
      //return $query->result_array();
      } else {
      return array();
      }
      }

      public function getAgentByTerritory($territory_id, $agent_id, $agent_name) {

      $sql = "select * from t_mtr_agent as tag join t_mtr_territory as tt on tag.territory_id = tt.territory_id where tt.parent_id = '$territory_id'  and tag.agent_id like '%$agent_id%' and tag.agent_name like '%$agent_name%' and tt.territory_type_id=6 ";

      $query = $this->db->query($sql);
      //$query = $this->db->query("");

      if( $query->num_rows() > 0 ) {
      return $query->result();
      //return $query->result_array();
      } else {
      return array();
      }
      }

      public function getTerritoryUserLogin() {
      $user = $this->session->userdata('user_id');
      $sql = "SELECT territory_id as mvalue, territory_name as mdisplay FROM t_mtr_territory ".
      "WHERE territory_type_id = 3 and territory_id = f_get_cluster('$user') LIMIT 1";
      $query = $this->db->query($sql);
      //$query = $this->db->query("");

      if( $query->num_rows() > 0 ) {

      return $query->row();
      //return $query->result_array();
      } else {
      return "";
      }
      }
      //get status apakah Head Office
      public function getStatusTerritoryUser() {
      $user = $this->session->userdata('user_id');
      $sql = "SELECT territory_id FROM t_mtr_territory a left join t_mtr_user b on (a.user_id = b.user_id) ".
      "WHERE a.user_id = '$user' and a.territory_id = '1' and b.user_group_id = '10'";
      $query = $this->db->query($sql);
      //$query = $this->db->query("");

      if( $query->num_rows() > 0 ) {

      return true;
      //return $query->result_array();
      } else {
      return false;
      }
      }
     */
}