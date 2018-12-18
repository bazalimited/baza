<?php

class Users extends MY_Controller {

    function __construct() {

        parent::__construct();
        $this->lang->load('employees');
        $this->lang->load('module');
        $this->lang->load('login');
    }

    function index() {
        $data = array();
        $this->load->view('welcome/index', $data);
    }

    function create_account($employee_id = -1, $redirect_code = 0) {
        $this->load->model('Module_action');
        $data['redirect_code'] = $redirect_code;
        $this->load->view("users/form", $data);
    }

    function save_user($employee_id = -1) {
        $person_data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'phone_number' => $this->input->post('phone_number'),
            'address_1' => $this->input->post('address_1'),
            'address_2' => $this->input->post('address_2'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'zip' => $this->input->post('zip'),
            'country' => $this->input->post('country'),
            'comments' => $this->input->post('comments')
        );

        //Password has been changed OR first time password set
        if ($this->input->post('password') != '') {
            $employee_data = array(
                'username' => $this->input->post('phone_number'),
                'password' => md5($this->input->post('password')),
                'inactive' => $this->input->post('inactive') && $employee_id != 1 ? 1 : 0,
                'reason_inactive' => $this->input->post('reason_inactive') ? $this->input->post('reason_inactive') : NULL,
                'hire_date' => $this->input->post('hire_date') ? date('Y-m-d', strtotime($this->input->post('hire_date'))) : NULL,
                'employee_number' => $this->input->post('employee_number') ? $this->input->post('employee_number') : NULL,
                'birthday' => $this->input->post('birthday') ? date('Y-m-d', strtotime($this->input->post('birthday'))) : NULL,
                'termination_date' => $this->input->post('termination_date') ? date('Y-m-d', strtotime($this->input->post('termination_date'))) : NULL,
                'force_password_change' => $this->input->post('force_password_change') ? 1 : 0,
                'is_employee' => 0, // for public users
            );
        } else { //Password not changed
            $employee_data = array(
                'username' => $this->input->post('phone_number'),
                'inactive' => $this->input->post('inactive') && $employee_id != 1 ? 1 : 0,
                'reason_inactive' => $this->input->post('reason_inactive') ? $this->input->post('reason_inactive') : NULL,
                'hire_date' => $this->input->post('hire_date') ? date('Y-m-d', strtotime($this->input->post('hire_date'))) : NULL,
                'employee_number' => $this->input->post('employee_number') ? $this->input->post('employee_number') : NULL,
                'birthday' => $this->input->post('birthday') ? date('Y-m-d', strtotime($this->input->post('birthday'))) : NULL,
                'termination_date' => $this->input->post('termination_date') ? date('Y-m-d', strtotime($this->input->post('termination_date'))) : NULL,
                'force_password_change' => $this->input->post('force_password_change') ? 1 : 0,
                'is_employee' => 0, // for public users
            );
        }
        $permission_data = array();
        $permission_action_data = array();
        $location_data = array('1');


        $this->load->helper('directory');

        $valid_languages = str_replace(DIRECTORY_SEPARATOR, '', directory_map(APPPATH . 'language/', 1));
        $employee_data = array_merge($employee_data, array('language' => in_array($this->input->post('language'), $valid_languages) ? $this->input->post('language') : 'english'));
        $save_employee = $this->Employee->save_employee($person_data, $employee_data, $permission_data, $permission_action_data, $location_data, $employee_id);
       
        if ($save_employee) {
            $data['server_message'] = '<div class="alert alert-success"><strong>' . lang('common_account_created_successfully') . '</strong></div>';
            $this->load->view('login/login', $data);
            
        } else {
            $data['server_message'] = '<div class="alert alert-danger"><strong>' . lang('common_account_created_failed') . '</strong></div>';
            $this->load->view('users/form', $data);
        }
    }

}

?>