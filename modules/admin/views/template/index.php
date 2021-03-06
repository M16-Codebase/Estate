<!DOCTYPE html>

<!--[if IEMobile 7]><html class="no-js iem7 oldie"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie" lang="ru"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie" lang="ru"><![endif]-->
<!--[if (gt IE 8)|(gt IEMobile 7)]><!--><html class="no-js" lang="ru"><!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Административная панель</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- http://davidbcalhoun.com/2010/viewport-metatag -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">

	<!-- http://www.kylejlarson.com/blog/2012/iphone-5-web-design/ -->
	<meta name="viewport" content="user-scalable=0, initial-scale=1.0">

	<!-- For all browsers -->
	<link rel="stylesheet" href="/asset/admin/css/reset.css?v=1">
	<link rel="stylesheet" href="/asset/admin/css/style.css?v=1">
	<link rel="stylesheet" href="/asset/admin/css/colors.css?v=1">
	<link rel="stylesheet" media="print" href="/asset/admin/css/print.css?v=1">
	<!-- For progressively larger displays -->
	<link rel="stylesheet" media="only all and (min-width: 480px)" href="/asset/admin/css/480.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 768px)" href="/asset/admin/css/768.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 992px)" href="/asset/admin/css/992.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 1200px)" href="/asset/admin/css/1200.css?v=1">
	<!-- For Retina displays -->
	<link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="/asset/admin/css/2x.css?v=1">

	<!-- Webfonts 
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>-->

	<!-- Additional styles -->
    <link rel="stylesheet" href="/asset/admin/css/styles/progress-slider.css?v=1">
	<link rel="stylesheet" href="/asset/admin/css/styles/switches.css?v=1">
	<link rel="stylesheet" href="/asset/admin/css/styles/files.css?v=1">
    <link rel="stylesheet" href="/asset/admin/css/styles/form.css">
    <link rel="stylesheet" href="/asset/admin/css/styles/table.css">
    <link rel="stylesheet" href="/asset/admin/css/styles/dashboard.css?v=1">    

    <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"> jQuery UI -->
      
    <link rel="stylesheet" href="/asset/admin/js/libs/google-code-prettify/sunburst.css?v=1"> <!-- Google code prettifier -->	
	<link rel="stylesheet" href="/asset/admin/js/libs/glDatePicker/developr.css?v=1"> <!-- glDatePicker -->  
    <link rel="stylesheet" href="/asset/admin/css/styles/modal.css">  
           
    <link rel="stylesheet" href="/asset/admin/js/libs/formValidator/developr.validationEngine.css">
    <link rel="stylesheet" href="/asset/admin/js/libs/chosen/chosen.css">    
    

    
    <!-- Scripts -->
	<script src="/asset/admin/js/libs/jquery-1.9.1.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.1.1.js"></script>
    
	<!-- JavaScript at bottom except for Modernizr -->
	<script src="/asset/admin/js/libs/modernizr.custom.js"></script>

	<!-- iOS web-app metas -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<!-- iPhone ICON -->
	<link rel="apple-touch-icon" href="/asset/admin/img/favicons/apple-touch-icon.png" sizes="57x57">
	<!-- iPad ICON -->
	<link rel="apple-touch-icon" href="/asset/admin/img/favicons/apple-touch-icon-ipad.png" sizes="72x72">
	<!-- iPhone (Retina) ICON -->
	<link rel="apple-touch-icon" href="/asset/admin/img/favicons/apple-touch-icon-retina.png" sizes="114x114">
	<!-- iPad (Retina) ICON -->
	<link rel="apple-touch-icon" href="/asset/admin/img/favicons/apple-touch-icon-ipad-retina.png" sizes="144x144">

	<!-- iPhone SPLASHSCREEN (320x460) -->
	<link rel="apple-touch-startup-image" href="/asset/admin/img/splash/iphone.png" media="(device-width: 320px)">
	<!-- iPhone (Retina) SPLASHSCREEN (640x960) -->
	<link rel="apple-touch-startup-image" href="/asset/admin/img/splash/iphone-retina.png" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)">
	<!-- iPhone 5 SPLASHSCREEN (640×1096) -->
	<link rel="apple-touch-startup-image" href="/asset/admin/img/splash/iphone5.png" media="(device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
	<!-- iPad (portrait) SPLASHSCREEN (748x1024) -->
	<link rel="apple-touch-startup-image" href="/asset/admin/img/splash/ipad-portrait.png" media="(device-width: 768px) and (orientation: portrait)">
	<!-- iPad (landscape) SPLASHSCREEN (768x1004) -->
	<link rel="apple-touch-startup-image" href="/asset/admin/img/splash/ipad-landscape.png" media="(device-width: 768px) and (orientation: landscape)">
	<!-- iPad (Retina, portrait) SPLASHSCREEN (2048x1496) -->
	<link rel="apple-touch-startup-image" href="/asset/admin/img/ipad-portrait-retina.png" media="(device-width: 1536px) and (orientation: portrait) and (-webkit-min-device-pixel-ratio: 2)">
	<!-- iPad (Retina, landscape) SPLASHSCREEN (1536x2008) -->
	<link rel="apple-touch-startup-image" href="/asset/admin/img/ipad-landscape-retina.png" media="(device-width: 1536px)  and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 2)">

	<!-- Microsoft clear type rendering -->
	<meta http-equiv="cleartype" content="on">

	<!-- IE9 Pinned Sites: http://msdn.microsoft.com/en-us/library/gg131029.aspx -->
	<meta name="application-name" content="Developr Admin Skin">
	<meta name="msapplication-tooltip" content="Cross-platform admin template.">
	<meta name="msapplication-starturl" content="http://www.display-inline.fr/demo/developr">
	<!-- These custom tasks are examples, you need to edit them to show actual pages -->
	<meta name="msapplication-task" content="name=Agenda;action-uri=http://www.display-inline.fr/demo/developr/agenda.html;icon-uri=http://www.display-inline.fr/demo/developr/asset/admin/img/favicons/favicon.ico">
	<meta name="msapplication-task" content="name=My profile;action-uri=http://www.display-inline.fr/demo/developr/profile.html;icon-uri=http://www.display-inline.fr/demo/developr/asset/admin/img/favicons/favicon.ico">
    
    <style>
        #langs {
            padding-top: 13px; 
            padding-left: 10px; 
            font-size: 16px;            
        }
        #langs a { 
            color: #E2E4E9;
            text-decoration: underline;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px; 
            padding: 0 4px 2px;
        }
        #langs a:hover, #langs a.activated {
            background: #0059A0;
            color: #fff; 
            text-decoration: none;
	    }
        .table-footer {
            height: 34px;
        }
    </style>
