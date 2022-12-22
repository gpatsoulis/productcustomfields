<?php

class Product extends ProductCore {

    public $product_custom_fields;

    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, $context = null){
        parent::__construct($id_product , $full , $id_lang , $id_shop , $context );
        
        self::$definition['fields']['product_custom_fields'] = array( 'type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString');
    }

    public function get_def(){
        var_dump( self::$definition['fields']);
    }
}