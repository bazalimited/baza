<?php
$this->load->view("partial_public/header");
$this->load->helper('demo');
?>

<div class="text-center">					
    <h5 class="text-center"><?php echo lang('home_welcome_message'); ?></h5><br /><br />
    <div class="row" style="margin-left: -1px;">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="<?php echo site_url('personal/registered'); ?>">
                <div class="dashboard-stats" id="totalCustomers">
                    <div class="left">
                        <h3 class="flatGreenc"><?php echo $registered_items_count; ?></h3>
                        <h4 style="margin-right: 100px;"><?php echo lang('common_registered_items'); ?></h4>
                    </div>
                    <div class="right flatRed">
                        <i class="icon ti-harddrive"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="<?php echo site_url('personal/pending'); ?>">
                <div class="dashboard-stats">
                    <div class="left">
                        <h3 class="flatBluec"><?php echo $pending_items_count; ?></h3>
                        <h4 style="margin-right: 100px;"><?php echo lang('common_total') . " Pending payment"; ?></h4>
                    </div>
                    <div class="right flatBlue">
                        <i class="ion ion-ios-cart-outline"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="<?php echo site_url('personal/found'); ?>">
                <div class="dashboard-stats" id="totalCustomers">
                    <div class="left">
                        <h3 class="flatGreenc"><?php echo $total_found_items; ?></h3>
                        <h4 style="margin-right: 100px;"><?php echo lang('common_total') . " Found items"; ?></h4>
                    </div>
                    <div class="right flatRed">
                        <i class="ion ti-cloud-down"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="text-center" style="background-color: #FFFFFF;">
    <div class="row" style="margin-left: -1px; padding-bottom: 50px;">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="buttons-list items-buttons">
                <?php
                echo
                anchor("personal/view/-1/", '<span class="">Create item</span>', array('class' => 'btn btn-primary btn-lg',
                    'title' => 'Create item'));
                ?>
            </div>
        </div>
    </div>
</div>

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

<?php $this->load->view("partial_public/footer"); ?>