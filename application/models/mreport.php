<?php
class Mreport extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}
    
    function getYearRange(){
        $n=0;
         for($i=date('Y');$i>date('Y')-10;$i--){
            $return[$n]['value']=$i;
            $return[$n++]['label']=$i;
            
         } 
         return $return;
    }
    
    //andedi, 2014-09-10 {^_^) Tumbas Wiralodra
    function getYearRange2(){
        $n=0;
         for($i=date('Y')+2;$i>=date('Y')-2;$i--){
            $return[$n]['value']=$i;
            $return[$n++]['label']=$i;
            
         } 
         return $return;
    }
    
    function getRegionList(){
        $this->db->select('region_id as value')
                ->select('region_name as label')
                ->where('status',1)
                ->distinct()
                ->order_by('region_id');
        return $this->db->get('t_mtr_region')->result_array();
    }
    
    function getRegionListByPrevilege(){
        $user_region=$this->session->userdata('user_region');
        $i=0;
        foreach ($user_region as $key){
            $region[$i]['value']=$key['region_name'];
            $region[$i++]['label']=$key['region_name'];
        }
        return $region;
    }
    
    function getMonthRange(){
        $month = array(
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July ',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December');
        $i=1;
        foreach ($month as $key => $value) {
            $return[$i]['value'] = $i;
            $return[$i]['label'] = $value;
            $i++;
        }
        return $return;
    }
    
    //added by andedi 2014-09-10
    function getMonthRange2(){
        $month = array(
                    ' - All Month - ',
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July ',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December');
        $i=0;
        foreach ($month as $key => $value) {
            $return[$i]['value'] = $i;
            $return[$i]['label'] = $value;
            $i++;
        }
        return $return;
    }
    
    function getChartType(){
        $return[0]['label']='No Chart';
        $return[0]['value']='data';
        
        $return[1]['label']='Multi Series';
        $return[1]['value']='MSColumn3D';
        
        $return[2]['label']='Stacked Column';
        $return[2]['value']='StackedColumn3D';
        
        $return[3]['label']='Stacked Bar';
        $return[3]['value']='StackedBar';
       
        return $return;
    }

    function getDismantleVendor(){
        $this->db->select('vendor_id as value')
                ->select('vendor_name as label')
                ->where('status',1);
        return $this->db->get('t_mtr_dismantle_vendor')->result_array();
    }
    
}