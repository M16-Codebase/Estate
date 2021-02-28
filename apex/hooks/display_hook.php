<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (function_exists('display_hook') === false) {
    function display_hook() {
        $ci = &get_instance();

        $ci->output->_display();
    }
}
