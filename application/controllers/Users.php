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
            $person_id = $this->Employee->get_last_inserted();
            $this->send_welcome_message($person_id);
            $data['server_message'] = '<div class="alert alert-success"><strong>' . lang('common_account_created_successfully') . '</strong></div>';
            $this->load->view('login/login', $data);
            
        } else {
            $data['server_message'] = '<div class="alert alert-danger"><strong>' . lang('common_account_created_failed') . '</strong></div>';
            $this->load->view('users/form', $data);
        }
    }
    
    function send_welcome_message($person_id) {
        $employee_info = $this->Employee->get_info($person_id);
        if ($employee_info->person_id == "") {
            echo 'Invalid account';
            exit();
        }
        
        require_once 'PHPMailer.php';

//Create a new PHPMailer instance
        $mail = new PHPMailer;

//Tell PHPMailer to use SMTP
        $mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
        $mail->SMTPDebug = 2;

//Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        $mail->SMTPDebug = 0;

//Whether to use SMTP authentication
        $mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "ncstkms@gmail.com";

//Password to use for SMTP authentication
        $mail->Password = "Paul12&&";

//Set who the message is to be sent from
        $mail->setFrom('ncstkms@gmail.com', 'BAZA App');


//Set who the message is to be sent to
        $mail->addAddress($employee_info->email, '');

//Set the subject line
        $mail->Subject = 'Welcome to BAZA App!';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
        $mail->msgHTML('Dear ' . $employee_info->first_name . ', <br /><br />
Welcome to Baza app! <br /><br /> '.lang('common_account_created_successfully').' <br /><br />

BAZA Ltd');



//send the message, check for errors
        if (!$mail->send()) {

            echo "Mailer Error: " . $mail->ErrorInfo;
        } 
    }

}

?>