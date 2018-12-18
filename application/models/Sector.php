<?php

class Sector extends CI_Model {
    /*
      Determines if a given category id exists
     */

    function exists($sector_id) {
        $this->db->from('sector');
        $this->db->where('id', $sector_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all() {
        $this->db->from('sector');
        return $this->db->get()->result_array();
    }

    function get_info($sector_id) {
        $this->db->from('sector');
        $this->db->where('id', $sector_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return null;
        }
    }

    function get_sectors_by_district($district_id) {
        $this->db->select('name, id');
        $this->db->from('sector');
        $this->db->where('district_id', $district_id);

        return $this->db->get()->result();
    }

}
