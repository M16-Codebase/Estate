<?php

$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value' => set_value('login')
);

?>


<?php echo form_open($this->uri->uri_string()); ?>

<?php echo $this->dx_auth->get_auth_error(); ?>

<dl>
	<dt><?php echo form_label($lang->form_input_text_forgot_password_text, $login['id']);?></dt>
	<dd>
		<?php echo form_input($login); ?> 
		<?php echo form_error($login['name']); ?>
		<?php echo form_submit('reset', $lang->form_input_text_forgot_password_submit); ?>
	</dd>
</dl>

<?php echo form_close()?>
