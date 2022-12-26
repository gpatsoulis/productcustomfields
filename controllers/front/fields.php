<?php

class ProductCustomFieldsFieldsModuleFrontController extends ModuleFrontController {

    public $product;

    public function initContent(){
        parent::initContent();
        
        $id_product = (int)Tools::getValue('id_product');
        $this->product = new Product((int)$id_product);

        var_dump(  $this->product->name[1] );

        $data = ProductCustomField::getCustomProductTabsByProductID($id_product);
        $this->context->smarty->assign('pcf', $data);



        $this->context->smarty->assign('product', $this->product);
      
        $this->setTemplate('fields.tpl');
    }


}