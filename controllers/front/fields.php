<?php

class ProductCustomFieldsFieldsModuleFrontController extends ModuleFrontController {

    public $product;

    public function initContent(){
        parent::initContent();
        
        $id_product = (int)Tools::getValue('id_product');
        $this->product = new Product((int)$id_product);

        var_dump(  $this->product->name[1] );

        $this->context->smarty->assign('product', $this->product);
        $this->setTemplate('fields.tpl');
    }


}