<?php echo $file; ?>
</script>
<script>
function clickLoad($id, request, webmd, pat)
{
    var req,re = '',mas = {};
    req = request.split(/[{},":]/);
    for (var i=0,len=req.length;i<len;i++) {
        if(req[i] !== '') {           
            re += req[i] + ':';                
        }
    }
    re = re.split(/[:]/);  
    
    mas['age'] = re[3];
    mas['gender'] = re[5];
    mas['bodypartid'] = re[12];
    
    $('#ok').empty();
    $('#' + $id).text('Завершающий этап').addClass('load');  
    
    $.ajax({
        type: 'POST',
        url: '/ajax/savedatabase',
        dataType: 'json', 
        data: {
            request: mas,
            symptoms: webmd.data.symptoms,
            paths: pat
        },
        success: function(data) { 
            $('#ok').empty();
            $('#' + $id).removeAttr('href').text(data.ok).removeClass('load').addClass('success');                               
        }
    });
};  
setTimeout(function(){
    clickLoad('<?php echo $id; ?>', request, webmd, '<?php echo $paths; ?>');
},2500);
</script>