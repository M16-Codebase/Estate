<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * CodeIgniter Element form Class
 */
class Element extends MX_Controller {
   
    public $alang;
    private $ci;
    private $tablesFi;
    
	function __construct()
	{
        // конструктор
		parent::__construct();                 
        
        if(!$this->session->userdata('languages'))
            { $this->session->set_userdata('languages', 'russian'); }
        
        $lng = $this->session->userdata('languages'); 
        
        // Загрузка языка                
        $foreachs = $this->load->language('admin/apex', $lng, true);
        foreach($foreachs as $key=>$l)
        {
            $apexLang = htmlspecialchars_decode($l['name']);
            $this->alang->$key = $apexLang; // создаем переменные            
        }                    
	} 
    
    // --------------------------------------------------------------------
    
    /** Передача массива данных шаблону */
    function initial($table)
    {
        $this->tablesFi = $table;
    }
    
    /** Вытаскивание данных и вывод информации */
	public function init($nameField, $wrapBlock = '', $wrapBlockClose = false)
	{	              
	     $value = '';                 
         
         if(is_array($nameField))
            { foreach($nameField as $item) 
                { $value .= $this->switchField($this->tablesFi[$item]); } }
         else
            { $value = $this->switchField($this->tablesFi[$nameField]); }                
        
        if(!empty($wrapBlock))
        { 
            if($wrapBlockClose) { $detail = ''; } else { $detail = 'open'; }
            $value = "<details class=\"details margin-bottom\" {$detail}>
                    	<summary class=\"blue\">{$wrapBlock}</summary>
                    	<div class=\"with-padding columns\">
                            {$value} 
                            <div class=\"clear-both\"></div>                   		                    
                    	</div>
                    </details>";
        }
        
        return $value;         
    }
    
    /** Автоматическая генерация шаблона */
    private function switchField($tableField)
    {
        $type = $tableField['type'];
        
        switch($type)
        {
            case 'input': 
            case 'input_date':
            case 'input_hidden':                
                $dateTempl = $this->fm_input($tableField);
            break;
            
            case 'input_pseudo': $dateTempl = $this->fm_input_pseudo($tableField);
            break;
            
            case 'input_info': $dateTempl = $this->fm_input_info($tableField);
            break;
            
            case 'input_icon': $dateTempl = $this->fm_input_icon($tableField);
            break;
            
            case 'input_enum': $dateTempl = $this->fm_select($tableField);
            break;
            
            case 'input_multi_enum': $dateTempl = $this->fm_multiSelect($tableField);
            break;
                        
            case 'input_switch': $dateTempl = $this->fm_switch($tableField);
            break; 
            
            case 'textarea': $dateTempl = $this->fm_short_textarea($tableField);
            break;
            
            case 'textarea_wysyvig': $dateTempl = $this->fm_wysywig($tableField);
            break;
            
            case 'input_image': $dateTempl = $this->fm_image($tableField);
            break;
            
            case 'input_multi_image': $dateTempl = $this->fm_multiImage($tableField);
            break;
            
            case 'input_files': $dateTempl = $this->fm_files($tableField);
            break;
        }
        
         if(!empty($tableField['label']))
         { 
            if($type != 'input_hidden')
                { $dateTempl = sprintf($this->fm_label($tableField), $dateTempl); } 
         }
         
         return $dateTempl;
    }
           
    
    /**
	 * Заголовок с кнопками(шаблон)
     * $id - Присутствует ли идентификатор записи
     * $button - Подпись кнопки
     * $uri_list - Урл на возвращение назад
	 */
	public function fm_topMenu($button, $uri_list, $id = '')
	{
        if(empty($id))
        {
            $return = '        	
                <button type="submit" class="button blue-gradient compact"> '.$button.' и Выйти</button>    
                <a href="javascript:void(0);" class="onlyAdd green-gradient button icon-squared-plus compact"> '.$button.'</a>                                                                                  
                <a style="display: block;" href="'.BASEURL.'/'.$uri_list.'" class="button icon-reply compact"> '.$this->alang->admin_back.'</a>         	            
            ';
        }
        else
        {
            $return = '        	
                <button type="submit" class="button blue-gradient compact"> '.$button.' и Выйти</button>                                             
                <a href="javascript:void(0);" class="onlySave green-gradient button icon-save compact"> '.$button.'</a>
                <a href="javascript:void(0);" data-chek="'.$id.'" class="onlyDelete red-gradient button icon-trash confirm compact"> '.$this->alang->admin_delete.'</a>         
                <a style="display: block;" href="'.BASEURL.'/'.$uri_list.'" class="button icon-reply compact"> '.$this->alang->admin_back.'</a>         	            
            ';
        }
        
        
        return $return;
	} 
    
