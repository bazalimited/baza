<?php $this->load->view("partial/header"); ?>

<?php echo form_open_multipart('items/save_category/' . $slected_category->id, array('id' => 'item_form', 'class' => 'form-horizontal')); ?>
<div class="row" id="form">
    <div class="spinner" id="grid-loader" style="display:none">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-piluku">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="ion-edit"></i> 
                    <?php echo lang("category_basic_information"); ?>
                    <small>(<?php echo lang('common_fields_required_message'); ?>)</small>
                </h3>
            </div>

            <div class="panel-body">

                <div class="form-group">
                    <?php echo form_label(lang('common_category_name') . ' :', 'name', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_input(array(
                            'name' => 'name',
                            'id' => 'category_name',
                            'class' => 'form-control form-inps',
                            'value' => $slected_category->name)
                        );
                        ?>
                        <input type="hidden" id="category_id" value="<?php echo $slected_category->id ?>" />
                        <input type="hidden" id="category_initial_name" value="<?php echo $slected_category->name; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label(lang('common_owner_payment_amount') . ' :', 'owner_payment_amount', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_input(array(
                            'name' => 'owner_payment_amount',
                            'id' => 'owner_payment_amount',
                            'type' => 'number',
                            'class' => 'form-control form-inps',
                            'value' => $slected_category->owner_payment_amount)
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label(lang('common_agent_payment_amount') . ' :', 'agent_payment_amount', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_input(array(
                            'name' => 'agent_payment_amount',
                            'id' => 'agent_payment_amount',
                            'type' => 'number',
                            'class' => 'form-control form-inps',
                            'value' => $slected_category->agent_payment_amount)
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label(lang('common_found_payment_percentage') . ' :', 'common_found_payment_percentage', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_input(array(
                            'name' => 'found_payment_percentage',
                            'id' => 'found_payment_percentage',
                            'type' => 'number',
                            'min'=>0,
                            'max'=>100,
                            'placeholder'=>'Percentage(eg: 5)',
                            'class' => 'form-control form-inps',
                            'value' => $slected_category->found_payment_percentage)
                        );
                        ?>
                    </div>
                </div>

                <div class="form-group">	
                    <?php echo form_label(lang('allow_sn_recording') . ' :', 'record_sn', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        if (isset($slected_category->id)) {
                            echo form_checkbox(array(
                                'name' => 'record_sn',
                                'id' => 'record_sn',
                                'checked' => ($slected_category->record_sn == 1) ? true : false,
                                'style' => 'display: inline;',
                            ));
                        } else {
                            echo form_checkbox(array(
                                'name' => 'record_sn',
                                'id' => 'record_sn',
                                'checked' => true,
                                'style' => 'display: inline;',
                            ));
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group">	
                    <?php echo form_label(lang('item_has_identification') . ' :', 'has_identification', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        if (isset($slected_category->id)) {
                            echo form_checkbox(array(
                                'name' => 'has_identification',
                                'id' => 'has_identification',
                                'checked' => ($slected_category->has_identification == 1) ? true : false,
                                'style' => 'display: inline;',
                            ));
                        } else {
                            echo form_checkbox(array(
                                'name' => 'has_identification',
                                'id' => 'has_identification',
                                'checked' => true,
                                'style' => 'display: inline;',
                            ));
                        }
                        ?>
                    </div>
                </div>
                <div class="form-actions pull-right">

                    <?php
                    echo form_submit(array(
                        'name' => 'submitf',
                        'id' => 'submitf',
                        'onclick' => 'return validate_form()',
                        'value' => lang('common_submit'),
                        'class' => 'submit_button btn btn-primary')
                    );
                    ?>
                </div>

            </div><!-- /panel-body-->
        </div><!--/panel-piluku-->
    </div>
</div>


<script type='text/javascript'>
    function validate_form() {

                    //Check the name
                    if ($('#category_name').val() !== '') {
                            if ($('#category_name').val() !== $('#category_initial_name').val()) {
                            url = '<?php echo site_url("ajax/category_name_exists"); ?>';
                                                        var exists = false;
                $.ajax({
                                                    type: 'POST',
                                                async: false,
                                            url: url,
                                                data: {
                                    category_name: $('#category_name').val()
                                        },
                    success: function (data) {
                                    if (data === "true") {
                                    exists = true;
                                                    alert('<?php echo lang('category_name_exists'); ?>');
                                                    $('#category_name').focus().select();

                                                }
                    }
                });
                                                    if (exists) {
                    return false;
                                                    }
            }
        } else {
            alert('<?php echo lang('category_name_required'); ?>');
            return false;
        }

        if ($('#owner_payment_amount').val() === '') {
            alert('<?php echo lang('owner_payment_required'); ?>');
            $('#owner_payment_amount').focus().select();
            return false;
        }
        if ($('#found_payment_percentage').val() === '') {
            alert('<?php echo lang('common_found_payment_percentage'); ?>');
            $('#found_payment_percentage').focus().select();
            return false;
        }

    }

</script>
<?php echo form_close(); ?>
</div>
<?php $this->load->view('partial/footer'); ?>
