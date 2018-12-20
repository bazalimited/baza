<?php

class Ussd_session extends CI_Model {
    /*
      Determines if a given item_id is an item
     */

    function exists($session_id) {
        $this->db->from('ussd_sessions');
        $this->db->where('session_id', $session_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function save(&$ussd_session_data, $ussd_session_id = false) {
        if (!$ussd_session_id or ! $this->exists($ussd_session_id)) {
            if ($this->db->insert('ussd_sessions', $ussd_session_data)) {
                    return true;
            }
            return false;
        }

        $this->db->where('session_id', $ussd_session_id);
        return $this->db->update('ussd_sessions', $ussd_session_data);
    }

    function delete($ussd_session_id) {
        $this->db->where('session_id', $ussd_session_id);
        if ($this->db->delete('ussd_sessions')) {
            return true;
        }
    }

    function get_info($session_id) {
        $this->db->from('ussd_sessions');
        $this->db->where('session_id', $session_id);

        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('ussd_sessions');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }

}

?>
