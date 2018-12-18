<?php $this->load->view("partial_public/header"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="float: left;"><?php echo lang("items_basic_information"); ?></h4>
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
                    <tr> <td><?php echo lang('common_where_found'); ?></td> <td> <?php echo H($item_info->where_found); ?></td></tr>
                    </tr>

                </table>
            </div>

            <?php
            $item_payment = $this->Payment->get_all_payment_by_item_id($item_info->item_id, 1); // 1 for found items

            if (isset($item_payment)) {
                if ($item_payment->payment_gatway_code == SUCCESS_STATUS_CODE) {

                    //load its payment
                    $item_payment = $this->Payment->get_item_payment($item_info->item_id, 1); // 1 for found items
                    ?>
                    <div class="panel-heading" style="background-color: green;">
                        <h3 class="panel-title" style="color: #FFFFFF !important;">
                            <i></i> 
                            Item claiming payment confirmed
                        </h3>
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
                }
            } else {
                ?>
                <div class="panel-heading" style="background-color: orange;">
                    <h3 class="panel-title" style="color: #FFFFFF !important;">
                        <i></i> 
                        Claim your item
                        <small>Please select a payment method to complete the recover</small>
                    </h3>
                </div>
                <div class="modal-body">
                    <br /><br />
                    <center>
                        <span>
                            <a href="<?php echo site_url('personal/found_item_payment/' . $item_info->item_id . '/1'); ?>" class=" " title="Pay with MOMO">
                                <img src="<?php echo base_url(); ?>assets/img/momo.jpg" width="100" height="50" />
                            </a>
                            <a href="#">
                                <img src="<?php echo base_url(); ?>assets/img/visa.jpg" width="100" height="50" />
                            </a>
                        </span>
                    </center>
                </div>
<?php } ?>
        </div>
    </div>
</div>

<?php $this->load->view("partial_public/footer"); ?>

