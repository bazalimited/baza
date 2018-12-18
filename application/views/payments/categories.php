<?php $this->load->view("partial/header"); ?>
<?php echo form_open('items/save_category/', array('id' => 'categories_form', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-md-12">

        <div class="panel-piluku panel">
            <div class="panel-heading"><?php echo lang("items_manage_categories"); ?></div>
            <div class="panel-body">
                <div id="category_tree">
                    <div class="panel panel-piluku">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <?php echo lang("items_category_list"); ?>                    
                                <span title="5 total locations" class="badge bg-primary tip-left"><?php echo $category_conter; ?></span>
                                <span class="panel-options custom">
                                </span>
                                <a href="javascript:void(0);" class="add_child_category btn btn-primary btn-lg" data-category_id="0" style="float: right; color: #FFFFFF;"><?php echo lang('items_add_root_category'); ?></a>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="panel-body nopadding table_holder table-responsive">
                    <?php echo $category_tree; ?>
                </div>		
            </div>
        </div>
    </div>
</div>
</div>
</div><!-- /row -->
<?php echo form_close(); ?>
</div>


<script type='text/javascript'>

    $(document).on('click', ".edit_category", function ()
    {
        var parent_id = $(this).data('parent_id');
        var category_id = $(this).data('category_id');
        bootbox.prompt({
            title: <?php echo json_encode(lang('items_please_enter_category_name')); ?>,
            value: $(this).data('name'),
            callback: function (category_name) {

                if (category_name)
                {
                    $.post('<?php echo site_url("items/save_category"); ?>' + '/' + category_id, {category_name: category_name, parent_id: parent_id}, function (response) {

                        show_feedback(response.success ? 'success' : 'error', response.message, response.success ? <?php echo json_encode(lang('common_success')); ?> : <?php echo json_encode(lang('common_error')); ?>);

                        //Refresh tree if success
                        if (response.success)
                        {
                            location.reload(); 
                        }
                    }, "json");
                }
            }
        });
    });

    $(document).on('click', ".add_child_category", function ()
    {
        var parent_id = $(this).data('category_id');

        bootbox.prompt(<?php echo json_encode(lang('items_please_enter_category_name')); ?>, function (category_name)
        {
            if (category_name)
            {
                $.post('<?php echo site_url("items/save_category"); ?>', {category_name: category_name, parent_id: parent_id}, function (response) {

                    show_feedback(response.success ? 'success' : 'error', response.message, response.success ? <?php echo json_encode(lang('common_success')); ?> : <?php echo json_encode(lang('common_error')); ?>);

                    //Refresh tree if success
                    if (response.success)
                    {
                        location.reload(); 
                    }
                }, "json");

            }
        });
    });

    $(document).on('click', ".delete_category", function ()
    {
        var category_id = $(this).data('category_id');
        if (category_id)
        {
            bootbox.confirm(<?php echo json_encode(lang('items_category_delete_confirmation')); ?>, function (result)
            {
                if (result)
                {

                    $.post('<?php echo site_url("items/delete_category"); ?>', {category_id: category_id}, function (response) {

                        show_feedback(response.success ? 'success' : 'error', response.message, response.success ? <?php echo json_encode(lang('common_success')); ?> : <?php echo json_encode(lang('common_error')); ?>);

                        //Refresh tree if success
                        if (response.success)
                        {
                            location.reload(); 
                        }
                    }, "json");
                }
            });
        }

    });

    $(document).on('click', ".hide_from_grid", function ()
    {
        var category_id = $(this).data('category_id');
        if (category_id)
        {
            $.post('<?php echo site_url("items/save_category"); ?>' + '/' + category_id, {hide_from_grid: $(this).prop('checked') ? 1 : 0}, function (response) {

                show_feedback(response.success ? 'success' : 'error', response.message, response.success ? <?php echo json_encode(lang('common_success')); ?> : <?php echo json_encode(lang('common_error')); ?>);
                //Refresh tree if success
                if (response.success)
                {
                    $('#category_tree').load("<?php echo site_url("items/get_category_tree_list"); ?>");
                }
            }, "json");

        }

    });

</script>
<?php $this->load->view('partial/footer'); ?>
