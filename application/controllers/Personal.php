<?php

require_once ("Secure_area.php");

class Personal extends Secure_area {

    function __construct() {
        parent::__construct();
        $this->lang->load('items');
        $this->lang->load('module');
        $this->load->model('Personal_item');
        $this->load->model('Item');
        $this->load->model('Category');
        $this->load->model('Message');
        $this->lang->load('messages');


        // all pending payment items
        $this->Payment->sync_payment(); //Sync all payments
    }

    //change text to check line endings
    //new line endings

    function items($offset = 0) {
        $search = $this->input->post('search');
        $category_id = $this->input->post('category_id');
        if (isset($search) && $search != '') {
            $data['user_items'] = $this->Personal_item->get_all_by_user($this->Employee->get_logged_in_employee_info()->id, $search, $category_id);
            $data['user_items_count'] = $this->Personal_item->count_all_by_user($this->Employee->get_logged_in_employee_info()->id, $search, $category_id);
        } else {
            $data['user_items'] = $this->Personal_item->get_all_by_user($this->Employee->get_logged_in_employee_info()->id);
            $data['user_items_count'] = $this->Personal_item->count_all_by_user($this->Employee->get_logged_in_employee_info()->id);
        }

        $data['categories'] = $this->Personal_item->get_user_categories($this->Employee->get_logged_in_employee_info()->id);
        $this->load->view('personal/manage_items', $data);
    }

    function pending() {
        $data['pending_items'] = $this->Personal_item->get_unpaid_by_user($this->Employee->get_logged_in_employee_info()->id);
        $data['pending_items_count'] = $this->Personal_item->count_unpaid_by_user($this->Employee->get_logged_in_employee_info()->id);

        $this->load->view('personal/pending', $data);
    }

    function registered() {
        $data['registered_items'] = $this->Personal_item->get_paid_by_user($this->Employee->get_logged_in_employee_info()->id);

        $data['registered_items_count'] = $this->Personal_item->count_paid_by_user($this->Employee->get_logged_in_employee_info()->id);

        $this->load->view('personal/registered', $data);
    }

    function found() {
        $data['registered_found_items'] = $this->Personal_item->get_registered_found_by_user($this->Employee->get_logged_in_employee_info()->id);
        $data['found_items'] = $this->Personal_item->get_found_by_user($this->Employee->get_logged_in_employee_info()->id);
        $registered_found_count = $this->Personal_item->count_registered_found_by_user($this->Employee->get_logged_in_employee_info()->id);
        $found_count = $this->Personal_item->count_found_by_user($this->Employee->get_logged_in_employee_info()->id);

        $data['total_found_items'] = intval($found_count) + intval($registered_found_count);

        $this->load->view('personal/found', $data);
    }

    function recover($item_id) {
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

        $data['file_name'] = $file_name;
        $data['collector_info'] = $collector_info;
        $data['logged_user_id'] = $this->Employee->get_logged_in_employee_info()->person_id;
        $this->load->view("personal/recover", $data);
    }

    function item_details($item_id = 0, $is_bulk = 0) { // 1 for bulk payment
        $this->lang->load('items');
        $this->load->model('Category');

        $data['selected_items'] = null;
        if ($is_bulk == 1) { //selected many selected items
            $selected_item_ids = array();
            $selected_ids = $this->input->post('item_ids');
            foreach ($selected_ids as $selected_id) {
                if (isset($selected_id)) {
                    array_push($selected_item_ids, $selected_id);
                }
            }

            if (count($selected_item_ids) > 0) {
                $data['selected_items'] = $this->Personal_item->get_items_by_ids($selected_item_ids);
                $data['selected_items_count'] = count($selected_item_ids);
            }
        } else {
            $data['item_info'] = $this->Personal_item->get_info($item_id);
            $data['item_location_name'] = $this->Location->get_info($this->Item->get_info($item_id)->item_location_id)->name;
            if ($this->Personal_item->get_info($item_id)->created_by_id != $this->Employee->get_logged_in_employee_info()->id) {
                echo 'Item Access denied';
                exit();
            }
            $data['category'] = $this->Category->get_info($data['item_info']->category_id)->name;
        }
        $this->load->view("personal/item_details", $data);
    }

    function confirm_payment($item_id = 0, $payment_method = 0) {
        $this->lang->load('items');
        $this->load->model('Category');

        $selected_ids = $this->input->post('item_ids');

        if (isset($selected_ids)) {
            $ids = explode(' ', $selected_ids);
            unset($ids[(count($ids) - 1)]);
            $category_amount = 0;
            if (count($ids) > 0) {
                $data['selected_items'] = $this->Personal_item->get_items_by_ids($ids);
                $selected_items = $this->Personal_item->get_items_by_ids($ids);
                $data['selected_items_count'] = count($ids);

                if (count($selected_items) > 0) {
                    foreach ($selected_items->result() as $item) {
                        $current_item = $this->Personal_item->get_info($item->item_id);
                        $category_amount += $this->Category->get_info($current_item->category_id)->owner_payment_amount;
                    }
                }
            }
            $data['category_amount'] = $category_amount;
        } else {
            $data['item_info'] = $this->Personal_item->get_info($item_id);
            $data['item_location_name'] = $this->Location->get_info($this->Item->get_info($item_id)->item_location_id)->name;
            if ($this->Personal_item->get_info($item_id)->created_by_id != $this->Employee->get_logged_in_employee_info()->id) {
                echo 'Item Access denied';
                exit();
            }

            $data['category'] = $this->Category->get_info($data['item_info']->category_id)->name;
            $data['category_amount'] = $this->Category->get_info($data['item_info']->category_id)->owner_payment_amount;
        }
        $data['phone_number'] = $this->Person->get_info($this->Employee->get_logged_in_employee_info()->person_id)->phone_number;
        $this->load->view("personal/confirm_payment", $data);
    }

    function payment_confirmation() {
        $selected_ids = $this->input->post('item_ids');

        if (isset($selected_ids)) {
            $ids = explode(' ', $selected_ids);
            unset($ids[(count($ids) - 1)]);

            if (count($ids) > 0) {
                $data['selected_items'] = $this->Personal_item->get_items_by_ids($ids);
                $selected_items = $this->Personal_item->get_items_by_ids($ids);

                if (count($selected_items) > 0) {
                    $shared_transaction_id = uniqid();
                    $payment_id = 0;
                    $amount = 0;
                    foreach ($selected_items->result() as $item) {
                        $current_item = $this->Personal_item->get_info($item->item_id);

                        $payment_data = array(
                            'item_id' => $current_item->item_id,
                            'item_type' => 2, // Owner item
                            'agent_id' => 0,
                            'payment_type' => 2, // 2 for Owner payment
                            'payment_method_id' => 2, // 2 is for MoMo
                            'amount' => $this->Category->get_info($current_item->category_id)->owner_payment_amount,
                            'payed_by_id' => $this->Employee->get_logged_in_employee_info()->id,
                            'transaction_id' => $shared_transaction_id,
                        );

                        $phone_number = $this->input->post('phone_number');

                        if (!$this->Payment->item_payment_saved($current_item->item_id)) {
                            $payment_id = $this->Payment->save($payment_data);
                            $amount += $this->Category->get_info($current_item->category_id)->owner_payment_amount;
                        }
                    }

                    if ($payment_id > 0 && $amount > 0) {
                        //request the Cash peyment
                        $request = $this->Payment->request_mobile_payment_approval($amount, $phone_number, 'Item_registration', $shared_transaction_id);
                        if ($request == 0) {
                            $message = '<div class="alert alert-danger" style="font-size: 20px;"><strong>Failed to communicate with the payment gateway</strong></div>';
                        } else if ($request) {
                            $message = '<div class="alert alert-success" style="color: red; font-size: 20px;"><strong>Payment successfully requested. Please confirm the payment from your Phone</strong></div>';
                        } else {
                            $message = '<div class="alert alert-danger" style="font-size: 20px;"><strong>Payment request failed. Check your number, Make sure that you have enough fund on your account and try again</strong></div>';
                        }
                    } else {
                        $message = '<div class="alert alert-warning" style="font-size: 20px;"><strong>Payment aleady requested</strong></div>';
                    }
                }
            }
            $this->session->set_userdata("payment_message", $message);
            redirect('personal/pending');
        } else {
            $payment_data = array(
                'item_id' => $this->input->post('item_id'),
                'item_type' => 2, // Owner item
                'agent_id' => 0,
                'payment_type' => 2, // 2 for Owner payment
                'payment_method_id' => 2, // 2 is for MoMo
                'amount' => $this->input->post('amount'),
                'payed_by_id' => $this->Employee->get_logged_in_employee_info()->id,
                'transaction_id' => uniqid(),
            );

            $phone_number = $this->input->post('phone_number');
            $amount = $this->input->post('amount');
            if (!$this->Payment->item_payment_saved($this->input->post('item_id'))) {
                $payment_id = $this->Payment->save($payment_data);
            } else {
                $payment_id = $this->Payment->get_item_payment_id($this->input->post('item_id'), 2);
            }
            if ($payment_id > 0) {
                $payment = $this->Payment->get_info($payment_id);
                if (!in_array($payment->payment_gatway_code, array(100, 101, 102, 103))) {
                    //request the Cash peyment
                    $request = $this->Payment->request_mobile_payment_approval($amount, $phone_number, 'Item_registration', $payment->transaction_id);
                    if ($request == 0) {
                        $message = '<div class="alert alert-danger"><strong>Failed to communicate with the payment gateway</strong></div>';
                    } else if ($request) {
                        $message = '<div class="alert alert-success"><strong>Payment successfully requested. Please confirm the payment from your Phone</strong></div>';
                    } else {
                        $message = '<div class="alert alert-danger"><strong>Payment request failed. Check your number, Make sure that you have enough fund on your account and try again</strong></div>';
                    }
                } else {
                    $message = '<div class="alert alert-warning"><strong>Payment aleady requested</strong></div>';
                }
            }
            $this->session->set_userdata("payment_message", $message);
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function categories() {
        $this->check_action_permission('manage_categories');
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $categories = $this->Category->get_all_categories_and_sub_categories_as_tree($location_id);
        $data = array('category_tree' => $this->_category_tree_list_two($categories));
        $data['category_conter'] = $this->_category_tree_counter($categories);
        $this->load->view('items/categories', $data);
    }

    function save_category($category_id = FALSE) {
        $this->check_action_permission('manage_categories');

        $category_name = $this->input->post('name');
        $owner_payment_amount = $this->input->post('owner_payment_amount');
        $agent_payment_amount = $this->input->post('agent_payment_amount');
        $allow_sn_recording = $this->input->post('record_sn');
        if (isset($allow_sn_recording)) {
            $allow_sn_recording = 1;
        } else {
            $allow_sn_recording = 0;
        }
        $category_location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $hide_from_grid = 0;

        $category_data = array(
            'name' => $category_name,
            'owner_payment_amount' => $owner_payment_amount,
            'agent_payment_amount' => $agent_payment_amount,
            'record_sn' => $allow_sn_recording,
            'location_id' => $category_location_id,
            'hide_from_grid' => $hide_from_grid,
        );


        if ($this->Category->save($category_data, $category_id)) {

            redirect('items/categories');
        } else {
            $this->load->view('items/category_form', $category_data);
        }
    }

    function delete_category() {
        $this->check_action_permission('manage_categories');
        $category_id = $this->input->post('category_id');
        if ($this->Category->delete($category_id)) {
            echo json_encode(array('success' => true, 'message' => lang('items_successful_deleted')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('items_cannot_be_deleted')));
        }
    }

    function get_category_tree_list() {
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $categories = $this->Category->get_all_categories_and_sub_categories_as_tree($location_id);
        echo $this->_category_tree_list($categories);
    }

    function manage_tags() {
        $this->check_action_permission('manage_tags');
        $tags = $this->Tag->get_all();
        $data = array('tags' => $tags, 'tag_list' => $this->_tag_list());
        $this->load->view('items/tags', $data);
    }

    function save_tag($tag_id = FALSE) {
        $this->check_action_permission('manage_tags');
        $tag_name = $this->input->post('tag_name');

        if ($this->Tag->save($tag_name, $tag_id)) {
            echo json_encode(array('success' => true, 'message' => lang('items_tag_successful_adding') . ' ' . $tag_name));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('items_tag_successful_error')));
        }
    }

