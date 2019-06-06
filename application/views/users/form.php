<?php $this->load->view("partial_public/header"); ?>
<?php if ($server_message) { ?>
    <?php echo $server_message; ?>
<?php } ?>
<div class="row" id="form">
    <div class="spinner" id="grid-loader" style="display:none">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
    </div>
    <div class="col-md-12 form_fields">
        <?php
        echo form_open('users/save_user/' . (!isset($is_clone) ? $person_info->person_id : ''), array('id' => 'employee_form', 'class' => 'form-horizontal'));
        ?>
        <div class="panel panel-piluku">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="ion-edit"></i> 
                    <?php echo lang("employees_basic_information"); ?>
                    <small>(<?php echo lang('common_fields_required_message'); ?>)</small>
                </h3>
            </div>

            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <?php
                            echo form_label('Names' . ' :', 'first_name', array('class' => 'required col-sm-3 col-md-3 col-lg-2 control-label '));
                            ?>
                            <div class="col-sm-9 col-md-9 col-lg-10 validation">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control',
                                    'name' => 'first_name',
                                    'id' => 'first_name',
                                    'value' => $person_info->first_name)
                                );
                                ?>

                            </div>
                            <span class="error_message" style="display: none; color: red;"></span>
                        </div>

                        <div class="form-group">
                            <?php echo form_label(lang('common_email') . ' :', 'email', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label ' . ($controller_name == 'employees' || $controller_name == 'login' ? 'required' : 'not_required'))); ?>
                            <div class="col-sm-9 col-md-9 col-lg-10">
                                <?php
                                echo form_input(array(
                                    'class' => 'form-control',
                                    'name' => 'email',
                                    'type' => 'text',
                                    'id' => 'email',
                                    'value' => $person_info->email)
                                );
                                ?>
                            </div>
                        </div>
                        <div class="form-group">	
                            <?php echo form_label(lang('common_language') . ' :', 'language', array('class' => 'col-sm-3 col-md-3 col-lg-2 col-sm-3 col-md-3 col-lg-2 control-label  required')); ?>
                            <div class="col-sm-9 col-md-9 col-lg-10">
                                <?php
                                echo form_dropdown('language', array(
                                    'english' => 'English',
                                    'french' => 'Fançais',
                                    'kinyarwanda' => 'Kinyarwanda',
                                        ), $person_info->language ? $person_info->language : $this->Appconfig->get_raw_language_value(), 'class="form-control" id="language"');
                                ?>
                            </div>
                        </div>
                        <div id="loading" class="hide">
                            <div id="loading-content">
                                <?php echo lang('common_wait') ?>
                            </div>
                        </div>

                    </div><!-- /col-md-12 -->
                </div><!-- /row -->
                <div class="form-heading">
                    <?php echo lang("common_login_info"); ?>
                </div>
                <div class="form-group">	
                    <?php echo form_label(lang('common_phone_number') . ' :', 'phone_number', array('class' => 'required col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10 validation">
                        <?php
                        echo form_input(array(
                            'class' => 'form-control',
                            'name' => 'phone_number',
                            'id' => 'phone_number',
                            'placeholder' => 'eg: 250788000000',
                            'value' => $person_info->phone_number));
                        ?>
                    </div>
                    <span class="error_message" style="display: none;"></span>
                </div>
                <div class="form-group">	
                    <?php echo form_label(lang('common_password') . ' :', 'password', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10 validation">
                        <?php
                        echo form_password(array(
                            'name' => 'password',
                            'id' => 'password',
                            'class' => 'form-control',
                            'autocomplete' => 'off',
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">	
                    <?php echo form_label(lang('common_repeat_password') . ' :', 'repeat_password', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label required')); ?>
                    <div class="col-sm-9 col-md-9 col-lg-10 validation">
                        <?php
                        echo form_password(array(
                            'name' => 'repeat_password',
                            'id' => 'repeat_password',
                            'class' => 'form-control',
                            'autocomplete' => 'off',
                        ));
                        ?>
                    </div>
                </div>



                <div class="form-actions pull-right">
                    <?php
                    echo form_submit(array(
                        'name' => 'submitf',
                        'id' => 'submitf',
                        'value' => lang('common_submit'),
                        'class' => 'btn btn-primary float_right')
                    );
                    ?>
                </div>
            </div>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</div>					
<script>
    $(document).ready(function () {
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
                //this is a basic non-empty check
                'first_name': {
                    'field': $('input[name=first_name]'),
                    'validate': function (field, event) {
                        if (!field.val())
                            throw "Required";
                    }
                },
                'username': {
                    'field': $('input[name=username]'),
                    'validate': function (field, event) {
                        if (!field.val())
                            throw "Required";
                    }
                },
                'password': {
                    'field': $('input[name=password]'),
                    'validate': function (field, event) {
                        if (!field.val())
                            throw "Required";
                    }
                },
                'repeat_password': {
                    'field': $('input[name=repeat_password]'),
                    'validate': function (field, event) {
                        if (!field.val())
                            throw "Required";

                        if (field.val() !== $('#password').val())
                            throw "Password not Matching";
                    }
                },
                //this demonstrates more than one error message
                //and handling more than one event
                'phone_number': {
                    'field': $('input[name=phone_number]'),
                    'validate': function (field, event) {
                        //if the validation is fired from a blur event,
                        //don't throw any errors if it is empty
                        if (event === 'blur' && !field.val())
                            return true;

                        if (!field.val()) {
                            throw "A phone number is required";
                        }
                        var phone_pattern = /[0-9]{12}/;
                        if (!phone_pattern.test(field.val()) || field.val().length !== 12)
                            throw "Please enter a valid phone number with country code (eg: 250788000000)";
                    }
                },
                //checking for numbers within a range
                'age': {
                    'field': $('input[name=age]'),
                    'validate': function (field, event) {
                        if (!field.val())
                            throw "Please enter your age";
                        if (isNaN(field.val()))
                            throw "Only numeric values are allowed";
                        if (field.val() > 100)
                            throw "Age can't be greater than 100";
                        if (field.val() < 18)
                            throw "You must be 18 or older to use this form";
                    }
                }
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

            if (validator.validateFields(fields, 'submit')) {

                //Check that the username have been used
                event.preventDefault();
                var url = '<?php echo site_url("ajax/check_username"); ?>';
                $("#loading").removeClass('hide');
                $.post(url, {
                    username: $("#phone_number").val()
                },
                        function (data) {

                            if (data === 'true') {
                                $("#loading").addClass('hide');
                                alert('The phone number already taken');
                                return false;
                            } else {
                                $('#employee_form').submit();
                            }
                        });
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
<?php $this->load->view("partial_public/footer"); ?>