</head>

<body class="clearfix with-menu fixed-title-bar menu-hidden">

	<!-- Prompt IE 6 users to install Chrome Frame -->
	<!--[if lt IE 7]><p class="message red-gradient simpler">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

	<!-- Title bar -->
	<header role="banner" id="title-bar">
		<h2><a title="Перейти на сайт" target="_blank" href="/" class="with-tooltip"><?php echo langLine('apex_title'); ?></a></h2>
	</header>

	<!-- Button to open/hide menu -->
	<a href="#" class="with-tooltip" data-tooltip-options='{"position":"left"}' title="<?php echo $alang->admin_1; ?>" id="open-menu"><span><?php echo $alang->admin_2; ?></span></a>
    <a href="/admin" title="<?php echo $alang->admin_main; ?>" class="icon-home button anthracite-gradient compact with-tooltip" data-tooltip-options='{"position":"right"}' id="home-button"></a>

	<!-- Main content -->
	<section role="main" id="main">  
		<!-- The padding wrapper may be omitted -->
		<div class="content-panel show-panel-content">
			<div class="panel-navigation silver-gradient">
				<div id="panel-nav" class="panel-load-target scrollable" style="min-height: 500px !important;">
					<div class="">
						<ul class="big-menu thin collapsible white-gradient as-accordion" id="menus" style="text-shadow: none;">                        	
                            <?php echo $this->load->view('admin/template/menu',$data); ?>
                            <li><a href="/military/admin/index">Военная ипотека</a></li>
                            <li><a href="/commerce/admin/index">Коммерция</a></li>
                            <li><a href="/admin/parser">Парсер</a></li>
                            <li><a href="/admin/assignment">Парсер переуступок</a></li>
                        </ul>                                             
					</div>
				</div>
			</div>			                        
            <div id="ajaxNotification" class="mid-margin-left mid-margin-right"></div>                                    
            <div class="panel-content">				                                        
                <div id="panel-content" class="panel-load-target">									    
                        <?php echo $data; ?>      						
				</div>
			</div>
		</div>
	</section>
	<!-- End main content -->

	<!-- Sidebar/drop-down menu -->
	<section id="menu" role="complementary">

		<!-- This wrapper is used by several responsive layouts -->
		<div id="menu-content" class="scratch-metal">

			<header>
				<span class="icon-users icon-size2"> </span> <?php echo $this->session->userdata('DX_role_title'); ?>
			</header>
            
            <?php $ses = $this->session->userdata('languages'); ?>
            <p id="langs" class="hidden">
            <span class="big-text wrapped anthracite-gradient">Select language: <a href="/admin/languages/russian" class="with-tooltip <?php if($ses == 'russian'): ?> activated <?php endif; ?>" title="Русский">rus</a> | <a href="/admin/languages/english" class="with-tooltip <?php if($ses == 'english'): ?> activated <?php endif; ?>" title="English">eng</a></span>
            </p>
            
			<div id="profile">
				<a style="color: #bfbfbf; font-size: 14px; font-weight: 700;" href="<?php echo BASEURL; ?>/auth/admin/index/edit/<?php echo $this->session->userdata('DX_user_id'); ?>" title=""><img src="<?php echo $this->session->userdata('DX_photo'); ?>" width="64" height="64" alt="<?php echo $this->session->userdata('DX_first_name'); ?>" class="user-icon" /></a>
				<br /><span class="name" style="font-size: 16px; display: block; line-height: 16px;"><a style="color: #bfbfbf; font-size: 14px; font-weight: 700;" href="<?php echo BASEURL; ?>/auth/admin/index/edit/<?php echo $this->session->userdata('DX_user_id'); ?>" title=""><?php echo $this->session->userdata('DX_first_name'); ?> <?php echo $this->session->userdata('DX_last_name'); ?></a></span>
			</div>


			<!-- By default, this section is made for 4 icons, see the doc to learn how to change this, in "basic markup explained" -->			            
            <section class="navigable">
            	<ul class="big-menu white">
            		<?php if($this->dx_auth->is_admin()): ?>
                    <li class="blue-gradient settings"><a class="icon-gear" href="<?php echo BASEURL.'/admin/admin/config/'; ?>"> <?php echo $alang->admin_3; ?></a></li>
                    <li class="blue-gradient settings"><a class="icon-flow-tree" href="<?php echo BASEURL.'/admin/functions/sitemap'; ?>"> <?php echo $alang->admin_4; ?></a></li>
                    <?php endif; ?>                                        
                    <li class="blue-gradient"><a href="<?php echo site_url(); ?>" class="icon-home" target="_blank" title=""> <?php echo $alang->admin_6; ?></a></li>
                    <li class="grey-gradient"><a href="<?php echo BASEURL.'/auth/logout'; ?>" class="icon-logout" title=""> <?php echo $alang->admin_7; ?></a></li>
            	</ul>
            </section>

			<!-- Navigation menu goes here -->

		</div>
		<!-- End content wrapper -->

		<!-- This is optional -->
		<footer id="menu-footer">
			
		</footer>

	</section>
    
    <script src="/asset/admin/js/setup.js"></script>
    <script src="/asset/ckfinder/ckfinder.js"></script>
    <script src="/asset/ckeditor/ckeditor.js"></script>    	   
    <script src="/asset/admin/js/libs/chosen/chosen.jquery.js"></script>
    <script src="/asset/admin/js/developr.input.js"></script> <!-- Кнопки и поля -->
    <script src="/asset/admin/js/developr.navigable.js"></script> <!-- Навигация -->
    <script src="/asset/admin/js/developr.notify.js"></script> <!-- Всплывающие сообщения --> 
    <script src="/asset/admin/js/developr.scroll.js"></script> <!-- Скрол -->
    <script src="/asset/admin/js/developr.progress-slider.js"></script> <!-- Програес, слайдер --> 
	<script src="/asset/admin/js/developr.tooltip.js"></script> <!-- Подсказки --> 
	<script src="/asset/admin/js/developr.confirm.js"></script> <!-- Подтверждение -->
    <script src="/asset/admin/js/developr.content-panel.js"></script> <!-- Контент панели -->                
    <script src="/asset/admin/js/developr.collapsible.js"></script> <!-- Раскрывающая навигация -->
    <script src="/asset/admin/js/libs/jquery.ba-hashchange.min.js?v=1"></script> <!-- Управление хеш браузера -->
    <script src="/asset/admin/js/developr.content.load.js"></script> <!-- Мой скрипт загрузки данных в панель -->
    <script src="/asset/admin/js/developr.setup.js"></script> <!-- Мои установки -->
    <script src="/asset/admin/js/developr.table.js"></script> <!-- Таблицы -->
    <script src="/asset/admin/js/libs/jquery.tablesorter.min.js"></script> <!-- Сортировка таблиц -->
    <script src="/asset/admin/js/developr.auto-resizing.js"></script> <!-- Изминение размеров для полей -->    
	<script src="/asset/admin/js/libs/tinycon.min.js"></script> <!-- Управление favicon -->
	<script src="/asset/admin/js/libs/google-code-prettify/prettify.js?v=1"></script> <!-- Показ кода -->
    <script src="/asset/admin/js/developr.modal.js"></script> <!-- Визуальный редактор -->    
    <script src="/asset/admin/js/developr.tabs.js"></script> <!-- Вкладки -->
    <script src="/asset/admin/js/cookie.js"></script> <!-- куки -->
    <script src="/asset/admin/js/libs/glDatePicker/glDatePicker.min.js"></script>  <!-- Дата -->
    <!-- Валидация -->
    <script src="/asset/admin/js/libs/formValidator/jquery.validationEngine.js"></script>
    <script src="/asset/admin/js/libs/formValidator/languages/jquery.validationEngine-en.js"></script>        
    <script src="/asset/admin/js/developr.js"></script>

            
    <script>             
        // Фиксирование положения меню
        <?php $ur4 = ''; ?>        
        var curlink = '/<?php echo uri(1).'/'.uri(2).'/'.uri(3).$ur4; ?>';            
        $('.collapsible li a').each(function(i,obj){                                       
            if($(obj).attr('href') == curlink)
            {                
                $(obj).addClass('current');
                $(obj).closest('ul').parent('li').click();                                    
            }            
        });
    
    $(document).ready(function(){         
        prettyPrint();                                              
    });

    </script>
    
    <?php echo templ(); ?>
    
	<!-- Libs go here -->
</body>
</html>