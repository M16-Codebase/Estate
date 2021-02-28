<!DOCTYPE html>

<!--[if IEMobile 7]><html class="no-js iem7 oldie"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie" lang="ru"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie" lang="ru"><![endif]-->
<!--[if (gt IE 8)|(gt IEMobile 7)]><!--><html class="no-js linen" lang="ru"><!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Авторизация</title>
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
    <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"> jQuery UI -->
   
    <link rel="stylesheet" href="/asset/admin/js/libs/google-code-prettify/sunburst.css?v=1"> <!-- Google code prettifier -->	
	<link rel="stylesheet" href="/asset/admin/js/libs/glDatePicker/developr.css?v=1"> <!-- glDatePicker -->    
    <link rel="stylesheet" href="/asset/admin/css/styles/modal.css">        
    <link rel="stylesheet" href="/asset/admin/js/libs/formValidator/developr.validationEngine.css">    
    
    <!-- Login pages styles -->
	    <link rel="stylesheet" media="screen" href="/asset/admin/css/login.css"/>
    
    <!-- Scripts -->
	<script src="/asset/admin/js/libs/jquery-1.9.1.min.js"></script>
	<!-- JavaScript at bottom except for Modernizr -->
	<script src="/asset/admin/js/libs/modernizr.custom.js"></script>    	



	<!-- Microsoft clear type rendering -->
	<meta http-equiv="cleartype" content="on">

	<!-- IE9 Pinned Sites: http://msdn.microsoft.com/en-us/library/gg131029.aspx -->
	<meta name="application-name" content="Developr Admin Skin">
	<meta name="msapplication-tooltip" content="Cross-platform admin template.">
	<meta name="msapplication-starturl" content="http://www.display-inline.fr/demo/developr">
	<!-- These custom tasks are examples, you need to edit them to show actual pages -->
	<meta name="msapplication-task" content="name=Agenda;action-uri=http://www.display-inline.fr/demo/developr/agenda.html;icon-uri=http://www.display-inline.fr/demo/developr/asset/admin/img/favicons/favicon.ico">
	<meta name="msapplication-task" content="name=My profile;action-uri=http://www.display-inline.fr/demo/developr/profile.html;icon-uri=http://www.display-inline.fr/demo/developr/asset/admin/img/favicons/favicon.ico">
