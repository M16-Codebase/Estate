<?php

$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha'
);

?>

<?php echo form_open($this->uri->uri_string())?>

<?php

    if(ses_data('username_check') OR ses_data('email_check'))
    {
        echo '<p>'.ses_data('username_check').'</p>';
        echo '<p>'.ses_data('email_check').'</p>';
        $this->session->unset_userdata('username_check');
        $this->session->unset_userdata('email_check');
    }

?>

<dl>    
    
    <?php foreach($form as $key=>$value):?>
        <dt><?php echo form_label($value['label'], $value['id']);?></dt>
    	<dd>
    		<?php echo form_input($key,set_value($key))?>
        <?php echo form_error($key); ?>
    	</dd>
    <?php endforeach; ?>

		
<?php if ($this->dx_auth->captcha_registration): ?>

	<dt><?php echo form_label($lang->form_input_text_captcha, $captcha['id']);?></dt>
	<dd>
		<?php echo $this->dx_auth->get_captcha_image(); ?><br />
        <?php echo form_input($captcha);?>
		<?php echo form_error($captcha['name']); ?>
	</dd>
	
<?php endif; ?>

	<dt></dt>
	<dd><?php echo form_submit('register',$lang->form_input_text_register);?> <?php echo anchor($this->dx_auth->login_uri, $lang->form_input_text_enter);?> </dd>
</dl>

<?php echo form_close()?>
