ALTER TABLE `ospos_test` ADD `reason` text CHARACTER SET utf8 NOT NULL AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `step` tinyint(1) DEFAULT 2 COMMENT '1: Tiếp; 2: đang khám; 3: khám xong;' AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `updated_at` int(11) DEFAULT 0 AFTER `test_uuid`; 

ALTER TABLE `ospos_test` ADD `r_va_lo` text CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `l_va_lo` text CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `duration_dvt` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`;

ALTER TABLE `ospos_sales` ADD `sync` tinyint(1) DEFAULT 0 COMMENT '{0: moi tao; 1: Đã sync vào bảng history_ctv}' AFTER `updated_at`;

ALTER TABLE `ospos_reminders` ADD `address` VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_id`; 
ALTER TABLE `ospos_reminders` ADD `reminder_uuid` VARCHAR(36) DEFAULT UUID() AFTER `test_id`; 
ALTER TABLE `ospos_test` ADD `duration_dvt` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`;
ALTER TABLE `ospos_reminders` ADD `duration_dvt` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_id`;
ALTER TABLE `ospos_reminders` ADD `duration` int(2) DEFAULT 6 AFTER `test_id`;
ALTER TABLE `ospos_employees` ADD `comission_rate` decimal(5,2) NOT NULL DEFAULT 0 AFTER `log`;
ALTER TABLE `ospos_employees` ADD `total_sale` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `log`;

