<?php

class Ajax extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function category_name_exists() {

        $category_name = $this->input->post('category_name');
        if ($this->Category->category_name_exists($category_name)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function check_username() {
        $usename = $this->input->post('username');

        if ($this->Employee->user_username_exists($usename)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function item_is_registered() {
        $sn = $this->input->post('sn');
        $user_id = $this->input->post('user_id');

        if ($this->Personal_item->item_registed($sn, $user_id)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function get_category_fields() {

        $category_id = $this->input->post('category_id');
        $selected_category = $this->Category->get_info($category_id);
        if ($selected_category->has_identification == 0) {
            echo '';
        } else {
            if ($selected_category->record_sn == 1) {
                echo "<div class='form-group'>
                    " . form_label(lang('common_item_number_expanded') . ' :', 'item_number', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label wide')) . "
                    <div class='col-sm-9 col-md-9 col-lg-10 validation'>"
                . form_input(array(
                    'name' => 'item_number',
                    'id' => 'item_number',
                    'class' => 'form-control form-inps')
                ) . "</div>
                <span class='error_message' style='display: none;'></span></div>";
            } else {
                echo "<div class='form-group'>
                    " . form_label(lang('common_item_name_on_card') . ' :', 'name_on_card', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label wide')) . "
                    <div class='col-sm-9 col-md-9 col-lg-10 validation'>"
                . form_input(array(
                    'name' => 'name_on_card',
                    'id' => 'name_on_card',
                    'class' => 'form-control form-inps')
                ) . "</div>
                <span class='error_message' style='display: none;'></span></div>";
            }
        }
    }

    function province_districts() {
        $this->load->helper('demo');
        $province_id = $this->input->post('province_id');
        $districts = $this->District->get_districts_by_province($province_id);
        $response = '<select name="district_id" class="form-control" id="district_id" onchange="return search_sectors(this.value)">';
        if (count($districts) > 0) {
            foreach ($districts as $district) {
                $response .= '<option value="' . $district->id . '">' . $district->name . '</option>';
            }
        }
        $response .= '</select>';
        echo $response;
        return;
    }

    function district_sectors() {
        $this->load->helper('demo');
        $sector_id = $this->input->post('sector_id');
        ;
        $sectors = $this->Sector->get_sectors_by_district($sector_id);
        $response = '<select name="sector_id" class="form-control" id="sector_id">';
        if (count($sectors) > 0) {
            foreach ($sectors as $sector) {
                $response .= '<option value="' . $sector->id . '">' . $sector->name . '</option>';
            }
        }
        $response .= '</select>';
        echo $response;
        return;
    }

    function district_sectors1() {
        $this->check_action_permission('add_update');
        $location_info = $this->Location->get_info($location_id);
        $data = array();
        $province_list = array();
        $province_list[''] = ' ';

        $provinces = $this->Province->get_all();
        if (count($provinces) > 0) {
            foreach ($provinces as $province) {
                $province_list[$province['id']] = $province['name'];
            }
        }

        $data['province_list'] = $province_list;

        $district_list = array();
        $district_list[''] = ' ';

        $districts = $this->District->get_all();
        if (count($districts) > 0) {
            foreach ($districts as $district) {
                $district_list[$district['id']] = $district['name'];
            }
        }
        $data['district_list'] = $district_list;

        $sector_list = array();
        $sector_list[''] = ' ';

        $sectors = $this->Sector->get_all();
        if (count($sectors) > 0) {
            foreach ($sectors as $sector) {
                $sector_list[$sector['id']] = $sector['name'];
            }
        }
        $data['sector_list'] = $sector_list;

        $selected_province_id = $location_info->province_id;
        $selected_district_id = $location_info->district_id;
        $selected_sector_id = $location_info->sector_id;
        $data['selected_province_id'] = $selected_province_id;
        $data['selected_district_id'] = $selected_district_id;
        $data['selected_sector_id'] = $selected_sector_id;
        $data['needs_auth'] = FALSE;

        $this->load->helper('demo');
        if (!is_on_demo_host()) {
            if (!$location_info->location_id && !$this->session->flashdata('has_location_auth')) {
                $data['needs_auth'] = TRUE;
            }
        }
        if ($this->session->flashdata('purchase_email')) {
            $data['purchase_email'] = $this->session->flashdata('purchase_email');
        } else {
            $data['purchase_email'] = '';
        }

        $data['location_info'] = $location_info;
        $data['registers'] = $this->Register->get_all($location_id);

        $data['all_timezones'] = $this->_get_timezones();
        $data['redirect'] = $redirect;

        $data['employees'] = array();
        foreach ($this->Employee->get_all()->result() as $employee) {
            $has_access = $this->Employee->is_employee_authenticated($employee->person_id, $location_id);
            $data['employees'][$employee->person_id] = array('name' => $employee->first_name . ' ' . $employee->last_name, 'has_access' => $has_access);
        }

        require_once (APPPATH . 'libraries/Mercuryemvusbprocessor.php');
        $credit_card_processor = new MercuryEMVUSBProcessor($this);
        $data['mercury_emv_param_download_init_params'] = $credit_card_processor->get_emv_param_download_params();

        $this->load->view("locations/form", $data);
    }

}

?>