<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/classes/ProductCustomField.php';
require_once __DIR__ . '/controllers/admin/AdminProductCustomFieldsController.php';

class ProductCustomFields extends Module
{

    const INSTALL_SQL_FILE = 'install.sql';
    const UNINSTALL_SQL_FILE = 'uninstall.sql';

    protected $hooks = array();

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

    public function install():bool
    {

        if (!parent::install() ) {
            return false;
        }

        if (!$this->run_sql_queries(self::INSTALL_SQL_FILE)) {
            return false;
        }

        $this->setHooks();
        if(!$this->registerHooks($this->hooks)){
            return false;
        }
       
        return true;
    }

    public function uninstall():bool
    {

        if (!parent::uninstall()) {
            return false;
        }

        if (!$this->run_sql_queries(self::UNINSTALL_SQL_FILE)) {
            return false;
        }

        if(!$this->unregisterHooks($this->hooks)){
            return false;
        }

        return true;
    }

    public function registerHooks(array $hooks):bool
    {
        
        foreach ($hooks as $hook) {
            if(!$this->registerHook($hook)){
                return false;
            } 
        }

        return true;
    }

    public function unregisterHooks(array $hooks):bool
    {
        foreach ($hooks as $hook) {
            if(!$this->unregisterHook($hook)) return false;
        }

        return true;
    }

    public function setHooks():array
    {
        $this->hooks = array(
            'displayAdminProductsExtra',
            'actionProductUpdate',
            'displayProductTabContent',
        );

        return $this->hooks;
    }

    public function HookActionProductUpdate($params){
        
            
        $id_product = (int)Tools::getValue('id_product');
        $data = $this->getProductCustomFieldsByProductID($id_product);

        $id_pcf = null;
        if( isset($data[0]) &&  !empty($data[0]) ){
            $id_pcf = $data[0]['id_pcf'];
        }

        var_dump($id_pcf);

        $field_a = Tools::getValue('product_custom_field_a');
        $field_b = Tools::getValue('product_custom_field_b');
        $field_c = Tools::getValue('product_custom_field_c');

        

        try{
            $this->saveProductCustomFields( $id_product, $field_a, $field_b, $field_c, $id_pcf);
        } catch( PrestaShopDatabaseException $ex ){
            $ex->displayMessage();
            //echo ($ex->getMessage());
            $this->context->smarty->assign('pcf_add_new_field', 'error');
        }

    }

    //display content in fronend
    public function hookDisplayProductTabContent($params):string
    {
        
        $id_product = (int)Tools::getValue('id_product');
        $this->product = new Product((int)$id_product);

        var_dump(  $this->product->name[1] );

        $this->context->smarty->assign('product', $this->product);
        
        return $this->display(__FILE__, 'fields.tpl');
        //return 'hello';
    }

    public function hookDisplayAdminProductsExtra($params){

        $id_product = (int)Tools::getValue('id_product');
       
        
        $data = $this->getProductCustomFieldsByProductID($id_product);        

        $this->context->smarty->assign('pcf', $data[0]);
        return $this->display(__FILE__, 'productcustomfields.tpl');
    }

    public function adminFormSubmited(){

    }

    
    public function saveProductCustomFields(int $id_product,string $field_a,string $field_b,string $field_c,$id_pcf = null){

        $pcf = new ProductCustomField();

        $pcf->id_pcf = $id_pcf;
        if(!$id_pcf){
            $pcf->id_product = $id_product;
        }
        
        
        $pcf->custom_field_a = $field_a; 
        $pcf->custom_field_b = $field_b; 
        $pcf->custom_field_c = $field_c;
        
        $pcf->save();
    }

    /*
    public function hookActionProductUpdate($params){
        $product = $params['product'];
        //var_dump(Validate::isLoadedObject($product));
        if(Validate::isLoadedObject($product)){
            //$product->save();
        }
        
    }

    public function hookDisplayAdminProductsExtra($params)
    {

      
        //var_dump($params);
       
        $product = new Product((int) Tools::getValue('id_product'));

        var_dump($params);exit;
        $pcf = CustomFields::getCustomProductTabsByProductID( $product->reference );
        if(!$pcf){
            $pcf = array();
        }

        //$product->get_def();
        //var_dump($product->product_custom_fields);
        //var_dump($product->name);
        //var_dump(Hook::getHookModuleExecList('displayAdminProductsExtra'));

        if (Validate::isLoadedObject($product)) {
            $this->context->smarty->assign(array('custom_fields' => $pcf));
            return $this->display(__FILE__, 'productcustomfields.tpl');
        }
    }

    public function hookDisplayProductTabContent($param){
        return 'Hello from displayProductTabContent Hook';
    }
*/
    public function getProductCustomFieldsByProductID( $id_product ){

        $prefix = _DB_PREFIX_;
        $query = <<<SQL
        SELECT * 
        FROM `{$prefix}product_custom_fields` 
        WHERE id_product='{$id_product}'; 
        SQL;

        return Db::getInstance()->executeS($query);

    }

    public function run_sql_queries(string $path = self::INSTALL_SQL_FILE):bool
    {

        if (!file_exists(dirname(__FILE__) . '/' . $path)) {
            return false;
        } else if (!$sql = file_get_contents(dirname(__FILE__) . '/' . $path)) {
            return false;
        }

        //_MYSQL_ENGINE_
        $sql = str_replace(array('PREFIX_', 'DBNAME_','MYSQL_ENGINE'), array(_DB_PREFIX_, _DB_NAME_,_MYSQL_ENGINE_), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", $sql);

        file_put_contents( __DIR__ . '/test.sql',json_encode($sql));

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

    /*
    public function getContent(){
        return 'hello from Module';
    }*/

}


