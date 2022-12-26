<?php


class ProductCustomField extends ObjectModel {
    public $id_pcf;
    public $id_product;
    public $custom_field_a;
    public $custom_field_b;
    public $custom_field_c;
    public $created_at;
    
    public static $definition = array(
        'table' => 'product_custom_fields',
        'primary' => 'id_pcf',
        'multilang' => false,

        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT),
            'custom_field_a' => array( 'type' => self::TYPE_STRING, 'validate' => 'isString'),
            'custom_field_b' => array( 'type' => self::TYPE_STRING, 'validate' => 'isString'),
            'custom_field_c' => array( 'type' => self::TYPE_STRING, 'validate' => 'isString'),
            'created_at' => array('type' => self::TYPE_DATE),
        )
    );

    public function __construct($id = null){
        Shop::addTableAssociation(self::$definition['table'], array('type' => 'shop'));
        parent::__construct($id);
    }

    public function setCustomFieldsFromPost($data){
        foreach( self::$definition[$fields] as $fieldName => $fieldOption ){
            $this->$fieldName = $data[$fieldName];
        }
    }

    public static function getCustomProductTabsByProductID( $id_product ){
        $query = <<<SQL
            SELECT * FROM `{_DB_PREFIX_}product_custom_fields` WHERE id_product = '{$id_product}';
        SQL;

        //Executes (executeS) return the result of $sql as array
        return Db::getInstance()->executeS($query);
    }

}