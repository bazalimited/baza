<div class="modal-dialog customer-recent-sales">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label=<?php echo json_encode(lang('common_close')); ?>><span aria-hidden="true" class="ti-close"></span></button>
            <h4 class="modal-title"><?php echo lang("common_owner_payment"); ?></h4>
        </div>
        <div class="modal-body">
            <div class="modal-item-info">
                <div class="modal-item-details">
                    <span class="modal-item-name"><?php echo H($item_info->name); ?></span>
                    <span class="modal-item-category"><?php echo H($category); ?></span>
                </div>
            </div>
            <?php echo form_open_multipart('payments/register_owner_payment/', array('id' => 'pay_agent_orm', 'class' => 'form-horizontal')); ?>
            <table class="table table-bordered table-hover table-striped">
                <tr> <td><?php echo lang('common_owner'); ?></td> <td> <?php echo $agent->first_name . ' ' . $agent->last_name; ?></td></tr>
                <tr> <td><?php echo lang('common_item_number_expanded'); ?></td> <td> <?php echo $item->item_number; ?></td></tr>
                <tr> <td><?php echo lang('common_pay_agent_amount'); ?></td> <td> <?php echo $item_amount; ?> FRW<input type="hidden" name="agent_amount" value="<?php echo $item_amount; ?>" /></td></tr>

            </table> 
            <input type="hidden" name="item_id" value="<?php echo $item->item_id; ?>" />
            <input type="hidden" name="agent_id" value="<?php echo $agent->person_id; ?>" />
            <div class="input-group date" data-date="">
                <span class="input-group-addon bg">
                    <i class="ion ion-ios-calendar-outline"></i>
                </span>
                <?php
                echo form_input(array(
                    'name' => 'payment_date',
                    'id' => 'payment_date',
                    'class' => 'form-control datepicker',
                    'value' => $from_date ? date(get_date_format(), strtotime($from_date)) : '')
                );
                ?> 
            </div>
            <br /><br />
            <?php
            echo form_submit(array(
                'name' => 'submitf',
                'id' => 'submitf',
                'onclick' => 'return confirm_payment()',
                'value' => lang('common_complete_payment'),
                'class' => 'submit_button btn btn-primary')
            );
            ?>

        </div>
    </div>
</div>
<script type='text/javascript'>
    function confirm_payment() {
        if (confirm("Are you sure you want to perform this operation?")) {
            return true;
        } else {
            return false;
        }
    }
    date_time_picker_field($('.datepicker'), JS_DATE_FORMAT);
</script>


