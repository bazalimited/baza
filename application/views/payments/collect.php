<?php $this->load->view("partial/header"); ?>


<div class="row">
    <div class="spinner" id="grid-loader" style="display:none">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-piluku">
            <h4 class="modal-title" style="float: left;"><?php
                echo $this->session->userdata("save_collect_message");
                $this->session->unset_userdata('save_collect_message');
                ?>
            </h4>
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="ion-edit"></i> 
                    <?php echo lang("items_collection_information"); ?>
                    <small>(<?php echo lang('items_collection_details'); ?>)</small>
                </h3>
            </div>
            <div class="modal-header">

            </div>
            <div class="modal-body">
                <div class="modal-item-info">
                    <div class="modal-item-avatar">
                        <?php echo $item_info->image_id ? img(array('src' => site_url('app_files/view/' . $item_info->image_id), 'class' => ' img-polaroid')) : img(array('src' => base_url() . 'assets/img/avatar.png', 'class' => ' img-polaroid', 'id' => 'image_empty')); ?>
                    </div>
                    <div class="modal-item-details">
                        <span class="modal-item-name"><?php echo H($item_info->name); ?></span>
                        <span class="modal-item-category"><?php echo H($category); ?></span>
                    </div>
                </div>

                <table class="table table-bordered table-hover table-striped">
                    <tr> <td style="width: 40%;"><?php echo lang('common_item_number_expanded') . '/' . lang('common_item_name_on_card'); ?></td> 
                        <td> <?php
                            if ($item_info->item_number == '' || $item_info->item_number == null) {
                                echo H($item_info->name_on_card);
                            } else {
                                echo H($item_info->item_number);
                            }
                            ?></td></tr>
                    <tr> <td><h4><?php echo lang('common_item_name'); ?></h4></td> <td> <h4><?php echo H($item_info->name); ?></h4></td></tr>
                    <tr> <td><?php echo lang('common_category'); ?></td> <td> <?php echo H($category); ?></td></tr>

                    <tr> <td><?php echo lang('common_description'); ?></td> <td> <?php echo H($item_info->description); ?></td></tr>
                    <tr> <td><?php echo lang('common_where_found'); ?></td> <td> <?php echo H($item_info->where_found); ?></td></tr>
                    <tr> <td><?php echo lang('common_found_item_location'); ?></td> 
                        <td> 
                            <?php
                            echo H($item_location_name);
                            ?>
                        </td>
                    </tr>
                </table>


            </div>
            <?php
            $item_payment = $this->Payment->get_payment_by_item_id($item_info->item_id, 1); // 1 for found items
            $collector_info = $this->Employee->get_info($this->Employee->get_person_id_from_employee_id($item_payment->payed_by_id));
            if ($collector_info->person_id != '' && $item_info->status == 0) { // The Collector is found
                ?>
                <div class="panel-heading" style="background-color: orange;">
                    <h3 class="panel-title" style="color: #FFFFFF !important;">
                        <?php echo lang("customers_basic_information"); ?>
                        <small>(<?php echo lang('common_fields_required_message'); ?>)</small>
                    </h3>
                </div>
                <div class="modal-header">

                </div>
                <?php echo form_open_multipart('payments/save_collect/', array('id' => 'customer_form', 'class' => 'form-horizontal')); ?>
                <div class="modal-body">
                    <table class="table table-bordered table-hover table-striped">
                        <tr> <td><h4><?php echo lang('items_collector_names'); ?></h4></td> 
                            <td colspan="2"> <h4><?php echo H($collector_info->first_name) . ' ' . ($collector_info->last_name); ?></h4></td></tr>
                        <?php if ($collector_info->company_name != '') { ?>
                            <tr> <td><?php echo lang('items_collector_company_name'); ?></td> <td colspan="2"> <?php echo H($collector_info->company_name); ?></td></tr>
                        <?php } ?>
                        <tr> <td><?php echo lang('items_collector_phone'); ?></td> <td colspan="2"> <?php echo H($collector_info->phone_number); ?></td></tr>
                        <tr> <td><?php echo lang('items_collector_email'); ?></td> <td colspan="2"> <?php echo H($collector_info->email); ?></td></tr>
                        <tr> <td><?php echo lang('items_collector_full_address'); ?></td> <td colspan="2"> <?php echo H($collector_info->address_1); ?></td></tr>
                        <tr> <td><?php echo lang('items_collector_comment'); ?></td> <td colspan="2"> <textarea cols="70" rows="5" name="collection_comment"></textarea></td></tr>
                        <tr> <td>Date: </td> <td colspan="2"> 
                                <div class="input-group date">
                                    <span class="input-group-addon bg">
                                        <i class="ion ion-ios-calendar-outline"></i>
                                    </span>
                                    <?php
                                    echo form_input(array(
                                        'name' => 'collection_date',
                                        'id' => 'collection_date',
                                        'class' => 'form-control datepicker',)
                                    );
                                    ?> 
                                </div>
                            </td></tr>
                        <tr> <td><?php echo lang('items_collector_consent'); ?></td> 
                            <td style="width: 40%"> 
                                <input type="file" name="consent_id" id="image_id" class="filestyle" data-icon="false" >  
                            </td>
                            <td> 
                                <a href="<?php echo site_url('personal/item_consent/' . $item_info->item_id) ?>" target="_blank" class="btn btn-success btn-lg" title="Download the Consent form"><span class="">Download the Consent form</span></a>
                            </td>
                        </tr>
                    </table>


                </div>
                <input type="hidden" name="item_id" value="<?php echo $item_info->item_id; ?>" />
                <input type="hidden" name="collector_id" value="<?php echo $item_payment->payed_by_id; ?>" />
                <?php
                echo form_submit(array(
                    'name' => 'submitf',
                    'id' => 'submitf',
                    'value' => 'Approve Item Collection',
                    'class' => ' submit_button btn btn-primary')
                );
                ?>
                <?php echo form_close(); ?>
                <?php
            } else {
                ?>
                <div class="panel-heading" style="background-color: green;">
                    <h3 class="panel-title" style="color: #FFFFFF !important;">
                        <i class="ion-edit"></i> 
                        <?php echo $this->Item->get_found_item_status($item_info->item_id); ?>
                    </h3>
                </div>
                <div class="modal-body">

                    <table class="table table-bordered table-hover table-striped">
                        <tr> <td><h4><?php echo lang('items_collector_names'); ?></h4></td> <td> <h4><?php echo H($collector_info->first_name) . ' ' . ($collector_info->last_name); ?></h4></td></tr>
                        <?php if ($collector_info->company_name != '') { ?>
                            <tr> <td><?php echo lang('items_collector_company_name'); ?></td> <td> <?php echo H($collector_info->company_name); ?></td></tr>
                        <?php } ?>
                        <tr> <td><?php echo lang('items_collector_phone'); ?></td> <td> <?php echo H($collector_info->phone_number); ?></td></tr>
                        <tr> <td><?php echo lang('items_collector_email'); ?></td> <td> <?php echo H($collector_info->email); ?></td></tr>
                        <tr> <td><?php echo lang('items_collector_full_address'); ?></td> <td> <?php echo H($collector_info->address_1); ?></td></tr>
                        <tr> <td><?php echo lang('items_collector_comment'); ?></td> <td> <?php echo H($item_collection->collection_comment); ?></td></tr>
                        <tr> <td><?php echo lang('items_collector_consent'); ?></td> <td> 
                                <?php echo $item_collection->consent_form_id ? '<a href="' . site_url('app_files/download/' . $item_collection->consent_form_id) . '">' . $file_name . '</a>' . '' : '<div class="user-badge-avatar">None</div>'; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>
<script type='text/javascript'>

    date_time_picker_field($('.datepicker'), JS_DATE_FORMAT);
</script>
<?php
$this->load->view('partial/footer')?>