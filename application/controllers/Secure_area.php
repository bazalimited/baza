<?php

class Secure_area extends MY_Controller {

    var $module_id;

    /*
      Controllers that are considered secure extend Secure_area, optionally a $module_id can
      be set to also check if a user can access a particular module in the system.
     */

    function __construct($module_id = null) {
        parent::__construct();
        $this->module_id = $module_id;
        $this->load->model('Employee');
        $this->load->model('Location');
        if (!$this->Employee->is_logged_in()) {
            redirect('login');
        }

        if (!$this->Employee->has_module_permission($this->module_id, $this->Employee->get_logged_in_employee_info()->person_id)) {
            redirect('no_access/' . $this->module_id);
        }

        //load up global data
        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $data['allowed_modules'] = $this->Module->get_allowed_modules($logged_in_employee_info->person_id);
        $data['user_info'] = $logged_in_employee_info;
        $data['new_message_count'] = $this->Employee->get_unread_messages_count();
        ;

        $search = $this->input->post('search');
        $category_id = $this->input->post('category_id');
        if (isset($search) && $search != '') {
            $data['user_items_count'] = $this->Personal_item->count_all_by_user($this->Employee->get_logged_in_employee_info()->id, $search, $category_id);
        } else {
            $data['user_items_count'] = $this->Personal_item->count_all_by_user($this->Employee->get_logged_in_employee_info()->id);
        }
        $data['pending_items_count'] = $this->Personal_item->count_unpaid_by_user($this->Employee->get_logged_in_employee_info()->id);
        $data['registered_items_count'] = $this->Personal_item->count_paid_by_user($this->Employee->get_logged_in_employee_info()->id);
        $registered_found_count = $this->Personal_item->count_registered_found_by_user($this->Employee->get_logged_in_employee_info()->id);
        $found_count = $this->Personal_item->count_found_by_user($this->Employee->get_logged_in_employee_info()->id);

        $data['total_found_items'] = intval($found_count) + intval($registered_found_count);

        $locations_list = $this->Location->get_all();

        $authenticated_locations = $this->Employee->get_authenticated_location_ids($logged_in_employee_info->person_id);
        $locations = array();
        $total_locations_in_system = 0;
        foreach ($locations_list->result() as $row) {
            if (in_array($row->location_id, $authenticated_locations)) {
                $locations[$row->location_id] = $row->name;
            }

            $total_locations_in_system++;
        }

        $data['total_locations_in_system'] = $total_locations_in_system;
        $data['authenticated_locations'] = $locations;

        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $loc_info = $this->Location->get_info($location_id);

        $data['current_logged_in_location_id'] = $location_id;
        $data['current_employee_location_info'] = $loc_info;
        $data['location_color'] = $loc_info->color;
        $this->load->vars($data);
    }

    function check_action_permission($action_id) {
        if (!$this->Employee->has_module_action_permission($this->module_id, $action_id, $this->Employee->get_logged_in_employee_info()->person_id)) {
            redirect('no_access/' . $this->module_id);
        }
    }

}

?>