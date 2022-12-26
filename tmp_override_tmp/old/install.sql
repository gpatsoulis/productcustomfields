ALTER TABLE `DBNAME_`.`PREFIX_product` ADD COLUMN `product_custom_fields` VARCHAR(4000) default 'test' after `pack_stock_type` ;
ALTER TABLE `DBNAME_`.`PREFIX_product_shop` ADD COLUMN `product_custom_fields` VARCHAR(4000) default 'test' after `pack_stock_type` ;

CONSTRAINT fk_product FOREIGN key (id_product) REFERENCES `PREFIX_product`(id_product) ON DELETE CASCADE