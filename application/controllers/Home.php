<?php

require_once ("Secure_area.php");

class Home extends Secure_area {

    function __construct() {
        parent::__construct();
        $this->load->helper('report');
        $this->lang->load('module');
        $this->lang->load('home');
        $this->load->model('Item');
        $this->load->model('Item_kit');
        $this->load->model('Supplier');
        $this->load->model('Customer');
        $this->load->model('Employee');
        $this->load->model('Giftcard');
        $this->load->model('Sale');
    }

    function index($choose_location = 0) {

        if (!$choose_location && $this->config->item('timeclock') && !$this->Employee->is_clocked_in()) {
            redirect('timeclocks');
        }

        $data['page_title'] = 'Dashboard';
        $data['choose_location'] = $choose_location;

        $data['total_items'] = $this->Item->count_all();
        $data['total_item_kits'] = $this->Item_kit->count_all();
        $data['total_suppliers'] = $this->Supplier->count_all();
        $data['total_customers'] = $this->Customer->count_all();
        $data['total_employees'] = $this->Employee->count_all();
        $data['total_locations'] = $this->Location->count_all();
        $data['total_giftcards'] = $this->Giftcard->count_all();
        $data['total_sales'] = $this->Sale->count_all();
        $data['total_receivings'] = $this->Receiving->count_all();
        $current_location = $this->Location->get_info($this->Employee->get_logged_in_employee_current_location_id());
        $data['message'] = "";

        if (!$this->config->item('hide_dashboard_statistics')) {
            $data['month_sale'] = $this->sales_widget();
        }

        $this->load->helper('demo');
        $data['can_show_mercury_activate'] = (!is_on_demo_host() && !$this->config->item('mercury_activate_seen')) && !$this->Location->get_info_for_key('enable_credit_card_processing');

        if ($this->Employee->is_not_employee($this->Employee->get_logged_in_employee_info()->id)) {
            $data['user_items_count'] = $this->Personal_item->count_all_by_user($this->Employee->get_logged_in_employee_info()->id);
            $data['pending_payement_user_items_count'] = $this->Personal_item->count_unpaid_by_user($this->Employee->get_logged_in_employee_info()->id);
            $data['found_user_items_count'] = $this->Personal_item->count_found_by_user($this->Employee->get_logged_in_employee_info()->id);
            $data['found_count'] = $this->Personal_item->count_found_by_user($this->Employee->get_logged_in_employee_info()->id);
            $data['pending_items_count'] = $this->Personal_item->count_unpaid_by_user($this->Employee->get_logged_in_employee_info()->id);
            $data['registered_items_count'] = $this->Personal_item->count_paid_by_user($this->Employee->get_logged_in_employee_info()->id);
            $this->load->view("users/home", $data);
        } else {
            $this->load->view("home", $data);
        }
    }

    function dismiss_mercury_message() {
        $this->Appconfig->mark_mercury_activate(true);
    }

    function logout() {
        $this->Employee->logout();
    }

    function set_employee_current_location_id() {
        $this->Employee->set_employee_current_location_id($this->input->post('employee_current_location_id'));

        //Clear out logged in register when we switch locations
        $this->Employee->set_employee_current_register_id(false);
    }

    function get_employee_current_location_id() {

        $current_location = $this->Location->get_info($this->Employee->get_logged_in_employee_current_location_id());

        echo $current_location->current_announcement;
    }

    function keep_alive() {
        //Set keep alive session to prevent logging out
        $this->session->set_userdata("keep_alive", time());
        echo $this->session->userdata('keep_alive');
    }

    function set_fullscreen($on = 0) {
        $this->session->set_userdata("fullscreen", $on);
    }

    function set_fullscreen_customer_display($on = 0) {
        $this->session->set_userdata("fullscreen_customer_display", $on);
    }

    function view_item_modal($item_id) {
        $this->lang->load('items');
        $this->lang->load('receivings');
        $this->load->model('Tier');
        $this->load->model('Category');
        $this->load->model('Tag');
        $this->load->model('Item_location');
        $this->load->model('Item_taxes_finder');
        $this->load->model('Item_location_taxes');
        $this->load->model('Receiving');
        $this->load->model('Item_taxes');

        $data['item_info'] = $this->Item->get_info($item_id);
        $data['item_location_name'] = $this->Location->get_info($this->Item->get_info($item_id)->item_location_id)->name;
        $data['tier_prices'] = array();
        foreach ($this->Tier->get_all()->result() as $tier) {
            $tier_id = $tier->id;
            $tier_price = $this->Item->get_tier_price_row($tier_id, $item_id);

            if ($tier_price) {
                $value = $tier_price->unit_price !== NULL ? to_currency($tier_price->unit_price) : $tier_price->percent_off . '%';
                $data['tier_prices'][] = array('name' => $tier->name, 'value' => $value);
            }
        }
        $file_name = '';
        if ($this->Item->is_collected($item_id)) {
            //Get who collected it
            $collector_person_id = $this->Item_collection->get_customer($item_id);
            $collector_info = $this->Customer->get_info($collector_person_id);
            $file_name = $this->Appfile->get_file_name($collector_info->image_id);
        }

        $data['category'] = $this->Category->get_info($data['item_info']->category_id)->name;
        $data['item_location_info'] = $this->Item_location->get_info($item_id);
        $data['item_tax_info'] = $this->Item_taxes_finder->get_info($item_id);
        $data['reorder_level'] = ($data['item_location_info'] && $data['item_location_info']->reorder_level) ? $data['item_location_info']->reorder_level : $data['item_info']->reorder_level;

        if ($supplier_id = $this->Item->get_info($item_id)->supplier_id) {
            $supplier = $this->Supplier->get_info($supplier_id);
            $data['supplier'] = $supplier->company_name . ' (' . $supplier->first_name . ' ' . $supplier->last_name . ')';
        }

        if ($this->Item->is_collected($item_id)) {
            $item_payment = $this->Payment->get_payment_by_item_id($item_id, 1); // 1 for found items
            $collector_info = $this->Employee->get_info($this->Employee->get_person_id_from_employee_id($item_payment->payed_by_id));
            $item_collection = $this->Item_collection->get_info($item_id);
            $file_name = $this->Appfile->get_file_name($item_collection->consent_form_id);
        }

        $data['file_name'] = $file_name;
        $data['collector_info'] = $collector_info;
        $data['logged_user_id'] = $this->Employee->get_logged_in_employee_info()->person_id;
        $data['suspended_receivings'] = $this->Receiving->get_suspended_receivings_for_item($item_id);
        $data['file_name'] = $file_name;
        $data['item_collection'] = $item_collection;
        $data['collector_info'] = $collector_info;

        $this->load->view("items/items_modal", $data);
    }

