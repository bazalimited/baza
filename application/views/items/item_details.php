<?php $this->load->view("partial/header"); ?>


<div class="row">
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
                    <?php
                    $payed_amount = 0;
                    $item_payment = $this->Payment->get_all_payment_by_item_id($item_info->item_id, 1); // 1 for found items
                    $success_item_payment = $this->Payment->get_payment_by_item_id($item_info->item_id, 1); // 1 for found items
                    if (isset($success_item_payment)) {
                        $payed_amount = $item_payment->amount;
                        $agent = $this->Employee->get_info($item_info->created_by_id);
                        ?>
                        <tr> <td><h4><?php echo lang('common_found_item_location'); ?></h4></td> <td> <h4><?php echo H($this->Location->get_info($item_info->item_location_id)->name); ?></h4></td></tr>
                        <tr> <td><h4><?php echo lang('common_found_item_contact_person'); ?></h4></td> <td> <h4><?php echo $agent->first_name.' '.$agent->last_name.'<br />Phone: '.$agent->phone_number; ?></h4></td></tr>
                        <tr> <td colspan="2"><center><a href="<?php echo site_url('personal/found_invoice/' . $item_info->item_id); ?>" target="_blank"><input name="submitf" value="Print receipt..." class="submit_button btn btn-primary" type="submit"></a></center></td></tr>
                    <?php }
                    ?>
                </table>
                <?php
                if ($item_info->status == 1) { // Item Collected 
                    ?>
                    <div class="panel-heading" style="background-color: green;">
                        <h3 class="panel-title" style="color: #FFFFFF !important;">
                            <i class="ion-edit"></i> 
                            <?php echo lang("items_collected"); ?>
                            <small>(<?php echo lang('items_collected_by_details'); ?>)</small>
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
                        <?php if ($this->Employee->has_module_action_permission('items', 'approve_item_return', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
                            <a href="<?php echo site_url('payments/return_item/' . $item_info->item_id) ?>" class="btn btn-warning btn-lg" title="<?php echo lang('common_return_item') ?>"><span class=""><?php echo lang('common_return_item') ?></span></a>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <?php 
                    if ($item_info->status == 0 && $this->Item->item_owner_payed($item_info->item_id)) { // Item not Collected 
                        ?>
                        <?php if ($this->Employee->has_module_action_permission('items', 'approve_item_collection', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
                            <a href="<?php echo site_url('payments/collect/' . $item_info->item_id) ?>" class="btn btn-green btn-lg" title="<?php echo lang('common_approve_item_collection') ?>"><span class=""><?php echo lang('common_approve_item_collection') ?></span></a>
                        <?php } ?>
                        <?php
                    } else { // Item not paid
                        ?>
                        <div class="panel-heading" style="background-color: orange;">
                            <h3 class="panel-title" style="color: #FFFFFF !important;">
                                <?php
                                echo $this->Item->get_found_item_status($item_info->item_id);
                                ?>
                                
                            </h3>
                        </div>
                        <?php
                    }
                }
                ?>

            </div>
        </div>
    </div>

</div>
<?php
$this->load->view('partial/footer')?>