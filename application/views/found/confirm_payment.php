<?php $this->load->view("partial_public/header"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" style="float: left;"><?php echo lang("items_basic_information"); ?></h4>
            </div>
            <div class="modal-body">

                <h4 class="modal-title" style="float: left;"><?php
                    echo $this->session->userdata("payment_message");
                    $this->session->unset_userdata('payment_message');
                    ?>
                </h4>
                <table class="table table-bordered table-hover table-striped">
                    <tr> 
                        <td colspan="2"><?php
                            echo $this->session->userdata("payment_message");
                            $this->session->unset_userdata('payment_message');
                            ?>
                        </td>
                    </tr>

                    <tr> 
                        <td style="width: 30%;"><div class="modal-item-info">
                                <div class="modal-item-avatar">
                                    <?php echo $item_info->image_id ? img(array('src' => site_url('personal_app_files/view/' . $item_info->image_id), 'class' => ' img-polaroid')) : img(array('src' => base_url() . 'assets/img/avatar.png', 'class' => ' img-polaroid', 'id' => 'image_empty')); ?>
                                </div>
                                <div class="modal-item-details">
                                    <span class="modal-item-name"><?php echo H($item_info->name); ?></span>
                                    <span class="modal-item-category"><?php echo H($category); ?></span>
                                </div>
                            </div>
                        </td> 
                        <td>&nbsp;</td>
                    </tr>

                    <tr> 
                        <td style="width: 30%;"><?php echo lang('common_item_number_expanded') . '/' . lang('common_item_name_on_card'); ?></td> 
                        <td> <?php
                            if ($item_info->item_number == '' || $item_info->item_number == null) {
                                echo H($item_info->name_on_card);
                            } else {
                                echo H($item_info->item_number);
                            }
                            ?></td>
                    </tr>
                    <tr> <td><h4><?php echo lang('common_item_name'); ?></h4></td> <td> <h4><?php echo H($item_info->name); ?></h4></td></tr>
                    <tr> <td><?php echo lang('common_category'); ?></td> <td> <?php echo H($category); ?></td></tr>

                    <tr> <td><?php echo lang('common_description'); ?></td> <td> <?php echo H($item_info->description); ?></td></tr>
                </table>
            </div>
            <?php
            if ($this->Personal_item->item_payment_status($item_info->item_id) == SUCCESS_STATUS_CODE) {
                ?>
                <div class="panel-heading" style="background-color: green;">
                    <h3 class="panel-title" style="color: #FFFFFF !important;">
                        <i></i> 
                        Item registration payment confirmed
                    </h3>
                </div>
                <?php
            } else if ($this->Personal_item->item_payment_status($item_info->item_id) == PENDING_STATUS_CODE || $this->Personal_item->item_payment_status($item_info->item_id) == PENDING_COMMIT_TO_WALLET_STATUS_CODE) {
                ?>
                <div class="panel-heading" style="background-color: orange;">
                    <h3 class="panel-title" style="color: #FFFFFF !important;">
                        <i></i> 
                        Item registration payment is pending for approval
                    </h3>
                </div>
            <?php } else { ?>
                <div class="panel-heading" style="background-color: green;">
                    <h3 class="panel-title" style="color: #FFFFFF !important;">
                        <i class="ion-edit"></i> 
                        Item registration payment
                        <small>You are about to pay with Mobile Money(MTN, Airtel and Tigo)</small>
                    </h3>
                </div>
                <div class="modal-body">
                    <center>
                        <span>
                            <a href="#">
                                <img src="<?php echo base_url(); ?>assets/img/momo.jpg" width="100" height="50" />
                            </a>
                        </span>
                    </center>
                    <table class="table table-bordered table-hover table-striped">

                        <tr> <td style="width: 20%;"><h4>Total Amount</h4></td> <td> <h4><?php echo number_format($category_amount, 0); ?> FRW</h4></td></tr>
                        <tr> <td style="width: 20%;"><h4>Phone Number</h4></td> <td> <h4><?php echo $phone_number; ?> </h4></td></tr>
                    </table> 
                    <?php echo form_open_multipart('found/payment_confirmation/', array('id' => 'payment_form')); ?>
                    <input type="hidden" name="phone_number" value="<?php echo $phone_number; ?>" />
                    <input type="hidden" name="amount" value="<?php echo $category_amount; ?>" />
                    <input type="hidden" name="item_id" value="<?php echo $item_info->item_id; ?>" />
                    <input name="submitf" value="Confirm payment..." id="submitf" class="submit_button btn btn-primary" type="submit" onclick="return confirm_Payment();">
                    <?php echo form_close(); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<div id="loading" class="hide">
    <div id="loading-content">
        <?php echo lang('common_wait') ?>
    </div>
</div>
<?php $this->load->view("partial_public/footer"); ?>

<script type='text/javascript'>
    function confirm_Payment() {
        if (confirm("Are you sure you want request a Mobile Payment? Please follow the instructions from your Phone")) {
            $("#loading").removeClass('hide');
            return true;
        }
        return false;
    }
</script>