    /** Создание блока Вкладок (названия вкладок для переключения) */   
    function fm_createTabLi($arrayTab)
    {         
        $templateLi = '<li class="%s"><a href="#%s">%s</a></li>'; 		           
        $return = '';
        
        foreach($arrayTab as $value)
        {
            $return .= sprintf($templateLi, $value['class'], $value['id'], $value['name']);
        }            
        
        return "<ul class=\"tabs\">{$return}</ul>";    		                                    	     
    }    
    
    /** Блок (шаблон) */
	public function fm_label($data)
	{		                
        $info = $data['info'];     
        $key = $data['name'];
        $label = $data['label'];
        $width = '';
        $labelClass = '';
        
        if(isset($data['options']['width']))
        {
            if(!empty($data['options']['width']))
                { $width = "{$data['options']['width']}-columns"; }
            else
                { $width = "twelve-columns"; }            
        }
        else
            { $width = "twelve-columns"; }  
            
            
        if(isset($data['options']['label_class']))
        {
            if(!empty($data['options']['label_class']))
                { $labelClass = $data['options']['label_class']; }            
        }       
        
        if(!empty($info) and $data['type'] != 'input_info')
        {
            $Tinfo = "
                <span class=\"info-spot\">
				    <span class=\"icon-info-round\"></span>
					<span class=\"info-bubble\">
						{$info}
					</span>
				</span>
            ";
        }                        
        
        $template = "         
            <div id=\"templ_{$key}\" class=\"block-label mid-margin-bottom {$labelClass} button-height {$width}\">						
				<label for=\"{$key}\" class=\"label mid-margin-top\">
                    <span>
                        {$Tinfo} <strong>{$label}</strong>
                    </span> 
                    <small class=\"\" id=\"small-{$key}\"></small>                       
                </label>
                %s
            </div>        
        ";
        
        return $template; // возвращаем блок
	}
    
    /** Поле (input) */
	public function fm_input($input)
	{
        $type = $input['type'];
        
        $input['class'] = empty($input['options']['class']) ? 'input full-width' : 'input '.$input['options']['class'];                  
        
        if($type == 'input_date')
            { $input['class'] = 'input datepicker '.$input['options']['class']; }
        
        if(!empty($input['valid'])) // подсоединяем валидацию
            { $input['class'] .= ' validate['.$input['valid'].']'; }
        
        if(empty($input['placeholder']))
         { unset($input['placeholder']); }
        
        unset($input['valid']);
        unset($input['label']);
        unset($input['type']);
        unset($input['params']);
        unset($input['info']);        
        unset($input['type']);
        unset($input['options']);

        if($type == 'input_hidden')
            { $return = form_hidden($input['name'], $input['value']); }
        else
            { $return = form_input($input); }
                                
        return $return;        
	}
    
    /** Поле input (varchar) | поле с кнопкой справа */
    function fm_input_pseudo($input)
    {        
        $input['class'] = empty($input['options']['class']) ? 'full-width' : $input['options']['class'];        
        
        if(!empty($input['valid'])) // подсоединяем валидацию 
            { $input['class'] .= ' validate['.$input['valid'].']'; }
        
        $aButttonName = empty($input['options']['text_button']) ? '' : $input['options']['text_button'];
            
        if($input['name'] == 'link') 
            { if(empty($aButttonName)) 
                { $aButttonName = 'Автозаполнение'; }  
                $aButtton = '<span class="icon-cycle red"></span> '.$aButttonName; }
                    
        $return = '<span class="input relative full-width">                    	
                    	<input type="text" name="'.$input['name'].'" id="'.$input['id'].'" class="input-unstyled '.$input['class'].'" style="width: 75%" value="'.$input['value'].'">                    
                    	<a style="position: absolute; right: 9px; top: 4px" href="javascript:void(0)" id="check-'.$input['id'].'" class="button compact blue white-gradient">'.$aButtton.'</a>
                    </span>';
        
        return $return;        
    }

