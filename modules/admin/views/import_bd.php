<?php

/**
 * Шаблон импорта БД
 *      
 * 
**/
?>

<?php

    if(ses_data('error'))
    {
        echo '
            <p class="message red-gradient white">						
            	<strong style="line-height: 120%;">'.ses_data('error').'</strong><br/>
            </p>
        ';
    }
    elseif(ses_data('success'))
    {
        echo '
            <p class="message green-gradient white">						
            	<strong style="line-height: 120%;">Файл успешно загружен!</strong><br/>
            </p>
        ';
    }
    
    $this->session->unset_userdata('error');
    $this->session->unset_userdata('success');
?>

<div class="with-padding">

    <h2 class="thin underline relative">
        
        Дамп БД   
        <span class="button-group absolute-right">
            
        </span> 
    </h2>
    
    <div class="columns">
        
        
        <div class="seven-columns twelve-columns-mobile">
            
            
    		<div class="block margin-bottom">
    
    			<h3 class="block-title">Создание дампа базы данных</h3>
    
    			<div class="with-padding">
                    <iframe src="<?php echo BASEURL; ?>/asset/dumper/dumper.php" width="100%" height="500"></iframe>                                               
    			</div>
    
    		</div>
            
            
            
        </div>
        
        
    	<div class="five-columns twelve-columns-mobile">
                    
            
            <?php echo form_open(); ?>
            
    		<div class="block margin-bottom">
    
    			<h3 class="block-title">
                    Список файлов дампа 
                    <a class="float-right button hidden" target="_blank" href="/dumper/dumper.php">Сделать дамп</a>
                </h3>                       
                
    			<div class="with-padding">			                                                            
                    <ol>
                    <?php if(!empty($sql)): ?>
                    <?php foreach($sql as $s):?>
                        <li><a class="button compact icon-download" href="<?php echo $s['download']; ?>"> Скачать</a><?php echo nbs(1);?><strong><?php echo $s['name']; ?></strong></li>                        
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </ol>
    			</div>
    
    		</div>
            
            <?php echo form_close(); ?>
            
        </div>    
           
    </div>

</div>