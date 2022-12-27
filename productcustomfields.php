<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/classes/ProductCustomField.php';

class ProductCustomFields extends Module
{

    const INSTALL_SQL_FILE = 'sql/install.sql';
    const UNINSTALL_SQL_FILE = 'sql/uninstall.sql';

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

        //parent's constructor - after the creation of $this->name and before any use of the $this->l() method
        parent::__construct();

        $this->displayName = $this->l('Product Custom Fields');
        $this->description = $this->l('Add 3 new text custom fields in product tab');
    }

    public function install(): bool
    {

        if (!parent::install()) {
            return false;
        }

        if (!$this->run_sql_queries(self::INSTALL_SQL_FILE)) {
            return false;
        }

        $this->setHooks();
        if (!$this->registerHooks($this->hooks)) {
            return false;
        }

        return true;
    }

    public function uninstall(): bool
    {

        if (!parent::uninstall()) {
            return false;
        }

        if (!$this->run_sql_queries(self::UNINSTALL_SQL_FILE)) {
            return false;
        }

        if (!$this->unregisterHooks($this->hooks)) {
            return false;
        }

        return true;
    }

    public function registerHooks(array $hooks): bool
    {

        foreach ($hooks as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }

        return true;
    }

    public function unregisterHooks(array $hooks): bool
    {
        foreach ($hooks as $hook) {
            if (!$this->unregisterHook($hook)) {
                return false;
            }

        }

        return true;
    }

    public function setHooks(): array
    {
        $this->hooks = array(
            'displayAdminProductsExtra',
            'actionProductUpdate',
            'displayProductTabContent',
        );

        return $this->hooks;
    }

    public function HookActionProductUpdate($params): void
    {

        $id_product = (int) Tools::getValue('id_product');

        if (!$id_product) {
            return;
        }

        $pcf = ProductCustomField::getProductCustomFieldsByProductID($id_product);

        if (!$pcf->id_product) {
            $pcf->id_product = $id_product;
        }

        $pcf->custom_field_a = Tools::getValue('product_custom_field_a');
        $pcf->custom_field_b = Tools::getValue('product_custom_field_b');
        $pcf->custom_field_c = Tools::getValue('product_custom_field_c');

        try {
            $pcf->save();
        } catch (PrestaShopDatabaseException $ex) {
            $ex->displayMessage();
            $this->context->smarty->assign('pcf_add_new_field', 'error');
        }

    }

    //display content in fronend
    public function hookDisplayProductTabContent($params): string
    {

        $id_product = (int) Tools::getValue('id_product');
        $pcf = ProductCustomField::getProductCustomFieldsByProductID($id_product);

        if ($pcf->id_pcf) {
            $this->context->smarty->assign('pcf', $pcf);
            $this->context->controller->addCSS($this->_path . 'views/css/productcustomfields.css');
        }

        $this->context->smarty->assign('device', $this->getDevice());

        return $this->display(__FILE__, 'fields.tpl');
    }

    //display tab in admin product
    public function hookDisplayAdminProductsExtra($params): string
    {

        $id_product = (int) Tools::getValue('id_product');
        if (!$id_product) {
            return 'Please select a valid product!';
        }

        $pcf = ProductCustomField::getProductCustomFieldsByProductID($id_product);

        $this->context->smarty->assign('pcf', $pcf);

        return $this->display(__FILE__, 'views/templates/admin/productcustomfields.tpl');
    }

    public function getDevice(): string
    {
        switch ($this->context->getDevice()) {
            case 1:
                $device = 'computer';
                break;
            case 2:
                $device = 'tablet';
                break;
            case 4:
                $device = 'mobile';
                break;
            default:
                $device = 'computer';
        }

        return $device;
    }

    public function run_sql_queries(string $path = self::INSTALL_SQL_FILE): bool
    {

        if (!file_exists(dirname(__FILE__) . '/' . $path)) {
            return false;
        } else if (!$sql = file_get_contents(dirname(__FILE__) . '/' . $path)) {
            return false;
        }

        //_MYSQL_ENGINE_
        $sql = str_replace(array('PREFIX_', 'DBNAME_', 'MYSQL_ENGINE'), array(_DB_PREFIX_, _DB_NAME_, _MYSQL_ENGINE_), $sql);
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
