<?php
    if(!isset($removeElem)) { $removeElem = 'null'; }
    if(!isset($func)) { $func = 'send_onValidComplete'; }      
?>
<script>
    $(document).ready(function() { 
        $('<?php echo $form; ?>').validationEngine('attach', {
          onValidationComplete: function(form, status){
            if(status)
            {
                <?php echo $func; ?>('<?php echo $form; ?>','<?php echo $url; ?>','<?php echo $removeElem; ?>');
            }
          }  
        });       
    });
</script>