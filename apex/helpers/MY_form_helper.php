<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Text Input Field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
 
if ( ! function_exists('form_image'))
{
	function form_image($data = '', $value = '', $extra = '')
	{
		$defaults = array(
            'type' => 'hidden',                                                 
            'name' => (( ! is_array($data)) ? $data : ''), 
            'value' => $value
            );
        
        $extra_input = str_replace('id="','id="input_',$extra);
        return "<img src='".$value."' ".$extra." />"."<input "._parse_form_attributes($data, $defaults).$extra_input." />";		
	}
}


// ------------------------------------------------------------------------

/**
 * Textarea field | description
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_description'))
{
	function form_description($data = '', $value = '', $extra = '')
	{
		$defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '40', 'style' => 'height: 55px; padding-bottom: 0; width: 99%; resize: none;');

		if ( ! is_array($data) OR ! isset($data['value']))
		{
			$val = $value;
		}
		else
		{
			$val = $data['value'];
			unset($data['value']); // textareas don't use the value attribute
		}

		$name = (is_array($data)) ? $data['name'] : $data;
		return "<textarea "._parse_form_attributes($data, $defaults).$extra.">".form_prep($val, $name)."</textarea>";
	}
}

/* End of file form_helper.php */
/* Location: ./system/helpers/form_helper.php */
