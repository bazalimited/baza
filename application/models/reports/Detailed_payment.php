<?php

require_once ("Report.php");

class Detailed_payment extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getDataColumns() {

        $columns = array(
            array('data' => lang('reports_id'), 'align' => 'left')
            , array('data' => lang('common_item_name'), 'align' => 'left')
            , array('data' => lang('common_description'), 'align' => 'left')
            , array('data' => lang('common_category'), 'align' => 'left')
            , array('data' => lang('reports_payment_date'), 'align' => 'left')
            , array('data' => lang('common_amount'), 'align' => 'left')
        );

        $location_count = count(self::get_selected_location_ids());

        if ($location_count > 1) {
            array_unshift($columns, array('data' => lang('common_location'), 'align' => 'left'));
        }
        return $columns;
    }

    public function getData() {
//        $location_ids = self::get_selected_location_ids();
        $this->db->select(
                'items.item_id,
                items.`name` as item_name,
                items.`owner_payment_id`,
                items.`agent_payment_id`,
                categories.`name` as category_name,
                items.description,
                payments.payment_date,
                payments.amount,
                payments.payed_by_id,
                payments.date_created', false);
        $this->db->from('items');
        $this->db->join('payments', 'items.item_id = payments.item_id and items.owner_payment_id = payments.payment_id');
        $this->db->join('categories', 'categories.id = items.category_id');
        $this->db->where('payments.deleted', 0);
        if (isset($this->params['start_date']) && isset($this->params['end_date'])) {
            $this->db->where($this->db->dbprefix('payments') . '.payment_date BETWEEN ' . $this->db->escape($this->params['start_date']) . ' and ' . $this->db->escape($this->params['end_date']));
        }
        $this->db->order_by('payments.payment_id');
        //If we are exporting NOT exporting to excel make sure to use offset and limit
//        if (isset($this->params['export_excel']) && !$this->params['export_excel']) {
//            $this->db->limit($this->report_limit);
//            $this->db->offset($this->params['offset']);
//        }
        
        return $this->db->get()->result_array();
    }

    public function getSummaryData() {
        $location_ids = self::get_selected_location_ids();
        $this->db->select('SUM(expense_amount) as total_expenses,SUM(expense_tax) as total_taxes', false);
        $this->db->from('expenses');
        $this->db->where('deleted', 0);
        if (isset($this->params['start_date']) && isset($this->params['end_date'])) {
            $this->db->where($this->db->dbprefix('expenses') . '.expense_date BETWEEN ' . $this->db->escape($this->params['start_date']) . ' and ' . $this->db->escape($this->params['end_date']));
        }
        $this->db->where_in('expenses.location_id', $location_ids);
        return $this->db->get()->row_array();
    }

    function getTotalRows() {
        $this->db->from('payments');
        $this->db->where('payments.deleted', 0);
        if (isset($this->params['start_date']) && isset($this->params['end_date'])) {
            $this->db->where($this->db->dbprefix('payments') . '.payment_date BETWEEN ' . $this->db->escape($this->params['start_date']) . ' and ' . $this->db->escape($this->params['end_date']));
        }
        $this->db->join('items', 'items.owner_payment_id = payments.payment_id');
        $this->db->order_by('payment_id');
        
        $result = $this->db->get();
        if (is_object($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

}

?>