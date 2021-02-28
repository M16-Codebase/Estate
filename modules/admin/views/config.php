<?php
	/**
     * Шаблон загрузки конфигурационных данных с файла конфигурации
     * 
     * $conf - массив файла конфигурции     
     * $valid - переменная в которую подгружается шаблон валидации а также функция для отправки данных через json
     * 
    **/   
    
    // переназначаем
    $elemF = $this->elem;  
    
    // Массив настроек
    $conf['apex_names'] = array(
        'name' => 'apex_names',
        'id' => 'apex_names',
        'type' => 'input',
        'label' => $alang->admin_16,        
        'value' => $confs['apex_names'],
        'params' => '',
        'info' => ''
    );      
    $conf['apex_email'] = array(
        'name' => 'apex_email',
        'id' => 'apex_email',
        'type' => 'input',
        'label' => $alang->admin_17,
        'value' => $confs['apex_email'],
        'params' => '',
        'info' => ''
    );  
    $conf['apex_site'] = array(
        'name' => 'apex_site',
        'id' => 'apex_site',
        'type' => 'input_switch',
        'label' => $alang->admin_18,
        'value' => $confs['apex_site'],
        'params' => array($alang->admin_19, $alang->admin_20),
        'info' => $alang->admin_21
    );
    $conf['apex_site_name'] = array(
        'name' => 'apex_site_name',
        'id' => 'apex_site_name',
        'type' => 'input',
        'label' => $alang->admin_22,
        'value' => $confs['apex_site_name'],        
        'info' => $alang->admin_23
    );
    $conf['apex_site_text'] = array(
        'name' => 'apex_site_text',
        'id' => 'apex_site_text',
        'type' => 'textarea_wysyvig',
        'label' => $alang->admin_24,
        'value' => htmlspecialchars_decode($confs['apex_site_text']),        
        'info' => ''
    );            
    $conf['default_time_zone'] = array(
        'name' => 'default_time_zone',
        'id' => 'default_time_zone',
        'type' => 'input',
        'label' => $alang->admin_25_1,
        'value' => $confs['default_time_zone'],        
        'info' => ''
    );
    $conf['bread_delimiter'] = array(
        'name' => 'bread_delimiter',
        'id' => 'bread_delimiter',
        'type' => 'input',
        'label' => $alang->admin_25_2,
        'value' => $confs['bread_delimiter'],        
        'info' => '',
        'options' => array('width' => 'five')
    );
    $conf['bread_block'] = array(
        'name' => 'bread_block',
        'id' => 'bread_block',
        'type' => 'input',
        'label' => $alang->admin_25_3,
        'value' => htmlspecialchars_decode($confs['bread_block']),        
        'info' => '',
        'options' => array('width' => 'seven')
    );
    $conf['bread_href'] = array(
        'name' => 'bread_href',
        'id' => 'bread_href',
        'type' => 'input',
        'label' => $alang->admin_25_4,
        'value' => htmlspecialchars_decode($confs['bread_href']),        
        'info' => '',
        'options' => array('width' => 'five')
    );
    $conf['bread_no_href'] = array(
        'name' => 'bread_no_href',
        'id' => 'bread_no_href',
        'type' => 'input',
        'label' => $alang->admin_25_5,
        'value' => htmlspecialchars_decode($confs['bread_no_href']),        
        'info' => '',
        'options' => array('width' => 'seven')
    );
    
    $elemF->initial($conf);        
    
    if($this->dx_auth->is_super()) { $superAdmin = true; } else { $superAdmin = false; }         
?>

