<?php

class Message extends CI_Model {

    function save_message($data) {
        $message_data = array(
            'message_title' => $data['message_title'],
            'message' => $data['message'],
            'created_at' => date('Y-m-d H:i:s'),
            'sender_id' => $this->Employee->get_logged_in_employee_info()->person_id,
        );

        if ($this->db->insert('messages', $message_data)) {
            $message_id = $this->db->insert_id();


            if ($data['all_employees'] == "all") {

                if ($data["all_locations"] == "all") {
                    $employee_ids = array();

                    foreach ($this->Location->get_all()->result() as $location) {
                        $location_ids[] = $location->location_id;
                    }

                    $employee_ids = $this->Employee->get_multiple_locations_employees($location_ids)->result_array();
                } else {
                    $employee_ids = $this->Employee->get_multiple_locations_employees($data['locations'])->result_array();
                }

                //Prepare the employees ids format 
                $person_ids = array();
                foreach ($employee_ids as $value) {

                    if ($this->Employee->get_logged_in_employee_info()->person_id != $value['employee_id']) {
                        $message_receiver = array(
                            'message_id' => $message_id,
                            'receiver_id' => $value['employee_id'],
                        );

                        $this->db->insert('message_receiver', $message_receiver);
                    }
                }

                return true;
            } else {
                if (empty($data["employees"])) {
                    return false;
                }
                foreach ($data["employees"] as $employee_id) {

                    if ($this->Employee->get_logged_in_employee_info()->person_id != $employee_id) {

                        $message_receiver = array(
                            'message_id' => $message_id,
                            'receiver_id' => $employee_id,
                        );

                        $this->db->insert('message_receiver', $message_receiver);
                    }
                }

                return true;
            }

            return false;
        }
    }

    function get_messages($limit = 20, $offset = 0) {

        $logged_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        $this->db->from('messages');
        $this->db->join('message_receiver', 'messages.id=message_receiver.message_id');
        $this->db->where('receiver_id', $logged_employee_id);
        $this->db->where('messages.deleted', 0);
        $this->db->order_by("created_at", "desc");
        $this->db->limit($limit);
        $this->db->offset($offset);
        $query = $this->db->get();

        return $query->result_array();
    }

    function get_info($message_id) {

        $this->db->from('messages');
        $this->db->where('messages.deleted', 0);
        $this->db->where('messages.id', $message_id);
        $query = $this->db->get();
        $this->read_message($message_id);

        return $query->result_array();
    }

    function get_messages_count() {
        $logged_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        $this->db->from('messages');
        $this->db->join('message_receiver', 'messages.id=message_receiver.message_id');
        $this->db->where('receiver_id', $logged_employee_id);
        $this->db->where('messages.deleted', 0);

        return $this->db->count_all_results();
    }

    function get_sent_messages($limit = 20, $offset = 0) {

        $logged_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $this->db->select('messages.*, GROUP_CONCAT(' . $this->db->dbprefix('people') . '.first_name, " ",' . $this->db->dbprefix('people') . '.last_name SEPARATOR ", ") as sent_to', false);
        $this->db->from('messages');
        $this->db->join('people', 'people.person_id = sender_id');
        $this->db->where('sender_id', $logged_employee_id);
        $this->db->where('messages.deleted', 0);
        $this->db->order_by("created_at", "desc");
        $this->db->group_by('messages.id');
        $this->db->limit($limit);
        $this->db->offset($offset);

        $query = $this->db->get();
        return $query->result_array();
    }

    function get_sent_messages_count() {

        $logged_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $this->db->from('messages');
        $this->db->where('sender_id', $logged_employee_id);
        $this->db->where('messages.deleted', 0);

        return $this->db->count_all_results();
    }

    function get_unread_messages_count($limit = 20, $offset = 0) {
        $logged_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $this->db->from('message_receiver');
        $this->db->join('messages', 'messages.id=message_receiver.message_id');
        $this->db->where('receiver_id', $logged_employee_id);
        $this->db->where('message_read', 0);
        $this->db->where('deleted', 0);
        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->count_all_results();
    }

    function read_message($message_id) {
        $logged_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $this->db->where('receiver_id', $logged_employee_id);
        $this->db->where('message_id', $message_id);
        return $this->db->update('message_receiver', array('message_read' => 1));
    }

    function delete_message($message_id) {
        $this->db->where('id', $message_id);
        return $this->db->update('messages', array('deleted' => 1));
    }

    function can_read_message($message_id, $sent_message = 0) {
        $logged_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        if ($sent_message) {
            $this->db->from('messages');
            $this->db->where('id', $message_id);
            $this->db->where('sender_id', $logged_employee_id);
            $this->db->where('messages.deleted', 0);
        } else {
            $this->db->from('messages');
            $this->db->join('message_receiver', 'messages.id=message_receiver.message_id');
            $this->db->where('receiver_id', $logged_employee_id);
            $this->db->where('message_id', $message_id);
            $this->db->where('deleted', 0);
        }
        $query = $this->db->get();

        return ($query->num_rows() >= 1);
    }

    function send_notification($personal_item, $type = 're-registration', $found_item_id = 0) {

        //get the list of the previously register
        $previous_registers = $this->Personal_item->get_previous_register($personal_item->item_number, $personal_item->category_id, $personal_item->item_id);
        $employees = array();
        if (count($previous_registers) > 0) {
            foreach ($previous_registers->result() as $registered_item) {
                array_push($employees, $this->Employee->get_person_id_from_employee_id($registered_item->created_by_id));
            }
        }
        if (count($employees) > 0) {

            if ($type == 're-registration') {
                //Create message
                $message_data = array(
                    'message_title' => 'Your Item registered by someone else',
                    'message' => 'Dear Baza User, <br />This is to inform you that the Item <strong>' . $personal_item->name . '</strong> with Serial number <strong>' . $personal_item->item_number  . '</strong> has been registered by someone else in the system.<br />Please contact Baza support for more information. <br /><br />Baza Technical Team.',
                    'employees' => $employees,
                );
            } else {
                $found_item = $this->Item->get_info($found_item_id);
                //Create message
                $message_data = array(
                    'message_title' => 'Your registered item is found',
                    'message' => 'Dear Baza User, <br />This is to inform you that the Item <strong>' . $personal_item->name . '</strong> with Serial number <strong>' . $personal_item->item_number . '</strong> has been found.<br /><a href="'. site_url('welcome/item_details') . '/' . $found_item->item_id.'">Click here to claim it</a>. <br /><br />Baza Technical Team.',
                    'employees' => $employees,
                );
            }


            $this->Message->save_message($message_data);
        }
    }

}

?>
