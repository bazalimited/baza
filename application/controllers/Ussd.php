<?php

class Ussd extends MY_Controller {

    function __construct() {

        parent::__construct();
    }

    function index() {
        $data = file_get_contents('php://input');
        $request_json = json_decode($data, true);
        $item_collection_data = array(
            'item_id' => 1,
            'collector_id' => 1,
            'collection_comment' => $data,
            'collection_date' => '',
            'consent_form_id' => 1,
            'created_by' => 1,
        );
        $this->Item_collection->save($item_collection_data);

        $respoonse_object = '{';
        //1. Check if is an exiting session
        if ($this->Ussd_session->exists($request_json['sessionId'])) {
            // Process the user input
            $current_session = $this->Ussd_session->get_info($request_json['sessionId']);

            //check the Text Values from the session
            $session_texts = $current_session->session_text;

            switch ($session_texts) {
                case null: {
                        //save the selected Language
                        if (in_array($request_json['text'], array('1', '2', '3', '0'))) { // 3 languages and an Exit Code
                            if ($request_json['text'] == strval("0")) {
                                $respoonse_object .= '"response":"' . $this->get_language_label(1, 'exit_message') . '",';
                                $respoonse_object .= '"action":"END",';
                            } else {
                                $session_data = array('session_text' => $request_json['text']);
                                if ($this->Ussd_session->save($session_data, $request_json['sessionId'])) {

                                    //Check the user is registered
                                    if ($this->Employee->is_a_registered_user($current_session->phone_number)) {
                                        $respoonse_object .= '"response":"' . $this->get_language_label(intval($request_json['text']), 'registered_select_service') . '",';
                                    } else {
                                        $respoonse_object .= '"response":"' . $this->get_language_label(intval($request_json['text']), 'unregistered_select_service') . '",';
                                    }
                                    $respoonse_object .= '"action":"CON",';
                                } else {
                                    $respoonse_object .= '"response":"' . $this->get_language_label(1, 'system_error') . '",';
                                    $respoonse_object .= '"action":"END",';
                                }
                            }
                        } else {
                            $respoonse_object .= '"response":"' . $this->get_language_label(1, 'invalid_input') . '",';
                            $respoonse_object .= '"action":"END",';
                        }
                    }
                    break;

                case strlen($session_texts) == 1: { // The Language Exists. I am expecting the Selelected service
                        //Save the selection 
                        //save the selected Service
                        $ussd_session_text = $request_json['text'];
                        $selected_language_number = strtolower(substr(strval($session_texts), 0, 1));

                        if (in_array($ussd_session_text, array('1', '2', '3', '0'))) { //3 Options and 0 for Exit
                            if ($request_json['text'] == strval('0')) {
                                $respoonse_object .= '"response":"' . $this->get_language_label(intval($selected_language_number), 'exit_message') . '",';
                                $respoonse_object .= '"action":"END",';
                            } else {
                                $session_data = array('session_text' => $session_texts . $request_json['text']); //append the selected service on the Language which was there

                                if ($this->Ussd_session->save($session_data, $request_json['sessionId'])) {

                                    if ($ussd_session_text == strval('1') | $ussd_session_text == strval('2')) { //Registered or Found items require Serial number
                                        $respoonse_object .= '"response":"' . $this->get_language_label(intval($selected_language_number), 'enter_item_serial_number') . '",';
                                        $respoonse_object .= '"action":"CON",';
                                    }
                                    if ($ussd_session_text == strval('3')) { //Either Register or My Account
                                        if ($this->Employee->is_a_registered_user($current_session->phone_number)) {
                                            $respoonse_object .= '"response":"' . $this->get_language_label(intval($selected_language_number), 'my_account_menu') . '",';
                                            $respoonse_object .= '"action":"CON",';
                                        } else {
                                            $respoonse_object .= '"response":"' . $this->get_language_label(intval($selected_language_number), 'enter_full_name') . '",';
                                            $respoonse_object .= '"action":"CON",';
                                        }
                                    }
                                } else {
                                    $respoonse_object .= '"response":"' . $this->get_language_label(1, 'system_error') . '",';
                                    $respoonse_object .= '"action":"END",';
                                }
                            }
                        } else {
                            $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'invalid_input') . '",';
                            $respoonse_object .= '"action":"END",';
                        }
                    }
                    break;
                case strlen($session_texts) == 2: { //The Language is Selected and The service
                        $selected_service = strtolower(substr(strval($session_texts), 1, 2));
                        $ussd_session_text = $request_json['text'];
                        $selected_language_number = strtolower(substr(strval($session_texts), 0, 1));

                        switch ($selected_service) {

                            case '1': { //Checking registered Items
                                    $registered_items_count = $this->Personal_item->public_search_items_count($ussd_session_text);
                                    if (intval($registered_items_count) > 0) {
                                        $registered_items = $this->Personal_item->public_search_items($ussd_session_text);

                                        $response_text = $this->get_language_label(intval($selected_language_number), 'registered_item_found');
                                        $counter = 1;
                                        foreach ($registered_items->result() as $item) {
                                            $response_text .= $counter . '. ' . $item->item_number . ' - ' . $item->category . ' - ' . $item->name;
                                            $counter++;
                                        }
                                        $response_text .= '\n\n' . $this->get_language_label(intval($selected_language_number), 'exit_message');

                                        $respoonse_object .= '"response":"' . $response_text . '",';
                                        $respoonse_object .= '"action":"END",';
                                    } else {
                                        $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'no_registered_item_found') . strtoupper($ussd_session_text) . '",';
                                        $respoonse_object .= '"action":"END",';
                                    }
                                }
                                break;

                            case '2': { //Search in lost items
                                    $registered_items_count = $this->Item->public_ussd_search_items_count_all($ussd_session_text);
                                    if (intval($registered_items_count) > 0) {
                                        $registered_items = $this->Item->public_ussd_search_items($ussd_session_text);
                                        $item_ids = '';
                                        $response_text = $this->get_language_label(intval($selected_language_number), 'registered_item_found');
                                        $counter = 1;
                                        foreach ($registered_items->result() as $item) {
                                            $item_ids .= '-' . $item->item_id;
                                            $response_text .= $counter . '. ' . $item->item_number . ' - ' . $item->category . ' - ' . $item->name;
                                            $counter++;
                                        }
                                        $item_ids .= '-';
                                        $response_text .= '\n\n' . $this->get_language_label(intval($selected_language_number), 'exit_message');

                                        //Save the found ids on the session text
                                        $session_data = array('session_text' => $session_texts . $item_ids); //append found item ids in the session data

                                        if ($this->Ussd_session->save($session_data, $request_json['sessionId'])) {
                                            $respoonse_object .= '"response":"' . $response_text . '",';
                                            $respoonse_object .= '"action":"CON",';
                                        } else {
                                            $respoonse_object .= '"response":"' . $this->get_language_label(1, 'system_error') . '",';
                                            $respoonse_object .= '"action":"END",';
                                        }
                                    } else {
                                        $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'no_registered_item_found') . strtoupper($ussd_session_text) . '",';
                                        $respoonse_object .= '"action":"END",';
                                    }
                                }
                                break;

                            case '3': { //Account or Registration
                                    $session_data = array('session_text' => $session_texts . $request_json['text']); //append the selected service on the Language which was there
                                    if ($this->Ussd_session->save($session_data, $request_json['sessionId'])) {
                                        if ($this->Employee->is_a_registered_user($current_session->phone_number)) {
                                            //User account Menu
                                            $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'user_account_menu') . '",';
                                            $respoonse_object .= '"action":"CON",';
                                        } else {
                                            //Request the Names for registration
                                            $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'user_account_registration') . '",';
                                            $respoonse_object .= '"action":"CON",';
                                        }
                                    } else {
                                        $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'system_error') . '",';
                                        $respoonse_object .= '"action":"END",';
                                    }
                                }
                                break;
                            case '0': { //Search in lost items
                                    //Request the Names for registration
                                    $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'exit_message') . '",';
                                    $respoonse_object .= '"action":"CON",';
                                }
                                break;
                            default : {
                                    $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'invalid_input') . '",';
                                    $respoonse_object .= '"action":"CON",';
                                }
                        }
                    }
                case strlen($session_texts) == 3: { //The Language is Selected and The service
                        $selected_service = strtolower(substr(strval($session_texts), 1, 2));
                        $ussd_session_text = $request_json['text'];
                        $selected_language_number = strtolower(substr(strval($session_texts), 0, 1));

                        switch ($selected_service) {

                            case '1': { //Checking registered Items
                                    $registered_items_count = $this->Personal_item->public_search_items_count($ussd_session_text);
                                    if (intval($registered_items_count) > 0) {
                                        $registered_items = $this->Personal_item->public_search_items($ussd_session_text);

                                        $response_text = $this->get_language_label(intval($selected_language_number), 'registered_item_found');
                                        $counter = 1;
                                        foreach ($registered_items->result() as $item) {
                                            $response_text .= $counter . '. ' . $item->item_number . ' - ' . $item->category . ' - ' . $item->name;
                                            $counter++;
                                        }
                                        $response_text .= '\n\n' . $this->get_language_label(intval($selected_language_number), 'exit_message');

                                        $respoonse_object .= '"response":"' . $response_text . '",';
                                        $respoonse_object .= '"action":"END",';
                                    } else {
                                        $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'no_registered_item_found') . strtoupper($ussd_session_text) . '",';
                                        $respoonse_object .= '"action":"END",';
                                    }
                                }
                                break;

                            case '2': { //Search in lost items
                                    $registered_items_count = $this->Item->public_ussd_search_items_count_all($ussd_session_text);
                                    if (intval($registered_items_count) > 0) {
                                        $registered_items = $this->Item->public_ussd_search_items($ussd_session_text);
                                        $item_ids = '';
                                        $response_text = $this->get_language_label(intval($selected_language_number), 'registered_item_found');
                                        $counter = 1;
                                        foreach ($registered_items->result() as $item) {
                                            $item_ids .= '-' . $item->item_id;
                                            $response_text .= $counter . '. ' . $item->item_number . ' - ' . $item->category . ' - ' . $item->name;
                                            $counter++;
                                        }
                                        $item_ids .= '-';
                                        $response_text .= '\n\n' . $this->get_language_label(intval($selected_language_number), 'exit_message');

                                        //Save the found ids on the session text
                                        $session_data = array('session_text' => $session_texts . $item_ids); //append found item ids in the session data

                                        if ($this->Ussd_session->save($session_data, $request_json['sessionId'])) {
                                            $respoonse_object .= '"response":"' . $response_text . '",';
                                            $respoonse_object .= '"action":"CON",';
                                        } else {
                                            $respoonse_object .= '"response":"' . $this->get_language_label(1, 'system_error') . '",';
                                            $respoonse_object .= '"action":"END",';
                                        }
                                    } else {
                                        $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'no_registered_item_found') . strtoupper($ussd_session_text) . '",';
                                        $respoonse_object .= '"action":"END",';
                                    }
                                }
                                break;

                            case '3': { //Search in lost items
                                    if ($this->Employee->is_a_registered_user($current_session->phone_number)) {
                                        //Ask the PIN
                                        $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'user_account_menu') . '",';
                                        $respoonse_object .= '"action":"CON",';
                                    } else {
                                        //Request the Names for registration
                                        $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'user_account_registration') . '",';
                                        $respoonse_object .= '"action":"CON",';
                                    }
                                }
                                break;
                            case '0': { //Search in lost items
                                    //Request the Names for registration
                                    $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'exit_message') . '",';
                                    $respoonse_object .= '"action":"CON",';
                                }
                                break;
                            default : {
                                    $respoonse_object .= '"response":"' . $this->get_language_label($selected_language_number, 'invalid_input') . '",';
                                    $respoonse_object .= '"action":"CON",';
                                }
                        }
                    }
                    break;
                default : {
                        $respoonse_object .= '"response":"UNDER DEVELOPMENT3",';
                        $respoonse_object .= '"action":"END",';
                    }
            }
        } else {
            // Save the Session in the db
            date_default_timezone_set('Africa/Cairo');
            $ussd_session_data = array(
                'session_id' => $request_json['sessionId'],
                'phone_number' => $request_json['phoneNumber'],
                'request_time' => $request_json['requestTime']
            );
            $this->Ussd_session->save($ussd_session_data, $request_json['sessionId']);
            //send the language menu selection
            $respoonse_object .= '"response":"' . $this->get_language_label(1, 'welcome_message') . '",'; //By default the Language is ENGLISH
            $respoonse_object .= '"action":"CON",';
        }

        $respoonse_object .= '"sessionId":"' . $request_json['sessionId'] . '",';
        $respoonse_object .= '"serviceCode":"' . $request_json['serviceCode'] . '",';
        $respoonse_object .= '"phoneNumber":"' . $request_json['phoneNumber'] . '",';
        $respoonse_object .= '"text":"' . $request_json['text'] . '"';
        $respoonse_object .= '}';

        header('Content-type: application/json;charset=iso-8859-1');
        print($respoonse_object);
    }

    function get_language_label($language_number, $label_key) {
        $language_label = array(
            1 => array(
                'welcome_message' => 'Welcome to Baza App!\nSelect Language \n1. ENGLISH \n2. KINYARWANDA\n3. FRANCAIS \n\n0. Exit',
                'exit_message' => 'Thank you',
                'invalid_input' => 'Invalid input',
                'no_registered_item_found' => 'No registered item found with ',
                'registered_select_service' => 'Select service:\n1. Check registred item\n2. Search Lost\n3. My Account\n\n0. Exit',
                'unregistered_select_service' => 'Select service:\n1. Check registred item\n2. Search Lost\n3. Register\n\n0. Exit',
                'user_account_registration' => 'Please enter your full names\n\n0. Exit',
                'user_account_menu' => 'Select option:\n1. Unpaid registration\n2. My items\n3. My found items\n\n0. Exit',
                'enter_item_serial_number' => 'Please enter the Serial number of the Item:',
                'enter_full_name' => 'Enter Your Full name:',
                'my_account_menu' => 'Select action: \n1. My Items \n2. Unregister item \n3. Pay for item registration\n\n0. Exit',
                'system_error' => 'SYSTEM ERROR',
                'registered_item_found' => 'Registered item found. \n'
            ),
            2 => array(
                'welcome_message' => 'Murakaza neza Baza App!\nHitamo ururimi \n1. ENGLISH \n2. KINYARWANDA\n3. FRANCAIS \n\n0. Exit',
                'exit_message' => 'Thank you',
                'invalid_input' => 'Invalid input',
                'no_registered_item_found' => 'No registered item found with ',
                'registered_select_service' => 'Select service:\n1. Check registred item\n2. Search Lost\n3. My Account\n\n0. Exit',
                'unregistered_select_service' => 'Select service:\n1. Check registred item\n2. Search Lost\n3. Register\n\n0. Exit',
                'enter_item_serial_number' => 'Please enter the Serial number of the Item:',
                'enter_full_name' => 'Enter Your Full name:',
                'my_account_menu' => 'Select action: \n1. My Items \n2. Unregister item \n3. Pay for item registration\n\n0. Exit',
                'system_error' => 'SYSTEM ERROR'
            ),
            3 => array(
                'welcome_message' => 'Bienvenue au service Baza!\nChoisessez la langue \n1. ENGLISH \n2. KINYARWANDA\n3. FRANCAIS \n\n0. Exit',
                'exit_message' => 'Thank you',
                'invalid_input' => 'Invalid input',
                'no_registered_item_found' => 'No registered item found with ',
                'registered_select_service' => 'Select service:\n1. Check registred item\n2. Search Lost\n3. My Account\n\n0. Exit',
                'unregistered_select_service' => 'Select service:\n1. Check registred item\n2. Search Lost\n3. Register\n\n0. Exit',
                'enter_item_serial_number' => 'Please enter the Serial number of the Item:',
                'enter_full_name' => 'Enter Your Full name:',
                'my_account_menu' => 'Select action: \n1. My Items \n2. Unregister item \n3. Pay for item registration\n\n0. Exit',
                'system_error' => 'SYSTEM ERROR'
            ),
        );

        return $language_label[$language_number][$label_key];
    }

    function varDumpToString($var) {
        ob_start();
        var_dump($var);
        return ob_get_clean();
    }

    function test() {
        $sent_text = $this->varDumpToString('0');
        $item_collection_data = array(
            'item_id' => 1,
            'collector_id' => 1,
            'collection_comment' => $sent_text,
            'collection_date' => '',
            'consent_form_id' => 1,
            'created_by' => 1,
        );
        $this->Item_collection->save($item_collection_data);
        exit();

        $registered = $this->Employee->is_a_registered_user('250788683036');
        echo $this->db->last_query();
        var_dump($registered);
        exit();
//        $ussd_session_data = array(
//            'phone_number' => '0788683037',
//        );
//
//        $save = $this->Ussd_session->save($ussd_session_data, '5c1b4d6fb373c');
//        //$save = $this->Ussd_session->save($ussd_session_data);
//
//        var_dump($save);
//        exit();
        echo strtolower(substr(strval(23132), 0, 1));
        ;
        exit();
        $respoonse_object = '';
        $session_texts = '112';
        switch ($session_texts) {
            case null: {
                    //save the selected Language
                    $respoonse_object .= '"response":"Welcome to Baza App. Choose Language. \n1.ENGLISH \n2.KINYARWANDA",';
                    $respoonse_object .= '"action":"CON"';
                }
                break;

            case strlen($session_texts) == 2: {
                    //save the selected Language
                    $respoonse_object .= '"response":"Select the task",';
                    $respoonse_object .= '"action":"CON"';
                }
                break;
            default: {
                    $respoonse_object .= '"response":"NOTHING",';
                    $respoonse_object .= '"action":"CON"';
                }
                break;
        }

        echo $respoonse_object;
        exit();
    }

}

?>