   	/** Выборка select (enum) */
	public function fm_select($input)
	{
        if($input['options']['select_chosen'] == 1 or $input['options']['select_chosen'] == 'true')
                { $input['class'] = 'chzn-select '; }
            else
                { $input['class'] = 'select '; }

        $input['class'] .= empty($input['options']['class']) ? '' : $input['options']['class'];

        if(!empty($input['valid'])) // подсоединяем валидацию
            { $input['class'] .= ' validate['.$input['valid'].']'; }

            $multiple = '';
            if($input['options']['select_chosen'] == 'multiple')
            {
              $multiple = ' multiple';
            }

        $return = form_dropdown($input['name'], $input['params'], $input['value'], 'class="'.$input['class'].'"'.$multiple);
                
        return $return;        
	} 
    
    /** Поле textarea (text) */
	public function fm_short_textarea($input)
	{		
        $return = form_description($input['name'], $input['value'], 'placeholder="'.$input['placeholder'].'" id="'.$input['id'].'" class="input '.$input['options']['class'].'"');
        
        return $return;        
	}      
    
    /** Поле textarea (Визуальный редактор) */
	public function fm_wysywig($input)
	{		
        $this->load->model('shortcode/admin_shortcode_model');
        $parent = $this->admin_shortcode_model->parent_id();
                                
        $selects = '<option value="0"> '.$this->alang->admin_not_select.' </option>';
        $optgroup = "<optgroup label=\"%s\">%s</optgroup>";
        
        foreach($parent as $pr)
        {
            if($pr['tema'] == 0)
                { $selects0 .= '<option value="'.$pr['name'].'" data-textarea="'.$input['name'].'">'.$pr['title'].'</option>'; }
            elseif($pr['tema'] == 2)
                { $selects2 .= '<option value="'.$pr['name'].'" data-textarea="'.$input['name'].'">'.$pr['title'].'</option>'; }
            elseif($pr['tema'] == 6)
                { $selects6 .= '<option value="'.$pr['name'].'" data-textarea="'.$input['name'].'">'.$pr['title'].'</option>'; }
        }            
                        
        $selects .= sprintf($optgroup, 'Шаблоны', $selects6);
        $selects .= sprintf($optgroup, 'Элементы дизайна', $selects0);            
        $selects .= sprintf($optgroup, 'Формы', $selects2);            
        
        $switch = '<select class="select anthracite-gradient check-'.$input['name'].'"> <option value="html">содержимое</option> <option value="name">название</option></select>';                        
        $clearAll = '<span class="wrapped short_cod hidden dark-text-bevel glow with-border float-left white-bg" style="width: 120px;"><strong>Дополнительно: </strong> <br /><a href="javascript:void(0);" class="red-gradient glossy  button clear-all confirm" data-txt="'.$input['name'].'">Очистить все</a> </span>';
        
        $return = '<span class="wrapped short_cod hidden dark-text-bevel glow with-border float-left white-bg" style="width: 130px;"><strong>Вставить как: </strong>'.$switch.'</span><span class="wrapped short_cod hidden dark-text-bevel glow with-border float-left white-bg" style="width: 450px;"><strong>Выбрать шорткод: </strong><select class="select shortSelect blue-gradient" style="width: 445px;">'.$selects.'</select></span>'.$clearAll;
        $return .= '<div class="clear-both"></div>';        
        
        $class = $input['options']['class'];
        $return .= form_textarea($input['name'], $input['value'], 'id="'.$input['name'].'" class="input full-width '.$class.'"');        
                        
        $fnc = 'editorFull';                                       
        $editorFunction = $input['options']['editor_function'];
        
        if(!empty($editorFunction))
            { $fnc = $editorFunction; }
        
        $return .= "<a href=\"javascript:void(0);\" onclick=\"{$fnc}('{$input['id']}'); setTimeout(function(){ $(this).refreshTabs(); $('.short_cod').removeClass('hidden'); }, 300); $(this).remove();\" class=\"button white-gradient textarea_text\">".$this->alang->admin_active_editor."</a><script>/*$(document).ready(function(){ $('.textarea_text').click();});*/</script>";      
        
        return $return;        
	}
    
