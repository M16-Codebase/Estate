<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (function_exists('js_hook') === false) {
    function js_hook() {
        $ci = &get_instance();

        $return = '';

        if (isset($ci->config->config['js']) === true) {
            foreach ($ci->config->config['js'] as $js => $params) {
                if (isset($params['language']) === true) {
                    $language = $params['language'];
                } else {
                    $language = 'JavaScript';
                }

                if (isset($params['type']) === true) {
                    $type = $params['type'];
                } else {
                    $type = 'text/javascript';
                }

                $return .= '<' . 'script language="' . $language . '" type="' . $type .
                    '" src="' . $js . '">' . '<' . '/' . 'script' . '>' . "\n";

                unset($ci->config->config['js'][$js]);
            }

            if (count($ci->config->config['js']) == 0) {
                unset($ci->config->config['js']);
            }
        }
        
        $ci->output->set_output(str_replace('[JS]', $return, $ci->output->get_output()));
    }
}