<div class="with-padding">
    <h2 class="thin underline relative"><?php echo $alang->admin_3; ?> <span class="blue small">CMS APEX</span></h2>
                         
               <div class="standard-tabs margin-bottom">                
                                
                	<ul class="tabs">
                		<li class="active"><a href="#tab-1">Basic</a></li>                						
                        <li><a href="#tab-2">SEO</a></li>
                        <li><a href="#tab-3">Header</a></li>                        
                        <li><a href="#tab-4">Google</a></li>
                        <li><a href="#tab-5">Yandex</a></li>
                        <li><a href="#tab-6">Rambler</a></li>                        
                        <li><a href="#tab-8">Robots.txt</a></li>
                	</ul>
                
                	<div class="tabs-content">
                		
                        <div id="tab-1" class="with-padding">
                        <!-- tab-1 -->
                                <form  class="serial_form" method="post">
                                    <h2 class="thin underline relative">
                                    <?php echo $alang->admin_25; ?>
                                        <span class="button-group absolute-right">
                                        	<button type="submit" class="button green-active icon-tick compact"><?php echo $alang->admin_saved; ?></button>                                                                                    	
                                        </span>
                                    </h2>
                                    <span class="button-group compact float-right">	
                                                        
                                    </span>                                        
                                        
                                    <div class="columns">
                                        
                                        <div class="five-columns boxed wrapped blue-gradient">
                                            <h3 class="align-center no-margin-bottom"><?php echo $alang->admin_25_6; ?></h3>
                                            <?php echo $elemF->init(array('apex_names','apex_email'));?>
                                            <div class="clear-both"></div>
                                        </div>  
                                        
                                        <div class="six-columns boxed wrapped orange-gradient black">
                                            <h3 class="align-center no-margin-bottom"><?php echo $alang->admin_25_7; ?></h3>
                                            <div class="columns"><?php echo $elemF->init(array('bread_block','bread_delimiter','bread_no_href','bread_href'));?></div>
                                            <div class="clear-both"></div>
                                        </div> 
                                        
                                        <div class="three-columns"><?php echo $elemF->init('apex_site'); ?></div>                                     
                                        <div class="four-columns"><?php echo $elemF->init('default_time_zone'); ?></div>
                                        
                                        <div id="templ_sites" class="eleven-columns boxed wrapped silver-gradient hidden">
                                            <?php echo $elemF->init(array('apex_site_name','apex_site_text'));?>
                                        </div>                                        
                                                                                                                                                                
                                    </div>
                                  
                                </form>
                        <!-- #tab-1 -->
                		</div>               
                        
                        
                        <div id="tab-2" class="with-padding">
                        <!-- tab-3 -->                
                                <h2 class="thin underline relative">
                                <?php echo $alang->admin_26; ?> <br />                                
                                    <span class="button-group absolute-right">
                                    	                                                                                  	
                                    </span>
                                </h2>
                                <br />                                                                
                                
                                <a href="javascript:timeOutId('/module/admin/index/list_language','admin');" class="button blue-active icon-thumbs with-tooltip blue" title=""> <?php echo $alang->admin_27; ?></a>
                                
                                <table class="table">
                                    <tr class="md_admin"></tr>
                                </table>                                
                                                
                        <!-- #tab-3 -->
                		</div>
                        
                        
                        <div id="tab-3" class="with-padding">
                        <!-- tab-2 -->                
                                <h2 class="thin underline relative">
                                <?php echo $alang->admin_28; ?> <br />
                                <span class="small red"><?php echo $alang->admin_29; ?> head</span>
                                    <span class="button-group absolute-right">
                                    	<a id="save_file_meta" href="javascript:void(0);" class="button icon-tick compact"> <?php echo $alang->admin_saved; ?></a>                                                                                  	
                                    </span>
                                </h2>
                                <br />
                                <textarea name="autoexpanding" id="autoexpanding_meta" class="input full-width autoexpanding" style="min-height: 150px;"><?php echo read_file(APPPATH.'/views/template/meta.php'); ?></textarea>
                                                
                        <!-- #tab-2 -->
                		</div>                  
                        
                        
                        <div id="tab-4" class="with-padding">
                        <!-- tab-3 -->                
                                <h2 class="thin underline relative">
                                <?php echo $alang->admin_28; ?> Google analitics <br />                                
                                    <span class="button-group absolute-right">
                                    	<a id="save_file_google" href="javascript:void(0);" class="button icon-tick compact"> <?php echo $alang->admin_saved; ?></a>                                                                                  	
                                    </span>
                                </h2>
                                <br />
                                <textarea name="autoexpanding" id="autoexpanding_google" class="input full-width autoexpanding" style="min-height: 250px;"><?php echo $google; ?></textarea>
                                                
                        <!-- #tab-3 -->
                		</div>
                        
                        <div id="tab-5" class="with-padding">
                        <!-- tab-4 -->                
                                <h2 class="thin underline relative">
                                <?php echo $alang->admin_28; ?> Yandex metrika <br />                                
                                    <span class="button-group absolute-right">
                                    	<a id="save_file_yandex" href="javascript:void(0);" class="button icon-tick compact"> <?php echo $alang->admin_saved; ?></a>                                                                                  	
                                    </span>
                                </h2>
                                <br />
                                <textarea name="autoexpanding" id="autoexpanding_yandex" class="input full-width autoexpanding" style="min-height: 250px;"><?php echo $yandex; ?></textarea>
                                                
                        <!-- #tab-4 -->
                		</div>                                                
                        
                        <div id="tab-6" class="with-padding">
                        <!-- tab-5 -->                
                                <h2 class="thin underline relative">
                                <?php echo $alang->admin_28; ?> Rambler TOP 100 <br />                                
                                    <span class="button-group absolute-right">
                                    	<a id="save_file_rambler" href="javascript:void(0);" class="button icon-tick compact"> <?php echo $alang->admin_saved; ?></a>                                                                                  	
                                    </span>
                                </h2>
                                <br />
                                <textarea name="autoexpanding" id="autoexpanding_rambler" class="input full-width autoexpanding" style="min-height: 250px;"><?php echo $rambler; ?></textarea>
                                                
                        <!-- #tab-5 -->
                		</div>
                        
                        
                        <div id="tab-8" class="with-padding">
                        <!-- tab-8 -->                
                                <h2 class="thin underline relative">
                                <?php echo $alang->admin_31; ?> robots.txt <br />                                
                                    <span class="button-group absolute-right">
                                    	<a id="save_file_robots" href="javascript:void(0);" class="button icon-tick compact"> <?php echo $alang->admin_saved; ?></a>                                                                                  	
                                    </span>
                                </h2>
                                <br />
                                <textarea name="autoexpanding" id="autoexpanding_robots" class="input full-width autoexpanding" style="min-height: 150px;"><?php echo read_file($_SERVER["DOCUMENT_ROOT"].'/robots.txt'); ?></textarea>
                                                
                        <!-- #tab-8 -->
                		</div>
                        
                	</div>
                </div>	            
