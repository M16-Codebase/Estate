<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' => set_value('username')
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30
);

$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> true,
	'style' => 'margin:0;padding:0'
);

$confirmation_code = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8
);

?>

<?php echo form_open($this->uri->uri_string())?>

<?php echo '<p class="error">'.$this->dx_auth->get_auth_error().'</p>'; ?>

<dl>	                
    <dt><?php echo form_label($lang->form_input_text_username, $username['id']);?></dt>
	<dd>
		<?php echo form_input($username)?>
    <?php echo form_error($username['name']); ?>
	</dd>

  <dt><?php echo form_label($lang->form_input_text_password, $password['id']);?></dt>
	<dd>
		<?php echo form_password($password)?>
    <?php echo form_error($password['name']); ?>
	</dd>

<?php if ($show_captcha): ?>

	<dt>Enter the code exactly as it appears. There is no zero.</dt>
	<dd><?php echo $this->dx_auth->get_captcha_image(); ?></dd>

	<dt><?php echo form_label($lang->form_input_text_captcha, $confirmation_code['id']);?></dt>
	<dd>
		<?php echo form_input($confirmation_code);?>
		<?php echo form_error($confirmation_code['name']); ?>
	</dd>
	
<?php endif; ?>

	<dt></dt>
	<dd>
		<?php echo form_checkbox($remember);?> <?php echo form_label($lang->form_input_text_remember, $remember['id']);?> 		
		<?php
			if ($this->dx_auth->allow_registration) {
				echo br().anchor($this->dx_auth->register_uri, $lang->form_input_text_register);
			};
            echo nbs().anchor($this->dx_auth->forgot_password_uri, $lang->form_input_text_forgot_password);
		?>
        
	</dd>

	<dt></dt>
	<dd><?php echo form_submit('login',$lang->form_input_text_enter);?> или <?php echo anchor('',$lang->form_input_text_cancel,array('class'=>'read_more')); ?></dd>
</dl>

<?php echo form_close()?>