</head>
                      
    <body>
        
     	<div id="container">


            
            <?php if($this->dx_auth->get_auth_error()): ?>
                <p class="message red-gradient" style="display: block;"><?php echo $this->dx_auth->get_auth_error(); ?><span class="close show-on-parent-hover">x</span><span class="block-arrow bottom"><span></span></span></p>
            <?php endif; ?>
                                                    
    		<div id="form-wrapper">

    			<div id="form-block" class="scratch-metal">
    				<div id="form-viewport">
    
    					<form method="post" action="/auth/login" id="form-login" class="input-wrapper blue-gradient glossy" title="Login">
    						<ul class="inputs black-input large">
    							<!-- The autocomplete="off" attributes is the only way to prevent webkit browsers from filling the inputs with yellow -->
    							<li><span class="icon-user mid-margin-right"></span><input type="text" name="username" id="login" value="" class="input-unstyled" placeholder="Login" autocomplete="off"/></li>
    							<li><span class="icon-lock mid-margin-right"></span><input type="password" name="password" id="pass" value="" class="input-unstyled" placeholder="Password" autocomplete="off"/></li>
    						</ul>
    
    						<p class="button-height">
    							<button type="submit" class="button float-right" id="login">Login</button>
    							<input type="checkbox" name="remember" id="remind" value="1" checked="checked" class="switch tiny mid-margin-right with-tooltip" title="Enable auto-login"/>
    							<label for="remind">Remind me</label>
    						</p>
    					</form>
    
    					<form method="post" action="" id="form-password" class="input-wrapper orange-gradient glossy hidden" title="Lost password?">
    
    						<p class="message">
    							If you can't remember your pass, fill the input below with your e-mail and we'll send you a new one:
    							<span class="block-arrow"><span></span></span>
    						</p>
    
    						<ul class="inputs black-input large">
    							<li><span class="icon-mail mid-margin-right"></span><input type="text" name="mail" id="emails" value="" class="input-unstyled" placeholder="Your e-mail" autocomplete="off"/></li>
    						</ul>
    
    						<button type="submit" class="button glossy full-width" id="send-password">Send new password</button>
    
    					</form>
    
    					<form method="post" action="" id="form-register" class="input-wrapper green-gradient glossy hidden" title="Register">
    
    						<p class="message">
    							New user? Yay! Let us know a little bit about you before you start:
    							<span class="block-arrow"><span></span></span>
    						</p>
    
    						<ul class="inputs black-input large">
    							<li><span class="icon-card mid-margin-right"></span><input type="text" name="name" id="name-register" value="" class="input-unstyled" placeholder="Your name" autocomplete="off"/></li>
    							<li><span class="icon-mail mid-margin-right"></span><input type="email" name="mail" id="mail-register" value="" class="input-unstyled" placeholder="Your e-mail" autocomplete="off"/></li>
    						</ul>
    						<ul class="inputs black-input large">
    							<li><span class="icon-user mid-margin-right"></span><input type="text" name="login" id="login-register" value="" class="input-unstyled" placeholder="Login" autocomplete="off"/></li>
    							<li><span class="icon-lock mid-margin-right"></span><input type="password" name="pass" id="pass-register" value="" class="input-unstyled" placeholder="Password" autocomplete="off"/></li>
    						</ul>
    
    						<button type="submit" class="button glossy full-width" id="send-register">Register</button>
    
    					</form>
    
    				</div>
    			</div>
    
    		</div>
    
    	</div>  
        
        

        <?php 
            /** Загрузка JS файлов */
            $js_f = array(
                'setup',
                // <!-- Template functions -->
                'developr.input', // украшение елементов форм                
                'developr.notify', // сообщения                
                'developr.tooltip', // подсказки                
                'developr.message', // Сообщения                         
            );
            echo js_files($js_f,true);             
        ?>

	<script>

		/*
		 * How do I hook my login script to these?
		 * --------------------------------------
		 *
		 * These scripts are meant to be non-obtrusive: if the user has disabled javascript or if an error occurs, the forms
		 * works fine without ajax.
		 *
		 * The only part you need to edit are the scripts between the EDIT THIS SECTION tags, which do inputs validation and
		 * send data to server. For instance, you may keep the validation and add an AJAX call to the server with the user
		 * input, then redirect to the dashboard or display an error depending on server return.
		 *
		 * Or if you don't trust AJAX calls, just remove the event.preventDefault() part and let the form be submitted.
		 */

		$(document).ready(function()
		{
			/*
			 * JS login effect
			 * This script will enable effects for the login page
			 */
				// Elements
			var doc = $('html').addClass('js-login'),
				container = $('#container'),
				formWrapper = $('#form-wrapper'),
				formBlock = $('#form-block'),
				formViewport = $('#form-viewport'),
				forms = formViewport.children('form'),

				// Doors
				topDoor = $('<div id="top-door" class="form-door"><div></div></div>').appendTo(formViewport),
				botDoor = $('<div id="bot-door" class="form-door"><div></div></div>').appendTo(formViewport),
				doors = topDoor.add(botDoor),

				// Switch
				formSwitch = $('<div id="form-switch"><span class="button-group"></span></div>').appendTo(formBlock).children(),

				// Current form
				hash = (document.location.hash.length > 1) ? document.location.hash.substring(1) : false,

				// If layout is centered
				centered,

				// Store current form
				currentForm,

				// Animation interval
				animInt,

				// Work vars
				maxHeight = false,
				blocHeight;

			/******* EDIT THIS SECTION *******/

			/*
			 * Login
			 * These functions will handle the login process through AJAX
			 */
			$('#form-login').submit(function(event)
			{
				// Values
				var login = $.trim($('#login').val()),
                    remind = $('#remind').is(':checked'),					
                    pass = $.trim($('#pass').val());

				// Check inputs
				if (login.length === 0)
				{
					// Display message
					displayError('Please fill in your login');
					return false;
				}
				else if (pass.length === 0)
				{
					// Remove empty login message if displayed
					formWrapper.clearMessages('Please fill in your login');

					// Display message
					displayError('Please fill in your password');
					return false;
				}
				else
				{
					// Remove previous messages
					formWrapper.clearMessages();

					// Show progress
					displayLoading('Checking credentials...');					
                    
                    // Stop normal behavior
                    event.preventDefault();
										  
					  $.ajax({
                            type: 'POST',
                            url: '/auth/auth/loginajax',
                            dataType: 'json',   
					  		data: {
					  			username:	login,
					  			password:	pass,
                                remember:   remind
					  		},
					  		success: function(data)
					  		{
                                if (data.logged)
					  			{
					  				document.location.href = '/admin';
					  			}
					  			else
					  			{
                                    formWrapper.clearMessages();
					  				if(data.ban !== '')
                                    {
                                        displayError(data.ban);
                                    }
                                    else
                                    {
                                        displayError('Invalid user/password, please try again');
                                    }
					  			}
					  		},
					  		error: function()
					  		{ 
                                formWrapper.clearMessages();
					  			displayError('Error while contacting server, please try again');
					  		}
					  });
				}
			});

			/*
			 * Password recovery
			 */
			$('#form-password').submit(function(event)
			{
				// Values
				var mail = $.trim($('#emails').val());
                
                event.preventDefault();
                
				// Check inputs
				if (mail.length === 0)
				{
					// Display message
					displayError('Please fill in your email');
				}
				else if (!isValidEmailAddress(mail))
				{
					// Display message
					displayError('Email is not valid');
					//return false;
				}
				else
				{
					// Remove previous messages
					formWrapper.clearMessages();

					// Show progress
					displayLoading('Sending credentials...');

					// Stop normal behavior
					event.preventDefault();

					$.ajax({
                            type: 'POST',
                            url: '/auth/auth/forgot_password_ajax',
                            dataType: 'json',   
					  		data: {
					  			login:	mail
					  		},
					  		success: function(data)
					  		{
                                if (data.logged)
					  			{
					  				formWrapper.clearMessages();
                                    displaySuccess('Сообщение с инструкцией активации вашего нового пароля, было выслано на ваш адрес электронной почты.');
					  			}
					  			else
					  			{
                                    formWrapper.clearMessages();
					  				displayError('Invalid e-mail, please try again');
					  			}
					  		},
					  		error: function()
					  		{ 
                                formWrapper.clearMessages();
					  			displayError('Error while contacting server, please try again');
					  		}
					  });
				}
			});

			/*
			 * Register
			 */
			$('#form-register').submit(function(event)
			{
				// Values
				var name = $.trim($('#name-register').val()),
					mail = $.trim($('#mail-register').val()),
					login = $.trim($('#login-register').val()),
					pass = $.trim($('#pass-register').val());

				// Remove previous messages
				formWrapper.clearMessages();

				// Check inputs
				if (name.length === 0)
				{
					// Display message
					displayError('Please fill in your name');
					return false;
				}
				else if (mail.length === 0)
				{
					// Display message
					displayError('Please fill in your email');
					return false;
				}
				else if (!/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/.test(mail))
				{
					// Display message
					displayError('Email is not valid');
					return false;
				}
				else if (login.length === 0)
				{
					// Display message
					displayError('Please fill in your login');
					return false;
				}
				else if (pass.length === 0)
				{
					// Display message
					displayError('Please fill in your password');
					return false;
				}
				else
				{
					// Show progress
					displayLoading('Registering...');

					// Stop normal behavior
					event.preventDefault();

					/*
					 * This is where you may do your AJAX call
					 */

					// Simulate server-side check
					setTimeout(function() {
						document.location.href = './'
					}, 2000);
				}
			});

			/******* END OF EDIT SECTION *******/

			/*
			 * Animated login
			 */

			// Prepare forms
			forms.each(function(i)
			{
				var form = $(this),
					height = form.outerHeight(),
					active = (hash === false && i === 0) || (hash === this.id),
					color = this.className.match(/[a-z]+-gradient/) ? ' '+(/([a-z]+)-gradient/.exec(this.className)[1])+'-active' : '';
                
                if(this.title != 'Register')
                {
                                                    
                //console.log();
                
				// Store size
				form.data('height', height);

				// Min-height for mobile layout
				if (maxHeight === false || height > maxHeight)
				{
					maxHeight = height;
				}

				// Button in the switch
				form.data('button', $('<a href="#'+this.id+'" class="button anthracite-gradient'+color+(active ? ' active' : '')+'">'+this.title+'</a>')
									.appendTo(formSwitch)
									.data('form', form));

				// If active
				if (active)
				{
					// Store
					currentForm = form;

					// Height of viewport
					formViewport.height(height);
				}
				else
				{
					// Hide for now
					form.hide();
                    form.removeClass('hidden');
				}
                
                }
			});

			// Main bloc height (without form height)
			blocHeight = formBlock.height()-currentForm.data('height');

			// Handle resizing (mostly for debugging)
			function handleLoginResize()
			{
				// Detect mode
				centered = (container.css('position') === 'absolute');

				// Set min-height for mobile layout
				if (!centered)
				{
					formWrapper.css('min-height', (blocHeight+maxHeight+20)+'px');
					container.css('margin-top', '');
				}
				else
				{
					formWrapper.css('min-height', '');
					if (parseInt(container.css('margin-top'), 10) === 0)
					{
						centerForm(currentForm, false);
					}
				}
			};

			// Register and first call
			$(window).bind('normalized-resize', handleLoginResize);
			handleLoginResize();

			// Switch behavior
			formSwitch.on('click', 'a', function(event)
			{
				var link = $(this),
					form = link.data('form'),
					previousForm = currentForm;

				event.preventDefault();
				if (link.hasClass('active'))
				{
					return;
				}

				// IE7/8 have an issue with correct form height
				if ($.template.ie7 || $.template.ie8)
				{
					forms.each(function(i)
					{
						var form = $(this),
							hidden = form.is(':hidden'),
							height = form.show().outerHeight();

						// Store size
						form.data('height', height);

						// If not active
						if (hidden)
						{
							// Hide for now
							form.hide();
						}
					});
				}

				// Clear messages
				formWrapper.clearMessages();

				// If an animation is already running
				if (animInt)
				{
					clearTimeout(animInt);
				}
				formViewport.stop(true);

				// Update active button
				currentForm.data('button').removeClass('active');
				link.addClass('active');

				// Set as current
				currentForm = form;

				// if CSS transitions are available
				if (doc.hasClass('csstransitions'))
				{
					// Close doors - step 1
					doors.removeClass('door-closed').addClass('door-down');
					animInt = setTimeout(function()
					{
						// Close doors, step 2
						doors.addClass('door-closed');
						animInt = setTimeout(function()
						{
							// Hide previous form
							previousForm.hide();

							// Show target form
							form.show();

							// Center layout
							centerForm(form, true);

							// Height of viewport
							formViewport.animate({
								height: form.data('height')+'px'
							}, function()
							{
								// Open doors, step 1
								doors.removeClass('door-closed');
								animInt = setTimeout(function()
								{
									// Open doors - step 2
									doors.removeClass('door-down');
								}, 300);
							});
						}, 300);
					}, 300);
				}
				else
				{
					// Close doors
					topDoor.animate({ top: '0%' }, 300);
					botDoor.animate({ top: '50%' }, 300, function()
					{
						// Hide previous form
						previousForm.hide();

						// Show target form
						form.show();

						// Center layout
						centerForm(form, true);

						// Height of viewport
						formViewport.animate({
							height: form.data('height')+'px'
						}, {

							/* IE7 is a bit buggy, we must force redraw */
							step: function(now, fx)
							{
								topDoor.hide().show();
								botDoor.hide().show();
								formSwitch.hide().show();
							},

							complete: function()
							{
								// Open doors
								topDoor.animate({ top: '-50%' }, 300);
								botDoor.animate({ top: '105%' }, 300);
								formSwitch.hide().show();
							}
						});
					});
				}
			});

			// Initial vertical adjust
			centerForm(currentForm, false);

			/*
			 * Center function
			 * @param jQuery form the form element whose height will be used
			 * @param boolean animate whether or not to animate the position change
			 * @param string|element|array any jQuery selector, DOM element or set of DOM elements which should be ignored
			 * @return void
			 */
			function centerForm(form, animate, ignore)
			{
				// If layout is centered
				if (centered)
				{
					var siblings = formWrapper.siblings().not('.closing'),
						finalSize = blocHeight+form.data('height');

					// Ignored elements
					if (ignore)
					{
						siblings = siblings.not(ignore);
					}

					// Get other elements height
					siblings.each(function(i)
					{
						finalSize += $(this).outerHeight(true);
					});

					// Setup
					container[animate ? 'animate' : 'css']({ marginTop: -Math.round(finalSize/2)+'px' });
				}
			};

			/**
			 * Function to display error messages
			 * @param string message the error to display
			 */
			function displayError(message)
			{
				// Show message
				var message = formWrapper.message(message, {
					append: false,
					arrow: 'bottom',
					classes: ['red-gradient'],
					animate: false					// We'll do animation later, we need to know the message height first
				});

				// Vertical centering (where we need the message height)
				centerForm(currentForm, true, 'fast');

				// Watch for closing and show with effect
				message.bind('endfade', function(event)
				{
					// This will be called once the message has faded away and is removed
					centerForm(currentForm, true, message.get(0));

				}).hide().slideDown('fast');
			};
            
            
            function displaySuccess(message)
			{
				// Show message
				var message = formWrapper.message(message, {
					append: false,
					arrow: 'bottom',
					classes: ['green-gradient'],
					animate: false					// We'll do animation later, we need to know the message height first
				});

				// Vertical centering (where we need the message height)
				centerForm(currentForm, true, 'fast');

				// Watch for closing and show with effect
				message.bind('endfade', function(event)
				{
					// This will be called once the message has faded away and is removed
					centerForm(currentForm, true, message.get(0));

				}).hide().slideDown('fast');
			};

			/**
			 * Function to display loading messages
			 * @param string message the message to display
			 */
			function displayLoading(message)
			{
				// Show message
				var message = formWrapper.message('<strong>'+message+'</strong>', {
					append: false,
					arrow: 'bottom',
					classes: ['blue-gradient', 'align-center'],
					stripes: true,
					darkStripes: false,
					closable: false,
					animate: false					// We'll do animation later, we need to know the message height first
				});

				// Vertical centering (where we need the message height)
				centerForm(currentForm, true, 'fast');

				// Watch for closing and show with effect
				message.bind('endfade', function(event)
				{
					// This will be called once the message has faded away and is removed
					centerForm(currentForm, true, message.get(0));

				}).hide().slideDown('fast');
			};
		});
		
        function isValidEmailAddress(emailAddress) 
        {
            var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
            return pattern.test(emailAddress);
        }
	</script>



	</body>
</html>
