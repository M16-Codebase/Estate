
<style>
.filehref {
    padding: 5px;
    float: left;    
    display: block;
    background: #eee;
    margin-right: 10px;
    margin-bottom: 10px;
    box-shadow: 0px 1px 1px #282830;
    border: none;
    text-decoration: none;
    color: #282830;   
}
.filehref:hover {
    background: #FF8080;
    color: #fff;
}
.filehref.load {
    background: #D5FCFF;
    color: #000;
}
.filehref.success {
    background: #D9FFD9;
    color: #000;    
}
</style>
<?php 

    $this->load->helper('directory'); 
    $map = directory_map(APPPATH.'../asset/files');
    $co = 0;
    
    foreach($map as $first_key => $first)
    {
        if(is_array($first))
        {
            foreach($first as $second_key => $second)
            {
                if(is_array($second))
                {
                    foreach($second as $third_key => $third)
                    {
                        if(is_array($third))
                        {
                            foreach($third as $four_key => $four)
                            {
                                if(is_array($four))
                                {
                                    foreach($four as $five_key => $five)
                                    {                                        
                                        if(is_array($five))
                                        {
                                            foreach($five as $files)
                                            {
                                                $f = str_replace('.php','',$files);
                                                echo '<a class="filehref" id="'.$co.$four_key.$five_key.$f.'" href="'.$first_key.'/'.$second_key.'/'.$third_key.'/'.$four_key.'/'.$five_key.'/'.$files.'">'.$four_key.'/'.$five_key.'/'.$f.'</a>';
                                            }
                                            
                                            $co += count($five);
                                        }    
                                        else
                                        {
                                            $f = str_replace('.php','',$five);
                                            echo '<a class="filehref" id="'.$co.$four_key.$f.'" href="'.$first_key.'/'.$second_key.'/'.$third_key.'/'.$four_key.'/'.$five.'">'.$four_key.'/'.$f.'</a>';
                                                                                        
                                            $co += 1;
                                        }                                    
                                    }
                                }
                                else
                                {
                                    $f = str_replace('.php','',$four);
                                    echo '<a class="filehref" id="'.$co.$third_key.$f.'" href="'.$first_key.'/'.$second_key.'/'.$third_key.'/'.$four.'">'.$third_key.'/'.$f.'</a>';
                                                                                
                                    $co += 1;
                                }
                            }
                        }
                    }
                }                
            }
        }        
    }    
    echo '<div style="clear: both;"><br />Всего файлов: '.$co.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="iclick" href="">Запустить обработку файлов</a></div>';
?>

<script>
$(document).ready(function(){
    $('#iclick').click(function(){
        $('.filehref').each(function(i, obj){       
           setTimeout(function(){       
               
               var $this = $(obj),
                    iid = $this.attr('id'),
                    ipath = $this.attr('href');
                   
               $.ajax({
                    type: 'POST',
                    url: '/ajax/loadContent',
                    dataType: 'json', 
                    data: {                
                        path: ipath,
                        id: iid
                    },
                    success: function(data) { 
                        $('#ok').empty().append(data.ok);
                        $this.text('В процессе обработки');                    
                    }
                });  
                            
            }, 2500 + ( i * 2500 ));                    
        }); 
    
        return false;
    });
    
    $('.filehref').click(function(){ 
        
        var $this = $(this),
                    iid = $this.attr('id'),
                    ipath = $this.attr('href');
                   
               $.ajax({
                    type: 'POST',
                    url: '/ajax/loadContent',
                    dataType: 'json', 
                    data: {                
                        path: ipath,
                        id: iid
                    },
                    success: function(data) { 
                        $('#ok').empty().append(data.ok);
                        $this.text('В процессе обработки');                    
                    }
                }); 
        
        return false; });    
});
</script>
<br />
<div id="ok"></div>