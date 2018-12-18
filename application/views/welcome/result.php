<?php $this->load->view("welcome/header"); ?>
<!-- Icons Grid -->
<section class="features-icons bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="preview-box">

                    <?php if ($type == 'registered') { ?>
                        <?php if (count($personal_items_result) > 0) { ?>
                            <div class="features-icons-icon d-flex">
                                <h3>Ibisubizo kubyo mwashatse "<strong><?php echo $search; ?></strong>" (<?php echo $total_rows . ' byandikishijwe'; ?>)</h3>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 2%;">No</th>
                                        <th style="width: 20%;">Category</th>
                                        <th style="width: 20%;">Name</th>
                                        <th style="width: 20%;">Serial Number/Item Label</th>
                                        <th style="width: 20%;">Owner's names</th>
                                        <th style="width: 18%;">Phone number</th>
                                    </tr>
                                </thead>
                                <?php
                                $counter = 1;
                                foreach ($personal_items_result as $item) {
                                    ?>
                                    <tr <?php
                                    if ($counter % 2 == 0) {
                                        echo 'class="success"';
                                    } else {
                                        echo 'class="info"';
                                    }
                                    ?> >
                                        <td>
                                            <?php echo $counter; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->category; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->name; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->item_number; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->first_name . ' ' . $item->last_name; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->phone_number; ?>
                                        </td>

                                    </tr>
                                    <?php
                                    $counter++;
                                }
                                ?>
                            </table>
                            <?php
                        } else {
                            ?>
                            <div class="features-icons-icon d-flex">
                                <h3>Mutubabarire, nta makuru ashoboye kuboneka kucyo mushaka. 
                                    Mwongere mugerageze numero y'ikirango</h3>
                            </div>
                            <br />
                            <br />
                            <br />

                            <div class="features-icons-icon d-flex">
                                <h3>Niba icyo ushakisha cyaratakaye</h3>&nbsp;&nbsp; <a href="<?php echo site_url('welcome/search') . '/' . $search . '/found'; ?>"><button type="submit" class="btn btn-block btn-lg btn-success">Kanda hano ushakishe</button></a>
                            </div>
                            <?php
                        }
                        ?>
                    <?php } else if ($type == 'found') {
                        ?>
                        <?php if (count($items_result) > 0) { ?>
                            <div class="features-icons-icon d-flex">
                                <h3>Ibisubizo kubyo mwashatse "<strong><?php echo $search; ?></strong>" (<?php echo $total_rows . ' byatoraguwe'; ?>)</h3>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 30%;">Category</th>
                                        <th style="width: 50%;">Serial Number/Item Label</th>
                                        <th style="width: 15%;">&nbsp;</th>
                                        <th style="width: 15%;">&nbsp;</th>
                                    </tr>
                                </thead>
                                <?php
                                $counter = 1;
                                foreach ($items_result as $item) {
                                    $avatar_url = $item->image_id ? site_url('app_files/view/' . $item->image_id) : base_url('assets/assets/images/avatar-default.jpg');
                                    ?>
                                    <tr <?php
                                    if ($counter % 2 == 0) {
                                        echo 'class="success"';
                                    } else {
                                        echo 'class="info"';
                                    }
                                    ?> >
                                        <td>
                                            <?php echo $counter; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->category; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->item_number; ?>
                                        </td>
                                        <?php
                                        if ($avatar_url) {
                                            echo "<td><a href='$avatar_url' class='rollover'><img src='" . $avatar_url . "' alt='" . H($item->name) . "' class='img-polaroid' width='45' /></a></td>";
                                        }
                                        ?>
                                        <td>
                                            <a href="<?php echo site_url('welcome/item_details') . '/' . $item->item_id; ?>"><button type="submit" class="btn btn-block btn-lg btn-success">Reba andi makuru</button></a>
                                        </td>

                                    </tr>
                                    <?php
                                    $counter++;
                                }
                                ?>
                            </table>
                            <?php
                        } else {
                            ?>
                            <div class="features-icons-icon d-flex">
                                <h3>Mutubabarire, nta makuru ashoboye kuboneka kucyo mushaka. 
                                    Mwongere mugerageze numero y'ikirango</h3>
                            </div>
                            <br />
                            <br />
                            <br />

                            <div class="features-icons-icon d-flex">
                                <h3>Numero ntibonetse? Shakisha</h3>&nbsp;&nbsp; <a href="<?php echo site_url('welcome/search') . '/' . $search . '/picture'; ?>"><button type="submit" class="btn btn-block btn-lg btn-success">Amafoto</button></a>
                            </div>
                            <?php
                        }
                        ?>
                    <?php } else if ($type == 'picture') {
                        ?>
                        <?php if (count($items_result) > 0) { ?>
                            <div class="features-icons-icon d-flex">
                                <h3>Amafoto(<?php echo $total_rows . ''; ?>)</h3>
                            </div>
                            <div class="form-group">
                                <?php echo form_label(lang('common_category') . ' :', 'category_id', array('class' => 'col-sm-3 col-md-3 col-lg-2 control-label  required wide')); ?>
                                <div class="col-sm-9 col-md-9 col-lg-10">
                                    <?php echo form_dropdown('category_id', $categories, ($category_id != '') ? $category_id : $item_info->category_id, 'class="form-control form-inps" id ="category_id" onchange="return check_category(this.value)"'); ?>
                                </div>
                            </div>
                            <table class="table">

                                <?php
                                $counter = 3;
                                foreach ($items_result as $item) {
                                    $avatar_url = site_url('app_files/view/' . $item->image_id);
                                    $item_link = site_url('welcome/item_details') . '/' . $item->item_id;
                                    if ($counter % 3 == 0) {
                                        echo '<tr>';
                                    }
                                    $counter++;
                                    echo "<td style='width: 33%'><a href='$item_link' class='rollover'><img src='" . $avatar_url . "' alt='" . H($item->name) . "' class='img-polaroid' /></a></td>";
                                    ?>
                                    <?php
                                    if ($counter % 3 == 0) {
                                        echo '</tr>';
                                    }
                                    ?>
                                    <?php
                                    ?>
                                    <?php
                                }
                                ?>
                            </table>
                            <?php
                        } else {
                            ?>
                            <div class="features-icons-icon d-flex">
                                <h3>Mutubabarire, nta makuru ashoboye kuboneka kucyo mushaka. 
                                    Mwongere mugerageze numero y'ikirango</h3>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                    } else {
                        //
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script type='text/javascript'>
    function check_category(category_id) {
        location.href = "<?php echo site_url("welcome/search/0/picture"); ?>" + "/" + category_id;
    }
</script>
<?php $this->load->view("welcome/footer"); ?>
