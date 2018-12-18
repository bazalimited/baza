
			<div id="footers" class="col-md-12 hidden-print text-center">
				<?php echo lang('common_please_visit_my'); ?> 
					<a tabindex="-1" href="http://baza.rw" target="_blank"><?php echo lang('common_website'); ?></a> <?php echo lang('common_learn_about_project'); ?>.
					<span class="text-info"><?php echo lang('common_you_are_using_phppos')?> <span class="badge bg-primary"> <?php echo APPLICATION_VERSION; ?></span></span>
			</div>
		</div>
		<!---content -->
	</div>
	
	<!-- wrapper -->
</body>
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
</script>
</html>