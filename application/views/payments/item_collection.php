<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
    $(document).ready(function ()
    {
<?php
$has_cost_price_permission = $this->Employee->has_module_action_permission('items', 'see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id);
if ($has_cost_price_permission) {
    ?>
            var table_columns = ["", "item_id", "item_number", 'name', 'category', 'size', 'cost_price', 'unit_price', 'quantity', '', '', '', '', ''];
    <?php
} else {
    ?>
            var table_columns = ["", "item_id", "item_number", 'name', 'category', 'size', 'unit_price', 'quantity', '', '', '', '', ''];
    <?php
}
?>
        enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>", table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col); ?>, <?php echo json_encode($order_dir); ?>);
        enable_select_all();
        enable_checkboxes();
        enable_row_selection();
        enable_search('<?php echo site_url("$controller_name"); ?>',<?php echo json_encode(lang("common_confirm_search")); ?>);
        enable_cleanup(<?php echo json_encode(lang("items_confirm_cleanup")); ?>);

        $('#generate_barcodes').click(function ()
        {
            var selected = get_selected_values();
            if (selected.length == 0)
            {
                bootbox.alert(<?php echo json_encode(lang('common_must_select_item_for_barcode')); ?>);
                return false;
            }

            $(this).attr('href', '<?php echo site_url("items/generate_barcodes"); ?>/' + selected.join('~'));
        });

        $('#generate_barcode_labels').click(function ()
        {
            var selected = get_selected_values();
            if (selected.length == 0)
            {
                bootbox.alert(<?php echo json_encode(lang('common_must_select_item_for_barcode')); ?>);
                return false;
            }

            $(this).attr('href', '<?php echo site_url("items/generate_barcode_labels"); ?>/' + selected.join('~'));
        });

<?php if ($this->session->flashdata('manage_success_message')) { ?>
            show_feedback('success', <?php echo json_encode($this->session->flashdata('manage_success_message')); ?>, <?php echo json_encode(lang('common_success')); ?>);
<?php } ?>
    });

    function post_bulk_form_submit(response)
    {
        window.location.reload();
    }

    function select_inv()
    {
        bootbox.confirm(<?php echo json_encode(lang('items_select_all_message')); ?>, function (result)
        {
            if (result)
            {
                $('#select_inventory').val(1);
                $('#selectall').css('display', 'none');
                $('#selectnone').css('display', 'block');
                $.post('<?php echo site_url("items/select_inventory"); ?>', {select_inventory: $('#select_inventory').val()});
            }
        });
    }
    function select_inv_none()
    {
        $('#select_inventory').val(0);
        $('#selectnone').css('display', 'none');
        $('#selectall').css('display', 'block');
        $.post('<?php echo site_url("items/clear_select_inventory"); ?>', {select_inventory: $('#select_inventory').val()});
    }

    $.post('<?php echo site_url("items/clear_select_inventory"); ?>', {select_inventory: $('#select_inventory').val()});

</script>

<div class="manage_buttons">
    <div class="manage-row-options hidden">
        <div class="email_buttons items">
            <?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
                <?php
                echo
                anchor("$controller_name/bulk_edit/", '<span class="">' . lang("items_bulk_edit") . '</span>', array('id' => 'bulk_edit', 'data-toggle' => 'modal', 'data-target' => '#myModal',
                    'class' => 'btn btn-primary btn-lg  disabled',
                    'title' => lang('items_edit_multiple_items')));
                ?>
            <?php } ?>
            <?php
            echo
            anchor("$controller_name/generate_barcode_labels", '<span class="">' . lang("common_barcode_labels") . '</span>', array('id' => 'generate_barcode_labels',
                'class' => 'btn btn-primary btn-lg  disabled',
                'title' => lang('common_barcode_labels')));
            ?>
            <?php
            echo
            anchor("$controller_name/generate_barcodes", '<span class="">' . lang("common_barcode_sheet") . '</span>', array('id' => 'generate_barcodes',
                'class' => 'btn btn-primary btn-lg  disabled',
                'target' => '_blank',
                'title' => lang('common_barcode_sheet')));
            ?>

            <?php if ($this->Employee->has_module_action_permission($controller_name, 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>				
                <?php
                echo
                anchor("$controller_name/delete", '<span class="">' . lang("common_delete") . '</span>', array('id' => 'delete',
                    'class' => 'btn btn-red btn-lg disabled', 'title' => lang("common_delete")));
                ?>
            <?php } ?>
            <a href="#" class="btn btn-lg btn-clear-selection btn-warning"><?php echo lang('common_clear_selection'); ?></a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo form_open("$controller_name/search_uncollected", array('id' => 'search_form', 'autocomplete' => 'off', 'class' => '')); ?>
            <div class="search search-items no-left-border">
                <ul class="list-inline">
                    <li>	
                        <input type="text" class="form-control" name ='search' id='search' value="<?php echo H($search); ?>" placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_' . $controller_name); ?>"/>
                    </li>

                    <li style="width: 20%; display: none;">	
                        <?php echo lang('common_fields'); ?>: 
                        <?php
                        echo form_dropdown('fields', array(
                            'all' => lang('common_all'),
                            $this->db->dbprefix('items') . '.item_id' => lang('common_item_id'),
                            $this->db->dbprefix('items') . '.item_number' => lang('common_item_number_expanded'),
                            $this->db->dbprefix('items') . '.name' => lang('common_item_name'),
                            $this->db->dbprefix('items') . '.description' => lang('common_description'),
                                ), $fields, 'class="form-control" id="fields"');
                        ?>
                    </li>
                    <li style="width: 40%; margin-left: 17px; ">
                        <?php echo lang('common_category'); ?>: 
                        <?php echo form_dropdown('category_id', $categories, $category_id, 'class="form-control" id="category_id"'); ?>
                    </li>
                    <li style="width: 40%;">	
                        <?php echo lang('common_location'); ?>: 	
                        <?php echo form_dropdown('location_id', $user_locations, $location_id, 'class="form-control" id="location_id"'); ?>
                    </li><br />
                    <li>		
                        <div class="form-group offset1">
                            <?php echo form_label(lang('common_from') . ': ', 'from_date', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?><br /><br />
                            <div class="col-sm-12 col-md-12 col-lg-10">
                                <div class="input-group date" data-date="<?php echo $item_info->start_date ? date(get_date_format(), strtotime($item_info->start_date)) : ''; ?>">
                                    <span class="input-group-addon bg">
                                        <i class="ion ion-ios-calendar-outline"></i>
                                    </span>
                                    <?php
                                    echo form_input(array(
                                        'name' => 'from_date',
                                        'id' => 'from_date',
                                        'class' => 'form-control datepicker',
                                        'value' => $from_date ? date(get_date_format(), strtotime($from_date)) : '')
                                    );
                                    ?> 
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group offset1">
                            <?php echo form_label(lang('common_to') . ': ', 'to_date', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?><br /><br />
                            <div class="col-sm-12 col-md-12 col-lg-10">
                                <div class="input-group date" data-date="<?php echo $item_info->start_date ? date(get_date_format(), strtotime($item_info->start_date)) : ''; ?>">
                                    <span class="input-group-addon bg">
                                        <i class="ion ion-ios-calendar-outline"></i>
                                    </span>
                                    <?php
                                    echo form_input(array(
                                        'name' => 'to_date',
                                        'id' => 'to_date',
                                        'class' => 'form-control datepicker',
                                        'value' => $to_date ? date(get_date_format(), strtotime($to_date)) : '')
                                    );
                                    ?> 
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><?php echo form_submit('submitf', lang('common_search'), 'class="btn btn-primary btn-lg"'); ?></li>
                    <li>
                        <div class="clear-block items-clear-block <?php echo ($search == '') ? 'hidden' : '' ?>">
                            <a class="clear" href="<?php echo site_url($controller_name . '/clear_state'); ?>">
                                <i class="ion ion-close-circled"></i>
                            </a>	
                        </div>
                    </li>
                </ul>
            </div>
            <?php echo form_close() ?>

        </div>
    </div>
</div>


<div class="row alert-select-all">
    <div class="col-md-12">

        <?php
        echo form_input(array(
            'name' => 'select_inventory',
            'id' => 'select_inventory',
            'style' => 'display:none',
        ));
        ?>
    </div>
</div>



<div class="container-fluid">
    <div class="row manage-table">
        <div class="panel panel-piluku">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo lang('common_list_of_not_collected'); ?>
                    <span title="<?php echo $total_rows; ?> total <?php echo $controller_name ?>" class="badge bg-primary tip-left"><?php echo $total_rows; ?></span>
                    <div class="panel-options custom">
                        <?php if ($pagination) { ?>
                            <div class="pagination pagination-top hidden-print  text-center" id="pagination_top">
                                <?php echo $pagination; ?>		
                            </div>
                        <?php } ?>
                    </div>
                </h3>
            </div>
            <div class="panel-body nopadding table_holder table-responsive" >
                <?php echo $manage_table; ?>			
            </div>		

        </div>
    </div>
</div>
<?php if ($pagination) { ?>
    <div class="text-center">
        <div class="row pagination hidden-print alternate text-center" id="pagination_bottom" >
            <?php echo $pagination; ?>
        </div>
    </div>
<?php } ?>
</div>

<script type='text/javascript'>

    date_time_picker_field($('.datepicker'), JS_DATE_FORMAT);
</script>
<?php $this->load->view("partial/footer"); ?>
