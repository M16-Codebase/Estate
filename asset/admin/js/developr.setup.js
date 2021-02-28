
    dynamicSend = {};
    
    // задаем основные настройки для ajax запросов    
    $.ajaxSetup({
      dataType: 'json',
      cache: false,
      global: true,
      type: "POST"
    });

    // переменные
    var titleBar = $('#title-bar'),
		panelNav = $('#panel-nav'),
		panelContent = $('#panel-content'),
		controlsSize = 0,        // Size of the panel-controls block and borders
		paddingSize = 0,        // Size of the padding on the panel content block
        hash = document.location.hash,
        globalUrl = undefined,
        win = $(window),
		bod = panelContent,
		main = panelContent,
		init = false
    

$(document).ready(function(){
   $('.paginationButtons').html('');   
   initCoookieList();
   
   // Выборка с поиском + Мульти выборка
   $(".chzn-select").chosen();  
   
   // Вставка в редактор шорткод (название или его содержимое в виде html)
   $('.shortSelect').change(function(){
        var $option = $(this).find('option:selected'),
            $txt = $option.data('textarea'),
            $shortcode = $option.val(),
            $check = $('.check-' + $txt + ' option:selected').val();            
        
        var                             
            $dates = $(this),
            $datesOption = $dates.find('option');
                                                
        if($txt != undefined || $shortcode != 0)
        {
            if($check == 'html')
            {                                        
                $.ajax({                
                    url: '/shortcode/admin/index/ajaxShortcode',                
                    data: {
                        shortcode: $shortcode
                    },
                    success: function(data) { 
                        $('#ajaxNotification').empty();
                        CKEDITOR.instances[$txt].insertHtml( data.ok );   
                                                                                                    
                        $datesOption.removeAttr('selected');                           
                        $datesOption.first().attr('selected','selected');                                                                                    
                        $dates.trigger('change');                              
                    },
                    beforeSend: function() { 
                       $('#ajaxNotification').appendSpinner(); 
                    }
                });   
             }
             else
             {
                CKEDITOR.instances[$txt].insertHtml( '[' + $shortcode + ']' );
             }         
        }
   });
   
   
   // Очистка поля редактора
   $('.clear-all').click(function() 
    {
    	$(this).confirm({
        	onConfirm: function()
    		{				                
                $('.message').hide();
                var $id = $(this).data('txt')                                
                CKEDITOR.instances[$id].setData('');                                                                                                
    			return false;
    		},
            onCancel: function()
    		{			
    
    		}
         });
    });
   
   // Компонент
   $('#selectValue').change(function(){
       var  $select = $('#selectValue option:selected'), // объект выбранного селекта
            $grp = $select.parent('optgroup').attr('id'), // идентификатор OPTGROUP
            $sVal = $select.val(), // значение выбранного селекта
            $sName = $select.text(), // название выбранного селекта
            $selectGrp = $('#selectSelected').find('optgroup[id='+$grp+']'); // Выбранный селект
            
       $selectGrp.append('<option id="'+$sVal+'" value="'+$sVal+'">'+$sName+'</option>'); 
       $select.remove();
       
       var  $txt = $('#link_id'),
            $eachText = '';
            
            $txt.text('');
            console.log($('#selectSelected option').length);
            $('#selectSelected option').each(function(i, obj){                   
               var $obj = $(obj);
               
               if($eachText == '') {
                    $eachText = $obj.val();
               } else {
                    $eachText = $eachText + ',' + $obj.val();
               } 
            });
            $txt.text($eachText);
       
       $('#selectValue, #selectSelected').trigger('update-select-list'); // обновляем селекты       
    });   
    
    $('#selectSelected').change(function(){
       var  $select = $('#selectSelected option:selected'), // объект выбранного селекта
            $grp = $select.parent('optgroup').attr('id'), // идентификатор OPTGROUP
            $sVal = $select.val(), // значение выбранного селекта
            $sName = $select.text(), // название выбранного селекта
            $selectGrp = $('#selectValue').find('optgroup[id='+$grp+']'); // Выбранный селект
            
       $selectGrp.append('<option id="'+$sVal+'" value="'+$sVal+'">'+$sName+'</option>'); 
       $select.remove();
       
       var  $txt = $('#link_id'),
            $eachText = '';
            
            $txt.text('');
            $('#selectSelected option').each(function(i, obj){                   
               var $obj = $(obj);
               
               if($eachText == '') {
                    $eachText = $obj.val();
               } else {
                    $eachText = $eachText + ',' + $obj.val();
               } 
            });
            $txt.text($eachText);
            
       
       $('#selectValue, #selectSelected').trigger('update-select-list'); // обновляем селекты       
    });    
    
    $('#forward').click(function(){           
       $('#selectValue option').each(function(i, obj){                      
           var  $select = $(obj), // объект выбранного селекта
                $grp = $select.parent('optgroup').attr('id'), // идентификатор OPTGROUP
                $sVal = $select.val(), // значение выбранного селекта
                $sName = $select.text(), // название выбранного селекта
                $selectGrp = $('#selectSelected').find('optgroup[id='+$grp+']'); // Выбранный селект
                
           $selectGrp.append('<option id="'+$sVal+'" value="'+$sVal+'">'+$sName+'</option>'); 
           $select.remove();
       });
       
        var  $txt = $('#link_id'),
             $eachText = '';
            
        $txt.text('');
        $('#selectSelected option').each(function(i, obj){                   
           var $obj = $(obj);
           
           if($eachText == '') {
                $eachText = $obj.val();
           } else {
                $eachText = $eachText + ',' + $obj.val();
           } 
        });
        $txt.text($eachText);
       
       $('#selectValue, #selectSelected').trigger('update-select-list'); // обновляем селекты
    });
    
    
    $('#backward').click(function(){           
       $('#selectSelected option').each(function(i, obj){                      
           var  $select = $(obj), // объект выбранного селекта
                $grp = $select.parent('optgroup').attr('id'), // идентификатор OPTGROUP
                $sVal = $select.val(), // значение выбранного селекта
                $sName = $select.text(), // название выбранного селекта
                $selectGrp = $('#selectValue').find('optgroup[id='+$grp+']'); // Выбранный селект
                
           $selectGrp.append('<option id="'+$sVal+'" value="'+$sVal+'">'+$sName+'</option>'); 
           $select.remove();
       });
       
        var  $txt = $('#link_id'),
             $eachText = '';
            
        $txt.text('');
        $('#selectSelected option').each(function(i, obj){                   
           var $obj = $(obj);
           
           if($eachText == '') {
                $eachText = $obj.val();
           } else {
                $eachText = $eachText + ',' + $obj.val();
           } 
        });
        $txt.text($eachText);
       
       $('#selectValue, #selectSelected').trigger('update-select-list'); // обновляем селекты
    });  
    // конец
   
   // Дата
    if($('.datepicker').is(':visible')){
        $(".datepicker").glDatePicker({
            onClick: function(target, cell, date, data) {
                target.val(('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth()+1)).slice(-2) + '.' + date.getFullYear());

            }
        }).glDatePicker(true);

        /*
        $(".datepicker").glDatePicker({
    		showAlways: false,
            cssName: 'default ',
            onClick: function(target, cell, date, data) {
                target.val(date.getDate() + '.' + (date.getMonth()+1) + '.' + date.getFullYear());

                if(data != null) {
                    alert(data.message + '\n' + date);
                }
            }
    	});
        */

    } 
    
    
    // функция конвертирования имени в ссылку #name => #link        
    $('#check-link').on('click', function()
    {
        $('.serial_form').liTranslit({
            elName: '#name',        //Класс елемента с именем
            elAlias: '#link'        //Класс елемента с алиасом
        });
    });
    
    
});


function timeOutId(url, module, elem)
{
    $('#timeoutId').parent().empty();
    $('.md_'+module).after('<tr><td colspan="4" id="timeoutId"></td></tr>');
    $('.message').hide();

    var dat = {'moduleName':module};    
    var container = '#timeoutId';
    
    $.ajax({        
        url: url,    
        data: {params: dat},
        success: function(data) { // когда AJAX запрос завершился успешно   
            $(container).empty().append(data.ok);   
            if(elem == '1') { removeMessages($('#timeoutId').parent(), true, 3000); } 
            if(data.td)
            {
                if(data.td != ''){
                    $('.md_'+module+' .td_'+module).html(data.td);
                }
            }                                                             
        },        
        beforeSend: function() { // после отправки запроса
            $(container).appendSpinner({spinner:'loader', text:'Загрузка<br /><br /><br />'});
        },
        error: function() { // когда AJAX запрос завершится ошибкой
            $('#timeoutId').parent().empty();
            notifyMes('red', 'Ошибка загрузки');                
        }                
    });    
}    


function timeOutId_mes(url, module, elem)
{
    var container = '#timeoutId_mes';
    
    $(container).parent().remove();
    $(elem).before('<div id="timeoutId_mes"></div>');
    $('.message').hide();

    var dat = {'moduleName':module};        
    
    $.ajax({        
        url: url,    
        data: {params: dat},
        success: function(data) { // когда AJAX запрос завершился успешно   
            $(container).empty().append(data.ok);   
            removeMessages($(container), true, 3000);
            if(data.td)
            {
                if(data.td != ''){
                    $('.md_'+module+' .td_'+module).html(data.td);
                }
            }                                                             
        },        
        beforeSend: function() { // после отправки запроса
            $(container).appendSpinner({spinner:'loader', text:'Загрузка<br /><br /><br />'});
        },
        error: function() { // когда AJAX запрос завершится ошибкой
            $('#timeoutId').parent().remove();
            notifyMes('red', 'Ошибка загрузки');                
        }                
    });    
}


/* ********************* Делаем панели на 100% высоту ********************* */
	// Function to update panels size
	updatePanelsSize = function()
	{
		panelNav.height($.template.viewportHeight-titleBar.outerHeight()-controlsSize);
		panelContent.height($.template.viewportHeight-titleBar.outerHeight()-controlsSize-paddingSize);
	};

	// First call
	updatePanelsSize();

	// Refresh on resize
	$(window).on('normalized-resize', updatePanelsSize);
/* ********************* #Делаем панели на 100% высоту ********************* */
 
 
    
 
/* ********************* Хеш адресной строки ********************* */     
    /*if (hash)
    {
        globalUrl = hash.replace(/^#/, '');
    }
    
    if(globalUrl !== undefined)
    {
       panelContent.contentLoad({url:globalUrl, clk:false});      
    }
    
	// Ajax navigation
	$(panelNav).on('click', 'a', function(event)
	{			   
        var link = $(this),
			href = link.attr('href'),
			docmenu;                       
        
		// If local link
		if (href)
		{
			event.preventDefault();
			window.location.hash = '#'+href;
			if (!bod.parent().hasClass('hashchange'))
			{
				win.hashchange();
			}
                                    
			// If in menu, add visual indicator
			docmenu = link.closest('#doc-menu');
			if (docmenu.length > 0)
			{
				docmenu.find('.current').removeClass('current');
				link.addClass('current');
			}
		}
	});
    
    
    // Listen to hash changes
	win.hashchange(function(event)
	{
        var hash = $.trim(window.location.hash || '');                        
        
		if (hash.length > 1)
		{
            if( panelContent.contentLoad({url:hash.substring(1), clk:false}))
            {
				// Scroll
				if (init)
				{
					bod.animate({
						scrollTop: 0
					});
				}
            }			
		}
		else
		{
			window.location.reload();
		}
	});

	// Init
	if (window.location.hash && window.location.hash.length > 1)
	{
		win.hashchange();
	}
    
	init = true;*/
/* ********************* #Хеш адресной строки ********************* */        
    
    
    
/* ********************* Скрыть/Показать настройки ********************* */       
function showHideOptions()     
{
     $('.sets_setting').click(function(){
        
        if($('.sets_add').hasClass('hidden'))
        {                                     
            $('.sets_add').removeClass('hidden');                                      
            $('.sets').addClass('hidden');                                    
        }
        else
        {
            $('.sets_add').addClass('hidden');
            $('.sets').removeClass('hidden');                                                                                 
        }
            
        return false;
        
      });
}
/* ********************* #Скрыть/Показать настройки ********************* */      
    
    

/* ********************* Выставляем автоматически высоту под скрол ********************* */      
function scrollHeight()
{
    var h = $('.panel-content').height()-100;         
    $('.scrollable').css('height',h);        
}    
/* ********************* #Выставляем автоматически высоту под скрол ********************* */     
    
    

/* ********************* Включить или отключить чекбоксы ********************* */      
function toggleCheckbox()
{
    $('#button-checkbox-all').change(function(){
        
        if($(this).is(':checked'))
        {                
            chek = true;
        }
        else
        {
            chek = false;
        }  
        
        $('.checks').each(function()
        {
            this.checked = chek;
            if(chek)
            {
                $(this).closest('tr').addClass('chekDel');
            }
            else
            {
                $(this).closest('tr').removeClass('chekDel');
            }
        });
                             
    });
    
    // когда включаем или отключаем чекбокс отдельно то додаем или отнимаем класс chekDel
   $('.checks').change(function(){                
        if($(this).closest('tr').hasClass('chekDel'))
        {
            $(this).closest('tr').removeClass('chekDel');
        }
        else
        {
            $(this).closest('tr').addClass('chekDel');
        }
   });        
}
/* ********************* #Включить или отключить чекбоксы ********************* */
    
    
    
/* ********************* Пагинация списка таблицы вывода ********************* */    
function listTablePagination()
{
    initCoookieList(true);

    $(document).on('click', '.current_rows', function(){
        var iPage = parseInt($(this).attr('href'));        
        ajaxPagination(iPage); 
        initCoookieList();       
        return false;
    });               
}        

// Загрузка данных
function ajaxPagination(page, selector) {
    
    if(selector === undefined) { selector = '#loadPagination'; }
        
    var $mdName = $('#moduleName').data('table');
        $.cookie('page' + $mdName, page);
    
    if(page == 1)
    {
        $.removeCookie('page' + $mdName);
    }
    
    $.ajax({
        url: $('#uriData').text(),
        data: {
            pagination: page
        },
        success: function(data) {
           $(selector).empty().append(data.ok);                                     
        },                
        beforeSend: function() {
            var ch = parseInt($('#countHeading').text()); // к-во столбцов в таблице
            $('#button-checkbox-all').checked = false;
            $('#button-checkbox-all').removeAttr('checked');
            $(selector).empty().append('<tr class="white anthracite-gradient"><td id="refreshTableList" colspan="'+ch+'"></td></tr>');
            $('#refreshTableList').appendSpinner({text:' Обновление данных...', clas: 'on-dark'});
        },
        error: function() {
            $(selector).empty().append(data.error);
        }
    });
}    


// Использование cookie для пагинации и поиска
function initCoookieList(clicks)
{
    if($('#moduleName'))
    {              
       var $mdName = $('#moduleName').data('table'),
           $cookieSearch = $.cookie($mdName),
           $cookiePage = $.cookie('page' + $mdName),
           $buttonSearch = '';

       if( $cookieSearch != '' && $cookieSearch != undefined && $cookieSearch != 'empty' )
       {
           $('#table_search').val($cookieSearch);
           
           $.ajax({
                url: $('#uriData').text(),
                data: {
                    search: $cookieSearch
                },
                success: function(data) {
                   $('#loadPagination').empty().append(data.ok);                                     
                },                
                beforeSend: function() {
                    var ch = parseInt($('#countHeading').text()); // к-во столбцов в таблице        
                    $('#loadPagination').empty().append('<tr class="white anthracite-gradient"><td id="refreshTableList" colspan="'+ch+'"></td></tr>');
                    $('#refreshTableList').appendSpinner({text:' Идет поиск данных...', clas: 'on-dark', padding: 'no-padding'});
                }
            });
        }
        else
        {
            var $iFirst = 1,
                $iSecond = 1,
                $iLimit = 3,
                $iAll = parseInt($('#countPages').text()),
                $iActive = '',
                $iReturn = 'return false';
            
            if($cookiePage > 1)
            {
                $iFirst = $cookiePage - $iLimit;
                if($iFirst < 1) { $iFirst = 1; }                                                        
            }
            else
            {
                $.removeCookie('page' + $mdName);
                $cookiePage = 1;
                $iLimit = 3;
            }                                               
            
            if(clicks !== undefined) 
            { 
                if($cookiePage > 1)
                {
                    ajaxPagination($cookiePage);
                }
            }
            
            if($iAll > 1)
            {
                $iSecond = parseInt($cookiePage) + parseInt($iLimit);            
                if($iSecond > $iAll) { $iSecond = $iAll; }     
                
                for($iFirst; $iFirst <= $iSecond; $iFirst++)
                {
                    $iActive = 'current_rows';
                    if($cookiePage == $iFirst)
                    {
                        $iActive = 'active';
                    }                
                    $buttonSearch += '<a href="' + $iFirst + '" class="button blue-gradient glossy ' + $iActive + '" onclick="' + $iReturn + '">' + $iFirst + '</a>';
                } 
                
                $('.paginationButtons').html($buttonSearch);
            
                $('.navigationPage2').remove();
                $('.table-header').after('<div class="table-header navigationPage2" style="border-radius: 0 !important;"><div class="float-right"></div><div class="clear-both"></div></div>');
                $('.navigationPage2').find('.float-right').append('.navigationPage2').html($buttonSearch);
                
                // расчет кол-ва показа данных
                var perPaging = $('#perPaging').text(), // к-во записей на страницу 
                    allRows = $('#allRows').text(); // всего записей
                                   
                if($cookiePage > 1)
                {
                    second_rows = perPaging * $cookiePage;
                    if(second_rows > allRows)
                    {
                        second_rows = allRows;
                    }
                    
                    first_rows = perPaging * ($cookiePage-1);
                }
                else
                {
                    first_rows = 1;
                    second_rows = perPaging;
                }
                
                // показываем данные записей
                $('#second_rows').text(second_rows);
                $('#first_rows').text(first_rows);
            }
        }                
    }
}
/* ********************* #Пагинация списка таблицы вывода ********************* */   
     
     
     
/* ********************* Удаление одиночной записи ********************* */      
function oneDelRow()
{
    $('.delRow').click(function() 
    {
    	$(this).confirm({
        	onConfirm: function()
    		{				
                var id = parseInt($(this).data('id'));                                                                                                                
                var $link = $(this).data('links');

                $.ajax({
                    url: $('#urlData').text(),
                    data: {
                        deleteId: id,
                        deleteLink: $link
                    },
                    success: function(data) { // когда AJAX запрос завершился успешно                            
                        if(data.status) {
                            $('.dlt'+id).remove();                            
                        }                                                   
                        $('#workingData').empty().append(data.ok);
                        removeMessages();
                    },
                    beforeSend: function() { // после отправки запроса
                        $('#workingData').remove();
                        var ch = parseInt($('#countHeading').text());
                        $('.dlt'+id).after('<tr><td colspan="'+ch+'" id="workingData"></td></tr>');
                        $('#workingData').appendSpinner({text:' Удаление записи...'});
                    }
                });                                
                
                $('.message').hide();   
                              
    			return false;
    		},
            onCancel: function()
    		{			
    
    		}
         });
    });  
}         

    $('.onlyDelete').click(function() 
    {
    	$(this).confirm({
        	onConfirm: function()
    		{				
                var $id = $('input[name=id]').val(),
                    $link = $('#link').val(),
                    $url = $('.icon-reply').attr('href');
                     
                     
                    console.log($id); 
                                
                $.ajax({
                    url: $url + '/deleteRow',
                    data: {
                        deleteId: $id,
                        deleteLink: $link
                    },
                    success: function(data) { // когда AJAX запрос завершился успешно                            
                        $('#ajaxNotification').empty().append(data.ok); 
                        if(data.status) {                                                       
                            setTimeout(function(){                                   
                                window.location.href = $url;
                            }, 500);                    
                        } else {
                            removeMessages('#ajaxNotification',false, 3000);
                        }                                                   
                    },
                    beforeSend: function() { // после отправки запроса                                                
                        $('#ajaxNotification').appendSpinner();            
                    }
                });                                
                
                $('.message').hide();   
                              
    			return false;
    		},
            onCancel: function()
    		{			
    
    		}
         });
    });  

/* ********************* #Удаление одиночной записи ********************* */    
    


/* ********************* Удаление либо зачистка селектора ********************* */     
function removeMessages(selector, actionRemove, times)
{
    if(selector === undefined) { selector = '#workingData'; } // селектор                   
    if(actionRemove === undefined) { actionRemove = true; } // если true то удаляем
    if(times === undefined) { times = 2000; } // время после которого выполняем действие
        
    setTimeout(function(){       
        if(actionRemove) {
            $(selector).remove();
        } else {
            $(selector).empty();
        }
    },times);
}  
/* ********************* #Удаление либо зачистка селектора ********************* */   



/* ********************* Множественные действия с записями ********************* */ 
function actionSubmitOn()
{
    $('#actionSubmit').click(function() {           
       var ckeckInput = {}; // отмеченные чекбоксы
       var action = $('select[name=action]').val(); // выбранное действие
       
       $('.checks:checked').each(function(i) { // проходимся по отмеченным чекбоксам               
            ckeckInput[i] = $(this).val(); // и вытаксиваем id записей
       });
       
       $.ajax({                
            url: $('#urlData').text(),                
            data: {
                ckecked: {'checked': ckeckInput, 'action': action}  
            },
            success: function(data) { 
                if(data.status) {
                    $('.chekDel').remove();                            
                }                                                   
                $('#ajaxNotification').empty().append(data.ok);
                removeMessages('#ajaxNotification',false, 3000);                                           
            },
            beforeSend: function() {
                $('#ajaxNotification').appendSpinner();
            },
            error: function() {
                
            }
        });           
       
       return false; 
    });
}
/* ********************* #Множественные действия с записями ********************* */    



/* ********************* Передача параметров для простого редактирования определенных данных по типу: видимость, сортировка, title ... ********************* */
function switchesActionAjax()
{
// Сохранение видимости    
    $('.switches').change(function() {       
       var dt = '0',
           objs = {}; 
       
       if($(this).is(':checked')) {
            dt = '0'; // видим
       } else {
            dt = '1'; // скрыт
       } 
    
       objs['table'] = $('#moduleName').data('table'); // имя таблицы
       objs['module'] = $('#moduleName').data('module'); // имя модуля
       objs['id'] = $(this).data('id'); // идентификатор       
       objs['val'] = dt; // статус чекбокса         
       objs['action'] = 'switch'; // опред``еляем действие
    
       actionAjax(objs);                          
    });

// Сохранение сортировки, title
    $('.sorte, .title').blur(function(){
        var objs = {};
        
        objs['table'] = $('#moduleName').data('table'); // имя таблицы
        objs['module'] = $('#moduleName').data('module'); // имя модуля
        objs['id'] = $(this).data('id'); // идентификатор
        objs['val'] = $(this).val(); // значение
        objs['action'] = $(this).data('action');; // определяем действие
        
        if(objs['val'] !== '') {        
            actionAjax(objs);
        } else {
            notifyMes('silver','Ошибка! Поле пустое.'); 
        }         
    });    
    
    
}

function actionAjax(objs)
{
   var identifyClasses = 'ntf'+objs.id;
   var identifyClass = '.'+identifyClasses;
   
   $.ajax({        
        url: '/admin/functions/actionAjax',        
        data: {
            params: objs,
        },
        success: function(data) {                             
            if(data.ok) {
                $(identifyClass).empty().append('Сохранено!');
                $(identifyClass).removeClass('white-gradient').addClass('green-gradient');
            } else {                                       
                $(this).val(0);
                $(identifyClass).empty().append('Не сохранено!');
                $(identifyClass).removeClass('white-gradient').addClass('red-gradient');
            }                                              
        },
        beforeSend: function() {                   
            notifyMes(identifyClasses + ' with-small-padding white','');
            $(identifyClass).appendSpinner();
        },
        error: function(){
            $(this).val(0);
            $(identifyClass).empty().append('Не сохранено!');
            $(identifyClass).removeClass('white-gradient').addClass('red-gradient');
        }
    });
}
/* ********************* #Передача параметров для простого редактирования определенных данных по типу: видимость, сортировка, title ... ********************* */



/* ********************* Плавающее сообщение ********************* */
function notifyMes(color,mes)
{
    notify('', mes, {
		classes: color + '-gradient glossy'
	});
}
/* ********************* #Плавающее сообщение ********************* */



/* ********************* ajax Поиск данных в таблице ********************* */
function searchAjaxTable()
{
    $('#table_search').delayKeyup(function(ev) 
    {       
        var text = ev.val(),
            $mdName = $('#moduleName').data('table');                                   
        
        if(text === '') { text = 'empty'; $('.paginationButtons, .countPaginationRows, .navigationPage2').show(); $.removeCookie($mdName);  initCoookieList(); } else { $('.paginationButtons, .countPaginationRows, .navigationPage2').hide(); }                
        
        var $mdName = $('#moduleName').data('table');
        $.cookie($mdName, text);
        
        $.ajax({
            url: $('#uriData').text(),
            data: {
                search: text
            },
            success: function(data) {
               $('#loadPagination').empty().append(data.ok);                                     
            },                
            beforeSend: function() {
                var ch = parseInt($('#countHeading').text()); // к-во столбцов в таблице        
                $('#loadPagination').empty().append('<tr class="white anthracite-gradient"><td id="refreshTableList" colspan="'+ch+'"></td></tr>');
                $('#refreshTableList').appendSpinner({text:' Идет поиск данных...', clas: 'on-dark', padding: 'no-padding'});
            }
        });
        
    },300);
}
/* ********************* #ajax Поиск данных в таблице ********************* */



/* ********************* Выборка изображения ********************* */
function BrowseServer( startupPath, functionData ) // Открывает popup для роботы с изображением
{	           
	var finder = new CKFinder(); // Создаем объект   
	finder.startupPath = 'Images:/' + startupPath; // Какую папку следует открывать сразу при создании popup()	
	finder.selectActionFunction = SetFileField; // Функция которая будет запускаться при выборе файла	
	finder.selectActionData = functionData; // Передаем идентификатор
    console.log('aaaaaaaaaa');
    finder.callback = function( api ) { // Возвращающая функция
    	//api.openMsgDialog("Almost ready to go!" );
    };            
	
	finder.popup(); // Открываем окно
}

// Функция выполняется когда был выбран файл
function SetFileField( fileUrl, data )
{
    document.getElementById( data["selectActionData"] ).src = fileUrl;
    document.getElementById( 'input_' + data["selectActionData"] ).value = fileUrl;            
}

// Удаление главного фото
$('.deleters').data('confirm-options', {			
    onConfirm: function()
	{
        var ids = $(this).data('id');                    
        $('#' + ids).attr('src','/asset/uploads/_thumbs/images/no_image.jpg');
        $('#input_' + ids).val('/asset/uploads/_thumbs/images/no_image.jpg');
                        
		return false;
	}
});

// Удаление доп. фото
function deletersLi(id) 
{			                   
    $('#' + id).remove();
    $('.message').hide();                          
}


// Открывает popup для выбора файла
function BrowseServerFile( startupPath, functionData )
{	       
    console.log(functionData);
    
    // Создаем объект
	var finder = new CKFinder();    
	// Какую папку следует открывать сразу при создании popup()
	finder.startupPath = 'Files:/' + startupPath;
	// Функция которая будет запускаться при выборе файла
	finder.selectActionFunction = SetFilesField;
	// Передаем идентификатор
	finder.selectActionData = functionData;
    // Возвращающая функция
    finder.callback = function( api ) {
    	//api.openMsgDialog("Almost ready to go!" );
    };            
	// Открываем окно
	finder.popup();
}

// Функция выполняется когда был выбран файл
function SetFilesField( fileUrl, data )
{    
    simpleArray = fileUrl.split('/');
	var lng = simpleArray.length - 1;
    var size = '';    
    var prSize = parseInt(data['fileSize']);
    
    if(prSize >= 1024)
    {
        var size = (prSize / parseInt(1024)).toFixed(2) + ' Мб';
    }
    else
    {
        size = prSize + ' Кб';
    }
    

    //document.getElementById( data["selectActionData"] ).textContent = simpleArray[lng]; // имя файла    
    $('#input_' + data["selectActionData"] ).val(fileUrl); // ссылка           
    $('#input_alt_' + data["selectActionData"] ).val(simpleArray[lng]); // имя
    $('#input_size_' + data["selectActionData"] ).val(size); // размер   
}

// Добавление дополнительного фото
$('#add_ft').click(function()
{
   var f = (Math.random())*123456;
   var ft = parseInt($('#multi_files li').length) + parseInt(f);   
   var path = $(this).data('path');
   
   var files = "" +
    "<div id='mt"+ft+"_li' class='block-label fixed-size-columns left-border boxed wrapped align-center no-padding'>" +						                     
            "<div class='controls' style='line-height: 100%'>" +
    		"<span class='button-group compact children-tooltip'>" +
    		  "<a href='javascript:void(0);' class='button icon-pages blue-gradient' onclick='BrowseServerFile("+'"'+path+'"'+","+'"mt'+ft+'"'+");' title='Выбрать файл'> выбрать</a>" +
    		  "<a href='javascript:void(0);' data-id='mt"+ft+"' class='button icon-trash red-gradient' onclick='deletersLi("+'"mt'+ft+'_li"'+");' title='Удалить файл'> удалить</a>" +                        
            "</span>" +                                     
            "</div>" +                                 
        "<input type='text' name='input' id='input_mt"+ft+"' placeholder='Расположение файла' class='full-width input' />" +
        "<input type='text' name='input_alt' id='input_alt_mt"+ft+"' placeholder='Название файла' class='full-width input' />" +                       
        "<input type='text' name='input_size' id='input_size_mt"+ft+"' placeholder='Размер файла' class='full-width input' />" +
    "</div>";
   
   $('#multi_files').append(files);
}); 

$('#add_mtfoto').click(function()
{
   var f = (Math.random())*123456;
   var ft = parseInt($('#foto li').length) + parseInt(f);
   var path = $(this).data('path');
   
   var img = "" +
    "<div id='fotomt"+ft+"_li' class='block-label fixed-size-columns left-border boxed wrapped align-center no-padding'>" +
        "<ul class=gallery>" +
            "<li>" +
                "<img src='/asset/uploads/_thumbs/images/no_image.jpg' class='framed large-margin-left' id='fotomt"+ft+"' />" +
                "<input type='hidden' name='foto[]' value='/asset/uploads/_thumbs/images/no_image.jpg' id='input_fotomt"+ft+"' />" +
                "<div class='controls margin-left'>" +
        		"<span class='button-group compact children-tooltip'>" +
        		  "<a href='javascript:void(0);' class='button icon-pictures' onclick='BrowseServer("+'"'+path+'"'+","+'"fotomt'+ft+'"'+");' title='Выбрать изображение'></a>" +
        		  "<a href='javascript:void(0);' data-id='fotomt"+ft+"' class='button icon-trash' onclick='deletersLi("+'"fotomt'+ft+'_li"'+");' title='Удалить блок с изображением'></a>" +
                "</span>" +                                     
                "</div>" +                     
            "</li>" +                   
        "</ul>" +
        "<input type='text' name='foto_alt' placeholder='Описание изображения' class='full-width input' />" +                       
    "</div>";
   
   $('#foto').append(img);
});


    $('#add_mtfoto_otdelka').click(function()
    {
        var f = (Math.random())*123456;
        var ft = parseInt($('#foto_otdelka li').length) + parseInt(f);
        var path = $(this).data('path');

        var img = "" +
            "<div id='foto_otdelkamt"+ft+"_li' class='block-label fixed-size-columns left-border boxed wrapped align-center no-padding'>" +
            "<ul class=gallery>" +
            "<li>" +
            "<img src='/asset/uploads/_thumbs/images/no_image.jpg' class='framed large-margin-left' id='foto_otdelkamt"+ft+"' />" +
            "<input type='hidden' name='foto_otdelka[]' value='/asset/uploads/_thumbs/images/no_image.jpg' id='input_foto_otdelkamt"+ft+"' />" +
            "<div class='controls margin-left'>" +
            "<span class='button-group compact children-tooltip'>" +
            "<a href='javascript:void(0);' class='button icon-pictures' onclick='BrowseServer("+'"'+path+'"'+","+'"foto_otdelkamt'+ft+'"'+");' title='Выбрать изображение'></a>" +
            "<a href='javascript:void(0);' data-id='foto_otdelkamt"+ft+"' class='button icon-trash' onclick='deletersLi("+'"foto_otdelkamt'+ft+'_li"'+");' title='Удалить блок с изображением'></a>" +
            "</span>" +
            "</div>" +
            "</li>" +
            "</ul>" +
            "<input type='text' name='foto_alt' placeholder='Описание изображения' class='full-width input' />" +
            "</div>";

        $('#foto_otdelka').append(img);
    });



/* ********************* #Выборка изображения ********************* */



/* ********************* Подгрузка настроек в контейнер ********************* */      
function loadSettingDiv(url)
{
    $('#loadSettings').appendSpinner({spinner:'loader', text:'Загрузка настроек<br /><br /><br />'}); 
    $('#loadSettings').load(url + '/admin/index/config'); 
    $('.sets_setting').click();        
}    
/* ********************* #Подгрузка настроек в контейнер ********************* */     



/* ********************* Подгрузка данных в контейнер ********************* */      
function loadDataContainer(url, container)
{
    $(container).appendSpinner(); 
    $(container).load(url);                   
}    
/* ********************* #Подгрузка данных в контейнер ********************* */

function loadLanguageTable(url, module)
{
    $('.sets_setting').click();
    $('#loadLanguages').append('<div id="timeoutId"></div>');
    
    var dat = {'moduleName':module};    
    var container = '#timeoutId';
    
    $.ajax({        
        url: url,    
        data: {params: dat},
        success: function(data) { // когда AJAX запрос завершился успешно   
            $(container).empty().append(data.ok);                                                      
        },        
        beforeSend: function() { // после отправки запроса
            $(container).appendSpinner({spinner:'loader', text:'Загрузка<br /><br /><br />'});
        },
        error: function() { // когда AJAX запрос завершится ошибкой
            $(container).empty();
            notifyMes('red', 'Ошибка загрузки');                
        }                
    });    
}

/* ********************* Отправка формы после валидации ********************* */
function send_onValidComplete(form, url, elem)
{
   $('.message').hide();   
   
   for ( instance in CKEDITOR.instances )
   {
        CKEDITOR.instances[instance].updateElement();
        //CKEDITOR.instances[instance].destroy();
		//CKEDITOR.instances[instance].setData('');
   }
   
   if(!dynamicSend) { dynamicSend = {}; }
    
   var $serializeForm = $(form).serializeArray();
   
   $.ajax({
        url: url,
        data: {
            dani: $serializeForm,
            dynamic: dynamicSend
        },
        success: function(data) { // когда AJAX запрос завершился успешно                
            
            if(elem != 'null') { $(elem).empty(); }                                
            
            if(data.statusPage)
            {
                if(data.statusPage.length > 1)
                {
                    setTimeout(function(){                                   
                        window.location.href = data.statusPage;
                    }, 500);
                }
            }
                
            $('button[type=submit]').removeAttr('disabled');                                                  
            $('#ajaxNotification').empty().append(data.ok);
            removeMessages('#ajaxNotification',false, 3000);                                                   
        },
        beforeSend: function() { // после отправки запроса
            $('button[type=submit]').attr('disabled','disabled');
            $('#ajaxNotification').appendSpinner();
            $('.message').hide();            
        }
    });            
}    

// Кнопка сохранить
// document.URL
// window.location.protocol + '//' + window.location.host
function onlySaveForm()
{
   var  form = '.serial_form',
        url = document.URL,
        elem = '#timeoutId',
        host = window.location.protocol + '//' + window.location.host;
   
   url = url.replace(host, ''); 
   
   $('.message').hide();   
   
   for ( instance in CKEDITOR.instances )
   {
        CKEDITOR.instances[instance].updateElement();
        //CKEDITOR.instances[instance].destroy();
		//CKEDITOR.instances[instance].setData('');
   }
   
   if(!dynamicSend) { dynamicSend = {}; }
   
   var $serializeForm = $(form).serializeArray();
   
   $.ajax({
        url: url,
        data: {
            dani: $serializeForm,
            dynamic: dynamicSend
        },
        success: function(data) { // когда AJAX запрос завершился успешно                
            
            if(elem != 'null') { $(elem).empty(); }                                
                            
            $('button[type=submit]').removeAttr('disabled');                                                  
            $('#ajaxNotification').empty().append(data.ok);
            removeMessages('#ajaxNotification',false, 3000);
            if (data.href)
                location.href = data.href;
        },
        beforeSend: function() { // после отправки запроса
            $('button[type=submit]').attr('disabled','disabled');
            $('#ajaxNotification').appendSpinner();
            $('.message').hide();
        }
    });           
}


$('.onlySave, .onlyAdd').click(function(){
    onlySaveForm();
});



// list_fields
function send_onValidComplete_my(form,url,elem)
{         
   var $serialize = $(form).serialize();
   
   $.ajax({
        url: '/'+url,
        data: {
            dani: $serialize,
            dynamic: dynamicSend
        },
        success: function(data) { 
            $('button[type=submit]').removeAttr('disabled');                                                  
            $('#ajaxNotification').empty().append(data.ok);
            removeMessages('#ajaxNotification',false, 3000);                                           
        },
        beforeSend: function() {
            $('button[type=submit]').attr('disabled','disabled');
            $('#ajaxNotification').appendSpinner();
            $('.message').hide();
        }
    });
}
/* ********************* #Отправка формы после валидации ********************* */    
    

// Задаем максимальное количество символов для ввода
function maxLen(id, ln)
{            
    var l = $('#length').text();
    var k = ln;
        
    $('#' + id).bind('focus',function(){                 
        $('#' + id)
        .keyup(function(e) {                        
            
          var current = $(this).val().length-l;                    
          if(current != k) {            
            if(e.which != 0 && e.which != 8) {          
                e.preventDefault();          
            }            
          }          
          $('#small-' + id).text(parseInt(k) - parseInt(current)).addClass('tag');        
        })
        .keypress(function(e) {                        
            
          var current = $(this).val().length-l;                    
          if(current >= k) {            
            if(e.which != 0 && e.which != 8) {          
                e.preventDefault();          
            }            
          }          
          //$('#small-' + id).text(parseInt(k) - parseInt(current)).addClass('tag');        
        });                                                                     
    });    
    
    $('#' + id).blur(function(){ 
        $('#small-' + id).empty().removeClass('tag');
    });
}


// Активация редактора и присоединение к нему менеджера изображений
function editorFull(id)
{       
    if(CKEDITOR.instances[id])
    {
        delete CKEDITOR.instances[id];
    }
    var wi = $('#'+id).css('width');
    //wi = 1040;
    
    
    var editor = CKEDITOR.replace(id,
    {
        language: 'ru',
        toolbar: 
        [
  		    { name: 'document', items: [ 'Source','-','RemoveFormat'] },	
    		['Undo','Redo'],			
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Image','Table','Bold','Italic','Underline','Strike','Subscript','Superscript','TextColor','BGColor'],
            ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','SpecialChar'],			
    		['Link','Unlink','Anchor','-','ShowBlocks','CreateDiv'],            		
            ['Styles', 'Format', 'FontSize', 'Font','youtube']           
	    ],
        
        height: 500, // настройка высоты
        width: wi
                      
        // если нужно чтобы контент отображался в определенных стилях, например в стилях сайта
        //contentsCss: '/assets/admin_css/style_59edcbff.css'
    });                        
	CKFinder.setupCKEditor(editor, '/asset/ckfinder/');    
}


// Активация редактора и присоединение к нему менеджера изображений
function editorBasic(id)
{   
    if(CKEDITOR.instances[id])
    {
        delete CKEDITOR.instances[id];
    }
    
    var wi = $('#'+id).css('width');
    var editor = CKEDITOR.replace(id,
    {
        language: 'ru',        
        toolbar: // настройка инструментов
        [
  		    { name: 'document', items: [ 'Source','-','RemoveFormat'] },	
    		['Undo','Redo'],			
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Image','Table','Bold','Italic','Underline','Strike','Subscript','Superscript','TextColor','BGColor'],
            ['NumberedList','BulletedList','-','Outdent','Indent'],			
    		['Link','Unlink','-','ShowBlocks'],            		
            [ 'Styles', 'Format', 'FontSize', 'Font', 'youtube']            
	    ],        			                
        height: 300, // настройка высоты
        width: wi
                      
        // если нужно чтобы контент отображался в определенных стилях, например в стилях сайта
        //contentsCss: '/assets/admin_css/style_59edcbff.css'
    });                        
	CKFinder.setupCKEditor(editor, '/asset/ckfinder/');
}


function editorBasics(id)
{   
    if(CKEDITOR.instances[id])
    {
        delete CKEDITOR.instances[id];
    }
    
    var wi = $('#'+id).css('width');
    var editor = CKEDITOR.replace(id,
    {
        language: 'ru',        
        toolbar: // настройка инструментов
        [
  		    { name: 'document', items: [ 'Source','-','RemoveFormat'] },	
    		['Undo','Redo'],			
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Image','Table','Bold','Italic','Underline','Strike','Subscript','Superscript','TextColor','BGColor'],
            ['NumberedList','BulletedList','-','Outdent','Indent'],			
    		['Link','Unlink','-','ShowBlocks'],            		
            [ 'Styles', 'Format', 'FontSize', 'Font', 'youtube']            
	    ],        			                
        height: 200, // настройка высоты
        width: 500,
        //bodyClass: 'w960'
                      
        // если нужно чтобы контент отображался в определенных стилях, например в стилях сайта
        //contentsCss: '/assets/admin_css/style_59edcbff.css'
    });                        
	CKFinder.setupCKEditor(editor, '/asset/ckfinder/');
}





// Активация редактора и присоединение к нему менеджера изображений
function editorLanguage(id)
{   
    if(CKEDITOR.instances[id])
    {
        delete CKEDITOR.instances[id];
    }
    
    var wi = $('#'+id).css('width');
    var editor = CKEDITOR.replace(id,
    {
        language: 'ru', // настройка инструментов               
        toolbar: 
        [
    		{ name: 'document', items: [ 'Source','-','RemoveFormat'] },	
    		['Undo','Redo'],			
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Image','Table','Bold','Italic','Underline','Strike','Subscript','Superscript','TextColor','BGColor'],
            ['NumberedList','BulletedList','-','Outdent','Indent'],			
    		['Link','Unlink','-','ShowBlocks'],            		
            [ 'Styles', 'Format', 'FontSize', 'Font', 'youtube' ]             
	    ],                               
        height: 300, // настройка высоты
        width: wi
                      
        // если нужно чтобы контент отображался в определенных стилях, например в стилях сайта
        //contentsCss: '/assets/admin_css/style_59edcbff.css'
    });                        
	CKFinder.setupCKEditor(editor, '/asset/ckfinder/');
}




/* ********************* Подгрузка выпадающих данных для таблиц ********************* */ 
$('.loading-tr').on('click', 'tbody td', function(event)
{
	// Do not process if something else has been clicked
	if (event.target !== this)
	{
		return;
	}

	var tr = $(this).parent(),
		row = tr.next('.row-drop'),
		rows;

	// If click on a special row
	if (tr.hasClass('row-drop'))
	{
		return;
	}

	// If there is already a special row
	if (row.length > 0)
	{
		// Un-style row
		tr.children().removeClass('anthracite-gradient');

		// Remove row
		row.remove();

		return;
	}

	// Remove existing special rows
	rows = tr.siblings('.row-drop');
	if (rows.length > 0)
	{
		// Un-style previous rows
		rows.prev().children().removeClass('anthracite-gradient');

		// Remove rows
		rows.remove();
	}

	// Style row
	tr.children().addClass('anthracite-gradient');
    
    var $mod = $('#moduleName').data('module'),
        $id = tr.attr('class');
    
    $.ajax({
        type: 'POST',
        url: '/' + $mod + '/admin/index/dropRow',
        dataType: 'json', 
        data: {                
            len: tr.children().length,
            id: $id
        },
        success: function(data) { 
            $('#appendAjax').empty().append(data.ok);                                        
        },
        beforeSend: function() {
            // Add fake row
			$('<tr class="row-drop">'+
				'<td id="appendAjax" colspan="'+tr.children().length+'">'+
                '<p class="big-message blue-gradient white"><span class="icon-info-round margin-right icon-size2"></span>Загрузка контента</p>' +					
				'</td>'+
			'</tr>').insertAfter(tr);
        }
    });     
});


function saveRowForm($this)
{
   $rowArray = {};
        
        var form = '.row_form',
            elem = '',
            $module = $this.data('module'),
            $id = $this.data('id'),
            $serializeForm = $(form).serializeArray();

   url = '/' + $module + '/admin/index/edit/' + $id;         

   $.ajax({
        url: url,
        data: {
            dani: $serializeForm
        },
        success: function(data) { // когда AJAX запрос завершился успешно                            
            $('.dlt' + $id).find('td').first().click();
            if(elem != 'null') { $(elem).empty(); }                                                            
            $this.removeAttr('disabled');                                                  
            $('#ajaxNotification').empty().append(data.ok);
            removeMessages('#ajaxNotification',false, 3000);                                                               
        },
        beforeSend: function() { // после отправки запроса
            $this.attr('disabled','disabled');
            $('#ajaxNotification').appendSpinner();
            $('.message').hide();
        }
    });             
}

/* ********************* Подгрузка выпадающих данных для таблиц ********************* */



    
    
/******************************************************************************************************************************************/    
/***************************************************************************** Функции установки ******************************************/    
/******************************************************************************************************************************************/    

    
// Выставляем автоматически высоту под скрол    
    $.template.addSetupFunction(scrollHeight) 
    {
        scrollHeight();
        $(window).on('normalized-resize', scrollHeight);
    }
// Включить или отключить все чекбоксы    
    $.template.addSetupFunction(toggleCheckbox) 
    {
        toggleCheckbox();
    }
// Удаление одиночной записи    
    $.template.addSetupFunction(oneDelRow) 
    {
        oneDelRow();        
    }
    
