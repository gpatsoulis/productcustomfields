<?php

class ProductCustomField extends ObjectModel
{
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
            'custom_field_a' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'custom_field_b' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'custom_field_c' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'created_at' => array('type' => self::TYPE_DATE),
        ),
    );

    public static function getProductCustomFieldsByProductID($id_product): ProductCustomField
    {
        $prefix = _DB_PREFIX_;

        $query = <<<SQL
        SELECT `id_pcf`
        FROM `{$prefix}product_custom_fields`
        WHERE id_product='{$id_product}';
        SQL;

        return new ProductCustomField(Db::getInstance()->getValue($query));
    }

    public function save($null_values = false, $auto_date = true): bool
    {

        if (isset($this->id_pcf)) {
            return $this->update($null_values);
        }
        return $this->add($auto_date, $null_values);
    }

    public function add($auto_date = true, $null_values = false): bool
    {

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

    public function update($null_values = false): bool
    {
        $this->clearCache();

        // Automatically fill dates
        if (property_exists($this, 'created_at') && $this->created_at == null) {
            $this->created_at = date('Y-m-d H:i:s');
            if (isset($this->update_fields) && is_array($this->update_fields) && count($this->update_fields)) {
                $this->update_fields['created_at'] = true;
            }
        } else {
            $this->update_fields['created_at'] = false;
        }

        // Database update
        if (!$result = Db::getInstance()->update($this->def['table'], $this->getFields(), '`' . pSQL($this->def['primary']) . '` = ' . (int) $this->id_pcf, 0, $null_values)) {
            return false;
        }

        return $result;
    }

}