</div>
<?php /*            
            </div>
            
            <div id="maintab-2" class="with-padding">
					<div class="carbon with-padding margin-bottom">
        				<div class="boxed white-bg">
        					
      						  <button class="button blue-gradient glossy" onclick="$(this).parent().parent().load('/module/admin/index/config/news');">Click me to load some content</button>
        					
        				</div>
        			</div>
            </div>
                				
    </div>
</div>

*/ ?>

<?php echo $valid; ?>



<script>
    

	$(document).ready(function() { 

        // подгрузка языков
        timeOutId('/module/admin/index/list_language','admin');
                
        // мета теги
        $('#save_file_meta').click(function(){
            params = $('#autoexpanding_meta').val();
            timeOutId_mes('/admin/admin/save_file/meta',params,'#autoexpanding_meta');
        });
        
        // гугл аналитик
        $('#save_file_google').click(function(){
            params = $('#autoexpanding_google').val();
            timeOutId_mes('/admin/admin/save_file/google',params,'#autoexpanding_google');
        });
        
        // яндекс метрика
        $('#save_file_yandex').click(function(){
            params = $('#autoexpanding_yandex').val();
            timeOutId_mes('/admin/admin/save_file/yandex',params,'#autoexpanding_yandex');
        });
        
        // rambler top 100
        $('#save_file_rambler').click(function(){
            params = $('#autoexpanding_rambler').val();
            timeOutId_mes('/admin/admin/save_file/rambler',params,'#autoexpanding_rambler');
        });
        
        // rambler top 100
        $('#save_file_robots').click(function(){
            params = $('#autoexpanding_robots').val();
            timeOutId_mes('/admin/admin/save_robots',params,'#autoexpanding_robots');
        });
        
        
        <?php if($conf['apex_site']['value'] == 1): ?>
        $('#templ_sites').removeClass('hidden');
        $(this).refreshTabs();
        <?php endif; ?>
        
        $('#apex_site').change(function(){
            if($(this).is(':checked'))
            {
                $('#templ_sites').removeClass('hidden');
            }
            else
            {
                $('#templ_sites').addClass('hidden');                
            }
            $(this).refreshTabs();
        });
        
    });
    

$('.tabs-content > div').on('showtab', function()
{
	$(this).refreshTabs();
});

</script>