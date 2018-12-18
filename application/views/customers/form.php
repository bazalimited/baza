<?php $this->load->view("partial/header"); ?>

<div class="row" id="form">
    <div class="spinner" id="grid-loader" style="display:none">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
    </div>

    <div class="col-md-12">

        <?php if ($person_info->person_id) { ?>
            <div class="panel">
                <div class="panel-body">
                    <div class="user-badge">
                        <?php echo $person_info->image_id ? '<div class="user-badge-avatar">' . img(array('src' => site_url('app_files/view/' . $person_info->image_id), 'class' => 'img-polaroid img-polaroid-s')) . '</div>' : '<div class="user-badge-avatar">' . img(array('src' => base_url('assets/assets/images/avatar-default.jpg'), 'class' => 'img-polaroid', 'id' => 'image_empty')) . '</div>'; ?>
                        <div class="user-badge-details">
                            <?php echo $person_info->first_name . ' ' . $person_info->last_name; ?>
                            <?php if ($this->config->item('customers_store_accounts')) { ?>
                                <div class="amount">
                                    <?php echo lang('customers_store_account_balance') . ': '; ?>
                                    <?php echo $person_info->balance ? to_currency($person_info->balance) : '0.00'; ?>
                                </div>
                            <?php } ?>
                            <?php
                            if ($this->config->item('enable_customer_loyalty_system') && $this->config->item('loyalty_option') == 'simple') {
                                ?>
                                <div class="amount">								
                                    <?php echo lang('common_sales_until_discount') . ': '; ?>
                                    <?php
                                    $sales_until_discount = $this->config->item('number_of_sales_for_discount') - $person_info->current_sales_for_discount;

                                    echo to_quantity($sales_until_discount);
                                    ?>
                                </div>

                                <?php
                            }
                            ?>

                            <?php
                            if ($this->config->item('enable_customer_loyalty_system') && $this->config->item('loyalty_option') == 'advanced') {
                                list($spend_amount_for_points, $points_to_earn) = explode(":", $this->config->item('spend_to_point_ratio'), 2);
                                ?>
                                <div class="amount">
                                    <?php echo lang('common_points') . ': '; ?>
                                    <?php echo to_quantity($person_info->points); ?>
                                </div>

                                <div class="amount">
                                    <?php echo lang('customers_amount_to_spend_for_next_point') . ': '; ?>
                                    <?php echo to_currency($spend_amount_for_points - $person_info->current_spend_for_points); ?>
                                </div>								

                                <?php
                            }
                            ?>
                        </div>
                        <ul class="list-inline pull-right">
                            <?php
                            $six_months_ago = date('Y-m-d', strtotime('-6 months'));
                            $today = date('Y-m-d') . '%2023:59:59';
                            ?>
                            <li><a href="<?php echo site_url('reports/specific_customer/' . $six_months_ago . '/' . $today . '/' . $person_info->person_id . '/all/0'); ?>" class="btn btn-success"><?php echo lang('common_view_report'); ?></a></li>
                            <?php if ($person_info->email) { ?>
                                <li><a href="mailto:<?php echo $person_info->email; ?>" class="btn btn-primary"><?php echo lang('common_send_email'); ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php echo form_open_multipart('customers/save/' . $person_info->person_id, array('id' => 'customer_form', 'class' => 'form-horizontal')); ?>

        <div class="panel panel-piluku">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="ion-edit"></i> 
                    <?php echo lang("customers_basic_information"); ?>
                    <small>(<?php echo lang('common_fields_required_message'); ?>)</small>
                </h3>
            </div>

            <div class="panel-body">
                <div class="form-group">	
                    <?php echo form_label(lang('common_company') . ' :', 'company_name', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10">
                        <?php
                        echo form_input(array(
                            'name' => 'company_name',
                            'id' => 'company_name',
                            'class' => 'company_names form-control',
                            'value' => $person_info->company_name)
                        );
                        ?>
                    </div>
                </div>
                <?php $this->load->view("people/form_basic_info"); ?>

                <div class="form-actions pull-right">
                    <?php
                    if ($redirect_code == 1) {
                        echo form_button(array(
                            'name' => 'cancel',
                            'id' => 'cancel',
                            'class' => 'submit_button btn btn-danger',
                            'value' => 'true',
                            'content' => lang('common_cancel')
                        ));
                    }
                    ?>

                    <?php
                    echo form_submit(array(
                        'name' => 'submitf',
                        'id' => 'submitf',
                        'value' => lang('common_submit'),
                        'class' => ' submit_button btn btn-primary')
                    );
                    ?>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>
</div><!-- /row -->
</div>

<script type='text/javascript'>

    $(".override_default_tax_checkbox").change(function()
    {
    $(this).parent().parent().next().toggleClass('hidden')
    });
            check_taxable();
            $("#taxable").change(check_taxable);
            function check_taxable()
            {
            if ($("#taxable").prop('checked'))
            {
            $("#tax_certificate_holder").hide();
            }
            else
            {
            $("#tax_certificate_holder").show();
            }
            }

    $('#image_id').imagePreview({ selector : '#avatar' }); // Custom preview container
            //validation and submit handling
            $(document).ready(function()
    {
    $("#cancel").click(cancelCustomerAddingFromSale);
            setTimeout(function(){$(":input:visible:first", "#customer_form").focus(); }, 100);
            var submitting = false;
            $('#customer_form').validate({
    submitHandler:function(form)
    {
    $.post('<?php echo site_url("customers/check_duplicate"); ?>', {name: $('#first_name').val() + ' ' + $('#last_name').val(), email: $("#email").val(), phone_number: $("#phone_number").val()}, function(data) {
<?php if (!$person_info->person_id) { ?>
        if (data.duplicate)
        {
        bootbox.confirm(<?php echo json_encode(lang('customers_duplicate_exists')); ?>, function(result)
        {
        if (result)
        {
        doCustomerSubmit(form);
        }
        });
        }
        else
        {
        doCustomerSubmit(form);
        }
<?php } else { ?>
        doCustomerSubmit(form);
<?php } ?>
    }, "json")
            .error(function() {
            });
    },
            rules:
    {
<?php if (!$person_info->person_id) { ?>
        account_number:
        {
        remote:
        {
        url: "<?php echo site_url('customers/account_number_exists'); ?>",
                type: "post"

        }
        },
<?php } ?>
    first_name: "required",
    last_name: "required",
    phone_number: "required",
    address_1: "required"
    },
            errorClass: "text-danger",
            errorElement: "span",
            highlight:function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
            },
            messages:
    {
<?php if (!$person_info->person_id) { ?>
        account_number:
        {
        remote: <?php echo json_encode(lang('common_account_number_exists')); ?>
        },
<?php } ?>
    first_name: <?php echo json_encode(lang('common_first_name_required')); ?>,
            last_name: <?php echo json_encode(lang('common_last_name_required')); ?>
    }
    });
    });
            var submitting = false;
            function doCustomerSubmit(form)
            {
            $("#grid-loader").show();
                    if (submitting) return;
                    submitting = true;
                    $(form).ajaxSubmit({
            success:function(response)
            {
            $("#grid-loader").hide();
                    submitting = false;
                    show_feedback(response.success ? 'success' : 'error', response.message, response.success ? <?php echo json_encode(lang('common_success')); ?> : <?php echo json_encode(lang('common_error')); ?>);
                    if (response.redirect_code == 1 && response.success)
            {
            $.post('<?php echo site_url("sales/select_customer"); ?>', {customer: response.person_id}, function()
            {
            window.location.href = '<?php echo site_url('sales/index/1'); ?>';
            });
            }
            else if (response.redirect_code == 2 && response.success)
            {
            window.location.href = '<?php echo site_url('customers'); ?>';
            }
            else
            {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            }
            },
<?php if (!$person_info->person_id) { ?>
                resetForm: true,
<?php } ?>
            dataType:'json'
            });
            }

    function cancelCustomerAddingFromSale()
    {
    bootbox.confirm(<?php echo json_encode(lang('customers_are_you_sure_cancel')); ?>, function(response)
    {
    if (response)
    {
    window.location = <?php echo json_encode(site_url('sales')); ?>;
    }
    });
    }
</script>

<?php $this->load->view("partial/footer"); ?>
