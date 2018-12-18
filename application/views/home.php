<?php
$this->load->view("partial/header");
$this->load->helper('demo');
?>


<?php
//if ($can_show_mercury_activate) { 
if (false) {
    ?>
    <!-- mercury activation message -->
    <div class="row " id="mercury_container">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <a id="dismiss_mercury" href="<?php echo site_url('home/dismiss_mercury_message') ?>" class="pull-right text-danger"><?php echo lang('common_dismiss'); ?></a>
                    <div id="mercury_activate_container">
                        <a href="https://baza.rw/mercury_activate.php" target="_blank"><?php
                            echo img(
                                    array(
                                        'src' => base_url() . 'assets/img/mercury_logo.png',
                                        'class' => 'hidden-print',
                                        'id' => 'mercury-logo',
                            ));
                            ?>
                        </a>
                        <h3><a href="https://baza.rw/mercury_activate.php" target="_blank"><?php echo lang('common_credit_card_processing'); ?></a></h3>
                        <a href="https://baza.rw/mercury_activate.php" class="mercury_description" target="_blank">
                            <?php echo lang('home_mercury_activate_promo_text'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php
$this->load->helper('demo');
?>

<div class="text-center">					
    <h5 class="text-center"><?php echo lang('home_welcome_message'); ?></h5><br /><br />
    <?php if (!$this->config->item('hide_dashboard_statistics') && (!$this->agent->is_mobile() || $this->agent->is_tablet())) { ?>

        <div class="row" style="margin-left: -1px;">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a href="<?php echo site_url('employees'); ?>">
                    <div class="dashboard-stats" id="totalCustomers">
                        <div class="left">
                            <h3 class="flatGreenc"><?php echo $total_employees; ?></h3>
                            <h4><?php echo lang('common_total') . " " . lang('module_employees'); ?></h4>
                        </div>
                        <div class="right flatGreen">
                            <i class="ion ion-ios-people-outline"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a href="<?php echo site_url('locations'); ?>">
                    <div class="dashboard-stats">
                        <div class="left">
                            <h3 class="flatBluec"><?php echo $total_locations; ?></h3>
                            <h4><?php echo lang('common_total') . " " . lang('module_locations'); ?></h4>
                        </div>
                        <div class="right flatRed">
                            <i class="icon ti-harddrive"></i>
                        </div>
                    </div>
                </a>
            </div>
<!--            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a href="#">
                    <div class="dashboard-stats">
                        <div class="left">
                            <h3 class="flatBluec">18,975</h3>
                            <h4><?php echo lang('common_total') . " " . lang('visits'); ?></h4>
                        </div>
                        <div class="right flatRed">
                            <i class="icon ti-bar-chart"></i>
                        </div>
                    </div>
                </a>
            </div>-->

        </div>
<!--        <div class="row" style="margin-left: -1px;">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a href="<?php echo site_url('items'); ?>">
                    <div class="dashboard-stats" id="totalCustomers">
                        <div class="left">
                            <h3 class="flatGreenc"><?php echo $total_items; ?></h3>
                            <h4 style="margin-right: 100px;"><?php echo lang('common_registered_items'); ?></h4>
                        </div>
                        <div class="right flatRed">
                            <i class="icon ti-harddrive"></i>
                        </div>
                    </div>
                </a>
            </div>

        </div>-->
        <div class="row" style="margin-left: -1px;">
<!--            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a href="<?php echo site_url('receivings'); ?>">
                    <div class="dashboard-stats" id="totalCustomers">
                        <div class="left">
                            <h3 class="flatGreenc"><?php echo $total_receivings; ?></h3>
                            <h4 style="margin-right: 100px;"><?php echo lang('common_total') . " " . lang('common_lost_and_found'); ?></h4>
                        </div>
                        <div class="right flatRed">
                            <i class="ion ti-cloud-down"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a href="<?php echo site_url('sales'); ?>">
                    <div class="dashboard-stats">
                        <div class="left">
                            <h3 class="flatBluec"><?php echo $total_sales; ?></h3>
                            <h4 style="margin-right: 100px;"><?php echo lang('common_total') . " " . lang('common_collected_lost_items'); ?></h4>
                        </div>
                        <div class="right flatBlue">
                            <i class="ion ion-ios-cart-outline"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a href="#">
                    <div class="dashboard-stats">
                        <div class="left">
                            <h3 class="flatBluec">18,975</h3>
                            <h4 style="margin-right: 100px;"><?php echo lang('common_total') . " " . lang('common_unpaid_agent'); ?></h4>
                        </div>
                        <div class="right flatRed">
                            <i class="ion ion-ios-cart-outline"></i>
                        </div>
                    </div>
                </a>
            </div>-->

        </div>
    </div>

<?php } ?>

<div class="row quick-actions" style="display: none">

    <?php if ($this->Employee->has_module_action_permission('reports', 'view_closeout', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="list-group">
                <a class="list-group-item" href="<?php echo site_url('reports/closeout/' . date('Y-m-d') . '/0'); ?>"> <i class="ion-clock"></i> <?php echo lang('home_todays_closeout_report'); ?></a>
            </div>
        </div>
    <?php } ?>

    <?php if ($this->Employee->has_module_action_permission('reports', 'view_sales', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="list-group">
                <a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/' . date('Y-m-d') . '%2000:00:00/' . date('Y-m-d') . '%2023:59:59/all/0'); ?>"> <i class="ion-stats-bars"></i> <?php echo lang('home_todays_detailed_sales_report'); ?></a>
            </div>
        </div>
    <?php } ?>

    <?php if ($this->Employee->has_module_action_permission('reports', 'view_items', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="list-group">
                <a class="list-group-item" href="<?php echo site_url('reports/summary_items/' . date('Y-m-d') . '%2000:00:00/' . date('Y-m-d') . '%2023:59:59/0/0/0/-1/-1/all/0'); ?>"> <i class="ion-clipboard"></i> <?php echo lang('home_todays_summary_items_report'); ?></a>
            </div>
        </div>
    <?php } ?>


    <?php if ($this->Employee->has_module_permission('receivings', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="list-group">
                <a class="list-group-item" href="<?php echo site_url('receivings'); ?>"> <i class="icon ti-cloud-down"></i> <?php echo lang('home_receivings_start_new_receiving'); ?></a>
            </div>
        </div>
    <?php } ?>

    <?php if ($this->Employee->has_module_permission('sales', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="list-group">
                <a class="list-group-item" href="<?php echo site_url('sales'); ?>"> <i class="icon ti-shopping-cart"></i> <?php echo lang('home_start_new_sale'); ?></a>
            </div>
        </div>
    <?php } ?>

</div>

<?php if (!$this->config->item('hide_dashboard_statistics')) { ?>
    <div class="row " style="display: none">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">

                    <?php if (can_display_graphical_report()) { ?>
                        <div class="panel-heading">
                            <h4 class="text-center"><?php echo lang('common_sales_info') ?></h4>	
                        </div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs piluku-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#month" data-type="monthly" aria-controls="month" role="tab"><?php echo lang('common_month') ?></a></li>
                            <li role="presentation"><a href="#week" data-type="weekly" aria-controls="week" role="tab"><?php echo lang('common_week') ?></a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content piluku-tab-content">
                            <div role="tabpanel" class="tab-pane active" id="month">
                                <div class="chart">
                                    <?php if (isset($month_sale) && !isset($month_sale['message'])) { ?>
                                        <canvas id="charts" width="400" height="100"></canvas>		
                                        <?php
                                    } else {
                                        echo $month_sale['message'];
                                    }
                                    ?>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="week">

                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>

<?php if ($choose_location && count($authenticated_locations) > 1) { ?>


    <!-- Modal -->
    <div class="modal fade" id="choose_location_modal" tabindex="-1" role="dialog" aria-labelledby="chooseLocation" style="display: none">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label=<?php echo json_encode(lang('common_close')); ?>><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="chooseLocation"><?php echo lang('common_locations_choose_location'); ?></h4>
                </div>
                <div class="modal-body">
                    <ul class="list-inline choose-location-home">
                        <?php foreach ($authenticated_locations as $key => $value) { ?>
                            <li><a class="set_employee_current_location_after_login" data-location-id="<?php echo $key; ?>" href="<?php echo site_url('home/set_employee_current_location_id/' . $key) ?>"> <?php echo $value; ?> </a></li>
                            <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<!-- Location Message to employee -->
<script>
    $(document).ready(function () {

        $("#dismiss_mercury").click(function (e) {
            e.preventDefault();
            $.get($(this).attr('href'));
            $("#mercury_container").fadeOut();

        });

        $("#dismiss_test_mode").click(function (e) {
            e.preventDefault();
            $.get($(this).attr('href'));
            $("#test_mode_container").fadeOut();
        });

<?php if ($choose_location && count($authenticated_locations) > 1) { ?>

            $('#choose_location_modal').modal('show');

            $(".set_employee_current_location_after_login").on('click', function (e)
            {
                e.preventDefault();

                var location_id = $(this).data('location-id');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url('home/set_employee_current_location_id'); ?>',
                    data: {
                        'employee_current_location_id': location_id,
                    },
                    success: function () {

                        window.location = <?php echo json_encode(site_url('home')); ?>;
                    }
                });

            });

<?php } ?>


<?php if (isset($month_sale) && !isset($month_sale['message'])) { ?>
            var data = {
                labels: <?php echo $month_sale['day'] ?>,
                datasets: [
                    {
                        fillColor: "#5d9bfb",
                        strokeColor: "#5d9bfb",
                        highlightFill: "#5d9bfb",
                        highlightStroke: "#5d9bfb",
                        data: <?php echo $month_sale['count'] ?>
                    }
                ]
            };
            var ctx = document.getElementById("charts").getContext("2d");
            var myBarChart = new Chart(ctx).Bar(data, {
                responsive: true
            });
<?php } ?>



        $('.piluku-tabs a').on('click', function (e) {
            e.preventDefault();
            $('.piluku-tabs li').removeClass('active');
            $(this).parent('li').addClass('active');
            var type = $(this).attr('data-type');
            $.post('<?php echo site_url("home/sales_widget/'+type+'"); ?>', function (res)
            {
                var obj = jQuery.parseJSON(res);
                if (obj.message)
                {
                    $(".chart").html(obj.message);
                    return false;
                }

                renderChart(obj.day, obj.count);

                myBarChart.update();
            });
        });

        function renderChart(label, data) {

            $(".chart").html("").html('<canvas id="charts" width="400" height="400"></canvas>');
            var lineChartData = {
                labels: label,
                datasets: [
                    {
                        fillColor: "#5d9bfb",
                        strokeColor: "#5d9bfb",
                        highlightFill: "#5d9bfb",
                        highlightStroke: "#5d9bfb",
                        data: data
                    }
                ]

            }
            var canvas = document.getElementById("charts");
            var ctx = canvas.getContext("2d");

            myLine = new Chart(ctx).Bar(lineChartData, {
                responsive: true,
                maintainAspectRatio: false
            });
        }
    });
</script>

<?php $this->load->view("partial/footer"); ?>