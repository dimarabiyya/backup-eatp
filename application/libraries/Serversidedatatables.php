<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Serversidedatatables {

    public function executeServerSideProcess($defaultOrder, $status, $userlogin, $inputQuery, $aColumns, $sIndexColumn, $orderingSequence = "")
    {

    	//tentukan limit dan offset
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".pg_escape_string( $_GET['iDisplayLength'] )." OFFSET ".
				pg_escape_string( $_GET['iDisplayStart'] );
		}

			
		 //Tentukan urutan tampilan data (Ordering)
		 	$sOrder = "";
		 	
		 	if(isset($defaultOrder)) //$defaultOrder -> urutan default saat data pertama kali ditampilkan berdasarkan field tertentu
		 	{
		 		$_GET['iSortCol_0'] = $defaultOrder;
		 		
		 		$sOrder = "ORDER BY  ";
		 		if(isset($_GET['iSortingCols']) && $_GET['iSortingCols'] == "1")
		 		{
					for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
					{
						if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
						{
							$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
								".pg_escape_string( $_GET['sSortDir_'.$i] ) .", ";
						}
					}
				}

				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}

		 	}
			else 
			{
				if ( isset( $_GET['iSortCol_0'] ) )
				{
					//var_dump($_GET['iSortCol_0']); die();
					$sOrder = "ORDER BY  ";
					if(isset($_GET['iSortingCols']) && $_GET['iSortingCols'] == "1")
					{	
						for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
						{
							if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
							{
								$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
									".pg_escape_string( $_GET['sSortDir_'.$i] ) .", ";
							}
						}
					} 
					$sOrder = substr_replace( $sOrder, "", -2 );
					if ( $sOrder == "ORDER BY" )
					{
						$sOrder = "";
					}
				}
			}
			//jika asc atau desc != ""
			if(isset($orderingSequence) and $orderingSequence!="")
			{
				$sOrder = str_replace('asc', $orderingSequence, $sOrder);
			}
			
			// var_dump($_GET['iSortingCols']); die();

			//blok kode utk menghandle fitur pencarian
			$sWhere = "";
			if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				if(stristr($inputQuery, "WHERE") || stristr($inputQuery, "where"))
				{
					$sWhere = " and (";
					for ( $i=0 ; $i<count($aColumns) ; $i++ )
					{
						if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
						{
							// $sWhere .= "cast(quote_ident(".$aColumns[$i].") as text) ILIKE '%".pg_escape_string( $_GET['sSearch'] )."%' OR ";
							// $sWhere .= "cast(".$aColumns[$i]." as text) ILIKE '%".pg_escape_string( $_GET['sSearch'] )."%' OR ";
							$sWhere .= "quote_literal(".$aColumns[$i].") ILIKE '%".pg_escape_string( $_GET['sSearch'] )."%' OR ";
						}
					}
					$sWhere = substr_replace( $sWhere, "", -3 );
					$sWhere .= ")";	
				}
				else
				{
					$sWhere = "WHERE (";
					for ( $i=0 ; $i<count($aColumns) ; $i++ )
					{
						if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
						{
							// $sWhere .= "cast(quote_ident(".$aColumns[$i].") as text) ILIKE '%".pg_escape_string( $_GET['sSearch'] )."%' OR ";
							$sWhere .= "quote_literal(".$aColumns[$i].") ILIKE '%".pg_escape_string( $_GET['sSearch'] )."%' OR ";
						}
					}
					$sWhere = substr_replace( $sWhere, "", -3 );
					$sWhere .= ")";
				}
			}
			 
			//Individual column filtering 
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && isset($_GET['sSearch_'.$i]) && $_GET['sSearch_'.$i] != '' )
				{
					if ( $sWhere == "" )
					{
						$sWhere = "WHERE ";
					}
					else
					{
						$sWhere .= " AND ";
					}
					$sWhere .= $aColumns[$i]." ILIKE '%".pg_escape_string($_GET['sSearch_'.$i])."%' ";
				}
			}

			// var_dump($sWhere) . "<br>";
		//Query yg akan dieksekusi
		$sQuery = $inputQuery ."
		$sWhere
		$sOrder
		$sLimit
		 ";
		
		// var_dump($sQuery);die();

		$rResult = pg_query( $sQuery ) or die(pg_last_error());
		
		// var_dump($rResult);die();

		//$iTotal = pg_num_rows($rResult);
	 	
		$sQuery = $inputQuery ."
				$sWhere
				$sOrder
		";



		$rResultTotal = pg_query( $sQuery ) or die(pg_last_error());
		$iTotal = pg_num_rows($rResultTotal);
		pg_free_result( $rResultTotal );
		
		
		if ( $sWhere != "" )
		{
			$sQuery =  $inputQuery ."
				$sWhere
				$sOrder
				$sLimit
				";
			$rResultFilterTotal = pg_query( $sQuery ) or die(pg_last_error());
			// $iFilteredTotal = pg_num_rows($rResultFilterTotal); //***
			$iFilteredTotal = $iTotal;
			pg_free_result( $rResultFilterTotal );
			// var_dump($sQuery);
		}
		else
		{
			$iFilteredTotal = $iTotal;
		}

		//memformat output
		$echo="";
		if(isset($_GET['sEcho']) && $_GET['sEcho'] != "")
		{
			$echo = intval($_GET['sEcho']);
		}

		$output = array(
			"sEcho" => $echo,
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array(),
		);
		
		while ( $aRow = pg_fetch_array($rResult, null, PGSQL_ASSOC) )
		{
			$row = array();
			//$output["no"][] = $i;
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ( $aColumns[$i] == "version" )
				{
					// Special output formatting for 'version' column 
					$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
				}
				else if ( $aColumns[$i] != ' ' )
				{
					// General output 
					$row[] = $aRow[ $aColumns[$i] ];
					//var_dump($row);die();
				}
			}
			$output['aaData'][] = $row;
		}

		pg_free_result( $rResult );
		//kirimkan return value kpd fungsi yg memanggil
		return $output;
		
    }
}