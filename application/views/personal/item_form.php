<?php $this->load->view("partial_public/header"); ?>

<?php echo form_open_multipart('personal/save_item/' . $item_id, array('id' => 'item_form', 'class' => 'form-horizontal')); ?>
<div class="row form_fields" id="form">
    <div class="spinner" id="grid-loader" style="display:none">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
    </div>
    <input type="hidden" id="item_id" value="<?php echo $selected_item_id; ?>" />
    <input type="hidden" id="item_sn" value="<?php
    if (isset($item_info->name_on_card)) {
        echo $item_info->name_on_card;
    } else {
        echo $item_info->item_number;
    }
    ?>" />
    <div class="col-md-12">
        <div class="panel panel-piluku">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="ion-edit"></i> 
                    <?php echo lang("items_basic_information"); ?>
                    <small>(<?php echo lang('common_fields_required_message'); ?>)</small>
                </h3>
            </div>
            <div class="form-group">
                <?php echo form_label(lang('common_category') . ' :', 'category_id', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required wide')); ?>
                <div class="col-sm-9 col-md-9 col-lg-10">
                    <?php echo form_dropdown('category_id', $categories, ($category_id != '') ? $category_id : $item_info->category_id, 'class="form-control form-inps" id ="category_id" onchange="return check_category(this.value)"'); ?>
                    <?php if ($this->Employee->has_module_action_permission('items', 'manage_categories', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
                        <div>
                            <?php echo anchor("items/categories", lang('items_manage_categories'), array('target' => '_blank', 'title' => lang('items_manage_categories'))); ?>
                        </div>
                    <?php } ?>		
                </div>

            </div>
            <div class="form-group">
                <?php echo form_label(lang('common_item_name') . ' :', 'name', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label required wide')); ?>
                <div class="col-sm-9 col-md-9 col-lg-10 validation">
                    <?php
                    echo form_input(array(
                        'name' => 'name',
                        'id' => 'name',
                        'class' => 'form-control form-inps',
                        'value' => $item_info->name)
                    );
                    ?>
                </div>
                <span class="error_message" style="display: none;"></span>
            </div>
            <div id="sn_label">

                <div class="form-group">
                    <?php
                    if ($has_identification) {
                        if ($selected_type == 1 || ($category_id == 0 && $selected_item_id == -1)) {
                            ?>
                            <div><?php echo form_label(lang('common_item_number_expanded') . ' :', 'item_number', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?></div>
                            <div class="col-sm-9 col-md-9 col-lg-10 validation">
                                <?php
                                echo form_input(array(
                                    'name' => 'item_number',
                                    'id' => 'serial_number',
                                    'class' => 'form-control form-inps',
                                    'value' => $item_info->item_number)
                                );
                                ?>
                            </div>
                        <?php } else { ?>
                            <div id="sn_label"><?php echo form_label(lang('common_item_name_on_card') . ' :', 'name_on_card', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>

                                <div class="col-sm-9 col-md-9 col-lg-10 validation">
                                    <?php
                                    echo form_input(array(
                                        'name' => 'name_on_card',
                                        'id' => 'serial_number',
                                        'class' => 'form-control form-inps',
                                        'value' => $item_info->name_on_card)
                                    );
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <span class="error_message" style="display: none;"></span>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label(lang('common_description') . ' :', 'description', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label wide')); ?>
                <div class="col-sm-9 col-md-9 col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'description',
                        'id' => 'description',
                        'value' => $item_info->description,
                        'class' => 'form-control  text-area',
                        'rows' => '5',
                        'cols' => '17')
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Imange' . ' :', 'image_id1', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                <div class="col-sm-9 col-md-9 col-lg-10">
                    <div class="image-upload">
                        <input type="file" id="image_id" accept="image/*" name="image_id" class="filestyle" data-icon="false" >
                    </div>
                    <br />
                    <div>
                        <div id="avatar">
                            <?php echo $item_info->image_id ? img(array('src' => site_url('personal_app_files/view/' . $item_info->image_id), 'class' => 'img-polaroid img-polaroid-s', 'id' => 'preview', 'width' => '200', 'height' => '200')) : img(array('src' => base_url() . 'assets/img/avatar.png', 'class' => '', 'id' => 'preview')); ?>
                        </div>
                        <?php
                        if ($item_info->image_id) {
                            ?>
                            <a style="color: #fd0f14;" href="<?php echo site_url("personal/delete_item_image") . '/' . $item_info->item_id ?>" onclick="return confirm_image_deletion();"><?php echo lang('common_delete_image'); ?></a>
                            <?php
                        }
                        ?>
                        <div id="output"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-md-9 col-lg-10" style="float: right">
                    <br />
                    <br />
                    <?php
                    echo form_submit(array(
                        'name' => 'submitf',
                        'id' => 'submitf',
                        'value' => lang('common_submit'),
                        'class' => 'submit_button btn btn-primary')
                    );
                    ?>
                </div> 
            </div>

            <div id="loading" class="hide">
                <div id="loading-content">
                    <?php echo lang('common_wait') ?>
                </div>
            </div>

        </div><!--/panel-body -->
    </div><!-- /panel-piluku -->


</div>
<?php echo form_close(); ?>

<script type='text/javascript'>
    function check_category(category_id) {
        url = '<?php echo site_url("ajax/get_category_fields"); ?>';
        $("#loading").removeClass('hide');
        location.href = "<?php echo site_url("personal/view/" . $item_id); ?>" + "/" + category_id;
    }

    function confirm_image_deletion() {
        if (confirm("Are you sure you want to remove the Image?")) {
            return true;
        }
        return false;
    }

    $(document).ready(function ()
    {
        $('#image_id').imagePreview({selector: '#avatar'}); // Custom preview container

        //create formValidator object
        //there are a lot of configuration options that need to be passed,
        //but this makes it extremely flexibility and doesn't make any assumptions
        var validator = new formValidator({
            //this function adds an error message to a form field
            addError: function (field, message) {
                //get existing error message field
                var error_message_field = $('.error_message', field.parent('.validation'));

                //if the error message field doesn't exist yet, add it
                if (!error_message_field.length) {
                    error_message_field = $('<span/>').addClass('error_message');
                    field.parent('.validation').append(error_message_field);
                }

                error_message_field.text(message).show(200);
                field.addClass('error');
            },
            //this removes an error from a form field
            removeError: function (field) {
                $('.error_message', field.parent('.validation')).text('').hide();
                field.removeClass('error');
            },
            //this is a final callback after failing to validate one or more fields
            //it can be used to display a summary message, scroll to the first error, etc.
            onErrors: function (errors, event) {
                //errors is an array of objects, each containing a 'field' and 'message' parameter
            },
            //this defines the actual validation rules
            rules: {
                'name_on_card': {
                    'field': $('input[name=name_on_card]'),
                    'validate': function (field, event) {
                        if (!field.val())
                            throw "Required";

                        if (field.val().length < 5) // At least five characters
                            throw "Invalid";
                    }
                },
                'item_number': {
                    'field': $('input[name=item_number]'),
                    'validate': function (field, event) {
                        if (!field.val())
                            throw "Required";

                        if (field.val().length < 5) // At least five characters
                            throw "Invalid";
                    }
                },
                'name': {
                    'field': $('input[name=name]'),
                    'validate': function (field, event) {
                        if (!field.val())
                            throw "Required";

                        if (field.val().length < 3) // At least five characters
                            throw "Invalid";
                    }
                },
            }
        });



        //now, we attach events

        //this does validation every time a field loses focus
        $('form').on('blur', 'input,select', function () {
            validator.validateField($(this).attr('name'), 'blur');
        });

        //this clears errors every time a field gains focus
        $('form').on('focus', 'input,select', function () {
            validator.clearError($(this).attr('name'));
        });

        //this is for the validate links
        $('#submitf').click(function (event) {
            var fields = [];
            $('input,select', $(this).closest('.form_fields')).each(function () {
                fields.push($(this).attr('name'));
            });
            if (validator.validateFields(fields)) {
                //Check that the username have been used
                if ($('#item_id').val() == -1 || $('#item_sn').val() != $('#serial_number').val()) {
                    event.preventDefault();
                    var url = '<?php echo site_url("ajax/item_is_registered"); ?>';
                    $("#loading").removeClass('hide');
                    $.post(url, {
                        sn: $("#serial_number").val(),
                        user_id: <?php echo $logged_user; ?>,
                    },
                            function (data) {

                                if (data === 'true') {
                                    $("#loading").addClass('hide');
                                    alert('Item already registered');
                                    return false;
                                } else {
                                    $('#item_form').submit();
                                }
                            });
                } else {
                    $('#item_form').submit();
                }

                return true;
            }
            return false;
        });
        $('.validate_form').click(function () {
            if (validator.validateFields('submit')) {
                alert('success');
            }
            return false;
        });

        //this is for the clear links
        $('.clear_section').click(function () {
            var fields = [];
            $('input,select', $(this).closest('.form_fields')).each(function () {
                fields.push($(this).attr('name'));
            });

            validator.clearErrors(fields);
            return false;
        });

    });
</script>

<script src="<?php echo base_url(); ?>assets/js/compress.js" type="text/javascript" charset="UTF-8"></script>
<script src="<?php echo base_url(); ?>assets/js/index.js" type="text/javascript" charset="UTF-8"></script>
<?php $this->load->view('partial_public/footer'); ?>
