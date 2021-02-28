<?php
$password = array(
	'name'	=> 'password',
	'id'		=> 'password',
	'size' 	=> 30
);

?>

<fieldset>
<legend>Удаление профиля</legend>
<?php echo form_open($this->uri->uri_string()); ?>

<?php echo '<p class="error">'.$this->dx_auth->get_auth_error().'</p>'; ?>

<dl>
	<dt><?php echo form_label('Введите текущий пароль', $password['id']); ?></dt>
	<dd>
		<?php echo form_password($password); ?>
		<?php echo form_error($password['name']); ?>
	</dd>
	<dt></dt>
	<dd><?php echo form_submit('cancel', 'Удалить профиль'); ?></dd>
</dl>

<?php echo form_close(); ?>
</fieldset>