    /** Изображение (image) */
	public function fm_image($input)
	{	   
        $key = $input['name'];
        $class = $input['options']['class'];
        $path = $input['options']['img_path'];

        if(empty($input['value'])) {
            $input['value'] = '/asset/uploads/_thumbs/images/no_image.jpg';
        }

        $return  = '<ul class="gallery">
                    <li>';
        $return .=  form_image($key, $input['value'], 'class="framed cursor-pointer with-tooltip '.$class.'" onclick=BrowseServer(\''.$path.'\',\''.$key.'\'); title="'.$this->alang->admin_change.'" id="'.$key.'"');
        $return .=  "<div class=\"controls\">
    				 <span class=\"button-group compact children-tooltip\">
    					<a href=\"javascript:void(0);\" class=\"button icon-pictures\" onclick=\"BrowseServer('$path/', '$key');\" title=\"{$this->alang->admin_change}\"></a>
    					<a href=\"javascript:void(0);\" data-id=\"$key\" class=\"button icon-trash confirm deleters\" title=\"{$this->alang->admin_delete}\"></a>                        
                     </span>                                     
			         </div>                     
    		       </li>                   
                   </ul>";
        
        return $return;        
	}    
        
	/** Множественный выбор изображений */
	public function fm_multiImage($masFoto)
	{
        $nam = $masFoto['name'];
        $path = $masFoto['options']['path'];

        $return = '<a href="javascript:void(0);" data-path="'.$path.'" class="button margin-right margin-bottom compact" id="add_mt'.$nam.'"><span class="button-icon blue-gradient glossy"><span class="icon-pictures"></span></span>'.$this->alang->admin_add.'</a>';
        $return .= '<div id="'.$nam.'" class="columns">';

        $masFoto = unserialize($masFoto['value']);

        if(isset($masFoto['foto'][0]))
        {
            foreach($masFoto['foto'] as $key=>$val)
            {
               $return .= "
               <div id='{$nam}mt{$key}_li' class=\"block-label fixed-size-columns left-border boxed wrapped align-center no-padding\">
                    <ul class=\"gallery\">
                        <li><img src='$val' class='framed large-margin-left' id='{$nam}mt{$key}' />
                            <input type='hidden' name='{$nam}[]' value='$val' id='input_{$nam}mt{$key}' />
                            <div class=\"controls margin-left\">
            				 <span class=\"button-group compact children-tooltip\">
            					<a href='javascript:void(0);' class='button icon-pictures' onclick='BrowseServer(\"$path/\",\"{$nam}mt{$key}\");' title='{$this->alang->admin_select}'></a>
							<a href='javascript:void(0);' data-id='{$nam}mt{$key}' class='button icon-trash' onclick='deletersLi(\"{$nam}mt{$key}_li\");' title='{$this->alang->admin_delete}'></a>
                             </span>                                     
        			        </div>                     
        		        </li>
                    </ul>
                    <input type='text' name='foto_alt' value='{$masFoto['alt'][$key]}' placeholder='{$this->alang->admin_32}' class='full-width input'/>                       
                </div>";
            }
        }
        
        $return .= '</div>';
        
        return $return;        
	}
    
    /** Поле input (varchar) | Поле с информацией справа */
    function fm_input_info($input)
    {
        $input['class'] = 'input-unstyled '.$input['options']['class'];        
        
        if(!empty($input['valid'])) // подсоединяем валидацию
        { $input['class'] .= ' validate['.$input['valid'].']'; }
        
        if(empty($input['placeholder']))
         { unset($input['placeholder']); }
        
        unset($input['valid']);
        unset($input['label']);
        unset($input['type']);
        unset($input['params']);
        unset($input['info']);        
        unset($input['type']);
        unset($input['options']);
        
        $inp = form_input($input);
        
        $return = '<span class="input">
                    	'.$inp.'
                    	<span class="info-spot">
                    		<span class="icon-info-round"></span>
                    		<span class="info-bubble">
                    			'.$input['info'].'
                    		</span>
                    	</span>
                   </span>';
        
        return $return;        
    }
    
