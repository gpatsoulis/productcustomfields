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
        $prefix = _DB_PREFIX_;
        $query = <<<SQL
        SELECT * 
        FROM `{$prefix}product_custom_fields` 
        WHERE id_product='{$id_product}'; 
        SQL;

        //Executes (executeS) return the result of $sql as array
        return Db::getInstance()->executeS($query);
    }

    public function save($null_values = false, $auto_date = true){

        if(isset($this->id_pcf)){
            return $this->update($null_values);
        }
        return $this->add($auto_date, $null_values);
    }

    public function add($auto_date = true, $null_values = false){

        if ($auto_date && property_exists($this, 'created_at')) {
            $this->created_at = date('Y-m-d H:i:s');
        }

        // Database insertion
        if (!$result = Db::getInstance()->insert($this->def['table'], $this->getFields(), $null_values)) {
            return false;
        }

        // Get object id in database
        $this->id = Db::getInstance()->Insert_ID();

        return $result;
    }

    public function update($null_values = false){
        $this->clearCache();

        // Database update
        if (!$result = Db::getInstance()->update($this->def['table'], $this->getFields(), '`'.pSQL($this->def['primary']).'` = '.(int)$this->id_pcf, 0, $null_values)) {
            return false;
        }

        return $result;
    }


}