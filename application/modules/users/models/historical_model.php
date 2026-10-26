<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of historical_model
 *
 * @author tumbas
 */
class Historical_model extends CI_Model {

  public function __construct() {
    parent::__construct();
  }

  public function get_list() {
    $limit = $this->input->get('rows');
    $offset = ($this->input->get('page') - 1) * $limit;
    $date_from = $this->input->get('date_from');
    $date_to = $this->input->get('date_to');
    $user = $this->input->get('username');
    $table_name = $this->input->get('table_name');
	//$q1 = strtolower($this->db->escape_like_str($this->input->get('q1')));
	//$q2 = strtolower($this->db->escape_like_str($this->input->get('q2')));
	$q1 = $this->db->escape_str(trim($this->input->get('q1')));
	$q2 = $this->db->escape_str(trim($this->input->get('q2')));
	$operand = $this->input->get('operand') ? $this->input->get('operand') : "OR";
    
	$where = "";
	
	if ($operand == 'OR') {
		$where = "
			WHERE (\"User Name\" ILIKE '%{$q1}%' {$operand} \"User Name\" ILIKE '%{$q2}%')
			OR (\"Table Name\" ILIKE '%{$q1}%' {$operand} \"Table Name\" ILIKE '%{$q2}%')
			OR (\"pk_field\" ILIKE '%{$q1}%' {$operand} \"pk_field\" ILIKE '%{$q2}%')
			OR (\"Field Name\" ILIKE '%{$q1}%' {$operand} \"Field Name\" ILIKE '%{$q2}%')
			OR (\"Value Before\" ILIKE '%{$q1}%' {$operand} \"Value Before\" ILIKE '%{$q2}%')
			OR (\"Value After\" ILIKE '%{$q1}%' {$operand} \"Value After\" ILIKE '%{$q2}%')
			OR (\"Primary Key\" ILIKE '%{$q1}%' {$operand} \"Primary Key\" ILIKE '%{$q2}%')
		";
	} elseif ($operand == 'AND') {
		$where = "
			WHERE (\"User Name\" ILIKE '%{$q1}%' {$operand} \"Table Name\" ILIKE '%{$q2}%')
			OR (\"User Name\" ILIKE '%{$q1}%' {$operand} \"pk_field\" ILIKE '%{$q2}%')
			OR (\"User Name\" ILIKE '%{$q1}%' {$operand} \"Field Name\" ILIKE '%{$q2}%')
			OR (\"User Name\" ILIKE '%{$q1}%' {$operand} \"Value Before\" ILIKE '%{$q2}%')
			OR (\"User Name\" ILIKE '%{$q1}%' {$operand} \"Value After\" ILIKE '%{$q2}%')
			OR (\"User Name\" ILIKE '%{$q1}%' {$operand} \"Primary Key\" ILIKE '%{$q2}%')
			
			OR (\"Table Name\" ILIKE '%{$q1}%' {$operand} pk_field ILIKE '%{$q2}%')
			OR (\"Table Name\" ILIKE '%{$q1}%' {$operand} \"Field Name\" ILIKE '%{$q2}%')
			OR (\"Table Name\" ILIKE '%{$q1}%' {$operand} \"Value Before\" ILIKE '%{$q2}%')
			OR (\"Table Name\" ILIKE '%{$q1}%' {$operand} \"Value After\" ILIKE '%{$q2}%')
			OR (\"Table Name\" ILIKE '%{$q1}%' {$operand} \"User Name\" ILIKE '%{$q2}%')
			OR (\"Table Name\" ILIKE '%{$q1}%' {$operand} \"Primary Key\" ILIKE '%{$q2}%')
			
			OR (pk_field ILIKE '%{$q1}%' {$operand} \"Field Name\" ILIKE '%{$q2}%')
			OR (pk_field ILIKE '%{$q1}%' {$operand} \"Value Before\" ILIKE '%{$q2}%')
			OR (pk_field ILIKE '%{$q1}%' {$operand} \"Value After\" ILIKE '%{$q2}%')
			OR (pk_field ILIKE '%{$q1}%' {$operand} \"User Name\" ILIKE '%{$q2}%')
			OR (pk_field ILIKE '%{$q1}%' {$operand} \"Table Name\" ILIKE '%{$q2}%')
			OR (pk_field ILIKE '%{$q1}%' {$operand} \"Primary Key\" ILIKE '%{$q2}%')
			
			OR (\"Field Name\" ILIKE '%{$q1}%' {$operand} \"Value Before\" ILIKE '%{$q2}%')
			OR (\"Field Name\" ILIKE '%{$q1}%' {$operand} \"Value After\" ILIKE '%{$q2}%')
			OR (\"Field Name\" ILIKE '%{$q1}%' {$operand} \"User Name\" ILIKE '%{$q2}%')
			OR (\"Field Name\" ILIKE '%{$q1}%' {$operand} \"Table Name\" ILIKE '%{$q2}%')
			OR (\"Field Name\" ILIKE '%{$q1}%' {$operand} \"pk_field\" ILIKE '%{$q2}%')
			OR (\"Field Name\" ILIKE '%{$q1}%' {$operand} \"Primary Key\" ILIKE '%{$q2}%')
			
			OR (\"Value Before\" ILIKE '%{$q1}%' {$operand} \"Value After\" ILIKE '%{$q2}%')
			OR (\"Value Before\" ILIKE '%{$q1}%' {$operand} \"User Name\" ILIKE '%{$q2}%')
			OR (\"Value Before\" ILIKE '%{$q1}%' {$operand} \"Table Name\" ILIKE '%{$q2}%')
			OR (\"Value Before\" ILIKE '%{$q1}%' {$operand} \"pk_field\" ILIKE '%{$q2}%')
			OR (\"Value Before\" ILIKE '%{$q1}%' {$operand} \"Field Name\" ILIKE '%{$q2}%')
			OR (\"Value Before\" ILIKE '%{$q1}%' {$operand} \"Primary Key\" ILIKE '%{$q2}%')
			
			OR (\"Value After\" ILIKE '%{$q1}%' {$operand} \"User Name\" ILIKE '%{$q2}%')
			OR (\"Value After\" ILIKE '%{$q1}%' {$operand} \"Table Name\" ILIKE '%{$q2}%')
			OR (\"Value After\" ILIKE '%{$q1}%' {$operand} \"pk_field\" ILIKE '%{$q2}%')
			OR (\"Value After\" ILIKE '%{$q1}%' {$operand} \"Field Name\" ILIKE '%{$q2}%')
			OR (\"Value After\" ILIKE '%{$q1}%' {$operand} \"Value Before\" ILIKE '%{$q2}%')
			OR (\"Value After\" ILIKE '%{$q1}%' {$operand} \"Primary Key\" ILIKE '%{$q2}%')
			
			OR (\"Primary Key\" ILIKE '%{$q1}%' {$operand} \"User Name\" ILIKE '%{$q2}%')
			OR (\"Primary Key\" ILIKE '%{$q1}%' {$operand} \"Table Name\" ILIKE '%{$q2}%')
			OR (\"Primary Key\" ILIKE '%{$q1}%' {$operand} \"pk_field\" ILIKE '%{$q2}%')
			OR (\"Primary Key\" ILIKE '%{$q1}%' {$operand} \"Field Name\" ILIKE '%{$q2}%')
			OR (\"Primary Key\" ILIKE '%{$q1}%' {$operand} \"Value Before\" ILIKE '%{$q2}%')
			OR (\"Primary Key\" ILIKE '%{$q1}%' {$operand} \"Value After\" ILIKE '%{$q2}%')
		";
	}

    if ($date_from != '' && $date_to != '') {
      $date_from = MakeUpDate($date_from);
      $date_to = MakeUpDate($date_to);
      $where .= " AND \"Date\" BETWEEN '{$date_from}' AND '{$date_to}' ";
    }

    if ($user != '') {
      $users = explode(',', $user);
      $users2 = array();
      foreach ($users as $value) {
        $users2[] = "'$value'";
      }
      $users_in = implode(',', $users2);
      $where .= " AND \"User Name\" IN ($users_in)";
    }

    if ($table_name != '') {
      $table_names = explode(',', $table_name);
      $table_names2 = array();
      foreach ($table_names as $value) {
        $table_names2[] = "'$value'";
      }
      $table_names_in = implode(',', $table_names2);
      $where .= " AND \"Table Name\" IN ($table_names_in)";
    }

    //Records List
    $sql = "SELECT *, COALESCE(to_char(\"Date\", 'DD FMMon YYYY HH24:MI:SS'), '0000-00-00 00:00:00') AS date2 FROM v_data_history {$where} ORDER BY history_id DESC LIMIT {$limit} OFFSET {$offset}";
    $data['rows'] = $this->db->query($sql)->result();
//    $data['q_rows'] = $this->db->last_query();
    //Records Total
    $sql = "SELECT COUNT(history_id) AS count FROM v_data_history {$where}";
    $data['total'] = $this->db->query($sql)->row()->count;

    return $data;
  }

  public function get_table_list() {
    $limit = $this->input->get('rows');
    $offset = ($this->input->get('page') - 1) * $limit;
    $search = strtolower($this->db->escape_like_str($this->input->get('q')));
    $where = "WHERE LOWER(\"Table Name\") LIKE '%{$search}%'";

    $sql = "SELECT DISTINCT \"Table Name\" AS table_name FROM v_data_history {$where} ORDER BY table_name ASC LIMIT {$limit} OFFSET {$offset}";
    $result['rows'] = $this->db->query($sql)->result();

    //Records Total
    $sql = "SELECT COUNT(DISTINCT \"Table Name\") AS count FROM v_data_history {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }

  public function get_detail($table_name, $field, $primary_key) {
    $primary_key = $this->db->escape($primary_key);
    $sql = "SELECT * FROM {$table_name} WHERE {$field} = {$primary_key}";
    return $this->db->query($sql)->row();
  }

  public function get_pk_field($table) {
    $fields = $this->db->field_data($table);

    foreach ($fields as $field) {
      if ($field->primary_key == 1) {
        return $field->name;
        break;
      }
    }
    
    return '';
  }

}

?>
