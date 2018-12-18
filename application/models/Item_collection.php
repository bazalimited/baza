<?php

class Item_collection extends CI_Model {
    /*
      Determines if a given category id exists
     */

    function exists($id) {
        $this->db->from('items_collection');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_info($item_id) {
        $this->db->from('items_collection');
        $this->db->where('item_id', $item_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('items_collection');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }

    function get_customer($item_id) {
        $collector_info = null;
        $this->db->from('items_collection');
        $this->db->where('item_id', $item_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $collection = $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('items_collection');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            $collection = $item_obj;
        }
//        echo $this->db->last_query();
//            exit();
        if (isset($collection)) {

            //get the customer
            $collector_info = $this->Customer->get_customer_info_person_id($collection->customer_id);
        }
        return $collector_info;
    }

    function save($item_collection_data = array(), $id = false) {
        if ($id == false) {
            if ($item_collection_data) {
                if ($this->db->insert('items_collection', $item_collection_data)) {
                    return $this->db->insert_id();
                }
            }
            return FALSE;
        } else {
            $this->db->where('id', $id);
            if ($this->db->update('items_collection', $item_collection_data)) {
                return $id;
            }
        }


        return FALSE;
    }

    /*
      Deletes one category
     */

    function approve_return($item_id) {
        $this->db->where('item_id', $item_id);
        $this->db->update('items', array('status' => 0)); // set the item status to returned
        //delete the item 
        return $this->db->delete('items_collection', array('item_id' => $item_id));
    }

    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->update('items_collection', array('deleted' => 1));
    }

}
