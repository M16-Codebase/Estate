<div class="with-padding">
    <div class="columns">
        <div class="two-columns"></div>
            <div class="eight-columns">
                <div class="block blue-gradient">
                	<div class="block-title">
                        <h3>Send an email to the technical support</h3>
                        <div class="button-group absolute-right">
                    		<a href="javascript:void(0);" class="button icon-mail icon-size2 blue-gradient">Send</a>		
                    	</div>
                    </div>
                	<div class="with-padding">                                                
                
                		<div class="columns">
                            <div class="twelve-columns no-margin-bottom"><?php echo $this->elem->fm_short_textarea(array('name'=>'text', 'id'=>'text', 'type' => 'textarea','placeholder'=>'Message')); ?></div>                            
                        </div>
                        <br />
                        <div class="columns">
                            <div class="six-columns"><?php echo $this->elem->fm_input(array('name' => 'name', 'id' => 'name', 'placeholder' => 'Your Name', 'type' => 'input')); ?></div>
                            <div class="six-columns"><?php echo $this->elem->fm_input(array('name' => 'email', 'id' => 'email', 'placeholder' => 'Your E-mail adress', 'type' => 'input')); ?></div>                  
                        </div>
                
                	</div>
                </div>
            </div>
        <div class="two-columns"></div>
    </div>
</div>

<script>
$(document).ready(function(){
   $('.icon-mail').click(function(){
        
        var f_name = $('#name').val(),
            f_email = $('#email').val(),
            f_text = $('#text').val(),
            err = 0;
        
        if(f_email == '' && f_text == '' && f_name == '')
        {
            $.modal.alert('All fields can not be empty.');
            return false;
        }
                                
        if(f_email == '')
        {
            $.modal.alert('field E-mail can not be empty.');
            err = 1;
            return false;
        }
        else if(!isValidEmailAddress(f_email))
        {
            $.modal.alert('E-mail is invalid.');
            err = 1;
            return false;
        }
        
        if(f_name == '')
        {
            $.modal.alert('field Name can not be empty.');
            err = 1;       
            return false;     
        }
        
        if(f_text == '')
        {
            $.modal.alert('field Message can not be empty.');
            err = 1;      
            return false;                  
        }
        
        if(err == 0)
        {
            $.ajax({
                type: 'POST',
                url: '/admin/functions/support',
                dataType: 'json', 
                data: {
                    text: f_text,
                    email: f_email,
                    name: f_name
                },
                success: function(data) { 
                    
                    if(data.ok == 2)
                    {
                        $('#name').val('');
                        $('#email').val('');
                        $('#text').val('');
                    }
                    
                    $('#ajaxNotification').empty().append(data.mes);
                    removeMessages('#ajaxNotification',false, 3500);                                                                                 
                },
                beforeSend: function() { 
                    $('#ajaxNotification').appendSpinner({spinner: 'working', text: 'Sending a message...'});
                }
            });
        }
        
        return false;                
   }); 
});


function isValidEmailAddress(emailAddress) 
{
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
</script>