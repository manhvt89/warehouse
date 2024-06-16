-- ManhVT
-- version 1.0
-- manhvt89@gmail.com
-- Hỗ trợ nhiều kho và nhiều đơn vị tính


DROP TABLE IF EXISTS `ospos_uoms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_uoms` (
  `uom_id` int(10) NOT NULL AUTO_INCREMENT,
  `uom_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `uom_name` varchar(50) DEFAULT NULL,
  `uom_code` varchar(20) DEFAULT NULL,
  `uom_abbreviation` decimal(5,2) NOT NULL DEFAULT 1.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`uom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `ospos_uoms` (`uom_name`, `uom_code`,`uom_abbreviation`, `status`) VALUES
('Kg', 'kg', 1.00, 1),
('Gr', 'gr', 1.00, 1),
('Tạ', 'ta', 1.00, 1),
('Tấn', 'tan', 1.00, 1),
('Mét', 'm', 1.00, 1),
('Cuộn', 'cuộn', 1.00, 1),
('Bao', 'bao', 1.00, 1);

DROP TABLE IF EXISTS `ospos_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_items` (
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `reorder_level` decimal(15,3) NOT NULL DEFAULT 0.000,
  `receiving_quantity` decimal(15,3) NOT NULL DEFAULT 1.000,
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `pic_id` int(10) DEFAULT NULL,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `custom1` varchar(25) NOT NULL,
  `custom2` varchar(25) NOT NULL,
  `custom3` varchar(25) NOT NULL,
  `custom4` varchar(25) NOT NULL,
  `custom5` varchar(25) NOT NULL,
  `custom6` varchar(25) NOT NULL,
  `custom7` varchar(25) NOT NULL,
  `custom8` varchar(25) NOT NULL,
  `custom9` varchar(25) NOT NULL,
  `custom10` varchar(25) NOT NULL,
  `standard_amount` decimal(15,3) NOT NULL DEFAULT 0.000,
  `item_number_new` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: mới tạo; 1 đã đồng bộ; 3 edited; ',
  `item_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `code` varchar(50) DEFAULT NULL,
  `category_code` varchar(50) NOT NULL DEFAULT '',
  `ref_item_id` varchar(50) NOT NULL DEFAULT '',
  `updated_time` int(11) NOT NULL DEFAULT 0,
  `created_time` int(11) NOT NULL DEFAULT 0,
  `normal_name` varchar(255) NOT NULL DEFAULT '',
  `short_name` varchar(255) NOT NULL DEFAULT '',
  `dpc_name` varchar(255) NOT NULL DEFAULT '',
  `encode` varchar(50) NOT NULL DEFAULT '',
  `group` varchar(50) NOT NULL DEFAULT '',
  `group_category` varchar(50) NOT NULL DEFAULT '',
  `cas_no` varchar(50) NOT NULL DEFAULT '',
  `kind` varchar(50) NOT NULL DEFAULT 'Tổng Kho',
  `country` varchar(50) NOT NULL DEFAULT 'VN',
  `brand` varchar(50) NOT NULL DEFAULT '',
  `manufactory` varchar(50) NOT NULL DEFAULT '',
  `ms` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `item_group` varchar(100) NOT NULL DEFAULT '',
  `catalogue_no` varchar(100) NOT NULL DEFAULT '',
  `purchase_uom_name` varchar(50) NOT NULL DEFAULT '',
  `purchase_uom_code` varchar(50) NOT NULL DEFAULT '',
  `purchase_item_per_purchase_unit` decimal(5,2) NOT NULL DEFAULT 1.00,
  `purchase_packing_uom_name` varchar(50) NOT NULL DEFAULT '',
  `purchase_quality_per_packge` decimal(5,2) NOT NULL DEFAULT 1.00,
  `purchase_packing_length` decimal(5,2) NOT NULL DEFAULT 1.00,
  `purchase_packing_height` decimal(5,2) NOT NULL DEFAULT 1.00,
  `purchase_packing_width` decimal(5,2) NOT NULL DEFAULT 1.00,
  `purchase_packing_volume` decimal(5,2) NOT NULL DEFAULT 1.00,
  `purchase_packing_weigth` decimal(5,2) NOT NULL DEFAULT 1.00,
  `sale_uom_name` varchar(50) NOT NULL DEFAULT '',
  `sale_uom_code` varchar(50) NOT NULL DEFAULT '',
  `sale_item_per_sale_unit` decimal(5,2) NOT NULL DEFAULT 1.00,
  `sale_quality_per_packge` decimal(5,2) NOT NULL DEFAULT 1.00,
  `sale_packing_length` decimal(5,2) NOT NULL DEFAULT 1.00,
  `sale_packing_height` decimal(5,2) NOT NULL DEFAULT 1.00,
  `sale_packing_width` decimal(5,2) NOT NULL DEFAULT 1.00,
  `sale_packing_volume` decimal(5,2) NOT NULL DEFAULT 1.00,
  `sale_packing_weigth` decimal(5,2) NOT NULL DEFAULT 1.00,
  `set_default_warehouse_id` int(11) NOT NULL DEFAULT 0,
  `inventory_uom_name` varchar(50) NOT NULL DEFAULT '',
  `inventory_uom_code` varchar(50) NOT NULL DEFAULT '',
  `inventory_weigth_per_unit` decimal(5,2) NOT NULL DEFAULT 1.00,
  `inventory_location` varchar(50) NOT NULL DEFAULT '',
  `uom_group_id` int(11) NOT NULL DEFAULT 0,
  `serial` varchar(25) NOT NULL DEFAULT '',
  `part_no` varchar(25) NOT NULL DEFAULT '',
  `leadtime` varchar(10) NOT NULL DEFAULT ''
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `supplier_id` (`supplier_id`),
  KEY `unit_cost` (`unit_price`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `ospos_supplier_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_supplier_items` (
  `supplier_product_id` int(10) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `uom_name` varchar(50) DEFAULT NULL,
  `uom_code` varchar(20) DEFAULT NULL,
  `quantity_received` decimal(5,2) NOT NULL DEFAULT 1.00,
  `date_received` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`supplier_product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `ospos_item_quantities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_item_quantities` (
  `item_quantities_id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `inventory_uom_name` varchar(50) DEFAULT NULL,
  `inventory_uom_code` varchar(20) DEFAULT NULL,
  `quantity` decimal(15,2) NOT NULL DEFAULT 1.00,
  `item_location` varchar(50) DEFAULT NULL,
  `cost_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`item_quantities_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


ALTER TABLE `ospos_employees` ADD `log` varchar(10) NOT NULL DEFAULT '0' AFTER `type`;
ALTER TABLE `ospos_employees` ADD `code` varchar(20) NOT NULL DEFAULT '' AFTER `type`;
ALTER TABLE `ospos_employees` ADD `token` varchar(20) NOT NULL DEFAULT md5(rand()) AFTER `type`; -- chưa đc
ALTER TABLE `ospos_employees` ADD `position` varchar(20) NOT NULL DEFAULT '' AFTER `type`;
ALTER TABLE `ospos_employees` ADD `date_started` varchar(20) NOT NULL DEFAULT '' AFTER `type`;
ALTER TABLE `ospos_employees` ADD `comission_rate` decimal(5,2) NOT NULL DEFAULT 0 AFTER `log`;
ALTER TABLE `ospos_employees` ADD `total_sale` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `log`;
ALTER TABLE `stock_locations` MODIFY `location_code` varchar(50) NOT NULL DEFAULT '';


DROP TABLE IF EXISTS `ospos_machines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_machines` (
  `machine_id` int(10) NOT NULL AUTO_INCREMENT,
  `machine_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `machine_name` varchar(50) DEFAULT '',
  `machine_code` varchar(50) DEFAULT '',
  `code` varchar(50) DEFAULT '',
  `manufactory` varchar(20) DEFAULT '',
  `seria` varchar(50) DEFAULT '',
  `created_date` int(11) DEFAULT 0,
  `used_date` int(11) DEFAULT 0,
  `position` varchar(50) DEFAULT '',
  `status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`machine_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `ospos_machines` (`machine_name`, `machine_code`,`code`, `manufactory`,`seria`, `created_date`,`used_date`, `position`,`status`) VALUES
('Máy ép nhựa', 'EN-120-1B-01', '01', 'ENAIVIV-Taiwan','120364',0,0,'Tổ ép nhựa',0),
('Máy ép cao su', 'EN-120-1B-01', '01', 'ENAIVIV-Taiwan','120364',0,0,'Tổ ép cao su',0),

DROP TABLE IF EXISTS `ospos_production_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_production_orders` (
  `producttion_order_no` varchar(25) NOT NULL,
  `producttion_order_id` int(10) NOT NULL AUTO_INCREMENT,
  `producttion_order_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `customer_code` varchar(50) NOT NULL DEFAULT '',
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(250) NOT NULL DEFAULT '',
  `product_code` varchar(15) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL DEFAULT '',
  `product_part_no` varchar(55) NOT NULL DEFAULT '',
  `requested_date` int(11) NOT NULL DEFAULT 0,
  `machine_name` varchar(255) NOT NULL DEFAULT '',
  `machine_code` varchar(15) DEFAULT NULL,
  `quantity` decimal(15,3) NOT NULL DEFAULT 1.000,
  `product_uom_name` varchar(50) NOT NULL DEFAULT '',
  `product_uom_code` varchar(50) NOT NULL DEFAULT '',
  `start_time` int(11) NOT NULL DEFAULT 0,
  `total_working_hours` int(11) NOT NULL DEFAULT 0,
  `shift_work_mode` varchar(15) NOT NULL DEFAULT '', 
  `material_name` varchar(150) NOT NULL DEFAULT '',
  `ms` varchar(50) NOT NULL DEFAULT '',
  `recipe_id` int(11) DEFAULT 0,
  `weight_of_piece` decimal(15,3) NOT NULL DEFAULT 0.000,
  `batch_number` int(11) DEFAULT 0,
  `current_inventory` decimal(15,3) NOT NULL DEFAULT 0.000,
  `usage_weight` decimal(15,3) NOT NULL DEFAULT 0.000,
  `batch_weight` decimal(15,3) NOT NULL DEFAULT 0.000,
  `product_volume` decimal(15,3) NOT NULL DEFAULT 0.000,
  `ending_inventory` decimal(15,3) NOT NULL DEFAULT 0.000,
  `actual_inventory` decimal(15,3) NOT NULL DEFAULT 0.000,
  `data` text NOT NULL DEFAULT '',
  PRIMARY KEY (`producttion_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `ospos_recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_recipes` (
  `recipe_id` int(10) NOT NULL AUTO_INCREMENT,
  `recipe_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `name` varchar(250) NOT NULL DEFAULT '',
  `master_batch` varchar(55) NOT NULL DEFAULT '',
  `date_issued` int(11) NOT NULL DEFAULT 0,
  `grade_of_standard` varchar(50) NOT NULL DEFAULT '',
  `str_date_issued` varchar(15) NOT NULL DEFAULT '',
  `certificate_no` varchar(50) DEFAULT NULL,
  `certificate_attack` varchar(250) NOT NULL DEFAULT '', 
  `kneader_a` varchar(50) DEFAULT NULL,
  `processing_time_a` int(11) NOT NULL DEFAULT 0,
  `weight_a` decimal(15,2) NOT NULL DEFAULT 75.00,

  `kneader_b` varchar(50) DEFAULT NULL,
  `processing_time_b` int(11) NOT NULL DEFAULT 0,
  `weight_b` decimal(15,2) NOT NULL DEFAULT 25.54,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `ospos_item_recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_item_recipes` (
  `item_recipe_id` int(10) NOT NULL AUTO_INCREMENT,
  `item_recipe_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `recipe_id` int(11) NOT NULL DEFAULT 0,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `item_group` varchar(10) NOT NULL DEFAULT '',
  `item_mix` varchar(10) NOT NULL DEFAULT '',
  `uom_code` varchar(10) NOT NULL DEFAULT '',
  `uom_name` varchar(10) NOT NULL DEFAULT '',
  `weight` decimal(15,3) NOT NULL DEFAULT 0.00,
  `tolerace` decimal(15,3) NOT NULL DEFAULT 0.00,
  `type` varchar(1) NOT NULL DEFAULT '',

  PRIMARY KEY (`item_recipe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `ospos_compounda_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_compounda_orders` (
  `compounda_order_id` int(10) NOT NULL AUTO_INCREMENT,
  `compounda_order_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `compounda_order_no` varchar(15) NOT NULL DEFAULT '',
  `created_at` int(11) NOT NULL DEFAULT 0,
  `creator_id` int(11) NOT NULL DEFAULT 0,
  `creator_name` varchar(150) NOT NULL DEFAULT '',
  `creator_account` varchar(50) NOT NULL DEFAULT '',
  `order_date` int(11) NOT NULL DEFAULT 0,
  `use_date` int(11) NOT NULL DEFAULT 0,
  `completed_at` int(11) NOT NULL DEFAULT 0,
  `start_at` int(11) NOT NULL DEFAULT 0,
  `suppervisor_id` int(11) NOT NULL DEFAULT 0,
  `suppervisor_name` varchar(150) NOT NULL DEFAULT '',
  `suppervisor_account` varchar(50) NOT NULL DEFAULT '',
  `area_make_order` varchar(50) DEFAULT NULL, 
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`compounda_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



DROP TABLE IF EXISTS `ospos_compounda_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_compounda_order_item` (
  `compounda_order_item_id` int(10) NOT NULL AUTO_INCREMENT,
  `compounda_order_item_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `compounda_order_id` int(11) NOT NULL DEFAULT 0,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `item_name` varchar(150) NOT NULL DEFAULT '',
  `uom_code` varchar(10) NOT NULL DEFAULT '',
  `uom_name` varchar(10) NOT NULL DEFAULT '',
  `ms` varchar(50) DEFAULT '', 
  `quantity_batch` decimal(15,3) NOT NULL DEFAULT 0.00,
  `quantity_schedule` decimal(15,3) NOT NULL DEFAULT 0.00,
  `quantity_use` decimal(15,3) NOT NULL DEFAULT 0.00,
  `quantity_completed` decimal(15,3) NOT NULL DEFAULT 0.00,
  `start_at` int(11) NOT NULL DEFAULT 0,
  `created_at` int(11) NOT NULL DEFAULT 0,
  `note` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`compounda_order_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `ospos_compounda_order_item_completed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_compounda_order_item_completed` (
  `compounda_order_item_completed_id` int(10) NOT NULL AUTO_INCREMENT,
  `compounda_order_item_completed_uuid` varchar(36) NOT NULL DEFAULT uuid(),
  `compounda_order_item_id` int(11) NOT NULL DEFAULT 0,
  `created_at` int(11) NOT NULL DEFAULT 0,
  `item_name` varchar(150) NOT NULL DEFAULT '',
  `ms` varchar(50) NOT NULL DEFAULT '',
  `uom_code` varchar(10) NOT NULL DEFAULT '',
  `uom_name` varchar(10) NOT NULL DEFAULT '',
  `creator_id` int(11) NOT NULL DEFAULT 0,
  `creator_name` varchar(150) NOT NULL DEFAULT '',
  `quantity_completed` decimal(15,3) NOT NULL DEFAULT 0.00,
  `completed_at` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`compounda_order_item_completed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
