    /** Поле input (varchar) | Поле с иконкой слева */
    function fm_input_icon($input)
    {
        $input['class'] = 'input-unstyled '.$input['options']['class'];        
        $icon = $input['options']['text_icon'];        
        
        if(!empty($input['valid'])) // подсоединяем валидацию
            { $input['class'] .= ' validate['.$input['valid'].']'; }
        
        if(empty($input['placeholder']))
         { unset($input['placeholder']); }
        
        unset($input['valid']);
        unset($input['label']);
        unset($input['type']);
        unset($input['params']);
        unset($input['info']);        
        unset($input['type']);
        unset($input['options']);
        
        $inp = form_input($input);
        
        $return = '<span class="input">
                    	<span class="icon-'.$icon.'"></span>
                    	'.$inp.'
                   </span>';
        
        return $return;        
    }
    
    /** Выборка checkbox | Поле переключатель */
	public function fm_switch($input)
	{

        $class = empty($input['options']['class']) ? 'wide' : $input['options']['class'];
        $input['class'] = 'switch medium blue-active '.$class;
        $text = $input['options']['switch_text'];
        if(empty($text))
            { $text = array('ВКЛ', 'ОТКЛ'); }
        else
            { $text = explode('|', $text); }
        
        if(!empty($input['valid'])) // подсоединяем валидацию
            { $input['class'] .= ' validate['.$input['valid'].']'; }

        $check = '';
        if($class == 'revers')
        {
            $input['class'] = 'switch medium blue-active wide';
            if($input['value'] == 1)
              { $check = 'checked="checked"'; }
        }
        else
        {
            if($input['value'] == 0)
              { $check = 'checked="checked"'; }
        }
        $return = '<input type="checkbox" name="'.$input['name'].'" id="'.$input['id'].'" '.$check.' class="'.$input['class'].'" value="0" data-text-on="'.$text[0].'" data-text-off="'.$text[1].'">';
                
        return $return;        
	}
    
    /** Выборка select (multi-select) | Множественный выпадающий список */
	public function fm_multiSelect($input)
	{
        if(empty($input['params']))
            { $input['params'] = array(''); }
            
        $valid = '';        
        if(!empty($input['valid'])) // подсоединяем валидацию
            { $valid = ' validate['.$input['valid'].']'; }
        
        $return = form_dropdown($input['name'], $input['params'], $input['value'], "class='four-columns {$input['options']['class']} chzn-select {$valid} data-placeholder='{$input['placeholder']}...' multiple");
        
        return $return;        
	} 
    
    /** Выбор множества файлов (multi-files) */
	public function fm_files($masFiles)
	{		              
        //BrowseServerFile
        $path = $masFiles['options']['img_path'];
        
        $return = '<a href="javascript:void(0);" data-path="'.$path.'" class="button margin-right margin-bottom compact" id="add_ft"><span class="button-icon blue-gradient glossy"><span class="icon-pages"></span></span>'.$this->alang->admin_add.'</a>';
        $return .= '<div id="multi_files" class="columns">';
        
        if(!empty($masFiles['value']))
        {
            foreach($masFiles['value'] as $key=>$val)
            {
               $return .= "
               <div id='mt{$key}_li' class=\"block-label fixed-size-columns left-border boxed wrapped align-center no-padding\">						                                                
                    <div class=\"controls\"  style=\"line-height: 100%\">
    				 <span class=\"button-group compact children-tooltip\">
    					<a href='javascript:void(0);' class='button icon-pictures blue-gradient' onclick='BrowseServerFile(\"$path\",\"mt{$key}\");' title='{$this->alang->admin_select}'>{$this->alang->admin_select}</a>
					<a href='javascript:void(0);' data-id='mt{$key}' class='button icon-trash red-gradient' onclick='deletersLi(\"mt{$key}_li\");' title='{$this->alang->admin_delete}'>{$this->alang->admin_delete}</a>                        
                     </span>                                     
			        </div>                             		                        
                    <input type='text' name='input' 'id='input_mt{$key}' placeholder='path file' value='{$val['file']}' class='full-width input'/>                       
                    <input type='text' name='input_alt' 'id='input_alt_mt{$key}' placeholder='name file' value='{$val['alt']}' class='full-width input'/>
                    <input type='text' name='input_size' 'id='input_size_mt{$key}' placeholder='size file' value='{$val['size']}' class='full-width input'/>
                </div>";
            }
        }
        
        $return .= '</div><div class="clear-both"></div>';
        
        return $return;
	}
}
// END CI_Element class

/* End of file Element.php */
/* Location: ./modules/admin/libraries/Element.php */
