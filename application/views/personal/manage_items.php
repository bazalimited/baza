<?php $this->load->view("partial_public/header"); ?>
<div class="manage_buttons">
    <div class="row">
        <div class="col-md-9">
            <?php echo form_open("personal/items"); ?>
            <div class="search search-items no-left-border">
                <ul class="list-inline">
                    <li>	
                        <input type="text" class="form-control" name ='search' id='search' value="<?php echo H($search); ?>" placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_' . $controller_name); ?>"/>
                    </li>
                    <li>
                        <?php echo lang('common_category'); ?>: 	
                        <?php echo form_dropdown('category_id', $categories, $category_id, 'class="form-control" id="category_id"'); ?>
                    </li>
                    <li><?php echo form_submit('submitf', lang('common_search'), 'class="btn btn-primary btn-lg"'); ?></li>

                </ul>
            </div>
            <?php echo form_close() ?>

        </div>
        <div class="col-md-3">
            <div class="buttons-list items-buttons">
                <div class="pull-right-btn">
                    <?php
                    echo
                    anchor("personal/view/-1/", '<span class="">Create item</span>', array('class' => 'btn btn-primary btn-lg',
                        'title' => 'Create item'));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row alert-select-all">
    <div class="col-md-12">

        <div id="selectall" class="selectall text-center" onclick="select_inv()">
            <div class="alert alert-danger">
                <?php echo lang('items_all') . ' ' . lang('items_select_inventory') . ' <strong>' . lang('items_for_current_search') . '</strong>'; ?>
            </div>
        </div>

        <div id="selectnone" class="selectnone text-center" onclick="select_inv_none()" >
            <div class="alert alert-danger">
                <?php echo '<strong>' . lang('items_selected_inventory_total') . ' ' . lang('items_select_inventory_none') . '</strong>'; ?>
            </div>
        </div>
        <?php
        echo form_input(array(
            'name' => 'select_inventory',
            'id' => 'select_inventory',
            'style' => 'display:none',
        ));
        ?>
    </div>
</div>



<div class="container-fluid">
    <div class="row manage-table">
        <div class="panel panel-piluku">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo ' My Items(' . $user_items_count . ')'; ?>
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
                <?php if (count($user_items) > 0) { ?>
                    <table class="table tablesorter table-hover" id="sortable_table">
                        <thead>
                            <tr>
                                <th class="leftmost">No</th>
                                <th>Item Category</th>
                                <th>Name</th>
                                <th>Serial Number or Name on the Card</th>
                                <th>Description</th>
                                <th>&nbsp;<th>
                                <th>&nbsp;<th>
                                <th class="rightmost">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1;
                            foreach ($user_items->result() as $item) {
                                $item_category = $this->Category->get_info($item->category_id);
                                $avatar_url = $item->image_id ? site_url('personal_app_files/view/' . $item->image_id) : base_url('assets/assets/images/avatar-default.jpg');
                                ?>
                                <tr style="cursor: pointer;">
                                    <td><?php echo $counter; ?></td>
                                    <td><?php echo $item->category; ?></td>
                                    <td><?php echo $item->name; ?></td>
                                    <td><?php 
                                    if($item_category->record_sn == 1){
                                        echo $item->item_number; 
                                    }else{
                                        echo $item->name_on_card;
                                    }
                                    ?></td>
                                    <td><?php echo $item->description; ?></td>

                                    <td class="rightmost">
                                        <a href="<?php echo site_url('personal/view/' . $item->item_id); ?>" class=" " title="Edit">Edit</a></td>
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
                <?php } ?>
            </div>		

        </div>
    </div>
</div>

<script type='text/javascript'>

    date_time_picker_field($('.datepicker'), JS_DATE_FORMAT);

    function confirm_deletion() {
        if (confirm("Are you sure you want to delete this item?")) {
            return true;
        }
        return false;
    }
</script>
<?php $this->load->view("partial_public/footer"); ?>
