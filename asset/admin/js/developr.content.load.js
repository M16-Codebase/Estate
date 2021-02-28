/**
 *
 * '||''|.                            '||
 *  ||   ||    ....  .... ...   ....   ||    ...   ... ...  ... ..
 *  ||    || .|...||  '|.  |  .|...||  ||  .|  '|.  ||'  ||  ||' ''
 *  ||    || ||        '|.|   ||       ||  ||   ||  ||    |  ||
 * .||...|'   '|...'    '|     '|...' .||.  '|..|'  ||...'  .||.
 *                                                  ||
 *                                                 .... 
 *
 * Загрузка данных через ajax
 */

;(function($, document)
{
	$.fn.contentLoad = function( options ) {
	   return this.each(function(){
            
            var $this = $(this),
                panelContent = $('#panel-content'),
                set = $.extend({
                    cont:     '',
                    clk:      true,  
                    url:      '', 
                    spinner:  '<span class="loader big with-padding"></span>',                      
                    dataType: 'json',
                    cache:    false,
                    success:  function(data) { panelContent.empty().append(data.ok); console.log(data); },
                    before:   function(data) { panelContent.empty().append(set.spinner); }, 
                    error:    function(data) { panelContent.empty().append('error'); } 
                }, options);                            
            
            if(set.clk)
            {                                                                                                                        
                $this.click(function(){                                                                         
                    
                   if(set.url === '') { set.url = $this.attr('href'); }                   
                   document.location.hash = '#'+set.url;
                                  
                   panelContent.loadPanelContent(set.url,{                    
                        onStartLoad: function(settings,ajaxOption)
                		{
                            ajaxOption.dataType = set.dataType;
                            ajaxOption.beforeSend = function(data) { set.before(data); }
                            ajaxOption.success = function(data) { set.success(data); }
                            ajaxOption.error = function(data) { set.error(data); }
                		}               
                    });
                    
                    set.url = '';
                                                                       
                    return false;
                    
                });             
            }   
            else
            {
                if(set.url === '') { set.url = $this.attr('href'); } 
                
                panelContent.loadPanelContent(set.url,{                    
                    onStartLoad: function(settings,ajaxOption)
            		{
                        ajaxOption.dataType = set.dataType;
                        ajaxOption.beforeSend = function(data) { set.before(data); }
                        ajaxOption.success = function(data) { set.success(data); }
                        ajaxOption.error = function(data) { set.error(data); }
            		}               
                });
                
                set.url = '';
            }                   
           
	   });              
	}

// Вставка loadera    
	$.fn.appendSpinner = function( options ) {
	   return this.each(function(){
            
            var $this = $(this),
                set = $.extend({  
                    spinner:    'refreshing', 
                    size:       '',
                    padding:    'with-small-padding',
                    text:       'Обработка данных...',
                    clas:       '',
                    times:      3000,
                    timer:    false                     
                }, options);                            
            
            $(this).empty().append('<span class="loader ' +  set.clas + ' ' +  set.spinner + ' ' + set.size + ' ' + set.padding + '"></span>' + set.text);
            
            if(set.timer) 
            {
                setTimeout(function(){       
                    $(this).empty();
                }, set.times);
            }            
           
	   });              
	}
    
// Проверка ввода данных в поле при нажатии клавиши
    $.fn.delayKeyup = function(callback, ms){
         var timer = 0;
         var el = $(this);
         $(this).keyup(function(){                   
             clearTimeout (timer);
             timer = setTimeout(function(){
                 callback(el)
                     }, ms);
         });
         return $(this);
     };  

// Конвертируем имя в ссылку     
jQuery.fn.liTranslit = function(options){
    // настройки по умолчанию
    var o = jQuery.extend({
        elName: '.s_name',        //Класс елемента с именем
        elAlias: '.s_alias'        //Класс елемента с алиасом
    },options);
    return this.each(function(){
        var elName = $(this).find(o.elName),
            elAlias = $(this).find(o.elAlias),
            nameVal;
        function tr(el){
            nameVal = el.val();
            inser_trans(get_trans(nameVal));
        };
        
        tr(elName);
        
        function get_trans() {
            en_to_ru = {
                'а': 'a',
                'б': 'b',
                'в': 'v',
                'г': 'g',
                'д': 'd',
                'е': 'e',
                'ё': 'jo',
                'ж': 'zh',
                'з': 'z',
                'и': 'i',
                'й': 'j',
                'к': 'k',
                'л': 'l',
                'м': 'm',
                'н': 'n',
                'о': 'o',
                'п': 'p',
                'р': 'r',
                'с': 's',
                'т': 't',
                'у': 'u',
                'ф': 'f',
                'х': 'h',
                'ц': 'c',
                'ч': 'ch',
                'ш': 'sh',
                'щ': 'sch',
                'ъ': '#',
                'ы': 'y',
                'ь': '',
                'э': 'je',
                'ю': 'ju',
                'я': 'ja',
                ' ': '-',
                'і': 'i',
                'ї': 'i',
                '”': ''
            };
            nameVal = nameVal.toLowerCase();
            nameVal = trim(nameVal);
            nameVal = nameVal.split("");
            var trans = new String();
            for (i = 0; i < nameVal.length; i++) {
                for (key in en_to_ru) {
                    val = en_to_ru[key];
                    if (key == nameVal[i]) {
                        trans += val;
                        break
                    } else if (key == "ї") {
                        trans += nameVal[i]
                    };
                };
            };
            return trans;
        }
        function inser_trans(result) {
            result = result.substring(0, 60);
            elAlias.val(result);
        }
        function trim(string) {
            string = string.replace(/'|"|<|>|\!|\||@|#|$|%|^|\^|\$|\\|\/|&|\*|\(\)|-|\|\/|;|\+|№|\.|,|\?|_|:|{|}|\[|\]/g, "");
            string = string.replace(/(^\s+)|(\s+$)/g, "");
            return string;
        };
    });
};                        

})(jQuery, document);