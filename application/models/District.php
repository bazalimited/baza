<?php

class District extends CI_Model {
    /*
      Determines if a given category id exists
     */

    function exists($district_id) {
        $this->db->from('district');
        $this->db->where('id', $district_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all() {
        $this->db->from('district');
        return $this->db->get()->result_array();
    }

    function get_info($district_id) {
        $this->db->from('district');
        $this->db->where('id', $district_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return null;
        }
    }

    function get_districts_by_province($province_id) {
        $this->db->select('name, id');
        $this->db->from('district');
        $this->db->where('province_id', $province_id);

        return $this->db->get()->result();
    }

}