    function pay_agent_modal($item_id) {
        $selected_item = $this->Item->get_info($item_id);
        $data['item_info'] = $this->Item->get_info($item_id);
        $item_category = $this->Category->get_info($this->Item->get_info($item_id)->category_id);
        $agent = $this->Employee->get_info($selected_item->created_by_id);
        $amount = $item_category->agent_payment_amount;
        $data = array();
        $data['item_amount'] = $amount;
        $data['agent_info'] = $this->Person->get_info($agent->person_id);
        $data['created_by_id'] = $this->Person->get_info($agent->person_id);
        $data['item'] = $selected_item;
        $this->load->view("payments/pay_agent_modal", $data);
    }

    function owner_payment_modal($item_id) {
        $selected_item = $this->Item->get_info($item_id);
        $data['item_info'] = $this->Item->get_info($item_id);
        $item_category = $this->Category->get_info($this->Item->get_info($item_id)->category_id);
        $agent = $this->Person->get_info($selected_item->owner_id);
        $amount = $item_category->owner_payment_amount;
        $data = array();
        $data['item_amount'] = $amount;
        $data['agent'] = $this->Person->get_info($agent->person_id);
        $data['item'] = $selected_item;
        $this->load->view("payments/owner_payment_modal", $data);
    }

    // Function to show the modal window when clicked on kit name
    function view_item_kit_modal($item_kit_id) {
        $this->lang->load('item_kits');
        $this->lang->load('items');
        $this->lang->load('receivings');
        $this->load->model('Item');
        $this->load->model('Item_kit');
        $this->load->model('Item_kit_items');
        $this->load->model('Tier');
        $this->load->model('Category');
        $this->load->model('Tag');
        $this->load->model('Item_kit_location');
        $this->load->model('Item_kit_taxes_finder');
        $this->load->model('Item_kit_location_taxes');
        $this->load->model('Receiving');
        $this->load->model('Item_kit_taxes');

        // Fetching Kit information using kit_id
        $data['item_kit_info'] = $this->Item_kit->get_info($item_kit_id);

        $tier_prices = $this->Item->get_all_tiers_prices();

        $data['tier_prices'] = array();
        foreach ($this->Tier->get_all()->result() as $tier) {
            $tier_id = $tier->id;
            $tier_price = $this->Item_kit->get_tier_price_row($tier_id, $item_kit_id);

            if ($tier_price) {
                $value = $tier_price->unit_price !== NULL ? to_currency($tier_price->unit_price) : $tier_price->percent_off . '%';
                $data['tier_prices'][] = array('name' => $tier->name, 'value' => $value);
            }
        }

        $data['category'] = $this->Category->get_info($data['item_kit_info']->category_id)->name;

        $this->load->view("item_kits/items_modal", $data);
    }

    function sales_widget($type = 'monthly') {
        $day = array();
        $count = array();

        if ($type == 'monthly') {
            $start_date = date('Y-m-d', mktime(0, 0, 0, date("m"), 1, date("Y"))) . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $current_week = strtotime("-0 week +1 day");
            $current_start_week = strtotime("last monday midnight", $current_week);
            $current_end_week = strtotime("next sunday", $current_start_week);

            $start_date = date("Y-m-d", $current_start_week) . ' 00:00:00';
            $end_date = date("Y-m-d", $current_end_week) . ' 23:59:59';
        }

        $return = $this->Sale->get_sales_per_day_for_range($start_date, $end_date);

        foreach ($return as $key => $value) {
            if ($type == 'monthly') {
                $day[] = date('d', strtotime($value['sale_date']));
            } else {
                $day[] = lang('common_' . strtolower(date('l', strtotime($value['sale_date']))));
            }
            $count[] = $value['count'];
        }


        if (empty($return)) {
            $day = array(0);
            $count = array(0);
            $data['message'] = lang('common_not_found');
        }
        $data['day'] = json_encode($day);
        $data['count'] = json_encode($count);

        if ($this->input->is_ajax_request()) {
            if (empty($return)) {
                echo json_encode(array('message' => lang('common_not_found')));
                die();
            }
            echo json_encode(array('day' => $day, 'count' => $count));
            die();
        }
        return $data;
    }

    function enable_test_mode() {
        $this->load->helper('demo');
        if (!is_on_demo_host()) {
            $this->Appconfig->save('test_mode', '1');
        }
        redirect('home');
    }

    function disable_test_mode() {
        $this->load->helper('demo');
        if (!is_on_demo_host()) {
            $this->Appconfig->save('test_mode', '0');
        }
        redirect('home');
    }

    function dismiss_test_mode() {
        $this->Appconfig->save('hide_test_mode_home', '1');
    }

}

?>