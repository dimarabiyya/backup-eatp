<?php

class Core {

  private $CI;
  private $required = array();
  private $where = array();
  public $set = array();
  public $error_message;
  public $db_debug = FALSE;

  public function __construct() {
    $this->CI = & get_instance();

    $this->CI->load->library('form_validation');
  }

  public function setFileNameIcon($filename) {
    if (!$filename)
      return '';

    $x = explode('.', $filename);
    $ext = $x[count($x) - 1];
    $ext = strtolower($ext);
    $icons = array('doc' => 'doc',
        'docx' => 'doc',
        'xls' => 'xls',
        'xlsx' => 'xls',
        'csv' => 'xls',
        'jpg' => 'jpg',
        'gif' => 'jpg',
        'png' => 'jpg',
        'ppt' => 'ppt',
        'pptx' => 'ppt',
        'pdf' => 'pdf',
        'jpeg' => 'jpg');
    if (isset($icons[$ext]))
      $icon = $icons[$ext];
    else
      $icon = 'file';

    if (strstr($filename, '/')) {
      $x = explode('/', $filename);
      $filename = $x[count($x) - 1];
    }

    return "<span class='files $icon' title='$filename'>$filename</span>";
  }

  public function setButton($icon, $onClick = '', $caption) {
    return "<a title='$caption' style='margin-right:2px' href='#' class='btn btn-mini btn-icon glyphicons $icon' onclick='$onClick'><i></i>$caption</a>";
  }

  // encode url
  public function encode($data) {
    return strtr(rtrim(base64_encode($data), '='), '+/', '-_');
  }

  // decode url
  public function decode($base64) {
    return base64_decode(strtr($base64, '-_', '+/'));
  }

