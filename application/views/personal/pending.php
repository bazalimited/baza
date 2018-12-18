<?php $this->load->view("partial_public/header"); ?>


<div class="manage_buttons">
    <div class="col-md-3" style="float: right;">
        <div class="buttons-list items-buttons">
            <div class="pull-right-btn">
                <button class="btn btn-primary btn-lg"  onclick="return check_selected()"><?php echo lang('common_bulk_payment'); ?></button>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row manage-table">
            <div class="panel panel-piluku">
                <?php
                $session_data = $this->session->userdata('payment_message');
                if (isset($session_data)) {
                    echo $this->session->userdata('payment_message');
                    $this->session->unset_userdata('payment_message');
                }
                ?>
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php echo ' My pending payment Items(' . $pending_items_count . ')'; ?>
                        <div class="panel-options custom">
                            <?php if ($pagination) { ?>
                                <div class="pagination pagination-top hidden-print  text-center" id="pagination_top">
                                    <?php echo $pagination; ?>		
                                </div>
                            <?php } ?>
                        </div>
                    </h3>
                </div>
                <div class="panel-body nopadding table_holder table-responsive" >
                    <?php if (count($pending_items) > 0) { ?>
                        <?php echo form_open_multipart('personal/item_details/0/1/', array('id' => 'item_form', 'class' => 'form-horizontal')); ?>
                        <table class="table tablesorter table-hover" id="sortable_table">
                            <thead>
                                <tr>
                                    <th class="leftmost">&nbsp;</th>
                                    <th class="leftmost">No</th>
                                    <th>Item Category</th>
                                    <th>Item Name</th>
                                    <th>Serial Number or item number</th>
                                    <th>Description</th>
                                    <th>&nbsp;<th>
                                    <th>&nbsp;<th>
                                    <th class="rightmost">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                foreach ($pending_items->result() as $item) {
                                    $avatar_url = $item->image_id ? site_url('personal_app_files/view/' . $item->image_id) : base_url('assets/assets/images/avatar-default.jpg');
                                    ?>
                                    <tr style="cursor: pointer;">
                                        <td><?php echo '<input type="checkbox" name="item_ids[]" id="item_' . $item->item_id . '" value="' . $item->item_id . '" style="display: block;">'; ?></td>
                                        <td><?php echo $counter; ?></td>
                                        <td><?php echo $item->category; ?></td>
                                        <td><a href="<?php echo site_url('personal/item_details/' . $item->item_id); ?>" class=" " title="View item and complete Payment"><?php echo $item->name; ?></a></td>
                                        <td><?php echo $item->item_number; ?></td>
                                        <td><?php echo $item->description; ?></td>

                                        <td class="rightmost">
                                            <a href="<?php echo site_url('personal/view/' . $item->item_id); ?>" class=" " title="Update Item">Edit</a></td>
                                        <td class="rightmost">
                                            <a style="color: #fd0f14;" href="<?php echo site_url('personal/delete_item/' . $item->item_id); ?>" class=" " onclick="return confirm_deletion();">Delete</a></td>
                                        <td><a href='<?php echo $avatar_url; ?>' class='rollover'><img src='<?php echo $avatar_url; ?>' alt='<?php echo $item->item_number; ?>' class='img-polaroid' width='45' /></a></td>
                                    </tr>
                                    <?php
                                    $counter++;
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php echo form_close(); ?>
                    <?php } ?>
                </div>		

            </div>
        </div>
    </div>

    <script type='text/javascript'>

        date_time_picker_field($('.datepicker'), JS_DATE_FORMAT);

        function check_selected() {
            ids = document.getElementsByName('item_ids[]');
            counter = 0;
            for (var i = 0; i < ids.length; i++) {
                if (ids[i].checked) {
                    checked = true;
                    counter++;
                }
            }
            if (counter === 0) {
                alert("<?php echo lang('common_select_items_for_payment'); ?>");
                return false;
            } else {
                $("#item_form").submit();
            }

        }
        function confirm_deletion() {
            if (confirm("Are you sure you want to delete this item?")) {
                return true;
            }
            return false;
        }
    </script>
    <?php $this->load->view("partial_public/footer"); ?>
