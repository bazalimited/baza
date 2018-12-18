<?php $this->load->view("welcome/header"); ?>
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
                        <span class="modal-item-name"><b><?php echo H($item_info->name); ?></b></span>

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

                    <tr> <td colspan="2" style="text-align: center;"><?php
                            if (isset($this->Employee->get_logged_in_employee_info()->id)) {

                                $item_payment = $this->Payment->get_payment_by_item_id($item_info->item_id, 1); // 1 for found items
                                if (isset($item_payment)) {
                                    if ($item_payment->payment_gatway_code == SUCCESS_STATUS_CODE && $item_payment->payed_by_id == $this->Employee->get_logged_in_employee_info()->id) {
                                        //get the Item Location
                                        $found_item_location = $this->Location->get_info($item_info->item_location_id);
                                        if (isset($found_item_location)) {
                                            ?>
                                            <table class="table table-bordered table-hover table-striped" style="text-align: left;">
                                                <tr><td colspan="2"><h3>You've completed the the payment for this item</h3></td></tr>
                                                <tr>
                                                    <td><h4>Item Location</h4></td>
                                                    <td><?php echo $found_item_location->name; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><h4>Contact</h4></td>
                                                    <td><?php echo $found_item_location->phone; ?></td>
                                                </tr>
                                            </table>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo site_url('personal/recover/' . $item_info->item_id); ?>" style="text-align: center;"><input value="Check item claim status..." style="width: 400px;" class="btn btn-success"></a>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo site_url('personal/recover/' . $item_info->item_id); ?>" style="text-align: center;"><input value="Check item claim status..." style="width: 400px;" class="btn btn-success"></a>
                                    <?php
                                }
                            } else {
                                session_start();
                                $_SESSION['result_item_id'] = $item_info->item_id;
                                ?>
                                <a href="<?php echo site_url('login/index') . '/' . $item_info->item_id; ?>"><input value="Login to Claim item " class="submit_button btn btn-primary"></a> Or 
                                <a class="btn btn-success" href="<?php echo site_url('users/create_account') ?>">Create account</a> 
                                    <?php
                                }
                                ?>
                        </td>
                    </tr>

                </table>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view("welcome/footer"); ?>

