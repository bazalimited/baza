<?php

class Province extends CI_Model {
    /*
      Determines if a given category id exists
     */

    function exists($province_id) {
        $this->db->from('province');
        $this->db->where('id', $province_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all() {
        $this->db->from('province');
        return $this->db->get()->result_array();
    }

    function get_info($province_id) {
        $this->db->from('province');
        $this->db->where('id', $province_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return null;
        }
    }

}
