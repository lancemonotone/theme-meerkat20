<?php

class M20_BB_Fields_Definitions {
    static public $simplefields = array(
        'random-header',
        'media-credit'
    );
    static public $fields = array(
        'my-custom-field'
    );
     static public function getsimple(){
        return self::$simplefields;
    }
    static public function get(){
        return self::$fields;
    }
}
