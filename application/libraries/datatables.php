<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datatables
{
  protected 	$ci;

	public function __construct()
	{
         $this->ci =& get_instance();
	}

	
	public function render($aColumns,$sTable,$where,$query, $order='', $group=''){
		$iDisplayStart =  $this->ci->input->get_post('iDisplayStart', true);
		$iDisplayLength =  $this->ci->input->get_post('iDisplayLength', true);
		$iSortCol_0 =  $this->ci->input->get_post('iSortCol_0', true);
		$iSortingCols =  $this->ci->input->get_post('iSortingCols', true);
		$sSearch =  $this->ci->input->get_post('sSearch');
		$sEcho =  $this->ci->input->get_post('sEcho', true);
		// $iDisplayStart = $param['iDisplayStart'];
		// $iDisplayLength = $param['iDisplayLength'];
		// $iSortCol_0 = $param['iSortCol_0'];
		// $iSortingCols = $param['iSortingCols'];
		// $sSearch = $param'sSearch'];
		// $sEcho = $param['sEcho'];



		$sLimit = "";

		if ( isset( $iDisplayStart ) && $iDisplayLength != '-1' )
		{
			$sLimit = "LIMIT ".intval( $iDisplayLength )." OFFSET ".
					intval( $iDisplayStart );
		}

		if ( isset( $iSortCol_0 ) ) {
			$sOrder="";
			$order!= ''? $order.',': '';
			for ( $i=0 ; $i<intval( $iSortingCols ) ; $i++ ) {
				$iSortCol =  $this->ci->input->get_post('iSortCol_'.$i, true);
				$bSortable =  $this->ci->input->get_post('bSortable_'.intval($iSortCol), true);
				$sSortDir =  $this->ci->input->get_post('sSortDir_'.$i, true);

				if ( $bSortable == "true" ) {
					$sOrder = "order by ".$order ." ".$aColumns[ intval( $iSortCol ) ]."
							".($sSortDir=='desc ' ? ' asc ' : ' desc ').", ";
				}
			}

			 $sOrder = substr_replace( $sOrder, "", -2 );
			// if ( $sOrder == "ORDER BY " )
			// {
			// 	$sOrder = "";
			// }
		}
			
		$sWhere = $where ;
		if ( $sSearch != "" )
		{

			$sWhere = ($where == '') ? 'where (' : $where .' and ('  ;
			if (count($aColumns) > 1) {
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$bSearchable =  $this->ci->input->get_post('bSearchable_'.$i, true);
				if ( $bSearchable == "true" )
				{
					$param = $aColumns[$i];

					// ------------------------- khusus SEM ..................//
					if($group != '')
						$param = ($aColumns[$i] == "user_id") ?  "mu.user_id" : $aColumns[$i];
					// -------------------------akhir khusus SEM ..................//


					$sWhere .= "cast(".$param." as text) ILIKE '%".( $sSearch )."%' OR ";
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			} else {
			$swhere .= "cast(".$aColumns." as TEXT) ILIKE '%".( $sSearch )."% ";
		   }
			$sWhere .= ")";
		}
			
		$sQuery = 
				$query.' FROM '.$sTable.' '.
				$sWhere.' '.
			    $group. ' '.
				$sOrder.' '.
				
				$sLimit  
				;
		$rResult =  $this->ci->db->query($sQuery);		

		// Data set length after filtering
		$sQuery = '
		SELECT COUNT (*) AS numrows
		FROM   '.$sTable.' '.$sWhere;

		//--------------------ini khusus SEM-----------------//

		if($group != '')             
			$sQuery = '
			SELECT COUNT (distinct mu.user_id) AS numrows    
			FROM   '.$sTable.' '.$sWhere;

		//-------------------- khusus SEM sampe sini-----------------//

		$rResultTotal =  $this->ci->db->query($sQuery)->row();
		$iTotal = $rResultTotal->numrows;
		$iTotal = ($iTotal == null) ?  '0' : $iTotal;		
		 
		if ( $sWhere != $where)
		{
			$sQuery = '
			SELECT COUNT (*) AS numrows
			FROM   '.$sTable.' '.$sWhere;
			$rResultFilterTotal =  $this->ci->db->query($sQuery)->row();
			$iFilteredTotal = $rResultFilterTotal->numrows;
			$iFilteredTotal = ($iFilteredTotal == null) ?  '0' : $iFilteredTotal;
		}
		else
		{ $iFilteredTotal = $iTotal;
		}

		

		// Total data set length
		// $iTotal =  $this->ci->db->count_all($sTable);

		// Output
		

		$output = array(
				'sEcho' => intval($sEcho),
				'iTotalRecords' => $iTotal,
				'iTotalDisplayRecords' => $iFilteredTotal,
				'aaData' => array()
		);

		
		foreach($rResult->result_array() as $aRow)
		{
			$row = array();
			foreach($aColumns as $col)
			{
				$row[] = $aRow[$col];
			}

			$output['aaData'][] = $row;
		}
     
		return $output;
	}
}

/* End of file datatables.php */
/* Location: ./application/libraries/datatables.php */