  // is ajax?
  public function is_ajax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
  }

  // save/update table	
  public function save($table, $id = '') {
    if (count($_POST) || count($_FILES)) {
      // get fields of this table
      $fields = $this->CI->db->list_fields($table);

      // get data from database
      if ($id) {
        $query = $this->CI->db->get_where($table, $id);

        if ($query->num_rows()) {
          $row = $query->row_array();
        }
      }

      // get values
      $values = @$this->get_values($row);

      if ($this->db_debug) {
        $this->CI->db->db_debug = TRUE;
      } else {
        $this->CI->db->db_debug = FALSE;
      }

      // check posted data is in fields
      foreach ($values as $field => $value) {
        if (@!in_array($field, $fields)) {
          unset($values[$field]);
        }
        if (array_key_exists($field, $this->set)) {
          unset($values[$field]);
        }
      }

      //  if validate is unsuccessful show errors (return false) else insert and redirect
      if ($this->check_errors()) {
        // set fields
        if ($this->set && sizeof($this->set) > 0) {
          $this->CI->db->set($this->set); // set fields
          unset($this->set);
        }

        // add row
        if (!@$row && !$id) {
          $this->CI->db->trans_start();

          if (!$this->CI->db->insert($table, $values)) {
            $this->error_message = array($this->CI->db->_error_message());

            return FALSE;
          }

          $this->CI->db->trans_complete();
        }
        // edit row
        else {
          if ($this->where && sizeof($this->where) > 0) {
            $this->CI->db->where($this->where);
          }
          $this->CI->db->where($id);

          $this->CI->db->trans_start();

          /*
            if ( ! $this->CI->db->update($table, $values)) {
            $this->error_message = array($this->CI->db->_error_message());

            return FALSE;
            }
           */

          unset($values); // field yang tidak didefine jangan diikutkan

          if (!$this->CI->db->update($table)) {
            $this->error_message = array($this->CI->db->_error_message());

            return FALSE;
          }

          $this->CI->db->trans_complete();
        }

        unset($this->required);

        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  public function get_values($data = '', $id = '') {
    // init array
    $values = array();

    // populate by row if set
    if (@is_array($data)) {
      $row = $data;
      $values = $data;
    }

    // get data from database		
    else {
      $table = $data;

      if ($id) {
        $query = $this->CI->db->get_where($table, $id);

        if ($query->num_rows()) {
          $row = $query->row_array();
          $values = $row;
        }
      }
    }

    // get post if there is any
    if ($post = $this->get_post()) {
      // check posted data is in fields
      foreach ($post as $field => $value) {
        // make sure the value is just a normal value and not an array
        if (!is_array($value)) {
          if (isset($row) && isset($row[$field]) && $value == $row[$field]) {
            unset($this->required[$field]);
          } else {
            // prep password
            if ($field == 'password') {
              if ($value != '') {
                $values[$field] = md5($value);
              }
            }

            // overwrite value with posted value
            else {
              $values[$field] = $value;
            }
          }

          if (array_key_exists($field, $this->set)) {
            unset($values[$field]);
          }
        }
      }
    }

    return $values;
  }

  public function get_post() {
    if (count($_POST)) {
      $post = array();
      foreach ($_POST as $key => $value) {
        $post[$key] = $this->CI->input->post($key);
      }

      return $post;
    } else {
      return FALSE;
    }
  }

  public function check_errors() {
    // set rules for validation
    if (isset($this->required)) {
      $config = array();

      foreach ($this->required as $field => $name) {
        if (is_array($name)) {
          $config[$field] = array('field' => $field, 'label' => $name['label'], 'rules' => $name['rules']);
        } else {
          if ($field == 'email') {
            $config[$field] = array('field' => $field, 'label' => $name, 'rules' => 'required|valid_email');
          } elseif ($field == 'password') {
            $config[$field] = array('field' => $field, 'label' => $name, 'rules' => 'required|matches[confirmPassword]');
          } else {
            $config[$field] = array('field' => $field, 'label' => $name, 'rules' => 'required');
          }
        }
      }

      // set rules and fields for validation
      $this->CI->form_validation->set_rules($config);

      if (!$this->CI->form_validation->run() && isset($this->required) && count($this->required)) {
        return FALSE;
      } else {
        return TRUE;
      }
    } else {
      return TRUE;
    }
  }

  public function view_all($table, $where = '', $order = '', $limit = '') {
    if ($where) {
      $this->CI->db->where($where);
    }

    if ($order && !is_array($order)) {
      $this->CI->db->order_by($order, 'ASC');
    } elseif ($order && is_array($order)) {
      $this->CI->db->order_by($order[0], $order[1]);
    }

    if ($limit) {
      $this->CI->db->limit($limit);
    }

    $query = $this->CI->db->get($table);

    $output = $query->result_array();

    return $output;
  }

  public function view_row($table, $where) {
    $this->CI->db->where($where);

    $query = $this->CI->db->get($table);

    $output = $query->row_array();

    return $output;
  }

  public function get_rows($data, $table, $where = '', $order = '', $limit = '') {
    if ($where) {
      $this->CI->db->where($where);
    }

    if ($order && !is_array($order)) {
      $this->CI->db->order_by($order, 'ASC');
    } elseif ($order && is_array($order)) {
      $this->CI->db->order_by($order[0], $order[1]);
    }

    if ($limit) {
      $this->CI->db->limit($limit);
    }

    if (is_array($data)) {
      $this->CI->db->select($data);
    }

    $query = $this->CI->db->get($table);

    $output = $query->result_array();

    return $output;
  }

  /**
   * @param $table is table name
   * @param $data is array
   * @param $where is array
   */
  public function update($table, $data, $where = '') {
    if ($where) {
      $this->CI->db->where($where);
    }

    $this->CI->db->update($table, $data);

    return TRUE;
  }

  public function delete($table, $where = '') {
    if ($where) {
      $this->CI->db->where($where);
    }

    $this->CI->db->delete($table);

    return TRUE;
  }

  /**
   * @param String $key lookup key
   * @param boolean $numeric_value just numeric value or others
   */
  public function get_lookup($key, $numeric_value = TRUE) {
    $sql = "SELECT lookup_value, lookup_desc FROM t_mtr_lookup WHERE lookup_key = '{$key}' AND \"active\" = 1 ORDER BY \"order\" ASC";
    $query = $this->CI->db->query($sql);
    $results = $query->result_array();

    $data = array();
    foreach ($results as $result) {
      if ($numeric_value) {
        if (filter_var($result['lookup_value'], FILTER_VALIDATE_INT)) {
          $data[] = array(
              'id' => $result['lookup_value'],
              'desc' => $result['lookup_desc']
          );
        }
      } else {
        $data[] = array(
            'id' => $result['lookup_value'],
            'desc' => $result['lookup_desc']
        );
      }
    }

    return $data;
  }

  public function get_lookup_options($key, $numeric_value = TRUE) {
    $sql = "SELECT lookup_value, lookup_desc FROM t_mtr_lookup WHERE lookup_key = '{$key}' AND \"active\" = 1 ORDER BY \"order\" ASC";
    $query = $this->CI->db->query($sql);
    $results = $query->result_array();

    $data = array();
    foreach ($results as $result) {
      if ($numeric_value) {
        if (filter_var($result['lookup_value'], FILTER_VALIDATE_INT)) {
          $data[$result['lookup_value']] = $result['lookup_desc'];
        }
      } else {
        $data[$result['lookup_value']] = $result['lookup_desc'];
      }
    }

    return $data;
  }

}