    function delete_tag() {
        $this->check_action_permission('manage_tags');
        $tag_id = $this->input->post('tag_id');
        if ($this->Tag->delete($tag_id)) {
            echo json_encode(array('success' => true, 'message' => lang('items_successful_deleted')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('items_cannot_be_deleted')));
        }
    }

    function tag_list() {
        echo $this->_tag_list();
    }

    function _tag_list() {
        $tags = $this->Tag->get_all();
        $return = '<ul>';
        foreach ($tags as $tag_id => $tag) {
            $return .= '<li>' . $tag['name'] .
                    '<a href="javascript:void(0);" class="edit_tag" data-name = "' . H($tag['name']) . '" data-tag_id="' . $tag_id . '">[' . lang('common_edit') . ']</a> ' .
                    '<a href="javascript:void(0);" class="delete_tag" data-tag_id="' . $tag_id . '">[' . lang('common_delete') . ']</a> ';
            $return .= '</li>';
        }
        $return .= '</ul>';

        return $return;
    }

    function _category_tree_list($tree) {
        $return = '';
        if (!is_null($tree) && count($tree) > 0) {
            $return = '<ul>';
            foreach ($tree as $node) {
                $return .= '<li>' . $node->name . ' <a href="javascript:void(0);" class="add_child_category" data-category_id="' . $node->id . '">[' . lang('items_add_child_category') . ']</a> ' .
                        '<a href="javascript:void(0);" class="edit_category" data-name = "' . H($node->name) . '" data-parent_id = "' . $node->parent_id . '" data-category_id="' . $node->id . '">[' . lang('common_edit') . ']</a> ' .
                        '<a href="javascript:void(0);" class="delete_category" data-category_id="' . $node->id . '">[' . lang('common_delete') . ']</a> ' .
                        '';
                $return .= $this->_category_tree_list($node->children);
                $return .= '</li>';
            }
            $return .= '</ul>';
        }

        return $return;
    }

    function _category_tree_list_two($tree) {
        $return = '';
        if (!is_null($tree) && count($tree) > 0) {
            $return = '<table class="table tablesorter table-hover" id="sortable_table">
                    <thead>
                        <tr>
                            <th class="header headerSortUp">' . lang('common_category_name') . '</th>
                            <th class="header headerSortUp">' . lang('common_owner_payment_amount') . '</th>
                            <th class="header headerSortUp">' . lang('common_agent_payment_amount') . '</th>
                            <th class="header headerSortUp">' . lang('allow_sn_recording') . '</th>
                            <th class="rightmost">&nbsp;</th>
                            <th class="rightmost">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>';
            $formatter = new NumberFormatter('en_GB', NumberFormatter::CURRENCY);

            foreach ($tree as $node) {
                $return .= '
                        <tr>
                            <td>' . $node->name . '</td>
                            <td>' . $formatter->formatCurrency($node->owner_payment_amount, 'FRW') . '</td>
                            <td>' . $formatter->formatCurrency($node->agent_payment_amount, 'FRW') . '</td>';
                if ($node->record_sn == 1) {
                    $return .= '<td>' . lang('common_yes') . '</td>';
                } else {
                    $return .= '<td>' . lang('common_no') . '</td>';
                }

                $return .= '<td><a href="' . site_url('items/add_category') . '/' . $node->id . '"  data-category_id="' . $node->id . '">[' . lang('common_edit') . ']</a> </td>
                            <td><a href="javascript:void(0);" class="delete_category" data-category_id="' . $node->id . '">[' . lang('common_delete') . ']</a> </td>
                        </tr>';
            }
            $return .= '</tbody></table>';
        }

        return $return;
    }

    function _category_tree_counter($tree) {
        $return = '';
        if (!is_null($tree) && count($tree) > 0) {
            $return = 0;
            foreach ($tree as $node) {
                $return++;
            }
        }

        return $return;
    }

