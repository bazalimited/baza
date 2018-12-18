<?php $this->load->view("partial_public/header"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="modal-content">
            <div class="modal-header">
                <?php
                $session_data = $this->session->userdata('item_registration_data');
                if (isset($session_data)) {
                    echo $this->session->userdata('item_registration_data');
                    $this->session->unset_userdata('item_registration_data');
                }
                ?>
                <?php if (isset($selected_items)) { ?>
                    <h4 class="modal-title" style="float: left;"><?php echo lang("common_selected_items") . ' (' . $selected_items_count . ')'; ?></h4>
                <?php } else { ?>
                    <h4 class="modal-title" style="float: left;"><?php echo lang("items_basic_information"); ?></h4>
                <?php } ?>
                <br />
            </div>
            <?php
            if (isset($selected_items)) {
                $item_ids = '';
                ?>
                <div class="modal-body">
                    <?php
                    foreach ($selected_items->result() as $item_info) {
                        $item_ids .= $item_info->item_id . ' ';
                        ?>
                        <div class="modal-item-info">
                            <div class="modal-item-avatar">
                                <?php echo $item_info->image_id ? img(array('src' => site_url('personal_app_files/view/' . $item_info->image_id), 'class' => ' img-polaroid')) : img(array('src' => base_url() . 'assets/img/avatar.png', 'class' => ' img-polaroid', 'id' => 'image_empty')); ?>
                            </div>
                            <div class="modal-item-details">
                                <span class="modal-item-name"><?php echo H($item_info->name); ?></span>
                                <span class="modal-item-category"><?php echo H($this->Category->get_info($item_info->category_id)->name); ?></span>
                            </div>
                        </div>

                        <table class="table table-bordered table-hover table-striped">
                            <tr> 
                                <td style="width: 30%;"><?php echo lang('common_item_number_expanded') . '/' . lang('common_item_name_on_card'); ?></td> 
                                <td> <?php
                                    if ($item_info->item_number == '' || $item_info->item_number == null) {
                                        echo H($item_info->name_on_card);
                                    } else {
                                        echo H($item_info->item_number);
                                    }
                                    ?></td></tr>
                            <tr> <td><h4><?php echo lang('common_item_name'); ?></h4></td> <td> <h4><?php echo H($item_info->name); ?></h4></td></tr>
                            <tr> <td><?php echo lang('common_category'); ?></td> <td> <?php echo H($this->Category->get_info($item_info->category_id)->name); ?></td></tr>

                            <tr> <td><?php echo lang('common_description'); ?></td> <td> <?php echo H($item_info->description); ?></td></tr>
                        </table>

                    <?php } ?>
                    <div class="panel-heading" style="background-color: orange;">
                        <h3 class="panel-title" style="color: #FFFFFF !important;">
                            <i></i> 
                            <?php echo lang('common_item_registration_payment'); ?>
                            <small><?php echo lang('common_select_payment_option'); ?></small>
                        </h3>
                    </div>
                    <div class="modal-body">
                        <br /><br />
                        <center>
                            <span>
                                <?php echo form_open('personal/confirm_payment/0/1/', array('id' => 'item_ids_payment', 'class' => 'form-horizontal')); ?>
                                <input type="hidden" name="item_ids" value="<?php echo $item_ids ?>" />
                                <?php echo form_close(); ?>
                                <a href="javascript:void(0);" title="Pay with MOMO" onclick="confirm_payment();">
                                    <img src="<?php echo base_url(); ?>assets/img/momo.jpg" width="100" height="50" />
                                </a>
                                <a href="#">
                                    <img src="<?php echo base_url(); ?>assets/img/visa.jpg" width="100" height="50" />
                                </a>
                            </span>
                        </center>
                    </div>
                </div>
            <?php } else { ?>
                <div class="modal-body">

                    <div class="modal-item-info">
                        <div class="modal-item-avatar">
                            <?php echo $item_info->image_id ? img(array('src' => site_url('personal_app_files/view/' . $item_info->image_id), 'class' => ' img-polaroid')) : img(array('src' => base_url() . 'assets/img/avatar.png', 'class' => ' img-polaroid', 'id' => 'image_empty')); ?>
                        </div>
                        <div class="modal-item-details">
                            <span class="modal-item-name"><?php echo H($item_info->name); ?></span>
                            <span class="modal-item-category"><?php echo H($category); ?></span>
                        </div>
                    </div>

                    <table class="table table-bordered table-hover table-striped">
                        <tr> 
                            <td style="width: 30%;"><?php echo lang('common_item_number_expanded') . '/' . lang('common_item_name_on_card'); ?></td> 
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
                        <?php
                        $payed_amount = 0;
                        $item_payment = $this->Payment->get_all_payment_by_item_id($item_info->item_id, 2); // 2 personal item registration
                        //echo $this->db->last_query();

                        if (isset($item_payment) && $item_payment->payment_gatway_code == SUCCESS_STATUS_CODE) {
                            $payed_amount = $item_payment->amount;
                            ?>
                            <tr> <td style="width: 20%;"><h4>Paid Amount</h4></td> <td> <h4><?php echo number_format($payed_amount, 0); ?> FRW</h4></td></tr>
                            <tr> <td colspan="2"><center><a href="<?php echo site_url('personal/personal_invoice/' . $item_info->item_id); ?>" target="_blank"><input name="submitf" value="Print receipt..." class="submit_button btn btn-primary" type="submit"></a></center></td></tr>
                        <?php }
                        ?>
                    </table>
                </div>
                <?php
                if (isset($item_payment)) {
                    if ($item_payment->payment_gatway_code == SUCCESS_STATUS_CODE) {
                        ?>
                        <div class="panel-heading" style="background-color: green;">
                            <h3 class="panel-title" style="color: #FFFFFF !important;">
                                <i></i> 
                                Item registration payment confirmed
                            </h3>
                            <table class="table table-bordered table-hover table-striped">

                                <tr> <td style="width: 20%;"><h4>Paid Amount</h4></td> <td> <h4><?php echo number_format($item_payment->amount, 0); ?> FRW</h4></td></tr>
                            </table> 
                        </div>
                        <?php
                    } else if ($item_payment->payment_gatway_code == PENDING_STATUS_CODE || $item_payment->payment_gatway_code == PENDING_COMMIT_TO_WALLET_STATUS_CODE) {
                        ?>
                        <div class="panel-heading" style="background-color: orange;">
                            <h3 class="panel-title" style="color: #FFFFFF !important;">
                                <i></i> 
                                Item registration payment is pending for approval
                            </h3>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="panel-heading" style="background-color: orange;">
                            <h3 class="panel-title" style="color: #FFFFFF !important;">
                                <i></i> 
                                Item registration not Paid
                                <small>Please select a payment method to complete the registration</small>
                            </h3>
                        </div>
                        <div class="modal-body">
                            <br /><br />
                            <center>
                                <span>
                                    <a href="<?php echo site_url('personal/confirm_payment/' . $item_info->item_id . '/1'); ?>" class=" " title="Pay with MOMO">
                                        <img src="<?php echo base_url(); ?>assets/img/momo.jpg" width="100" height="50" />
                                    </a>
                                    <a href="#">
                                        <img src="<?php echo base_url(); ?>assets/img/visa.jpg" width="100" height="50" />
                                    </a>
                                </span>
                            </center>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="panel-heading" style="background-color: orange;">
                        <h3 class="panel-title" style="color: #FFFFFF !important;">
                            <i></i> 
                            Item registration not Paid
                            <small>Please select a payment method to complete the registration</small>
                        </h3>
                    </div>
                    <div class="modal-body">
                        <br /><br />
                        <center>
                            <span>
                                <a href="<?php echo site_url('personal/confirm_payment/' . $item_info->item_id . '/1'); ?>" class=" " title="Pay with MOMO">
                                    <img src="<?php echo base_url(); ?>assets/img/momo.jpg" width="100" height="50" />
                                </a>
                                <a href="#">
                                    <img src="<?php echo base_url(); ?>assets/img/visa.jpg" width="100" height="50" />
                                </a>
                            </span>
                        </center>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<script type='text/javascript'>
    function confirm_payment() {
        $("#item_ids_payment").submit();
    }

</script>
<?php $this->load->view("partial_public/footer"); ?>

