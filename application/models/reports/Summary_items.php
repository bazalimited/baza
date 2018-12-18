<?php

require_once ("Report.php");

class Summary_items extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getReportDataColumns() {
        $columns = array();

        $columns[] = array('data' => lang('common_ordering_counter'), 'align' => 'left');
        $columns[] = array('data' => lang('common_item'), 'align' => 'left');
        $columns[] = array('data' => lang('common_item_number_expanded'), 'align' => 'left');
        $columns[] = array('data' => lang('reports_category'), 'align' => 'left');
        $columns[] = array('data' => lang('common_found_item_location'), 'align' => 'left');
        $columns[] = array('data' => lang('common_item_registration_date'), 'align' => 'left');
        return $columns;
    }

    public function getDataColumns() {
        $columns = array();

        $columns[] = array('data' => lang('common_item'), 'align' => 'left');
        $columns[] = array('data' => lang('common_item_number'), 'align' => 'left');
        $columns[] = array('data' => lang('common_product_id'), 'align' => 'left');
        $columns[] = array('data' => lang('reports_category'), 'align' => 'left');
        $columns[] = array('data' => lang('reports_current_selling_price'), 'align' => 'left');
        $columns[] = array('data' => lang('reports_quantity'), 'align' => 'left');
        $columns[] = array('data' => lang('reports_quantity_purchased'), 'align' => 'left');
        $columns[] = array('data' => lang('reports_subtotal'), 'align' => 'right');
        $columns[] = array('data' => lang('reports_total'), 'align' => 'right');
        $columns[] = array('data' => lang('common_tax'), 'align' => 'right');

        if ($this->Employee->has_module_action_permission('reports', 'show_profit', $this->Employee->get_logged_in_employee_info()->person_id)) {
            $columns[] = array('data' => lang('common_profit'), 'align' => 'right');
        }

        return $columns;
    }

    public function getData() {
        $location_ids = self::get_selected_location_ids();
        $location_ids_string = implode(',', $location_ids);


        $this->db->select('items.item_id,items.unit_price as current_selling_price, items.name, items.item_number, items.product_id, category, location_items.quantity , sum(quantity_purchased) as quantity_purchased, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
        $this->db->from('sales_items_temp');
        $this->db->join('items', 'sales_items_temp.item_id = items.item_id');
        $this->db->join('location_items', 'sales_items_temp.item_id = location_items.item_id', 'left');
        if ($this->params['sale_type'] == 'sales') {
            $this->db->where('quantity_purchased > 0');
        } elseif ($this->params['sale_type'] == 'returns') {
            $this->db->where('quantity_purchased < 0');
        }

        if ($this->params['category_id'] != -1) {
            $this->db->where('items.category_id', $this->params['category_id']);
        }

        if ($this->params['supplier_id'] != -1) {
            $this->db->where('sales_items_temp.supplier_id', $this->params['supplier_id']);
        }

        if (isset($this->params['compare_to_items']) && count($this->params['compare_to_items']) > 0) {
            $this->db->where_in('items.item_id', $this->params['compare_to_items']);
        }

        $this->db->where($this->db->dbprefix('sales_items_temp') . '.deleted', 0);
        $this->db->group_by('items.item_id');
        $this->db->order_by('name');

        //If we are exporting NOT exporting to excel make sure to use offset and limit
        if (isset($this->params['export_excel']) && !$this->params['export_excel']) {
            $this->db->limit($this->report_limit);
            $this->db->offset($this->params['offset']);
        }

        return $this->db->get()->result_array();
    }

    public function getItemsData() {
        $location_ids = self::get_selected_location_ids();

        $this->db->select('items.*, locations.location_id, locations.name as location_name, categories.id, categories.name as ctegory_name ', false);
        $this->db->from('items');
        $this->db->join('locations', 'locations.location_id = items.item_location_id');
        $this->db->join('categories', 'categories.id = items.category_id');
        if ($this->params['category_id'] != -1) {
            $this->db->where('items.category_id', $this->params['category_id']);
        }
        if (count($location_ids) > 0) {
            $this->db->where_in('items.item_location_id', $location_ids);
        }
        if ($this->params['start_date']) {
            $this->db->where('items.create_on between "' . $this->params['start_date'] . '" and "' . $this->params['end_date'] . '"');
        }
        $this->db->order_by('items.create_on');

        //If we are exporting NOT exporting to excel make sure to use offset and limit
        if (isset($this->params['export_excel']) && !$this->params['export_excel']) {
            $this->db->limit($this->report_limit);
            $this->db->offset($this->params['offset']);
        }

        return $this->db->get()->result_array();
    }

    public function getItemsDataTotalRows() {
        $location_ids = self::get_selected_location_ids();


        $this->db->select('items.*, locations.*, categories.*', false);
        $this->db->from('items');
        $this->db->join('locations', 'locations.location_id = items.item_location_id');
        $this->db->join('categories', 'categories.id = items.category_id');
        if ($this->params['category_id'] != -1) {
            $this->db->where('items.category_id', $this->params['category_id']);
        }
        if (count($location_ids) > 0) {
            $this->db->where_in('items.item_location_id', $location_ids);
        }
        if ($this->params['start_date']) {
            $this->db->where('items.create_on between "' . $this->params['start_date'] . '" and "' . $this->params['end_date'] . '"');
        }
        $this->db->order_by('items.create_on');

        //If we are exporting NOT exporting to excel make sure to use offset and limit
        if (isset($this->params['export_excel']) && !$this->params['export_excel']) {
            $this->db->limit($this->report_limit);
            $this->db->offset($this->params['offset']);
        }
        $result = $this->db->get();
        if (is_object($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

    function getTotalRows() {
        $this->db->select('COUNT(DISTINCT(' . $this->db->dbprefix('sales_items_temp') . '.item_id)) as item_count');
        $this->db->from('sales_items_temp');
        $this->db->join('items', 'items.item_id = sales_items_temp.item_id');

        if ($this->params['sale_type'] == 'sales') {
            $this->db->where('quantity_purchased > 0');
        } elseif ($this->params['sale_type'] == 'returns') {
            $this->db->where('quantity_purchased < 0');
        }

        if ($this->params['category_id'] != -1) {
            $this->db->where('items.category_id', $this->params['category_id']);
        }

        if ($this->params['supplier_id'] != -1) {
            $this->db->where('sales_items_temp.supplier_id', $this->params['supplier_id']);
        }

        if (isset($this->params['compare_to_items']) && count($this->params['compare_to_items']) > 0) {
            $this->db->where_in('items.item_id', $this->params['compare_to_items']);
        }

        $this->db->where($this->db->dbprefix('sales_items_temp') . '.deleted', 0);

        $ret = $this->db->get()->row_array();
        return $ret['item_count'];
    }

    public function getSummaryData() {
        $this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
        $this->db->from('sales_items_temp');
        $this->db->join('items', 'sales_items_temp.item_id = items.item_id');
        if ($this->params['sale_type'] == 'sales') {
            $this->db->where('quantity_purchased > 0');
        } elseif ($this->params['sale_type'] == 'returns') {
            $this->db->where('quantity_purchased < 0');
        }
        $this->db->where($this->db->dbprefix('sales_items_temp') . '.deleted', 0);

        if ($this->config->item('hide_store_account_payments_from_report_totals')) {
            $this->db->where('store_account_payment', 0);
        }

        if ($this->params['category_id'] != -1) {
            $this->db->where('items.category_id', $this->params['category_id']);
        }

        if ($this->params['supplier_id'] != -1) {
            $this->db->where('sales_items_temp.supplier_id', $this->params['supplier_id']);
        }


        $this->db->group_by('sale_id');

        $return = array(
            'subtotal' => 0,
            'total' => 0,
            'tax' => 0,
            'profit' => 0,
        );

        foreach ($this->db->get()->result_array() as $row) {
            $return['subtotal'] += to_currency_no_money($row['subtotal'], 2);
            $return['total'] += to_currency_no_money($row['total'], 2);
            $return['tax'] += to_currency_no_money($row['tax'], 2);
            $return['profit'] += to_currency_no_money($row['profit'], 2);
        }
        if (!$this->Employee->has_module_action_permission('reports', 'show_profit', $this->Employee->get_logged_in_employee_info()->person_id)) {
            unset($return['profit']);
        }
        return $return;
    }

}

?>