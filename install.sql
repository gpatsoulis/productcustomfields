CREATE TABLE IF NOT EXISTS `PREFIX_product_custom_fields` (
 id_pcf INT AUTO_INCREMENT,
 id_product INT UNSIGNED NOT NULL,
 
 custom_field_a VARCHAR(4000) default '',
 custom_field_b VARCHAR(4000) default '',
 custom_field_c VARCHAR(4000) default '',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

 PRIMARY KEY (id_pcf),
 UNIQUE (id_product),
 CONSTRAINT fk_product FOREIGN key (id_product) REFERENCES `PREFIX_product`(id_product) 
 ON DELETE CASCADE ON UPDATE CASCADE
) 
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;