    function sorting() {
        $this->check_action_permission('search');
        $search = $this->input->post('search') ? $this->input->post('search') : "";
        $category_id = $this->input->post('category_id');
        $fields = $this->input->post('fields') ? $this->input->post('fields') : 'all';

        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
        $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
        $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc';


        $item_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search, 'category_id' => $category_id, 'fields' => $fields);
        $this->session->set_userdata("item_search_data", $item_search_data);
        if ($search || $category_id) {
            $config['total_rows'] = $this->Item->search_count_all($search, $category_id);
            $table_data = $this->Item->search_items($search, $category_id, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc', $fields);
        } else {
            $config['total_rows'] = $this->Item->count_all();
            $table_data = $this->Item->get_all($per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        }
        $config['base_url'] = site_url('items/sorting');
        $config['per_page'] = $per_page;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_items_manage_table_data_rows($table_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    function find_item_info() {
        $item_number = $this->input->post('scan_item_number');
        echo json_encode($this->Item->find_item_info($item_number));
    }

    function item_number_exists() {
        if ($this->Item->account_number_exists($this->input->post('item_number')))
            echo 'false';
        else
            echo 'true';
    }

    function product_id_exists() {
        if ($this->Item->product_id_exists($this->input->post('product_id')))
            echo 'false';
        else
            echo 'true';
    }

    function check_duplicate() {
        echo json_encode(array('duplicate' => $this->Item->check_duplicate($this->input->post('term'))));
    }

    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $category_id = $this->input->post('category_id');
        $location_id = $this->input->post('location_id');
        $from_date = ($this->input->post('from_date') == '') ? '' : date("Y-m-d", strtotime($this->input->post('from_date')));
        $to_date = ($this->input->post('to_date') == '') ? '' : date("Y-m-d", strtotime($this->input->post('to_date')));
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
        $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
        $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc';
        $fields = $this->input->post('fields') ? $this->input->post('fields') : 'all';

        $item_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search, 'category_id' => $category_id, 'fields' => $fields, 'location_id' => $location_id, 'from_date' => $from_date, 'to_date' => $to_date);
        $this->session->set_userdata("item_search_data", $item_search_data);
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->Item->search_items($search, $category_id, $location_id, $from_date, $to_date, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc', $fields);
        $config['base_url'] = site_url('items/search');

        $config['total_rows'] = $this->Item->search_items_count_all($search, $category_id, $location_id, $from_date, $to_date, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc', $fields);


        $config['per_page'] = $per_page;

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['manage_table'] = get_items_manage_table_data_rows($search_data, $this);

        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    function suggest() {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->Item->get_manage_items_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

    function item_search() {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

    function get_info($item_id = -1) {
        echo json_encode($this->Item->get_info($item_id));
    }

    function _get_item_data($item_id) {
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $this->load->helper('report');

        $data = array();
        $data['controller_name'] = strtolower(get_class());

        $data['item_info'] = $this->Personal_item->get_info($item_id);

        $data['categories'][''] = lang('common_select_category');

        $categories = $this->Category->sort_categories_and_sub_categories($this->Category->get_all_categories_and_sub_categories(NULL, 0, $location_id));
        foreach ($categories as $key => $value) {
            $name = str_repeat('&nbsp;&nbsp;', $value['depth']) . $value['name'];
            $data['categories'][$key] = $name;
        }

        $data['tags'] = implode(',', $this->Tag->get_tags_for_item($item_id));
        $data['item_tax_info'] = $this->Item_taxes->get_info($item_id);
        $data['tiers'] = $this->Tier->get_all()->result();
        $data['locations'] = array();
        $data['location_tier_prices'] = array();
        $data['additional_item_numbers'] = $this->Additional_item_numbers->get_item_numbers($item_id);

        if ($item_id != -1) {
            $data['next_item_id'] = $this->Item->get_next_id($item_id);
            $data['prev_item_id'] = $this->Item->get_prev_id($item_id);
            ;
        }

        foreach ($this->Location->get_all()->result() as $location) {
            if ($this->Employee->is_location_authenticated($location->location_id)) {
                $data['locations'][] = $location;
                $data['location_items'][$location->location_id] = $this->Item_location->get_info($item_id, $location->location_id);
                $data['location_taxes'][$location->location_id] = $this->Item_location_taxes->get_info($item_id, $location->location_id);

                foreach ($data['tiers'] as $tier) {
                    $tier_prices = $this->Item_location->get_tier_price_row($tier->id, $data['item_info']->item_id, $location->location_id);
                    if (!empty($tier_prices)) {
                        $data['location_tier_prices'][$location->location_id][$tier->id] = $tier_prices;
                    } else {
                        $data['location_tier_prices'][$location->location_id][$tier->id] = FALSE;
                    }
                }
            }
        }


        if ($item_id == -1) {
            $suppliers = array('' => lang('common_not_set'), '-1' => lang('common_none'));
        } else {
            $suppliers = array('-1' => lang('common_none'));
        }
        foreach ($this->Supplier->get_all()->result_array() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'] . ' (' . $row['first_name'] . ' ' . $row['last_name'] . ')';
        }

        $data['tier_prices'] = array();
        $data['tier_type_options'] = array('unit_price' => lang('common_fixed_price'), 'percent_off' => lang('common_percent_off'));
        foreach ($data['tiers'] as $tier) {
            $tier_prices = $this->Item->get_tier_price_row($tier->id, $data['item_info']->item_id);

            if (!empty($tier_prices)) {
                $data['tier_prices'][$tier->id] = $tier_prices;
            } else {
                $data['tier_prices'][$tier->id] = FALSE;
            }
        }

        $data['suppliers'] = $suppliers;
        $data['selected_supplier'] = $this->Item->get_info($item_id)->supplier_id;

        $decimals = $this->Appconfig->get_raw_number_of_decimals();
        $decimals = $decimals !== NULL && $decimals != '' ? $decimals : 2;
        $data['decimals'] = $decimals;

        return $data;
    }

    function view($item_id = -1, $category_id = 0, $redirect = 0, $sale_or_receiving = 'sale') {
        $this->load->model('Item_taxes');
        $this->load->model('Personal_Item');
        $this->load->model('Item_location');
        $this->load->model('Item_location_taxes');
        $this->load->model('Supplier');
        $this->load->model('Item_taxes_finder');


        $selected_item = $this->Personal_Item->get_info($item_id);
        if (isset($selected_item) && $item_id != -1) {
            $selected_category = $this->Category->get_info($selected_item->category_id);
        }
        if ($category_id != 0) {
            $selected_category = $this->Category->get_info($category_id);
        }

        $selected_item_location = $selected_item->location_id;

        $data = $this->_get_item_data($item_id);
        $data['user_locations'] = $this->Employee->get_employee_locations();
        $data['selected_item_location'] = $selected_item_location;
        $data['redirect'] = $redirect;
        $data['sale_or_receiving'] = $sale_or_receiving;
        $data['item_id'] = $item_id;
        $data['selected_type'] = $selected_category->record_sn;
        $data['has_identification'] = ($selected_category->has_identification == 1) ? true : false;
        $data['category_id'] = $category_id;
        $data['logged_user'] = $this->Employee->get_logged_in_employee_info()->id;
        $data['page_title'] = 'Item';
        $data['selected_item_id'] = $item_id;


        $this->load->view("personal/item_form", $data);
    }

    function add_category($category_id = -1) {

        $this->check_action_permission('add_update');
        $this->load->helper('report');
        if ($category_id == -1) {
            $selected_category = array();
        } else {
            $selected_category = $this->Category->get_info($category_id);
        }
        $data = array();
        $data['slected_category'] = $selected_category;

        $this->load->view("items/category_form", $data);
    }

    function s_category($category_id = -1) {

        $this->check_action_permission('add_update');
        $this->load->helper('report');
        if ($category_id == -1) {
            $selected_category = array();
        } else {
            $selected_category = $this->Category->get_info($category_id);
        }
        $data = array();
        $data['slected_category'] = $selected_category;

        $this->load->view("items/category_form", $data);
    }

    function clone_item($item_id) {
        $this->load->model('Item_taxes');
        $this->load->model('Tier');
        $this->load->model('Item_location');
        $this->load->model('Item_location_taxes');
        $this->load->model('Supplier');
        $this->load->model('Item_taxes_finder');

        $this->check_action_permission('add_update');
        $this->load->helper('report');
        $data = $this->_get_item_data($item_id);
        $data['redirect'] = 2;
        //Unset unique identifiers
        $data['item_info']->item_number = '';
        $data['item_info']->product_id = '';
        $data['additional_item_numbers'] = array();
        $data['is_clone'] = TRUE;
        $this->load->view("items/form", $data);
    }

    function inventory($item_id = -1, $offset = 0) {
        $this->load->model('Item_location');

        $this->check_action_permission('edit_quantity');
        $data['item_info'] = $this->Item->get_info($item_id);
        $data['item_location_info'] = $this->Item_location->get_info($item_id);

        $data['customers'] = $this->Customer->get_all_customers();

        $config['base_url'] = site_url('items/inventory/' . $item_id);
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['total_rows'] = $this->Inventory->count_all($item_id);
        $config['uri_segment'] = 4;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['inventory_data'] = $this->Inventory->get_inventory_data_for_item($item_id, $config['per_page'], $offset)->result_array();

        $this->load->view("items/inventory", $data);
    }

    function generate_barcodes($item_ids) {
        $this->load->model('Item_taxes');
        $this->load->model('Item_location');
        $this->load->model('Item_location_taxes');
        $this->load->model('Item_taxes_finder');

        $this->load->helper('items');
        $data['items'] = get_items_barcode_data($item_ids);
        $data['scale'] = 1;
        $this->load->view("barcode_sheet", $data);
    }

    function generate_barcode_labels($item_ids) {
        $this->load->model('Item_taxes');
        $this->load->model('Item_location');
        $this->load->model('Item_location_taxes');
        $this->load->model('Item_taxes_finder');

        $this->load->helper('items');
        $data['items'] = get_items_barcode_data($item_ids);
        $data['scale'] = 1;
        $this->load->view("barcode_labels", $data);
    }

    function generate_barcodes_from_recv($recv_id) {
        $this->load->model('Item_taxes');
        $this->load->model('Item_location');
        $this->load->model('Item_location_taxes');
        $this->load->model('Item_taxes_finder');
        $this->load->model('Receiving');
        $item_ids = array();

        foreach ($this->Receiving->get_receiving_items($recv_id)->result() as $item) {
            for ($k = 0; $k < abs((int) $item->quantity_purchased); $k++) {
                $item_ids[] = $item->item_id;
            }
        }

        $data = array();
        $this->load->helper('items');
        $data['items'] = get_items_barcode_data(implode('~', $item_ids));
        $data['scale'] = 1;
        $data['from_recv'] = $recv_id;
        $this->load->view("barcode_sheet", $data);
    }

    function generate_barcodes_labels_from_recv($recv_id) {
        $this->load->model('Item_taxes');
        $this->load->model('Item_location');
        $this->load->model('Item_location_taxes');
        $this->load->model('Item_taxes_finder');
        $this->load->model('Receiving');

        $item_ids = array();

        foreach ($this->Receiving->get_receiving_items($recv_id)->result() as $item) {
            for ($k = 0; $k < abs((int) $item->quantity_purchased); $k++) {
                $item_ids[] = $item->item_id;
            }
        }

        $data = array();
        $this->load->helper('items');
        $data['items'] = get_items_barcode_data(implode('~', $item_ids));
        $data['scale'] = 1;
        $data['from_recv'] = $recv_id;
        $this->load->view("barcode_labels", $data);
    }

    function bulk_edit() {
        $this->load->model('Supplier');
        $this->load->model('Tier');

        $this->check_action_permission('add_update');
        $this->load->helper('report');
        $data = array();

        $suppliers = array('' => lang('common_do_nothing'), '-1' => lang('common_none'));
        foreach ($this->Supplier->get_all()->result_array() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'] . ' (' . $row['first_name'] . ' ' . $row['last_name'] . ')';
        }
        $data['suppliers'] = $suppliers;

        $data['categories'][''] = lang('common_do_nothing');

        $categories = $this->Category->sort_categories_and_sub_categories($this->Category->get_all_categories_and_sub_categories());
        foreach ($categories as $key => $value) {
            $name = str_repeat('&nbsp;&nbsp;', $value['depth']) . $value['name'];
            $data['categories'][$key] = $name;
        }

        $data['item_cost_price_choices'] = array(
            '' => lang('common_do_nothing'),
            'fixed' => lang('common_fixed_price'),
            'percent' => lang('items_increase_decrease_percent'),
        );


        $data['change_cost_price_during_sale_choices'] = array(
            '' => lang('common_do_nothing'),
            '0' => lang('common_no'),
            '1' => lang('common_yes'));

        $data['item_unit_price_choices'] = array(
            '' => lang('common_do_nothing'),
            'fixed' => lang('common_fixed_price'),
            'percent' => lang('items_increase_decrease_percent'),
        );


        $data['item_promo_price_choices'] = array(
            '' => lang('common_do_nothing'),
            'fixed' => lang('common_fixed_price'),
            'percent' => lang('items_increase_decrease_percent'),
        );

        $data['override_default_commission_choices'] = array(
            '' => lang('common_do_nothing'),
            '0' => lang('common_no'),
            '1' => lang('common_yes'));

        $data['override_default_tax_choices'] = array(
            '' => lang('common_do_nothing'),
            '0' => lang('common_no'),
            '1' => lang('common_yes'));

        $data['allow_alt_desciption_choices'] = array(
            '' => lang('common_do_nothing'),
            1 => lang('items_change_all_to_allow_alt_desc'),
            0 => lang('items_change_all_to_not_allow_allow_desc'));


        $data['serialization_choices'] = array(
            '' => lang('common_do_nothing'),
            1 => lang('items_change_all_to_serialized'),
            0 => lang('items_change_all_to_unserialized'));

        $data['tax_included_choices'] = array(
            '' => lang('common_do_nothing'),
            '0' => lang('common_no'),
            '1' => lang('common_yes'));

        $data['is_service_choices'] = array(
            '' => lang('common_do_nothing'),
            '0' => lang('common_no'),
            '1' => lang('common_yes'));


        $this->load->view("items/form_bulk", $data);
    }

    function save_item($item_id = -1) {
        $this->load->model('Item_taxes');
        $this->load->model('Item_location');
        $this->load->model('Item_location_taxes');

        $item_data = array(
            'category_id' => $this->input->post('category_id'),
            'name' => $this->input->post('name'),
            'item_number' => $this->input->post('item_number') == '' ? null : $this->input->post('item_number'),
            'name_on_card' => $this->input->post('name_on_card'),
            'description' => $this->input->post('description'),
            'created_by_id' => $this->Employee->get_logged_in_employee_info()->id,
            'updated_by_id' => ($item_id == -1) ? NULL : $this->Employee->get_logged_in_employee_info()->person_id
        );
        $created_id = $this->Personal_item->save($item_data, $item_id == -1 ? false : $item_id);

        if ($created_id > 0) { // 
            $cur_item_info = $this->Personal_item->get_info($created_id);

            //check that the item has been registered by someone else. Applicable to items with serial Number
            $item_category = $this->Category->get_info($cur_item_info->category_id);
            if ($item_category->record_sn == 1) {
                if ($this->Personal_item->item_sn_registered($cur_item_info->item_number, $cur_item_info->category_id, $cur_item_info->item_id)) {

                    $this->Message->send_notification($cur_item_info); // Send notifications to previous users who registered this item previously
                }
            }
            //Delete Image
            if ($this->input->post('del_image') && $item_id != -1) {
                if ($cur_item_info->image_id != null) {
                    $this->load->model('Personal_appfile');
                    $this->Item->update_image(NULL, $item_id);
                    $this->Personal_appfile->delete($cur_item_info->image_id);
                }
            }
            //Save Image File
            if (!empty($_FILES["image_id"]) && $_FILES["image_id"]["error"] == UPLOAD_ERR_OK) {
                $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                $extension = strtolower(pathinfo($_FILES["image_id"]["name"], PATHINFO_EXTENSION));

                if (in_array($extension, $allowed_extensions)) {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $_FILES["image_id"]["tmp_name"];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = 400;
                    $config['height'] = 300;
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                    $this->load->model('Personal_appfile');

                    $image_file_id = $this->Personal_appfile->save($_FILES["image_id"]["name"], file_get_contents($_FILES["image_id"]["tmp_name"]));
                }
                $this->Personal_item->update_image($image_file_id, $created_id);
            }
            if ($item_id == -1) {
                $this->session->set_userdata('item_registration_data', '<div class="alert alert-success" style="padding: 0px !important"><h5><center><strong>' . lang('common_item_created_proceed_to_payment') . '</strong></center></h5></div>');
                redirect('personal/item_details/' . $created_id);
            } else {
                redirect('personal/items');
            }
        } else { //failure
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function delete_item_image($item_id = -1) {


        //Delete Image
        if ($item_id != -1) {
            $cur_item_info = $this->Personal_item->get_info($item_id);
            if ($cur_item_info->image_id != null) {
                $this->load->model('Appfile');
                $this->Personal_item->update_image(NULL, $item_id);
                $this->Personal_appfile->delete($cur_item_info->image_id);
            }
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete_item($item_id = -1) {

        $item_data = array('deleted' => 1);
        //Delete Image
        $this->Personal_item->save($item_data, $item_id);

        redirect($_SERVER['HTTP_REFERER']);
    }

    function save_inventory($item_id = -1) {
        $this->load->model('Item_location');

        $this->check_action_permission('add_update');
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $cur_item_info = $this->Item->get_info($item_id);
        $cur_item_location_info = $this->Item_location->get_info($item_id);

        $inv_data = array
            (
            'trans_date' => date('Y-m-d H:i:s'),
            'trans_items' => $item_id,
            'trans_user' => $employee_id,
            'trans_comment' => $this->input->post('trans_comment'),
            'trans_inventory' => $this->input->post('newquantity'),
            'customer_id' => $this->input->post('customer_id'),
            'transaction_type' => $this->input->post('transaction_type'),
            'location_id' => $this->Employee->get_logged_in_employee_current_location_id()
        );
        $this->Inventory->insert($inv_data);

        //Update stock quantity
        if ($this->Item_location->save_quantity($cur_item_location_info->quantity + $this->input->post('newquantity'), $item_id)) {
            echo json_encode(array('success' => true, 'message' => lang('common_items_successful_updating') . ' ' .
                $cur_item_info->name, 'item_id' => $item_id));
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('common_error_adding_updating') . ' ' .
                $cur_item_info->name, 'item_id' => -1));
        }
    }

    function clear_state() {
        $this->session->unset_userdata('item_search_data');
        redirect('items');
    }

    function bulk_update() {
        $this->load->model('Item_location');
        $this->load->model('Item_taxes');

        $cost_price_percent = FALSE;
        $unit_price_percent = FALSE;
        $promo_price_percent = FALSE;

        $this->db->trans_start();

        $this->check_action_permission('add_update');
        $items_to_update = $this->input->post('item_ids');
        $select_inventory = $this->get_select_inventory();

        //clears the total inventory selection
        $this->clear_select_inventory();

        $item_data = array();


        foreach ($_POST as $key => $value) {
            if ($key == 'submit' || $key == 'tags' || $key == 'tier_types' || $key == 'tier_values') {
                continue;
            }

            //This field is nullable, so treat it differently
            if ($key == 'supplier_id') {
                if ($value != '') {
                    $item_data["$key"] = $value == '-1' ? null : $value;
                }
            } elseif ($value != '' && ($key == 'start_date' || $key == 'end_date')) {
                $item_data["$key"] = date('Y-m-d', strtotime($value));
            } elseif ($value != '' && $key == 'quantity') {
                $this->Item_location->update_multiple(array('quantity' => $value), $items_to_update, $select_inventory);
            } elseif ($value != '' && $key == 'item_cost_price_method' && $this->input->post('cost_price')) {
                if ($value == 'fixed') {
                    $item_data["cost_price"] = $this->input->post('cost_price');
                } elseif ($value == 'percent') {
                    $cost_price_percent = (float) $this->input->post('cost_price');
                }
            } elseif ($value != '' && $key == 'item_unit_price_method' && $this->input->post('unit_price')) {
                if ($value == 'fixed') {
                    $item_data["unit_price"] = $this->input->post('unit_price');
                } elseif ($value == 'percent') {
                    $unit_price_percent = (float) $this->input->post('unit_price');
                }
            } elseif ($value != '' && $key == 'item_promo_price_method' && $this->input->post('promo_price')) {
                if ($value == 'fixed') {
                    $item_data["promo_price"] = $this->input->post('promo_price');
                } elseif ($value == 'percent') {
                    $promo_price_percent = (float) $this->input->post('promo_price');
                }
            } elseif ($value != '' and ! (in_array($key, array('cost_price', 'unit_price', 'promo_price', 'item_cost_price_method', 'item_unit_price_method', 'item_promo_price_method', 'item_ids', 'tax_names', 'tax_percents', 'tax_cumulatives', 'select_inventory', 'commission_value', 'commission_type', 'commission_percent_type', 'override_default_commission')))) {
                $item_data["$key"] = $value;
            }
        }

        //If we have any of the percents to update then we will update them (one or more)
        if ($cost_price_percent || $unit_price_percent || $promo_price_percent) {
            $this->Item->update_multiple_percent($items_to_update, $select_inventory, $cost_price_percent, $unit_price_percent, $promo_price_percent);
        }

        $this->Item->update_tiers($items_to_update, $select_inventory, $this->input->post('tier_types'), $this->input->post('tier_values'));

        if ($this->input->post('override_default_commission') != '') {
            if ($this->input->post('override_default_commission') == 1) {
                if ($this->input->post('commission_type') == 'fixed') {
                    $item_data['commission_fixed'] = (float) $this->input->post('commission_value');
                    $item_data['commission_percent_type'] = '';
                    $item_data['commission_percent'] = NULL;
                } else {
                    $item_data['commission_percent'] = (float) $this->input->post('commission_value');
                    $item_data['commission_percent_type'] = $this->input->post('commission_percent_type');
                    $item_data['commission_fixed'] = NULL;
                }
            } else {
                $item_data['commission_percent'] = NULL;
                $item_data['commission_fixed'] = NULL;
                $item_data['commission_percent_type'] = '';
            }
        }

        //Item data could be empty if tax information is being updated
        if (empty($item_data) || $this->Item->update_multiple($item_data, $items_to_update, $select_inventory)) {
            //Only update tax data of we are override taxes
            if (isset($item_data['override_default_tax']) && $item_data['override_default_tax']) {
                $items_taxes_data = array();
                $tax_names = $this->input->post('tax_names');
                $tax_percents = $this->input->post('tax_percents');
                $tax_cumulatives = $this->input->post('tax_cumulatives');

                for ($k = 0; $k < count($tax_percents); $k++) {
                    if (is_numeric($tax_percents[$k])) {
                        $items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0');
                    }
                }

                if (!empty($items_taxes_data)) {
                    $this->Item_taxes->save_multiple($items_taxes_data, $items_to_update, $select_inventory);
                }
            }

            //Update all items with tags
            if ($this->input->post('tags')) {
                if ($select_inventory == 0) {
                    foreach ($items_to_update as $item_id) {
                        $this->Tag->save_tags_for_item($item_id, $this->input->post('tags'));
                    }
                } else {
                    $params = $this->session->userdata('item_search_data') ? $this->session->userdata('item_search_data') : array('offset' => 0, 'order_col' => 'name', 'order_dir' => 'asc', 'search' => FALSE, 'category_id' => FALSE, 'fields' => 'all');
                    $total_items = $this->Item->count_all();
                    $result = $this->Item->search(isset($params['search']) ? $params['search'] : '', isset($params['category_id']) ? $params['category_id'] : '', $total_items, 0, 'name', 'asc', isset($params['fields']) ? $params['fields'] : 'all');

                    foreach ($result->result() as $item) {
                        $this->Tag->save_tags_for_item($item->item_id, $this->input->post('tags'));
                    }
                }
            }
            echo json_encode(array('success' => true, 'message' => lang('items_successful_bulk_edit')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('items_error_updating_multiple')));
        }

        $this->db->trans_complete();
    }

    function delete() {
        $this->check_action_permission('delete');
        $items_to_delete = $this->input->post('ids');
        $select_inventory = $this->get_select_inventory();
        $params = $this->session->userdata('item_search_data') ? $this->session->userdata('item_search_data') : array('offset' => 0, 'order_col' => 'name', 'order_dir' => 'asc', 'search' => FALSE, 'category_id' => FALSE, 'fields' => 'all');
        $total_rows = $select_inventory ? $this->Item->search_count_all(isset($params['search']) ? $params['search'] : '', isset($params['category_id']) ? $params['category_id'] : '', $this->Item->count_all(), isset($params['fields']) ? $params['fields'] : 'all') : count($items_to_delete);
        //clears the total inventory selection
        $this->clear_select_inventory();
        if ($this->Item->delete_list($items_to_delete, $select_inventory)) {
            echo json_encode(array('success' => true, 'message' => lang('items_successful_deleted') . ' ' .
                $total_rows . ' ' . lang('items_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('items_cannot_be_deleted')));
        }
    }

    function _excel_get_header_row() {
        $this->load->model('Tier');

        $header_row = array();

        $header_row[] = lang('common_item_number');
        $header_row[] = lang('common_product_id');
        $header_row[] = lang('common_item_name');
        $header_row[] = lang('common_category');
        $header_row[] = lang('common_supplier_id');
        $header_row[] = lang('common_cost_price');
        $header_row[] = lang('common_unit_price');
        $header_row[] = lang('items_promo_price');
        $header_row[] = lang('items_promo_start_date');
        $header_row[] = lang('items_promo_end_date');

        foreach ($this->Tier->get_all()->result() as $tier) {
            $header_row[] = $tier->name;
        }

        $header_row[] = lang('items_price_includes_tax');
        $header_row[] = lang('items_is_service');
        $header_row[] = lang('items_quantity');
        $header_row[] = lang('items_reorder_level');
        $header_row[] = lang('common_description');
        $header_row[] = lang('items_allow_alt_desciption');
        $header_row[] = lang('items_is_serialized');
        $header_row[] = lang('common_size');
        $header_row[] = lang('reports_commission');
        $header_row[] = lang('items_commission_percent_based_on_profit');
        $header_row[] = lang('items_non_taxable');
        $header_row[] = lang('common_tags');
        $header_row[] = lang('items_days_to_expiration');
        $header_row[] = lang('common_change_cost_price_during_sale');
        return $header_row;
    }

    function excel() {
        $this->load->helper('report');
        $header_row = $this->_excel_get_header_row();
        $this->load->helper('spreadsheet');
        $content = array_to_spreadsheet(array($header_row));
        $this->load->helper('download');
        force_download('items_import.' . ($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
    }

    function excel_export() {
        $this->load->model('Tier');

        set_time_limit(0);

        $data = $this->Item->get_all($this->Item->count_all())->result_object();
        $non_taxable_item_ids = $this->Item->get_non_taxable_item_ids();
        $tier_prices = $this->Item->get_all_tiers_prices();
        $this->load->helper('report');

        $header_row = $this->_excel_get_header_row();
        $header_row[] = lang('common_item_id');
        $rows[] = $header_row;

        $tiers = $this->Tier->get_all()->result();
        $categories = $this->Category->get_all_categories_and_sub_categories_as_indexed_by_category_id();

        foreach ($data as $r) {
            $row = array();
            $row[] = $r->item_number;
            $row[] = $r->product_id;
            $row[] = $r->name;
            $row[] = isset($categories[$r->category_id]) ? $categories[$r->category_id] : '';
            $row[] = $r->supplier_id;
            $row[] = to_currency_no_money($r->cost_price, 10);
            $row[] = to_currency_no_money($r->unit_price);

            $row[] = $r->promo_price ? to_currency_no_money($r->promo_price) : '';
            $row[] = $r->start_date ? date(get_date_format(), strtotime($r->start_date)) : '';
            $row[] = $r->end_date ? date(get_date_format(), strtotime($r->end_date)) : '';

            foreach ($tiers as $tier) {
                $tier_id = $tier->id;
                $value = '';

                if (isset($tier_prices[$r->item_id][$tier->id])) {
                    $value = $tier_prices[$r->item_id][$tier->id]['unit_price'] !== NULL ? to_currency_no_money($tier_prices[$r->item_id][$tier->id]['unit_price']) : $tier_prices[$r->item_id][$tier->id]['percent_off'] . '%';
                }

                $row[] = $value;
            }


            $row[] = $r->tax_included ? 'y' : '';
            $row[] = $r->is_service ? 'y' : '';
            $row[] = to_quantity($r->quantity, FALSE);
            $row[] = to_quantity($r->reorder_level, fALSE);
            $row[] = $r->description;
            $row[] = $r->allow_alt_description ? 'y' : '';
            $row[] = $r->is_serialized ? 'y' : '';
            $row[] = $r->size;
            $commission = '';

            if ($r->commission_fixed) {
                $commission = to_currency_no_money($r->commission_fixed);
            } elseif ($r->commission_percent) {
                $commission = to_currency_no_money($r->commission_percent) . '%';
            }

            $row[] = $commission;
            $row[] = $r->commission_percent_type == 'profit' ? 'y' : '';
            $row[] = isset($non_taxable_item_ids[$r->item_id]) && $non_taxable_item_ids[$r->item_id] === TRUE ? 'y' : '';
            $row[] = $r->tags;
            $row[] = $r->expire_days ? $r->expire_days : '';
            $row[] = $r->change_cost_price ? 'y' : '';
            $row[] = $r->item_id;

            $rows[] = $row;
        }
        $this->load->helper('spreadsheet');
        $content = array_to_spreadsheet($rows);
        $this->load->helper('download');
        force_download('items_export.' . ($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
        exit;
    }

    function excel_import() {
        $this->check_action_permission('add_update');
        $this->load->view("items/excel_import", null);
    }

    function do_excel_import() {
        $this->load->helper('demo');
        $this->load->model('Tier');
        $this->load->model('Item_taxes');
        $this->load->model('Item_location');
        $this->load->model('Supplier');

        if (is_on_demo_host()) {
            $msg = lang('common_excel_import_disabled_on_demo');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        }

        $file_info = pathinfo($_FILES['file_path']['name']);
        if ($file_info['extension'] != 'xlsx' && $file_info['extension'] != 'csv') {
            echo json_encode(array('success' => false, 'message' => lang('common_upload_file_not_supported_format')));
            return;
        }


        set_time_limit(0);
        $this->check_action_permission('add_update');
        $this->db->trans_start();
        $msg = 'do_excel_import';

        $categories_indexed_by_name = $this->Category->get_all_categories_and_sub_categories_as_indexed_by_name_key();

        $category_map = array();
        $failCodes = array();
        if ($_FILES['file_path']['error'] != UPLOAD_ERR_OK) {
            $msg = lang('common_excel_import_failed');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        } else {
            if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE) {
                $this->load->helper('spreadsheet');
                $objPHPExcel = file_to_obj_php_excel($_FILES['file_path']['tmp_name']);
                $sheet = $objPHPExcel->getActiveSheet();
                $num_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
                $price_tiers_count = $this->Tier->count_all();
                $tiers = $this->Tier->get_all()->result();
                //Loop through rows, skip header row
                for ($k = 2; $k <= $num_rows; $k++) {
                    $name = $sheet->getCellByColumnAndRow(2, $k)->getValue();
                    if (!$name) {
                        $name = '';
                    }

                    $description = $sheet->getCellByColumnAndRow(14 + $price_tiers_count, $k)->getValue();

                    if (!$description) {
                        $description = '';
                    }

                    $category = $sheet->getCellByColumnAndRow(3, $k)->getValue();

                    if (!$category) {
                        $category_id = NULL;
                    } else {
                        $category_id = NULL;

                        if (!isset($categories_indexed_by_name[strtoupper($category)])) {
                            $this->Category->create_categories_as_needed($category, $categories_indexed_by_name);
                        }

                        $category_id = $categories_indexed_by_name[strtoupper($category)];
                    }

                    $cost_price = $sheet->getCellByColumnAndRow(5, $k)->getValue();

                    if ($cost_price == NULL) {
                        $cost_price = 0;
                    }

                    $unit_price = $sheet->getCellByColumnAndRow(6, $k)->getValue();

                    if ($unit_price == NULL) {
                        $unit_price = 0;
                    }

                    $tax_included = $sheet->getCellByColumnAndRow(10 + $price_tiers_count, $k)->getValue();
                    $tax_included = ($tax_included != null && $tax_included != '' and $tax_included != '0' and strtolower($tax_included) != 'n') ? '1' : '0';

                    $is_service = $sheet->getCellByColumnAndRow(11 + $price_tiers_count, $k)->getValue();
                    $is_service = ($is_service != null && $is_service != '' and $is_service != '0' and strtolower($is_service) != 'n') ? '1' : '0';

                    $quantity = $sheet->getCellByColumnAndRow(12 + $price_tiers_count, $k)->getValue();
                    $reorder_level = $sheet->getCellByColumnAndRow(13 + $price_tiers_count, $k)->getValue();

                    $supplier_id = $sheet->getCellByColumnAndRow(4, $k)->getValue();

                    if ($supplier_id) {
                        $supplier_name_before_searching = $supplier_id;
                        $supplier_id = $this->Supplier->exists($supplier_id) ? $supplier_id : $this->Supplier->find_supplier_id($supplier_id);

                        if (!$supplier_id) {
                            $person_data = array('first_name' => '', 'last_name' => '');
                            $supplier_data = array('company_name' => $supplier_name_before_searching);
                            $this->Supplier->save_supplier($person_data, $supplier_data);
                            $supplier_id = $supplier_data['person_id'];
                        }
                    }

                    $allow_alt_description = $sheet->getCellByColumnAndRow(15 + $price_tiers_count, $k)->getValue();
                    $allow_alt_description = ($allow_alt_description != null && $allow_alt_description != '' and $allow_alt_description != '0' and strtolower($allow_alt_description) != 'n') ? '1' : '0';

                    $is_serialized = $sheet->getCellByColumnAndRow(16 + $price_tiers_count, $k)->getValue();
                    $is_serialized = ($is_serialized != null && $is_serialized != '' and $is_serialized != '0' and strtolower($is_serialized) != 'n') ? '1' : '0';

                    $non_taxable = $sheet->getCellByColumnAndRow(20 + $price_tiers_count, $k)->getValue();
                    $non_taxable = ($non_taxable != null && $non_taxable != '' and $non_taxable != '0' and strtolower($non_taxable) != 'n') ? '1' : '0';

                    $size = $sheet->getCellByColumnAndRow(17 + $price_tiers_count, $k)->getValue();
                    if (!$size) {
                        $size = '';
                    }

                    $item_number = $sheet->getCellByColumnAndRow(0, $k)->getValue();
                    $product_id = $sheet->getCellByColumnAndRow(1, $k)->getValue();
                    $item_id = $sheet->getCellByColumnAndRow(24 + $price_tiers_count, $k)->getValue();

                    if (!$item_id) {
                        $item_id = FALSE;
                    }

                    //If we don't have a name or unit price skip the import
                    if (!$name || !$unit_price) {
                        continue;
                    }

                    $item_data = array(
                        'name' => $name,
                        'description' => $description,
                        'category_id' => $category_id,
                        'cost_price' => $cost_price,
                        'unit_price' => $unit_price,
                        'tax_included' => $tax_included,
                        'is_service' => $is_service,
                        'reorder_level' => $reorder_level,
                        'supplier_id' => $supplier_id,
                        'allow_alt_description' => $allow_alt_description,
                        'is_serialized' => $is_serialized,
                        'size' => $size,
                    );

                    if ($item_number != "") {
                        $item_data['item_number'] = $item_number;
                    } else {
                        $item_data['item_number'] = NULL;
                    }

                    if ($product_id != "") {
                        $item_data['product_id'] = $product_id;
                    } else {
                        $item_data['product_id'] = NULL;
                    }

                    if ($non_taxable) {
                        $item_data['override_default_tax'] = 1;
                    } else {
                        $item_data['override_default_tax'] = 0;
                    }

                    $commission = $sheet->getCellByColumnAndRow(18 + $price_tiers_count, $k)->getValue();

                    $commission_percent_based_on_profit = $sheet->getCellByColumnAndRow(19 + $price_tiers_count, $k)->getValue();
                    $commission_percent_type = ($commission_percent_based_on_profit != null && $commission_percent_based_on_profit != '' and $commission_percent_based_on_profit != '0' and strtolower($commission_percent_based_on_profit) != 'n') ? 'profit' : 'selling_price';

                    if ($commission != '') {
                        if (strpos($commission, '%') === FALSE) {
                            $item_data['commission_fixed'] = (float) $commission;
                            $item_data['commission_percent'] = NULL;
                            $item_data['commission_percent_type'] = '';
                        } else {
                            $item_data['commission_percent'] = (float) $commission;
                            $item_data['commission_fixed'] = NULL;
                            $item_data['commission_percent_type'] = $commission_percent_type;
                        }
                    } else {
                        $item_data['commission_percent'] = NULL;
                        $item_data['commission_fixed'] = NULL;
                        $item_data['commission_percent_type'] = '';
                    }

                    $promo_price = $sheet->getCellByColumnAndRow(7, $k)->getValue();
                    $start_date = date('Y-m-d', strtotime($sheet->getCellByColumnAndRow(8, $k)->getValue()));
                    $end_date = date('Y-m-d', strtotime($sheet->getCellByColumnAndRow(9, $k)->getValue()));

                    if ($promo_price == NULL) {
                        $promo_price = NULL;
                        $start_date = NULL;
                        $end_date = NULL;
                    }

                    $item_data['promo_price'] = $promo_price;
                    $item_data['start_date'] = $start_date;
                    $item_data['end_date'] = $end_date;

                    $expire_days = $sheet->getCellByColumnAndRow(22 + $price_tiers_count, $k)->getValue();

                    if ($expire_days == NULL) {
                        $expire_days = NULL;
                    }

                    $item_data['expire_days'] = $expire_days;


                    $change_cost_price = $sheet->getCellByColumnAndRow(23 + $price_tiers_count, $k)->getValue();

                    $change_cost_price = ($change_cost_price != null && $change_cost_price != '' and $change_cost_price != '0' and strtolower($change_cost_price) != 'n') ? '1' : '0';


                    if ($change_cost_price) {
                        $item_data['change_cost_price'] = 1;
                    } else {
                        $item_data['change_cost_price'] = 0;
                    }


                    if ($this->Item->save($item_data, $item_id)) {
                        $promo_end_date_col_index = 9;
                        $counter = 0;
                        //Save price tiers
                        foreach ($tiers as $tier) {
                            $tier_id = $tier->id;

                            $tier_data = array('tier_id' => $tier_id);
                            $tier_data['item_id'] = isset($item_data['item_id']) ? $item_data['item_id'] : $item_id;
                            $tier_value = $sheet->getCellByColumnAndRow($promo_end_date_col_index + ($counter + 1), $k)->getValue();

                            if ($tier_value) {
                                if (strpos($tier_value, '%') === FALSE) {
                                    $tier_data['unit_price'] = $tier_value;
                                    $tier_data['percent_off'] = NULL;
                                } else {
                                    $tier_data['percent_off'] = (float) $tier_value;
                                    $tier_data['unit_price'] = NULL;
                                }

                                $this->Item->save_item_tiers($tier_data, isset($item_data['item_id']) ? $item_data['item_id'] : $item_id);
                            } else {
                                $this->Item->delete_tier_price($tier_id, isset($item_data['item_id']) ? $item_data['item_id'] : $item_id);
                            }

                            $counter++;
                        }


                        $tags = $sheet->getCellByColumnAndRow(21 + $price_tiers_count, $k)->getValue();

                        if ($tags) {
                            $this->Tag->save_tags_for_item(isset($item_data['item_id']) ? $item_data['item_id'] : $item_id, $tags);
                        }

                        $item_location_before_save = $this->Item_location->get_info($item_id, $this->Employee->get_logged_in_employee_current_location_id());


                        if ($quantity !== NULL) {
                            $this->Item_location->save_quantity($quantity != null ? $quantity : NULL, isset($item_data['item_id']) ? $item_data['item_id'] : $item_id);

                            $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
                            $emp_info = $this->Employee->get_info($employee_id);
                            $comment = lang('items_csv_import');

                            //Only log inventory if quantity changes
                            if (!$item_data['is_service'] && $quantity != $item_location_before_save->quantity) {
                                $inv_data = array
                                    (
                                    'trans_date' => date('Y-m-d H:i:s'),
                                    'trans_items' => isset($item_data['item_id']) ? $item_data['item_id'] : $item_id,
                                    'trans_user' => $employee_id,
                                    'trans_comment' => $comment,
                                    'trans_inventory' => $quantity - $item_location_before_save->quantity,
                                    'location_id' => $this->Employee->get_logged_in_employee_current_location_id()
                                );
                                $this->Inventory->insert($inv_data);
                            }
                        }

                        if ($non_taxable) {
                            $this->Item_taxes->delete(isset($item_data['item_id']) ? $item_data['item_id'] : $item_id);
                        }
                    } else {//insert or update item failure
                        echo json_encode(array('success' => false, 'message' => lang('items_duplicate_item_ids')));
                        return;
                    }
                }//LOOP DONE (for items)
            } else {
                echo json_encode(array('success' => false, 'message' => lang('common_upload_file_not_supported_format')));
                return;
            }
        }

        $this->db->trans_complete();
        echo json_encode(array('success' => true, 'message' => lang('items_import_successful')));
    }

    function cleanup() {
        $this->Item->cleanup();
        echo json_encode(array('success' => true, 'message' => lang('items_cleanup_sucessful')));
    }

    function select_inventory() {
        $this->session->set_userdata('select_inventory', 1);
    }

    function get_select_inventory() {
        return $this->session->userdata('select_inventory') ? $this->session->userdata('select_inventory') : 0;
    }

    function clear_select_inventory() {
        $this->session->unset_userdata('select_inventory');
    }

    function tags() {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->Tag->get_tag_suggestions($this->input->get('term'), 25);
        echo json_encode($suggestions);
    }

    function count($status = 'open', $offset = 0) {
        $this->check_action_permission('count_inventory');
        $data = array();
        $config = array();
        $config['base_url'] = site_url("items/count/$status");
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['total_rows'] = $this->Inventory->get_count_by_status($status);
        $config['uri_segment'] = 4;
        $data['per_page'] = $config['per_page'];


        $data['total_rows'] = $config['total_rows'];
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $counts = $this->Inventory->get_counts_by_status($status, $config['per_page'], $offset)->result_array();

        $data['counts'] = $counts;
        $data['status'] = $status;
        $this->load->view('items/count', $data);
    }

    function new_count() {
        $this->check_action_permission('count_inventory');
        $count_id = $this->Inventory->create_count();
        redirect('items/do_count/' . $count_id);
    }

    function do_count($count_id, $offset = 0) {
        $this->check_action_permission('count_inventory');
        $this->session->set_userdata('current_count_id', $count_id);

        $data = array();
        $config = array();
        $config['base_url'] = site_url("items/do_count/$count_id");
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['total_rows'] = $this->Inventory->get_number_of_items_counted($count_id);
        $config['uri_segment'] = 4;
        $data['per_page'] = $config['per_page'];


        $data['total_rows'] = $config['total_rows'];
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['count_info'] = $this->Inventory->get_count_info($count_id);

        $data['items_counted'] = $this->Inventory->get_items_counted($count_id, $config['per_page'], $offset);
        $data['mode'] = $this->session->userdata('count_mode') ? $this->session->userdata('count_mode') : 'scan_and_set';
        $data['modes'] = array('scan_and_set' => lang('items_scan_and_set'), 'scan_and_add' => lang('items_scan_and_add'));

        $this->load->view('items/do_count', $data);
    }

    function add_item_to_inventory_count() {
        $this->check_action_permission('count_inventory');
        $this->load->model('Item_location');

        $item = $this->input->post('item');
        $count_id = $this->session->userdata('current_count_id');
        $mode = $this->session->userdata('count_mode') ? $this->session->userdata('count_mode') : 'scan_and_set';

        if ($item && $count_id) {
            if (!$this->Item->exists(does_contain_only_digits($item) ? (int) $item : -1)) {
                //try to get item id given an item_number
                $item = $this->Item->get_item_id($item);
            }

            if ($item) {
                $current_count = $this->Inventory->get_count_item_current_quantity($count_id, $item);
                $actual_quantity = $this->Inventory->get_count_item_actual_quantity($count_id, $item);

                if ($actual_quantity !== NULL) {
                    $current_inventory_value = $actual_quantity;
                } else {
                    $current_inventory_value = $this->Item_location->get_location_quantity($item);
                }

                if ($mode == 'scan_and_add') {
                    $this->Inventory->set_count_item($count_id, $item, $current_count + 1, $current_inventory_value);
                } else {
                    $this->Inventory->set_count_item($count_id, $item, $current_count, $current_inventory_value);
                }
            }
        }

        $this->_reload_inventory_counts();
    }

    function edit_count() {
        $this->check_action_permission('count_inventory');
        $name = $this->input->post('name');
        $count_id = $this->input->post('pk');
        $$name = $this->input->post('value');

        $this->Inventory->set_count($count_id, isset($status) ? $status : FALSE, isset($comment) ? $comment : FALSE);
    }

    function excel_import_count() {
        $this->check_action_permission('count_inventory');
        $this->load->view("items/excel_import_count", null);
    }

    function _excel_get_header_row_count() {
        return array(lang('common_item_id') . '/' . lang('common_item_number') . '/' . lang('common_product_id'), lang('items_count'));
    }

    function excel_count() {
        $this->load->helper('report');
        $header_row = $this->_excel_get_header_row_count();
        $this->load->helper('spreadsheet');
        $content = array_to_spreadsheet(array($header_row));
        $this->load->helper('download');
        force_download('items_count.' . ($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
    }

    function do_excel_import_count() {
        $this->check_action_permission('count_inventory');
        $this->load->model('Item_location');

        $count_id = $this->session->userdata('current_count_id');
        $this->load->helper('demo');
        if (is_on_demo_host()) {
            $msg = lang('common_excel_import_disabled_on_demo');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        }

        $file_info = pathinfo($_FILES['file_path']['name']);
        if ($file_info['extension'] != 'xlsx' && $file_info['extension'] != 'csv') {
            echo json_encode(array('success' => false, 'message' => lang('common_upload_file_not_supported_format')));
            return;
        }


        set_time_limit(0);
        $this->db->trans_start();
        $msg = 'do_excel_import';

        $category_map = array();
        $failCodes = array();
        if ($_FILES['file_path']['error'] != UPLOAD_ERR_OK) {
            $msg = lang('common_excel_import_failed');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        } else {
            if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE) {
                $this->load->helper('spreadsheet');
                $objPHPExcel = file_to_obj_php_excel($_FILES['file_path']['tmp_name']);
                $sheet = $objPHPExcel->getActiveSheet();
                $num_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

                //Loop through rows, skip header row
                for ($k = 2; $k <= $num_rows; $k++) {
                    $item_id = $sheet->getCellByColumnAndRow(0, $k)->getValue();
                    if (!$item_id) {
                        continue;
                    }

                    $quantity = $sheet->getCellByColumnAndRow(1, $k)->getValue();
                    if (!$quantity) {
                        continue;
                    }

                    if ($item_id && $quantity) {
                        if (!$this->Item->exists(does_contain_only_digits($item_id) ? (int) $item_id : -1)) {
                            //try to get item id given an item_number
                            $item_id = $this->Item->get_item_id($item_id);
                        }

                        if ($item_id) {
                            $current_inventory_value = $this->Item_location->get_location_quantity($item_id);
                            $this->Inventory->set_count_item($count_id, $item_id, $quantity, $current_inventory_value);
                        }
                    }
                }

                $this->db->trans_complete();
                echo json_encode(array('success' => true, 'message' => lang('items_import_successful')));
            } else {
                echo json_encode(array('success' => false, 'message' => lang('common_upload_file_not_supported_format')));
                return;
            }
        }
    }

    function count_import_success() {
        $count_id = $this->session->userdata('current_count_id');
        redirect('items/do_count/' . $count_id);
    }

    function finish_count($update_inventory = 0) {
        $this->check_action_permission('count_inventory');

        $count_id = $this->session->userdata('current_count_id');

        if ($update_inventory && $this->Employee->has_module_action_permission('items', 'edit_quantity', $this->Employee->get_logged_in_employee_info()->person_id)) {
            $this->Inventory->update_inventory_from_count($count_id);
        }

        $this->Inventory->set_count($count_id, 'closed');
        redirect('items/count');
    }

    function edit_count_item() {
        $this->check_action_permission('count_inventory');

        $name = $this->input->post('name');
        $item_id = $this->input->post('pk');
        $$name = $this->input->post('value');
        $count_id = $this->session->userdata('current_count_id');

        $current_count = $this->Inventory->get_count_item_current_quantity($count_id, $item_id);
        $actual_quantity = $this->Inventory->get_count_item_actual_quantity($count_id, $item_id);

        if ($actual_quantity !== NULL) {
            $current_inventory_value = $actual_quantity;
        } else {
            $current_inventory_value = $this->Item_location->get_location_quantity($item_id);
        }

        $this->Inventory->set_count_item($count_id, $item_id, isset($quantity) ? $quantity : $current_count, $current_inventory_value, isset($comment) ? $comment : FALSE);
        $this->_reload_inventory_counts();
    }

    function delete_inventory_count_item($item_id) {
        $this->check_action_permission('count_inventory');

        $count_id = $this->session->userdata('current_count_id');
        $this->Inventory->delete_count_item($count_id, $item_id);
        redirect('items/do_count/' . $count_id);
    }

    function delete_inventory_count($count_id, $go_back_to_status = 'open') {
        $this->check_action_permission('count_inventory');

        $this->Inventory->delete_inventory_count($count_id);
        redirect("items/count/$go_back_to_status");
    }

    function reload_inventory_counts() {
        $this->check_action_permission('count_inventory');

        $this->_reload_inventory_counts();
    }

    function change_count_mode() {
        $this->check_action_permission('count_inventory');

        $this->session->set_userdata('count_mode', $this->input->post('mode'));

        $this->_reload_inventory_counts();
    }

    function _reload_inventory_counts($data = array()) {
        $this->check_action_permission('count_inventory');

        $count_id = $this->session->userdata('current_count_id');
        $config = array();

        $config['base_url'] = site_url("items/do_count/$count_id");
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['total_rows'] = $this->Inventory->get_number_of_items_counted($count_id);
        $config['uri_segment'] = 4;
        $data['per_page'] = $config['per_page'];
        $data['count_info'] = $this->Inventory->get_count_info($count_id);

        $data['total_rows'] = $config['total_rows'];
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['items_counted'] = $this->Inventory->get_items_counted($count_id, $config['per_page']);

        $data['mode'] = $this->session->userdata('count_mode') ? $this->session->userdata('count_mode') : 'scan_and_set';
        $data['modes'] = array('scan_and_set' => lang('items_scan_and_set'), 'scan_and_add' => lang('items_scan_and_add'));

        $this->load->view("items/do_count_data", $data);
    }

    function messages($offset = 0) {
        $config['base_url'] = site_url('messages/index');
        $config['total_rows'] = $this->Message->get_messages_count();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['messages'] = $this->Message->get_messages($config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view("personal/messages", $data);
    }

    function sent_messages($offset = 0) {
        $data = array();

        $config = array();
        $config['base_url'] = site_url('messages/sent_messages');
        $config['total_rows'] = $this->Message->get_sent_messages_count();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 3;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['messages'] = $this->Message->get_sent_messages($config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view("personal/sent_messages", $data);
    }

    /*
      Loads the customer edit form
     */

    function view_message($message_id, $sent_message = 0) {
        if ($this->Message->can_read_message($message_id, $sent_message)) {
            $data['message'] = $this->Message->get_info($message_id);
            $this->load->view("personal/single-message", $data);
        } else {
            $data['message'] = array();
            $this->load->view("personal/single-message", $data);
        }
    }

    function delete_message() {
        $message_id = $this->input->post('message_id');
        $status = $this->Message->delete_message($message_id);
        echo json_encode(array('message_id' => $message_id, 'status' => $status));
    }

    function send_message() {

        $this->check_action_permission('send_message');

        $data['employees'] = array();

        foreach ($this->Employee->get_all()->result() as $employee) {
            $data['employees'][$employee->person_id] = $employee->first_name . ' ' . $employee->last_name;
        }


        $data['locations'] = array();

        foreach ($this->Location->get_all()->result() as $location) {
            $data['locations'][$location->location_id] = $location->name;
        }

        $this->load->view("personal/send_message", $data);
    }

    function send_invidual_message($person_id) {

        $this->check_action_permission('send_message');

        $data['employee'] = $this->Employee->get_info($person_id);

        $this->load->view("personal/send_individual_message", $data);
    }

    function save_message() {

        $message_data = array(
            'all_locations' => $this->input->post('all_locations'),
            'all_employees' => $this->input->post('all_employees'),
            'locations' => $this->input->post('locations'),
            'employees' => $this->input->post('employees'),
            'subject' => $this->input->post('subject'),
            'message' => $this->input->post('message'),
        );
        if (!$this->input->post('all_employees') && !$this->input->post('employees')) {
            echo json_encode(array('status' => false, 'message' => lang("messages_employees_required")));
        } else if ($this->Message->save_message($message_data)) {
            echo json_encode(array('status' => true, 'message' => $this->input->post('message')));
        } else {
            echo json_encode(array('status' => false, 'message' => $this->input->post('message')));
        }
    }

    function read_message() {
        $message_id = $this->input->post('message_id');
        $this->Message->read_message($message_id);
    }

    function get_locations_employees() {

        $selected_locations = $this->input->post('selected_locations');

        // Get all the employees in selected locations
        $employee_ids = $this->Employee->get_multiple_locations_employees($selected_locations)->result_array();

        //Prepare the employees ids format 
        $person_ids = array();
        foreach ($employee_ids as $value) {
            $person_ids[] = $value['employee_id'];
        }

        // Get all the empoyees data
        $employees_data = $this->Employee->get_multiple_info($person_ids)->result_array();
        $employees = array();

        foreach ($employees_data as $employee) {

            $employees[$employee['person_id']] = $employee['first_name'] . ' ' . $employee['last_name'];
        }


        echo json_encode(array('success' => true, 'employees' => $employees));
    }

    function found_item_payment($item_id = null, $payment_type = 0) {
        $this->lang->load('items');
        $this->load->model('Category');
        $data['item_info'] = $this->Item->get_info($item_id);


        $data['category'] = $this->Category->get_info($data['item_info']->category_id)->name;
        $data['category_amount'] = $this->Category->get_info($data['item_info']->category_id)->owner_payment_amount;
        $data['phone_number'] = $this->Person->get_info($this->Employee->get_logged_in_employee_info()->person_id)->phone_number;

        $this->session->unset_userdata('redirected'); //Clear the previous redirection
        $this->load->view("personal/found_item_payment", $data);
    }

    function found_payment_confirmation() {

        $payment_data = array(
            'item_id' => $this->input->post('item_id'),
            'item_type' => 1, // Found item
            'agent_id' => 0,
            'payment_type' => 2, // 2 for Owner payment
            'payment_method_id' => 2, // 2 is for MoMo
            'amount' => $this->input->post('amount'),
            'payed_by_id' => $this->Employee->get_logged_in_employee_info()->id,
            'transaction_id' => uniqid(),
        );

        $phone_number = $this->input->post('phone_number');
        $amount = $this->input->post('amount');
//        echo '0. ' . $this->db->last_query() . '<br />';
        if (!$this->Payment->item_payment_saved($this->input->post('item_id'), 1)) {
//            echo '1. ' . $this->db->last_query() . '<br />';
            $payment_id = $this->Payment->save($payment_data);
//            echo '1.1 ' . $this->db->last_query() . '<br />';
        } else {
            $payment_id = $this->Payment->get_item_payment_id($this->input->post('item_id'), 1); // found item
//            echo '2. ' . $this->db->last_query() . '<br />';
        }
        if ($payment_id > 0) {
            $payment = $this->Payment->get_info($payment_id);
//            echo '3. ' . $this->db->last_query() . '<br />';
            if (!in_array($payment->payment_gatway_code, array(100, 101, 102, 103))) {
                //request the Cash peyment
                $request = $this->Payment->request_mobile_payment_approval($amount, $phone_number, 'Item_registration', $payment->transaction_id);
//                echo '4. ' . $this->db->last_query() . '<br />';
                if ($request == 0) {
                    $message = '<div class="alert alert-danger"><strong>Failed to communicate with the payment gateway</strong></div>';
                } else if ($request) {
                    $message = '<div class="alert alert-success"><strong>Payment successfully requested. Please confirm the payment from your Phone</strong></div>';
                } else {
                    $message = '<div class="alert alert-danger"><strong>Payment request failed. Check your number, Make sure that you have enough fund on your account and try again</strong></div>';
                }
            } else {
                $message = '<div class="alert alert-warning"><strong>Payment aleady requested</strong></div>';
            }
//            exit();
            $this->session->set_userdata("payment_message", $message);
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function personal_invoice($personal_item_id = null) {

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('BAZA Ltd');
        $pdf->SetTitle('BAZA Ltd');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('BAZA, PDF, invoice, test, guide');

// set default header data
        $pdf->SetHeaderData('logo.jpg', PDF_HEADER_LOGO_WIDTH, 'BAZA LTD', "TIN: 103500000\nwww.baza.rw");

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $this->lang->load('items');
        $this->load->model('Category');

        $item_info = $this->Personal_item->get_info($personal_item_id);
        $item_payment = $this->Payment->get_payment_by_item_id($item_info->item_id, 2); // Personal item

        if ($this->Personal_item->get_info($personal_item_id)->created_by_id != $this->Employee->get_logged_in_employee_info()->id) {
            echo 'Item Access denied';
            exit();
        }

        $payed_amount = 0;
        $transaction_id = 0;
        if ($item_payment) {
            $payed_amount = $item_payment->amount;
            $transaction_id = $item_payment->transaction_id;
        } else {
            echo 'Item not Payed';
            exit();
        }

        $category_name = $this->Category->get_info($item_info->category_id)->name;
        $item_number = '';
        if ($item_info->item_number == '' || $item_info->item_number == null) {
            $item_number = $item_info->name_on_card;
        } else {
            $item_number = $item_info->item_number;
        }
// ---------------------------------------------------------
// set font
        $pdf->SetFont('dejavusans', '', 10);


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table
// add a page
        $pdf->AddPage();

// create some HTML content

        $html = '<table border="1" cellspacing="3" cellpadding="4">
	<tr>
		<th style="text-align: center;" colspan="2"><h2>ITEM REGISTRATION </h2></th>
	</tr>
	<tr>
		<th style="width: 30%">Item Name</th>
		<th style="width: 70%">' . $item_info->name . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Serial Number or item number/Name on the Document</th>
		<th style="width: 70%">' . $item_number
                . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Category</th>
		<th style="width: 70%">' . $category_name . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Description</th>
		<th style="width: 70%">' . $item_info->description . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Paid Amount</th>
		<th style="width: 70%">' . number_format($payed_amount, 0) . 'FRW </th>
	</tr>
	<tr>
		<th style="width: 30%">Transaction ID</th>
		<th style="width: 70%">' . $transaction_id . '</th>
	</tr>
	
</table><br /><br /><br /><span style="text-align: center;"><strong>THANK YOU! </strong></center>';

        $pdf->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 0, true, 'R', true);

// reset pointer to the last page
        $pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
        $pdf->Output('invoice.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
    }

    function found_invoice($item_id = null) {

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('BAZA Ltd');
        $pdf->SetTitle('BAZA Ltd');
        $pdf->SetSubject('Invoice');
        $pdf->SetKeywords('BAZA, PDF, invoice, test, guide');

// set default header data
        $pdf->SetHeaderData('logo.jpg', PDF_HEADER_LOGO_WIDTH, 'BAZA LTD', "TIN: 103500000\nwww.baza.rw");

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $this->lang->load('items');
        $this->load->model('Category');

        $item_info = $this->Item->get_info($item_id);
        $item_payment = $this->Payment->get_payment_by_item_id($item_info->item_id, 1); // Found item


        if ($item_payment->payed_by_id == $this->Employee->get_logged_in_employee_info()->id || $item_info->created_by_id == $this->Employee->get_logged_in_employee_info()->person_id) {
            $payed_amount = 0;
            $transaction_id = 0;
            if (isset($item_payment)) {
                $payed_amount = $item_payment->amount;
                $transaction_id = $item_payment->transaction_id;
            } else {
                echo 'Item not Payed';
                exit();
            }
            $category_name = $this->Category->get_info($item_info->category_id)->name;
            $item_number = '';
            if ($item_info->item_number == '' || $item_info->item_number == null) {
                $item_number = $item_info->name_on_card;
            } else {
                $item_number = $item_info->item_number;
            }

            $agent = $this->Employee->get_info($item_info->created_by_id);
// ---------------------------------------------------------
// set font
            $pdf->SetFont('dejavusans', '', 10);


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table
// add a page
            $pdf->AddPage();

// create some HTML content

            $html = '<table border="1" cellspacing="3" cellpadding="4">
	<tr>
		<th style="text-align: center;" colspan="2"><h2>FOUND ITEM RECOVERY PAYMENT</h2></th>
	</tr>
	<tr>
		<th style="width: 30%">Item Name</th>
		<th style="width: 70%">' . $item_info->name . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Serial Number or item number/Name on the Document</th>
		<th style="width: 70%">' . $item_number
                    . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Category</th>
		<th style="width: 70%">' . $category_name . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Description</th>
		<th style="width: 70%">' . $item_info->description . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Paid Amount</th>
		<th style="width: 70%">' . number_format($payed_amount, 0) . 'FRW </th>
	</tr>
	<tr>
		<th style="width: 30%">' . lang('common_found_item_location') . '</th>
		<th style="width: 70%">' . $this->Location->get_info($item_info->item_location_id)->name . '</th>
	</tr>
	<tr>
		<th style="width: 30%">' . lang('common_found_item_contact_person') . '</th>
		<th style="width: 70%">' . $agent->first_name . ' ' . $agent->last_name . '<br />Phone: ' . $agent->phone_number . 'FRW </th>
	</tr>
	<tr>
		<th style="width: 30%">Transaction ID</th>
		<th style="width: 70%">' . $transaction_id . '</th>
	</tr>
	
</table><br /><br /><br /><span style="text-align: center;"><strong>THANK YOU! </strong></center>';

            $pdf->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 0, true, 'R', true);

// reset pointer to the last page
            $pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
            $pdf->Output('invoice.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
        } else {
            echo 'Item Access denied';
            exit();
        }
    }

    function item_consent($item_id) {

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('BAZA Ltd');
        $pdf->SetTitle('BAZA Ltd');
        $pdf->SetSubject('Invoice');
        $pdf->SetKeywords('BAZA, PDF, invoice, test, guide');

// set default header data
        $pdf->SetHeaderData('logo.jpg', PDF_HEADER_LOGO_WIDTH, 'BAZA LTD', "TIN: 103500000\nwww.baza.rw");

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $this->lang->load('items');
        $this->load->model('Category');

        $item_payment = $this->Payment->get_payment_by_item_id($item_id, 1); // 1 for found items
        $collector_info = $this->Employee->get_info($this->Employee->get_person_id_from_employee_id($item_payment->payed_by_id));
        $logged_user = $this->Employee->get_logged_in_employee_info();
        if ($collector_info->person_id == '') { // The Collector is found
            echo 'Item Payment info not found. Please contact the Administrator';
        }
        $item_info = $this->Item->get_info($item_id);

        $payed_amount = 0;
        $transaction_id = 0;
        if (isset($item_payment)) {
            $payed_amount = $item_payment->amount;
            $transaction_id = $item_payment->transaction_id;
        } else {
            echo 'Item not Payed';
            exit();
        }
        $category_name = $this->Category->get_info($item_info->category_id)->name;
        $item_number = '';
        if ($item_info->item_number == '' || $item_info->item_number == null) {
            $item_number = $item_info->name_on_card;
        } else {
            $item_number = $item_info->item_number;
        }
// ---------------------------------------------------------
// set font
        $pdf->SetFont('dejavusans', '', 10);


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table
// add a page
        $pdf->AddPage();

// create some HTML content

        $html = '<span style="text-align: center;"><h2>FOUND ITEM RECOVERY CONSENT</h2></span><br /><br /><br />
            I, <b>' . $collector_info->first_name . ' ' . $collector_info->last_name . '</b>, Phone no: <b>' . $collector_info->phone_number . '</b>, acknoledge the receipt if the following found item: <br /><br />
            <table border="1" cellspacing="3" cellpadding="4">
	<tr>
		<th style="width: 30%">Item Name</th>
		<th style="width: 70%">' . $item_info->name . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Serial Number or item number/Name on the Document</th>
		<th style="width: 70%">' . $item_number
                . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Category</th>
		<th style="width: 70%">' . $category_name . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Description</th>
		<th style="width: 70%">' . $item_info->description . '</th>
	</tr>
	<tr>
		<th style="width: 30%">Paid Amount</th>
		<th style="width: 70%">' . number_format($payed_amount, 0) . 'FRW </th>
	</tr>
	<tr>
		<th style="width: 30%">Transaction ID</th>
		<th style="width: 70%">' . $transaction_id . '</th>
	</tr>
	
</table>
<br /><br /><br />
<table cellspacing="3" cellpadding="4">
	<tr>
		<th style="width: 30%"><b>Received by: </b></th>
		<th style="width: 70%"><b>Handed by: </b></th>
	</tr>
	<tr>
		<th style="width: 30%">' . $collector_info->first_name . ' ' . $collector_info->last_name . '<br /><br /><br /><br /><br /><br />Signature & Date</th>
		<th style="width: 70%">' . $logged_user->last_name . ' ' . $logged_user->first_name . '<br /><br /><br /><br /><br /><br />Signature & Date</th>
	</tr>
</table>
<span style="text-align: center;"><strong>THANK YOU! </strong></center>';

        $pdf->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 0, true, 'R', true);

// reset pointer to the last page
        $pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
        $pdf->Output('Consent_' . $item_info->item_id . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
    }

}

?>
