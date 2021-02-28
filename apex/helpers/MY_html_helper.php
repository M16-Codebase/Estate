<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Generates paragraph
 *
 * @access	public
 * @param	integer
 * @return	string
 */
if ( ! function_exists('p'))
{
	function p($text, $extra = '')
	{
		return "<p $extra >$text</p>";
	}
}


/* End of file helper.php */