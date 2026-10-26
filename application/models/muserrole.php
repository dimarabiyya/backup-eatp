<?php
class Muserrole extends CI_Model {

	function Muserrole(){
		parent::__construct();
	}
	
        #####################USER GROUP
        
        function getUserGroup($id = "")
        {    
                $dblokal  = $this->load->database("default", TRUE);
                if($id<>""){
                        $dblokal->where(array("user_id"=>$id));
                }
                $dblokal->order_by('user_group_name');
                $query = $dblokal->get("t_mtr_user_group");
                if( $query->num_rows() > 0 ) {
                        return $query->result();
                } else {
                        return array();
                }
	}
        
        function saveUserRole($group,$menu,$nilai){
                $dblokal  = $this->load->database("default", TRUE);
                $data = array("user_group_id"=>$group,"menu_id"=>$menu);
                if($nilai == "true"){
                        $dblokal->insert("t_mtr_user_role",$data);
                }elseif($nilai == "false"){
                        $dblokal->delete("t_mtr_user_role",$data);
                }
        }
		
		function saveUserRoleAction($group,$menu,$nilai,$actid){
                $dblokal  = $this->load->database("default", TRUE);
                $where = array("user_group_id"=>$group,"menu_id"=>$menu);
                $data[ $actid ]=0;
				if($nilai == "true"){
                        $data[ $actid ]=1;
                }
				$dblokal->where($where);
				$dblokal->update("t_mtr_user_role",$data);
        }
        
        function getUserRole($group){
                $dblokal  = $this->load->database("default", TRUE);
                
                $dblokal->select("menu_id");
                $dblokal->where(array("user_group_id"=>$group));
                $query = $dblokal->get("t_mtr_user_role");
                if( $query->num_rows() > 0 ) {
                        return $query->result();
                } else {
                        return array();
                }
        }
        
}