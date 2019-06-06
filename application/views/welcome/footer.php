
<!-- Footer -->
<footer class="footer bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 h-100 text-center text-lg-left my-auto">
                <ul class="list-inline mb-2">
                    <li class="list-inline-item">
                        <a href="#">About</a>
                    </li>
                    <li class="list-inline-item">&sdot;</li>
                    <li class="list-inline-item">
                        <a href="#">Contact</a>
                    </li>
                    <li class="list-inline-item">&sdot;</li>
                    <li class="list-inline-item">
                        <a href="#">Terms of Use</a>
                    </li>
                    <li class="list-inline-item">&sdot;</li>
                    <li class="list-inline-item">
                        <a href="#">Privacy Policy</a>
                    </li>
                </ul>
                <p class="text-muted small mb-4 mb-lg-0">&copy; Your Website 2018. All Rights Reserved.</p>
            </div>
            <div class="col-lg-6 h-100 text-center text-lg-right my-auto">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item mr-3">
                        <a href="#">
                            <i class="fa fa-facebook fa-2x fa-fw"></i>
                        </a>
                    </li>
                    <li class="list-inline-item mr-3">
                        <a href="#">
                            <i class="fa fa-twitter fa-2x fa-fw"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#">
                            <i class="fa fa-instagram fa-2x fa-fw"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap core JavaScript -->
<script src="<?php echo base_url(); ?>assets/public/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type='text/javascript'>
    function validate_search() {
        var searc_text = $('#search_text').val();
        var search_option = $('#search_option').val();
        if (searc_text === "") {
            alert('Shyiramo icyo ushakisha');
        } else {
            if (search_option == 'registered') {
                window.location.href = "<?php echo site_url('welcome/search'); ?>" + "/" + searc_text + "/registered";
            } else {
                window.location.href = "<?php echo site_url('welcome/search'); ?>" + "/" + searc_text + "/found";
            }

        }
    }
    function search_found() {
        var searc_text = $('#search_text').val();
        if (searc_text === "") {
            alert('Shyiramo icyo ushakisha');
        } else {
            window.location.href = "<?php echo site_url('welcome/search'); ?>" + "/" + searc_text + "/found";
        }

    }
    function search_registered() {
        var searc_text = $('#search_text').val();
        if (searc_text === "") {
            alert('Shyiramo icyo ushakisha');
        } else {
            window.location.href = "<?php echo site_url('welcome/search'); ?>" + "/" + searc_text + "/registered";
        }
    }
</script>
</body>

</html>
