<?php

require_once ("Secure_area.php");

class Found extends Secure_area {

    function __construct() {
        parent::__construct();
        $this->lang->load('items');
        $this->lang->load('items');
        $this->lang->load('module');
        $this->load->model('Personal_item');
        $this->load->model('Category');
    }

    //change text to check line endings
    //new line endings

    function item_details($item_id) {
        $this->lang->load('items');
        $this->load->model('Category');

        $this->Payment->set_item_payment_check($item_id, 1); // 1 for found item
        //first chack the status
        $data['is_item_payment_requested'] = $this->Item->found_item_paid($item_id);
        $data['is_not_employee'] = $this->Employee->is_not_employee($this->Employee->get_logged_in_employee_info()->id);

        $data['item_info'] = $this->Item->get_info($item_id);
        $data['item_location_name'] = $this->Location->get_info($this->Item->get_info($item_id)->item_location_id)->name;

        $data['category'] = $this->Category->get_info($data['item_info']->category_id)->name;

        $this->load->view("found/item_details", $data);
    }

    function confirm_payment($item_id = null, $payment_method = 0) {
        $this->lang->load('items');
        $this->load->model('Category');
        $this->Payment->set_item_payment_check($item_id, 1); // 1 for found item
        $data['item_info'] = $this->Item->get_info($item_id);
        $data['item_location_name'] = $this->Location->get_info($this->Item->get_info($item_id)->item_location_id)->name;
        if (!$this->Item->found_item_paid($item_id)) {
            echo 'Item already Claimed';
            exit();
        }
        if (!$this->Employee->is_not_employee($this->Employee->get_logged_in_employee_info()->id)) {
            echo 'Access denied';
            exit();
        }

        $data['category'] = $this->Category->get_info($data['item_info']->category_id)->name;
        $data['category_amount'] = $this->Category->get_info($data['item_info']->category_id)->owner_payment_amount;
        $data['phone_number'] = $this->Person->get_info($this->Employee->get_logged_in_employee_info()->person_id)->phone_number;

        $this->load->view("found/confirm_payment", $data);
    }

    function payment_confirmation() {
        if (!$this->Item->found_item_paid($this->input->post('item_id'))) {
            echo 'Item already Claimed';
            exit();
        }
        if (!$this->Employee->is_not_employee($this->Employee->get_logged_in_employee_info()->id)) {
            echo 'Access denied';
            exit();
        }
        $payment_data = array(
            'item_id' => $this->input->post('item_id'),
            'item_type' => 1, // found item
            'agent_id' => 0,
            'payment_type' => 2, // 2 for Owner payment
            'payment_method_id' => 2, // 2 is for MoMo
            'amount' => $this->input->post('amount'),
            'payed_by_id' => $this->Employee->get_logged_in_employee_info()->id,
            'transaction_id' => uniqid(),
        );
        $phone_number = $this->input->post('phone_number');
        $amount = $this->input->post('amount');
        $payment_id = $this->Payment->save($payment_data);
        if ($payment_id > 0) {
            $payment = $this->Payment->get_info($payment_id);
            //request the Cash peyment
            if ($this->Payment->request_mobile_payment_approval($amount, $phone_number, 'Item_registration', $payment->transaction_id)) {
                $message = '<div class="alert alert-success"><strong>Payment successfully requested. Please confirm the payment from your Phone</strong></div>';
            } else {
                $message = '<div class="alert alert-danger"><strong>Payment request failed. Make sure that you have enough fund on your account and try again</strong></div>';
            }
            $this->session->set_userdata("payment_message", $message);
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_userdata("payment_message", '<div class="alert alert-danger"><strong>Payment request failed. You can contact BAZA Ltd for help</strong></div>');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

}

?>