DROP TABLE IF EXISTS `ospos_history_ctv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_history_ctv` (
  `history_ctv_id` int(11) NOT NULL AUTO_INCREMENT,
  `ctv_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `ctv_name` varchar(255) DEFAULT NULL,
  `ctv_code` varchar(255) DEFAULT NULL,
  `ctv_phone` varchar(255) DEFAULT NULL,
  `sale_code` varchar(50) DEFAULT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `created_time` int(11) DEFAULT current_timestamp(),
  `payment_time` int(11) DEFAULT current_timestamp(),
  `payment_amount` decimal(15,2) NOT NULL,
  `comission_amount` decimal(15,2) NOT NULL,
  `comission_rate` decimal(5,2) NOT NULL,
  `history_ctv_uuid` varchar(50) NOT NULL DEFAULT uuid(),
  `status` tinyint(1) DEFAULT 0 COMMENT '{0: moi tao; 1: Yeu cau thanh toan, 2: phê duyệt; 3: đã thanh toán }',
  PRIMARY KEY (`history_ctv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `ospos_roles` VALUES (8,'CTV','CTV','CTV','7b498149-5877-1117-a953-040300000000',0,0,0,0,'1');
ALTER TABLE `ospos_purchases` ADD `v` tinyint(3) NOT NULL DEFAULT 0 AFTER `edited_time`;

ALTER TABLE `ospos_sales` ADD `completed_at` int(11) NOT NULL DEFAULT 0 AFTER `sync`;
ALTER TABLE `ospos_items` ADD `created_time` int(11) NOT NULL DEFAULT 0 AFTER `ref_item_id`;
ALTER TABLE `ospos_items` ADD `updated_time` int(11) NOT NULL DEFAULT 0 AFTER `ref_item_id`;

ALTER TABLE `ospos_items` ADD `synched_time` int(11) NOT NULL DEFAULT 0 AFTER `ref_item_id`;

DROP TABLE IF EXISTS `ospos_history_ctv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_history_ctv` (
  `history_ctv_id` int(11) NOT NULL AUTO_INCREMENT,
  `ctv_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `ctv_name` varchar(255) DEFAULT NULL,
  `ctv_code` varchar(255) DEFAULT NULL,
  `ctv_phone` varchar(255) DEFAULT NULL,
  `sale_code` varchar(50) DEFAULT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `created_time` int(11) DEFAULT current_timestamp(),
  `payment_time` int(11) DEFAULT current_timestamp(),
  `payment_amount` decimal(15,2) NOT NULL,
  `comission_amount` decimal(15,2) NOT NULL,
  `comission_rate` decimal(5,2) NOT NULL,
  `history_ctv_uuid` varchar(50) NOT NULL DEFAULT uuid(),
  `status` tinyint(1) DEFAULT 0 COMMENT '{0: moi tao; 1: Yeu cau thanh toan, 2: phê duyệt; 3: đã thanh toán }',
  PRIMARY KEY (`history_ctv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ospos_history_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_history_reminder` (
  `history_reminder_id` int(11) NOT NULL AUTO_INCREMENT,
  `employeer_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `reminder_id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT '''',
  `status` tinyint(1) DEFAULT 0 COMMENT '{0: chua lien lac duoc; 1: sai so dien thoai; 2: da hen; 3: chua sap xep dc}',
  `created_time` int(11) DEFAULT current_timestamp(),
  `history_reminder_uuid` varchar(50) NOT NULL DEFAULT uuid(),
  PRIMARY KEY (`history_reminder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1267 DEFAULT CHARSET=utf8;


ALTER TABLE `ospos_receivings` ADD `mode` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: Nhập hàng; 1: Trả hàng' AFTER `reference`; 

ALTER TABLE `ospos_receivings` ADD `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '{0: Đã thanh toán xong; 1: Chưa hoàn thành}' AFTER `mode`;
ALTER TABLE `ospos_receivings` ADD `code` varchar(14) NOT NULL DEFAULT 0 COMMENT '{Mã phiếu nhập hàng}' AFTER `mode`;
ALTER TABLE `ospos_receivings` ADD `receiving_uuid` varchar(50) NOT NULL DEFAULT uuid() COMMENT '{uuid của phiếu nhập hàng}' AFTER `mode`;
ALTER TABLE `ospos_receivings` ADD `remain_amount` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT '{Số tiền còn nợ}' AFTER `mode`;

ALTER TABLE `ospos_receivings` ADD `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT '{Số tiền đã thanh toán}' AFTER `mode`;
ALTER TABLE `ospos_receivings` ADD `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT '{Tổng đơn}' AFTER `mode`;

ALTER TABLE `ospos_receivings` MODIFY `remain_amount` decimal(15,2);
ALTER TABLE `ospos_receivings` MODIFY `paid_amount` decimal(15,2);
ALTER TABLE `ospos_receivings` MODIFY `receiving_uuid` varchar(36) NOT NULL DEFAULT uuid();

DROP TABLE IF EXISTS `ospos_receivings_payments`;
CREATE TABLE `ospos_receivings_payments` (
  `receivings_id` int(11) NOT NULL DEFAULT 0,
  `receivings_payments_uuid` varchar(50) NOT NULL DEFAULT uuid(),
  `note` varchar(255) NOT NULL DEFAULT '',
  `payment_type` varchar(40) NOT NULL DEFAULT '',
  `payment_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_kind` varchar(40) NOT NULL DEFAULT '''''' COMMENT '{Thanh Toán='''';Đặt Trước}',
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

INSERT INTO ospos_receivings_payments (receivings_id, note, payment_type, payment_amount, payment_kind)
    SELECT
        receiving_id,
        'Ghi chú thanh toán',
        'Chuyển khoản',
        0.00,
        ''
    FROM
        ospos_receivings;

UPDATE ospos_receivings r
      SET r.total_amount = (
          SELECT IFNULL(SUM(ri.item_cost_price * ri.quantity_purchased), 0)
          FROM ospos_receivings_items ri
          WHERE ri.receiving_id = r.receiving_id
      )
      WHERE r.receiving_id IN (
          SELECT receiving_id FROM ospos_receivings_items
      );

ALTER TABLE `ospos_employees` ADD `code` VARCHAR(20) NOT NULL DEFAULT '' AFTER `log`;

ALTER TABLE `ospos_test` ADD `prescription` text CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `r_va_o` VARCHAR(250) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `l_va_o` VARCHAR(250) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `right_e_old` VARCHAR(250) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `left_e_old` VARCHAR(250) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`; 
ALTER TABLE `ospos_test` ADD `old_toltal` VARCHAR(250) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `test_uuid`; 

ALTER TABLE `ospos_people` ADD `facebook` VARCHAR(250) CHARACTER SET utf8 NOT NULL DEFAULT '' AFTER `age`; 


ALTER TABLE `ospos_people` ADD FULLTEXT INDEX `last_name` (`last_name`);

ALTER TABLE `ospos_sales` ADD `kind` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: offline; 1: online' AFTER `code`;
ALTER TABLE `ospos_sales` ADD `shipping_address` varchar(250) CHARACTER SET utf8 DEFAULT '' COMMENT 'khác null khi kind=1' AFTER `code`;
ALTER TABLE `ospos_sales` ADD `shipping_city` varchar(100) CHARACTER SET utf8 DEFAULT '' COMMENT 'khac null kind = 1' AFTER `code`;
ALTER TABLE `ospos_sales` ADD `shipping_method` varchar(250) CHARACTER SET utf8 DEFAULT '' COMMENT 'VNPOST,VIETEL,....' AFTER `code`;
ALTER TABLE `ospos_sales` ADD `shipping_phone` varchar(11) CHARACTER SET utf8 DEFAULT '' AFTER `code`;
ALTER TABLE `ospos_sales` ADD `source` varchar(25) CHARACTER SET utf8 DEFAULT '' AFTER `code`;
ALTER TABLE `ospos_sales` ADD `completed` tinyint(1) DEFAULT 0 COMMENT '0 thông tin; 1 đặt hàng;2 chuyển đến nhà vận chuyển;3 nhận hàng;4 hoàn thành' AFTER `code`;
ALTER TABLE `ospos_sales` ADD `shipping_address_type` tinyint(1) DEFAULT 1 AFTER `code`;
ALTER TABLE `ospos_sales` ADD `shipping_fee` decimal(10,2) DEFAULT 0.00 AFTER `code`;
ALTER TABLE `ospos_sales` ADD `shipping_code` varchar(50) CHARACTER SET utf8 DEFAULT '' AFTER `code`;