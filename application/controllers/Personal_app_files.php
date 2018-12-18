<?php

class Personal_app_files extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function view($file_id) {
        //Don't allow images to cause hangups with session
        session_write_close();
        $this->load->model('Personal_appfile');
        $file = $this->Personal_appfile->get($file_id);
        $this->load->helper('file');
        header("Content-type: " . get_mime_by_extension($file->file_name));
        echo $file->file_data;
    }
    
    function file_name($file_id) {
        //Don't allow images to cause hangups with session
        session_write_close();
        $this->load->model('Personal_appfile');
        $file = $this->Personal_appfile->get($file_id);
        $this->load->helper('file');
        echo $file->file_name;
    }

    function download($file_id) {
        session_write_close();
        $this->load->model('Personal_appfile');
        $file = $this->Personal_appfile->get($file_id);
        $this->load->helper('file');
        header("Content-Description: File Transfer");
        header("Content-Type: ".get_mime_by_extension($file->file_name));
        header('Content-Disposition: attachment; filename="' . $file->file_name . '"');

        echo $file->file_data;
        exit();
    }

}

?>