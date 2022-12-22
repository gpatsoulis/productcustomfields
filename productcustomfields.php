<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductCustomFields extends Module
{

    const INSTALL_SQL_FILE = 'install.sql';
    const UNINSTALL_SQL_FILE = 'uninstall.sql';

    public function __construct()
    {

        $this->name = 'productcustomfields';
        $this->tab = 'front_office_features';
        $this->version = '1.0a';
        $this->author = 'George Patsoulis';
        $this->need_instance = 0;
        $this->ps_version_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        //parent's constructo - after the creation of $this->name and before any use of the $this->l() method
        parent::__construct();

        $this->displayName = $this->l('Product Custom Fields');
        $this->description = $this->l('Add 3 new text custom fields in product tab');
    }

    public function install()
    {

        if (!$this->run_sql_queries(self::INSTALL_SQL_FILE)) {
            return false;
        }

        if (!parent::install() ) {
            return false;
        }

        if ( !$this->registerHook('displayAdminProductsExtra')  ) {
            return false;
        }

        if ( !$this->registerHook('actionProductUpdate')  ) {
            return false;
        }
       
        return true;
    }

    public function uninstall()
    {

        if (!$this->run_sql_queries(self::UNINSTALL_SQL_FILE)) {
            return false;
        }

        if (!parent::uninstall()) {
            return false;
        }

        if ( !$this->unregisterHook('displayAdminProductsExtra')  ) {
            return false;
        }

        if ( !$this->unregisterHook('actionProductUpdate')  ) {
            return false;
        }

        return true;
    }

    public function hookActionProductUpdate($params){
        $product = $params['product'];
        //var_dump(Validate::isLoadedObject($product));
        if(Validate::isLoadedObject($product)){
            //$product->save();
        }
        
    }

    public function hookDisplayAdminProductsExtra($params)
    {

      
        //var_dump($_GET);
       
        $product = new Product((int) Tools::getValue('id_product'));
        //$product->get_def();
        //var_dump($product->product_custom_fields);
        //var_dump($product->name);
        //var_dump(Hook::getHookModuleExecList('displayAdminProductsExtra'));

        if (Validate::isLoadedObject($product)) {
            $this->context->smarty->assign(array('custom_filds' => $product->product_custom_fields));
            return $this->display(__FILE__, 'productcustomfields.tpl');
        }
    }

    public function getProductCustomFieldsByProductID( $id_product ){

        $query = <<<SQL
        SELECT custom_field_a,custom_field_a,custom_field_a 
        FROM `{_DB_PREFIX_}product_custom_fields` 
        WHERE id_product='{$id_product}'; 
        SQL;

        return Db::getInstance()->executeS($query);

    }

    public function run_sql_queries($path = self::INSTALL_SQL_FILE)
    {

        if (!file_exists(dirname(__FILE__) . '/' . $path)) {
            return false;
        } else if (!$sql = file_get_contents(dirname(__FILE__) . '/' . $path)) {
            return false;
        }

        $sql = str_replace(array('PREFIX_', 'DBNAME_'), array(_DB_PREFIX_, _DB_NAME_), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", $sql);

        foreach ($sql as $query) {
            if ($query) {
                try {
                    Db::getInstance()->execute(trim($query));
                } catch (PrestaShopDatabaseException $ex) {

                    $ex->displayMessage();
                    return false;
                }
            }
        }

        return true;
    }

}


