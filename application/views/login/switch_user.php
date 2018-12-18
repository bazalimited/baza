<?php echo form_open('login/switch_user/'.($reload ? '1' : '0' ),array('id'=>'login_form','class'=>'form-horizontal')); ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label=<?php echo json_encode(lang('common_close')); ?>><span aria-hidden="true" class="ti-close"></span></button>
			<h4 class="modal-title"> <?php echo lang('common_switch_user'); ?></h4>
		</div>
		<div class="modal-body ">

			<div class="row">
				<div class="col-md-12">
					<i id="spin" class="fa fa-spinner fa fa-spin  hidden"></i>
					<span id="error_message" class="text-danger">&nbsp;</span>

					<div class="form-group">
					<?php echo form_label(lang('common_employee').' :', 'employee',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_dropdown('username', $employees, $this->Employee->get_logged_in_employee_info()->username, 'class="form-control" id="username"');?>
						</div>
					</div>

					
					<?php if (!$this->config->item('fast_user_switching')) { ?>
					<div class="form-group">
						<?php echo form_label(lang('login_password').' :', 'password',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required wide')); ?>
						<div class="col-sm-9 col-md-9 col-lg-10">
							<?php echo form_password(array(
							'name'=>'password', 
							'id' => 'password',
							'value'=>'',
							'class'=>'form-control',
							'size'=>'20')); ?>
						</div>
					</div>
					
					<script type="text/javascript">
					$("#password").focus();
					</script>
					
					<?php }
					else
					{
					?>
						<h2 class='text-center'><?php echo lang('common_or'); ?></h2>
						
						<div class="form-group">
							<?php echo form_label(lang('common_employees_number').' / '.lang('common_username').' :', 'username_or_account_number',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  required wide')); ?>
							<div class="col-sm-9 col-md-9 col-lg-10">
								<?php echo form_input(array(
								'type' => 'text',
								'name'=>'username_or_account_number', 
								'id' => 'username_or_account_number',
								'value'=>'',
								'class'=>'form-control',
								'size'=>'20')); ?>
							</div>
						</div>
						
						<script type="text/javascript">
						$("#username_or_account_number").focus();
						</script>
						<?php } ?>
				</div>	
			</div>
		</div>
	
		<div class="modal-footer">
			<div class="form-acions">
				<?php
				echo form_submit(array(
					'name'=>'submit',
					'id'=>'submit',
					'value'=>lang('common_submit'),
					'class'=>'submit_button btn btn-primary btn-block btn-lg')
				);
				?>
			</div>
		</div>
			
	</div>
</div>
	
<?php echo form_close(); ?>

<script type='text/javascript'>
//validation and submit handling
$('#username').selectize();
var submitting = false;

$('#login_form').validate({
	submitHandler:function(form)
	{
		if (submitting) return;
		submitting = true;
		$('#spin').removeClass('hidden');
		$(form).ajaxSubmit({
			success:function(response)
			{
				$('#spin').addClass('hidden');
				submitting = false;
				if(!response.success)
				{
					$('#error_message').html(response.message);
				}
				else
				{
					if (response.reload == 0) 
					{
						if (response.is_clocked_in_or_timeclock_disabled)
						{
							$(".avatar_info").text(response.name);
							$(".avatar_width img").attr('src', response.avatar);
							$('#myModal').modal('hide');	
							show_feedback('success',<?php echo json_encode(lang('login_swich_user_success')); ?>,<?php echo json_encode(lang('common_success')); ?>);
						}
						else
						{
							window.location = '<?php echo site_url('timeclocks'); ?>';
						}
						
											
					}
					else 
					{
						$('#myModal').modal('hide');
						
						if (response.is_clocked_in_or_timeclock_disabled)
						{
							window.location.reload(true);								
						}
						else
						{
							window.location = '<?php echo site_url('timeclocks'); ?>';
						}
					} 
				}
			},
			dataType:'json'
		});
	},
	errorClass: "text-danger display-block",
	errorElement: "span",
	highlight:function(element, errorClass, validClass) {
		$(element).parents('.form-group').addClass('error');
	},
	unhighlight: function(element, errorClass, validClass) {
		$(element).parents('.form-group').removeClass('error');
		$(element).parents('.form-group').addClass('success');
	},rules:
	{
		password:"required"
	},
	messages:
	{
		password:
		{
			required: <?php echo json_encode(lang('login_invalid_username_and_password')); ?>
		}

	}
});


</script>