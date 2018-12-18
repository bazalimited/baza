<?php

class Personal_item extends CI_Model {
    /*
      Determines if a given item_id is an item
     */

    function get_info($item_id, $can_cache = TRUE) {
        if ($can_cache) {
            static $cache = array();
        } else {
            $cache = array();
        }

        if (is_array($item_id)) {
            $items = $this->get_multiple_info($item_id)->result();
            foreach ($items as $item) {
                $cache[$item->item_id] = $item;
            }

            return $items;
        } else {
            if (isset($cache[$item_id])) {
                return $cache[$item_id];
            }
        }


        //If we are NOT an int return empty item
        if (!is_numeric($item_id)) {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('personal_items');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }

        $this->db->from('personal_items');
        $this->db->where('item_id', $item_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $cache[$item_id] = $query->row();
            return $cache[$item_id];
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('personal_items');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }

    function item_payment_status($item_id) {
        $item_payment_status = $this->Payment->get_all_payment_by_item_id($item_id, 2); // 2 for personal items
        if (is_object($item_payment_status)) {
            return $item_payment_status->payment_gatway_code;
        }
        return 0;
    }

    function exists($item_id) {
        $this->db->from('personal_items');
        $this->db->where('item_id', $item_id);
        $query = $this->db->get();
        return ($query->num_rows() == 1);
    }

    function item_sn_registered($sn, $category_id, $exclude_id) {
        $this->db->from('personal_items');
        $this->db->where('item_number', $sn);
        $this->db->where('category_id', $category_id);
        $this->db->where('item_id <> ' . $exclude_id);
        $result = $this->db->get();

        if (is_object($result)) {
            if ($result->num_rows() > 0) {
                return true;
            }
        }
        return false;
    }

    function get_previous_register($sn, $category_id, $exclude_id) {
        $this->db->from('personal_items');
        $this->db->where('item_number', $sn);
        $this->db->where('category_id', $category_id);
        $this->db->where('item_id <> ' . $exclude_id);
        return $this->db->get();
    }

    /*
      Returns all the items
     */

    function get_all($limit = 10000, $offset = 0) {

        $this->db->select('personal_items.*, categories.name as category,
		categories.id as category_id');

        $this->db->from('personal_items');
        $this->db->join('categories', 'categories.id = items.category_id', 'left');
        $this->db->where('personal_items.deleted', 0);

        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get();
    }
    
    function get_items_by_ids($ids) {
        $this->db->select('personal_items.*, categories.name as category,
		categories.id as category_id');

        $this->db->from('personal_items');
        $this->db->join('categories', 'categories.id = personal_items.category_id', 'left');
        $this->db->where('personal_items.deleted', 0);
        $this->db->where('personal_items.item_id in ('. implode(',', $ids).')');

        return $this->db->get();
    }

    function count_all($limit = 10000, $offset = 0) {

        $this->db->select('personal_items.*, categories.name as category,
		categories.id as category_id');

        $this->db->from('personal_items');
        $this->db->join('categories', 'categories.id = items.category_id', 'left');
        $this->db->where('personal_items.deleted', 0);

        $this->db->limit($limit);
        $this->db->offset($offset);

        $result = $this->db->get();

        if (is_object($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

    function item_registed($sn, $user_id) {

        $this->db->select('personal_items.*');

        $this->db->from('personal_items');
        $this->db->where('personal_items.deleted', 0);
        $this->db->where('personal_items.item_number', $sn);
        $this->db->where('personal_items.created_by_id', $user_id);

        $result = $this->db->get();

        if (is_object($result)) {
            if ($result->num_rows() > 0) {
                return true;
            }
        }
        return false;
    }

    function get_all_by_user($user_id, $search = null, $category_id = '', $limit = 10000, $offset = 0) {

        $this->db->select('personal_items.*, categories.name as category,
		categories.id as category_id');

        $this->db->from('personal_items');
        $this->db->join('categories', 'categories.id = personal_items.category_id', 'left');
        $this->db->where('personal_items.deleted', 0);
        $this->db->where('personal_items.created_by_id', $user_id);

        if (isset($search)) {
            $this->db->where('personal_items.name like "%' . $search . '%" or personal_items.description like "%' . $search . '%" or personal_items.item_number like "%' . $search . '%" or personal_items.name_on_card like "%' . $search . '%"');
        }
        if ($category_id != '') {
            $this->db->where('personal_items.category_id', $category_id);
        }
        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get();
    }

    function count_all_by_user($user_id, $search = null, $category_id = '', $limit = 10000, $offset = 0) {

        $this->db->select('personal_items.*, categories.name as category,
		categories.id as category_id');

        $this->db->from('personal_items');
        $this->db->join('categories', 'categories.id = personal_items.category_id', 'left');
        $this->db->where('personal_items.deleted', 0);
        $this->db->where('personal_items.created_by_id', $user_id);
        if (isset($search)) {
            $this->db->where('personal_items.name like "%' . $search . '%" or personal_items.description like "%' . $search . '%" or personal_items.item_number like "%' . $search . '%" or personal_items.name_on_card like "%' . $search . '%"');
        }
        if ($category_id != '') {
            $this->db->where('personal_items.category_id', $category_id);
        }
        $this->db->limit($limit);
        $this->db->offset($offset);

        $result = $this->db->get();

        if (is_object($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

    function get_unpaid_by_user($user_id) {
        $result = $this->db->query("CALL get_unpaid_by_user(" . $user_id . ", " . SUCCESS_STATUS_CODE . ")");
        mysqli_next_result($this->db->conn_id);

        return $result;
    }

    function get_paid_by_user($user_id) {
        $result = $this->db->query("CALL get_paid_by_user(" . $user_id . ", " . SUCCESS_STATUS_CODE . ")");
        mysqli_next_result($this->db->conn_id);

        return $result;
    }

    function count_paid_by_user($user_id) {
        $result = $this->db->query("CALL get_paid_by_user(" . $user_id . ", " . SUCCESS_STATUS_CODE . ")");
        $row_count = $result->num_rows();

        mysqli_next_result($this->db->conn_id);

        return $row_count;
    }

    function count_unpaid_by_user($user_id) {
        $result = $this->db->query("CALL get_unpaid_by_user(" . $user_id . ", " . SUCCESS_STATUS_CODE . ")");
        mysqli_next_result($this->db->conn_id);

        return $result->num_rows();
    }

    function get_registered_found_by_user($user_id, $limit = 10000, $offset = 0) {

        $this->db->select('items.*, categories.name as category, categories.id as category_id');
        $this->db->from('personal_items');
        $this->db->join('items', 'items.item_number = personal_items.item_number', 'left');
        $this->db->join('categories', 'categories.id = items.category_id', 'left');
        $this->db->where('items.deleted', 0);
        $this->db->where('personal_items.deleted', 0);
        $this->db->where('personal_items.created_by_id', $user_id);
        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get();
    }

    function get_found_by_user($user_id, $limit = 10000, $offset = 0) {

        $this->db->select('items.*, payments.payment_gatway_code, categories.name as category, categories.id as category_id');
        $this->db->from('items');
        $this->db->join('payments', 'payments.item_id = items.item_id', 'left');
        $this->db->join('categories', 'categories.id = items.category_id', 'left');
        $this->db->where('items.deleted', 0);
        $this->db->where('payments.deleted', 0);
        $this->db->where('payments.item_type', 1); //found item
        $this->db->where('payments.payed_by_id', $user_id);
        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get();
    }

    function count_registered_found_by_user($user_id, $limit = 10000, $offset = 0) {

         $this->db->select('items.*, categories.name as category, categories.id as category_id');
        $this->db->from('personal_items');
        $this->db->join('items', 'items.item_number = personal_items.item_number', 'left');
        $this->db->join('categories', 'categories.id = items.item_id', 'left');
        $this->db->where('items.deleted', 0);
        $this->db->where('personal_items.deleted', 0);
        $this->db->where('personal_items.created_by_id', $user_id);
        $this->db->limit($limit);
        $this->db->offset($offset);

        $result = $this->db->get();

        if (is_object($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

    function count_found_by_user($user_id, $limit = 10000, $offset = 0) {

        $this->db->select('items.*, payments.payment_gatway_code, categories.name as category');
        $this->db->from('items');
        $this->db->join('payments', 'payments.item_id = items.item_id', 'left');
        $this->db->join('categories', 'categories.id = items.category_id', 'left');
        $this->db->where('items.deleted', 0);
        $this->db->where('payments.deleted', 0);
        $this->db->where('payments.item_type', 1); //found item
        $this->db->where('payments.payed_by_id', $user_id);
        $this->db->limit($limit);
        $this->db->offset($offset);

        $result = $this->db->get();

        if (is_object($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

    function save(&$item_data, $item_id = false) {
        if (!$item_id or ! $this->exists($item_id)) {
            if ($this->db->insert('personal_items', $item_data)) {
                return $this->db->insert_id();
            }
        }
        $this->db->where('item_id', $item_id);
        if ($this->db->update('personal_items', $item_data)) {
            return $item_id;
        }
        return 0;
    }

    function update_image($file_id, $item_id) {
        $this->db->set('image_id', $file_id);
        $this->db->where('item_id', $item_id);

        return $this->db->update('personal_items');
    }

    function get_user_categories($user_id) {

        $user_items = $this->Personal_item->get_all_by_user($user_id);
        $category_ids = array();
        $categories = array('' => 'All');
        if (is_object($user_items)) {
            foreach ($user_items->result() as $item) {
                array_push($category_ids, $item->category_id);
            }

            if (count($category_ids) > 0) {
                foreach ($category_ids as $category_id) {
                    $category = $this->Category->get_info($category_id);
                    $categories[$category_id] = $category->name;
                }
            }
        }
        return $categories;
    }

    function public_search_items($search) {
        $this->db->select('personal_items.*,categories.name as category, people.first_name, people.last_name, people.phone_number');
        $this->db->from('personal_items');
        $this->db->join('categories', 'categories.id = personal_items.category_id', 'left');
        $this->db->join('employees', 'employees.id = personal_items.created_by_id', 'left');
        $this->db->join('people', 'people.person_id = employees.person_id', 'left');
        $this->db->where($this->db->dbprefix('personal_items') . ".item_number = '" . $search . "' and " . $this->db->dbprefix('personal_items') . ".deleted=0");
        $this->db->where($this->db->dbprefix('personal_items') . ".owner_payment_id is not null");

        return $this->db->get();
    }

    function public_search_items_count($search) {
        $this->db->select('personal_items.*,categories.name as category, people.first_name, people.last_name, people.phone_number');
        $this->db->from('personal_items');
        $this->db->join('categories', 'categories.id = personal_items.category_id', 'left');
        $this->db->join('employees', 'employees.id = personal_items.created_by_id', 'left');
        $this->db->join('people', 'people.person_id = employees.person_id', 'left');
        $this->db->where($this->db->dbprefix('personal_items') . ".item_number = '" . $search . "' and " . $this->db->dbprefix('personal_items') . ".deleted=0");
        $this->db->where($this->db->dbprefix('personal_items') . ".owner_payment_id is not null");

        $result = $this->db->get();
        if (is_object($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

}

?>
