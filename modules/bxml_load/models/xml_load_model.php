<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Xml_load_model extends MY_Model {
    
    protected $timestamp;
    
    const CLASS_NAME_FIRST_PART = 'xml_load_';
    const CLASS_NAME_SECOND_PART = '_model';
    
    const CAT_FLAT = 'квартира';
    const CAT_HOUSE = 'дом';
    const CAT_COMMERCE = 'коммерческая';
    const TYPE_RENT = 'аренда';
    
    const CAT_FLAT_TYPE = 1;
    const CAT_HOUSE_TYPE = 2;
    const CAT_COMMERCE_TYPE = 4;
    
    
    
    
    public function getLoader ($method) {
        
        $now = new DateTime();
        $this->timestamp = $now->getTimestamp();
        
        $curModelClass = self::CLASS_NAME_FIRST_PART . $method . self::CLASS_NAME_SECOND_PART;    
        include( $curModelClass . '.php' );
        return new $curModelClass();
        
    }
    
    
    /**
     * function xml2array
     *
     * This function is part of the PHP manual.
     *
     * The PHP manual text and comments are covered by the Creative Commons
     * Attribution 3.0 License, copyright (c) the PHP Documentation Group
     *
     * @author  k dot antczak at livedata dot pl
     * @date    2011-04-22 06:08 UTC
     * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
     * @license http://www.php.net/license/index.php#doc-lic
     * @license http://creativecommons.org/licenses/by/3.0/
     * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
     */
    protected function xml2array ( $xmlObject, $out = array () ) {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;

        return $out;
    }
    
}