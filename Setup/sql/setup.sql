
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `{key}access`
-- ----------------------------
DROP TABLE IF EXISTS `{key}access`;
CREATE TABLE `{key}access` (
`role_id`  smallint(6) UNSIGNED NOT NULL ,
`node_id`  smallint(6) UNSIGNED NOT NULL ,
`level`  tinyint(1) NOT NULL ,
`module`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `{key}ad`
-- ----------------------------
DROP TABLE IF EXISTS `{key}ad`;
CREATE TABLE `{key}ad` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`img_url`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`code`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`type`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`remark`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(3) NOT NULL DEFAULT 0 ,
`link`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='广告'
AUTO_INCREMENT=44

;

-- ----------------------------
-- Table structure for `{key}article`
-- ----------------------------
DROP TABLE IF EXISTS `{key}article`;
CREATE TABLE `{key}article` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`title2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`pid`  int(11) NULL DEFAULT 0 ,
`keywords`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`status`  int(4) NULL DEFAULT 1 ,
`img_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`dateline`  int(11) NULL DEFAULT NULL ,
`href`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=58

;

-- ----------------------------
-- Table structure for `{key}article_cate`
-- ----------------------------
DROP TABLE IF EXISTS `{key}article_cate`;
CREATE TABLE `{key}article_cate` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`name2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`remark`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`pid`  int(11) NULL DEFAULT 0 ,
`keywords`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`status`  int(4) NULL DEFAULT 1 ,
`img_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`dateline`  int(11) NULL DEFAULT NULL ,
`href`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=14

;

-- ----------------------------
-- Table structure for `{key}billsprintsetting`
-- ----------------------------
DROP TABLE IF EXISTS `{key}billsprintsetting`;
CREATE TABLE `{key}billsprintsetting` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`Express`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`filed`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`top`  int(11) NULL DEFAULT NULL ,
`left`  int(11) NULL DEFAULT NULL ,
`width`  int(11) NULL DEFAULT NULL ,
`height`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=58

;

-- ----------------------------
-- Table structure for `{key}brand`
-- ----------------------------
DROP TABLE IF EXISTS `{key}brand`;
CREATE TABLE `{key}brand` (
`id`  smallint(5) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  smallint(5) NULL DEFAULT NULL ,
`imgurl`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`keywords`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`parent`  smallint(5) NULL DEFAULT NULL ,
`dateline`  timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP ,
`status`  smallint(5) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='品牌'
AUTO_INCREMENT=17

;

-- ----------------------------
-- Table structure for `{key}cart`
-- ----------------------------
DROP TABLE IF EXISTS `{key}cart`;
CREATE TABLE `{key}cart` (
`id`  int(3) NOT NULL AUTO_INCREMENT ,
`dateline`  int(11) NOT NULL DEFAULT 0 ,
`pid`  int(3) NOT NULL DEFAULT 0 ,
`price`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`session_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`name`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`count`  int(11) NOT NULL DEFAULT 0 ,
`uid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' ,
`model`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=910

;

-- ----------------------------
-- Table structure for `{key}cate`
-- ----------------------------
DROP TABLE IF EXISTS `{key}cate`;
CREATE TABLE `{key}cate` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`imgurl`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`pid`  int(11) NULL DEFAULT 0 ,
`type_id`  int(11) NOT NULL DEFAULT 0 ,
`sort`  int(11) NULL DEFAULT 0 ,
`txt2`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`total`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`ishot`  varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0' ,
`pagetitle`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`pagekey`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`pagedec`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`brandid`  int(11) NULL DEFAULT NULL ,
`remark`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci
COMMENT='类别'
AUTO_INCREMENT=379

;

-- ----------------------------
-- Table structure for `{key}countries`
-- ----------------------------
DROP TABLE IF EXISTS `{key}countries`;
CREATE TABLE `{key}countries` (
`countries_id`  int(11) NOT NULL AUTO_INCREMENT ,
`countries_name`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`countries_iso_code_2`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`countries_iso_code_3`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`address_format_id`  int(11) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`countries_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=241

;

-- ----------------------------
-- Table structure for `{key}coupon`
-- ----------------------------
DROP TABLE IF EXISTS `{key}coupon`;
CREATE TABLE `{key}coupon` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`coupon`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`amount`  decimal(11,2) NOT NULL ,
`start_time`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`expire_time`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`dateline`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  int(2) NULL DEFAULT 0 ,
`user`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=147

;

-- ----------------------------
-- Table structure for `{key}currencies`
-- ----------------------------
DROP TABLE IF EXISTS `{key}currencies`;
CREATE TABLE `{key}currencies` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`rate`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`symbol`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT 0 ,
`status`  int(2) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=7

;

-- ----------------------------
-- Table structure for `{key}discount`
-- ----------------------------
DROP TABLE IF EXISTS `{key}discount`;
CREATE TABLE `{key}discount` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`minmoney`  int(11) NULL DEFAULT NULL ,
`maxmoney`  int(11) NULL DEFAULT NULL ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`discount`  float NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=12

;

-- ----------------------------
-- Table structure for `{key}down`
-- ----------------------------
DROP TABLE IF EXISTS `{key}down`;
CREATE TABLE `{key}down` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`pid`  int(11) NULL DEFAULT 0 ,
`keywords`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`status`  int(4) NULL DEFAULT 1 ,
`file_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`dateline`  int(11) NULL DEFAULT NULL ,
`link`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=24

;

-- ----------------------------
-- Table structure for `{key}down_cate`
-- ----------------------------
DROP TABLE IF EXISTS `{key}down_cate`;
CREATE TABLE `{key}down_cate` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`remark`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`pid`  int(11) NULL DEFAULT 0 ,
`keywords`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`status`  int(4) NULL DEFAULT 1 ,
`img_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`dateline`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=8

;

-- ----------------------------
-- Table structure for `{key}fee`
-- ----------------------------
DROP TABLE IF EXISTS `{key}fee`;
CREATE TABLE `{key}fee` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`payment_id`  int(11) NULL DEFAULT NULL ,
`type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`fee`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`minimum_money`  decimal(11,2) NULL DEFAULT NULL ,
`min_freepaymoney`  decimal(11,2) NULL DEFAULT NULL ,
`paymoney`  decimal(11,2) NULL DEFAULT NULL ,
`insurance`  decimal(11,2) NULL DEFAULT NULL ,
`min_insurance`  decimal(11,2) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=8

;

-- ----------------------------
-- Table structure for `{key}members`
-- ----------------------------
DROP TABLE IF EXISTS `{key}members`;
CREATE TABLE `{key}members` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`firstname`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`lastname`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`email`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`password`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`createdate`  int(11) NULL DEFAULT 0 ,
`lastlogindate`  int(11) NULL DEFAULT 0 ,
`lastloginip`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`profav`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`points`  int(11) NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=200

;

-- ----------------------------
-- Table structure for `{key}members_group`
-- ----------------------------
DROP TABLE IF EXISTS `{key}members_group`;
CREATE TABLE `{key}members_group` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`minpoints`  int(11) NULL DEFAULT NULL ,
`maxpoints`  int(11) NULL DEFAULT NULL ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`discount`  float NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=6

;

-- ----------------------------
-- Table structure for `{key}nav`
-- ----------------------------
DROP TABLE IF EXISTS `{key}nav`;
CREATE TABLE `{key}nav` (
`id`  int(8) NOT NULL AUTO_INCREMENT ,
`ctype`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cid`  smallint(5) UNSIGNED NULL DEFAULT NULL ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  int(1) NULL DEFAULT NULL ,
`sort`  int(1) NULL DEFAULT NULL ,
`isnew`  int(1) NULL DEFAULT NULL ,
`url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=36

;

-- ----------------------------
-- Table structure for `{key}newsletter`
-- ----------------------------
DROP TABLE IF EXISTS `{key}newsletter`;
CREATE TABLE `{key}newsletter` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`email`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`addtime`  int(11) NULL DEFAULT NULL ,
`memberid`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Table structure for `{key}node`
-- ----------------------------
DROP TABLE IF EXISTS `{key}node`;
CREATE TABLE `{key}node` (
`id`  smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`title`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status`  tinyint(1) NULL DEFAULT 0 ,
`remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  smallint(6) UNSIGNED NULL DEFAULT NULL ,
`pid`  smallint(6) UNSIGNED NOT NULL ,
`level`  tinyint(1) UNSIGNED NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=40

;

-- ----------------------------
-- Table structure for `{key}orders`
-- ----------------------------
DROP TABLE IF EXISTS `{key}orders`;
CREATE TABLE `{key}orders` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`sn`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`member_id`  int(11) NOT NULL DEFAULT 0 ,
`delivery_email`  varchar(96) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`delivery_firstname`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`delivery_lastname`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_company`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_address`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`delivery_city`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`delivery_zip`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`delivery_state`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_country`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`delivery_telephone`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`payment_method`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`payment_module_code`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`shipping_method`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`shipping_module_code`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`coupon_code`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`cc_type`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cc_owner`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cc_number`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cc_expires`  varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cc_cvv`  blob NULL ,
`last_modified`  datetime NULL DEFAULT NULL ,
`dateline`  int(11) NULL DEFAULT 0 ,
`orders_status`  int(5) NOT NULL DEFAULT 1 ,
`orders_date_finished`  datetime NULL DEFAULT '0000-00-00 00:00:00' ,
`currency`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`currency_value`  decimal(14,2) NULL DEFAULT NULL ,
`orders_total`  decimal(14,2) NULL DEFAULT NULL ,
`order_tax`  decimal(14,2) NULL DEFAULT NULL ,
`paypal_ipn_id`  int(11) NULL DEFAULT 0 ,
`BuyNote`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`ip_address`  varchar(96) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`shippingmoney`  decimal(14,2) NULL DEFAULT 0.00 ,
`paymoney`  decimal(14,2) NULL DEFAULT 0.00 ,
`insurance`  decimal(14,2) NULL DEFAULT 0.00 ,
`currencies_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '$' ,
`shipping_id`  int(11) NULL DEFAULT NULL ,
`total_weight`  decimal(14,2) NULL DEFAULT NULL ,
`discount`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`coupon`  decimal(10,2) NULL DEFAULT NULL ,
`remark`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=188

;

-- ----------------------------
-- Table structure for `{key}orders_products`
-- ----------------------------
DROP TABLE IF EXISTS `{key}orders_products`;
CREATE TABLE `{key}orders_products` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`orders_id`  int(11) NOT NULL DEFAULT 0 ,
`products_id`  int(11) NOT NULL DEFAULT 0 ,
`products_model`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`products_name`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`products_price`  decimal(15,2) NOT NULL DEFAULT 0.00 ,
`products_pricespe`  decimal(15,2) NOT NULL DEFAULT 0.00 ,
`products_tax`  decimal(7,2) NOT NULL DEFAULT 0.00 ,
`products_quantity`  float NOT NULL DEFAULT 0 ,
`onetime_charges`  decimal(15,2) NOT NULL DEFAULT 0.00 ,
`products_priced_by_attribute`  tinyint(1) NOT NULL DEFAULT 0 ,
`product_is_free`  tinyint(1) NOT NULL DEFAULT 0 ,
`products_discount_type`  tinyint(1) NOT NULL DEFAULT 0 ,
`products_discount_type_from`  tinyint(1) NOT NULL DEFAULT 0 ,
`products_total`  float NOT NULL DEFAULT 0 ,
`products_prid`  tinytext CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=202

;

-- ----------------------------
-- Table structure for `{key}orders_shippingbills`
-- ----------------------------
DROP TABLE IF EXISTS `{key}orders_shippingbills`;
CREATE TABLE `{key}orders_shippingbills` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`order_id`  int(11) NOT NULL ,
`order_sn`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`sender_sname`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sender_city`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sender_region`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sender_country`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sender_address`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sender_zip`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sender_tel`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_city`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_state`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_country`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_zip`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_telephone`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`delivery_address`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`Express`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ExpressSN`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`dateline`  int(11) NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=69

;

-- ----------------------------
-- Table structure for `{key}orders_status`
-- ----------------------------
DROP TABLE IF EXISTS `{key}orders_status`;
CREATE TABLE `{key}orders_status` (
`orders_status_id`  int(11) NOT NULL DEFAULT 0 ,
`language_id`  int(11) NOT NULL DEFAULT 1 ,
`orders_status_name`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
PRIMARY KEY (`orders_status_id`, `language_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `{key}orders_status_history`
-- ----------------------------
DROP TABLE IF EXISTS `{key}orders_status_history`;
CREATE TABLE `{key}orders_status_history` (
`orders_status_history_id`  int(11) NOT NULL AUTO_INCREMENT ,
`orders_id`  int(11) NOT NULL DEFAULT 0 ,
`orders_status_id`  int(5) NOT NULL DEFAULT 0 ,
`date_added`  datetime NOT NULL DEFAULT '0001-01-01 00:00:00' ,
`customer_notified`  int(1) NULL DEFAULT 0 ,
`comments`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
PRIMARY KEY (`orders_status_history_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `{key}orders_total`
-- ----------------------------
DROP TABLE IF EXISTS `{key}orders_total`;
CREATE TABLE `{key}orders_total` (
`orders_total_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`orders_id`  int(11) NOT NULL DEFAULT 0 ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`text`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`value`  decimal(15,4) NOT NULL DEFAULT 0.0000 ,
`class`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`sort_order`  int(11) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`orders_total_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `{key}payment`
-- ----------------------------
DROP TABLE IF EXISTS `{key}payment`;
CREATE TABLE `{key}payment` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`title`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`remark`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`var`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`img_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=18

;

-- ----------------------------
-- Table structure for `{key}products`
-- ----------------------------
DROP TABLE IF EXISTS `{key}products`;
CREATE TABLE `{key}products` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cateid`  int(11) NOT NULL DEFAULT 0 ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`serial`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`price`  decimal(11,2) NULL DEFAULT 0.00 ,
`pricespe`  decimal(11,2) NULL DEFAULT NULL ,
`weight`  decimal(11,2) NULL DEFAULT 0.00 ,
`bigimage`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`smallimage`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`remark`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`isnew`  int(5) NULL DEFAULT 0 ,
`ishot`  int(5) NULL DEFAULT 0 ,
`isrec`  int(5) NULL DEFAULT 0 ,
`isprice`  int(5) NULL DEFAULT 0 ,
`isdown`  int(5) NULL DEFAULT 0 ,
`activity`  int(10) NULL DEFAULT 0 ,
`dateline`  int(11) NULL DEFAULT 0 ,
`sort`  int(11) NULL DEFAULT 0 ,
`brandid`  int(11) NULL DEFAULT NULL ,
`viewcount`  int(10) NULL DEFAULT 0 ,
`points`  int(11) NULL DEFAULT 0 ,
`costprice`  decimal(11,2) NULL DEFAULT NULL ,
`provider`  decimal(11,2) NULL DEFAULT NULL ,
`stock`  int(11) NULL DEFAULT 0 ,
`pagetitle`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`pagekey`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`pagedec`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`picprefix`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`piece`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci
AUTO_INCREMENT=95

;

-- ----------------------------
-- Table structure for `{key}products_ask`
-- ----------------------------
DROP TABLE IF EXISTS `{key}products_ask`;
CREATE TABLE `{key}products_ask` (
`id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`email`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tel`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`qq`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`msn`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`type`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`content`  mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`replay`  mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`ip`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`dateline`  int(255) NULL DEFAULT NULL ,
`products_id`  int(11) NULL DEFAULT NULL ,
`status`  enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' ,
`star`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=5632

;

-- ----------------------------
-- Table structure for `{key}products_attr`
-- ----------------------------
DROP TABLE IF EXISTS `{key}products_attr`;
CREATE TABLE `{key}products_attr` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`products_id`  mediumint(8) UNSIGNED NOT NULL DEFAULT 0 ,
`attr_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 ,
`attr_value`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`img_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`attr_price`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`sort`  int(10) NULL DEFAULT NULL ,
`dateline`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1418

;

-- ----------------------------
-- Table structure for `{key}products_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `{key}products_gallery`;
CREATE TABLE `{key}products_gallery` (
`id`  mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
`pid`  mediumint(8) UNSIGNED NOT NULL DEFAULT 0 ,
`img_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`img_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`thumb_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`img_original`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`sort`  int(11) NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=107

;

-- ----------------------------
-- Table structure for `{key}products_lot`
-- ----------------------------
DROP TABLE IF EXISTS `{key}products_lot`;
CREATE TABLE `{key}products_lot` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`product_id`  int(11) NOT NULL ,
`mincount`  int(11) NULL DEFAULT NULL ,
`maxcount`  int(11) NULL DEFAULT NULL ,
`remark`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`price`  float NULL DEFAULT 1 ,
`dateline`  int(11) NULL DEFAULT NULL ,
`start_time`  int(11) NULL DEFAULT NULL ,
`end_time`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=16

;

-- ----------------------------
-- Table structure for `{key}products_related`
-- ----------------------------
DROP TABLE IF EXISTS `{key}products_related`;
CREATE TABLE `{key}products_related` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`products_id`  int(11) NOT NULL ,
`related_products_id`  int(11) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=5

;

-- ----------------------------
-- Table structure for `{key}region`
-- ----------------------------
DROP TABLE IF EXISTS `{key}region`;
CREATE TABLE `{key}region` (
`id`  smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
`pid`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 ,
`name`  varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`type`  tinyint(1) NOT NULL DEFAULT 2 ,
`agency_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3726

;

-- ----------------------------
-- Table structure for `{key}role`
-- ----------------------------
DROP TABLE IF EXISTS `{key}role`;
CREATE TABLE `{key}role` (
`id`  smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`pid`  smallint(6) NULL DEFAULT NULL ,
`status`  tinyint(1) UNSIGNED NULL DEFAULT NULL ,
`remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=17

;

-- ----------------------------
-- Table structure for `{key}role_user`
-- ----------------------------
DROP TABLE IF EXISTS `{key}role_user`;
CREATE TABLE `{key}role_user` (
`role_id`  mediumint(9) UNSIGNED NULL DEFAULT NULL ,
`user_id`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `{key}session`
-- ----------------------------
DROP TABLE IF EXISTS `{key}session`;
CREATE TABLE `{key}session` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`sessionid`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`adminid`  int(11) NOT NULL ,
`dateline`  int(11) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=9

;

-- ----------------------------
-- Table structure for `{key}setting`
-- ----------------------------
DROP TABLE IF EXISTS `{key}setting`;
CREATE TABLE `{key}setting` (
`vid`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`valuename`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valuetxt`  longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
PRIMARY KEY (`vid`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=234

;

-- ----------------------------
-- Table structure for `{key}shipping`
-- ----------------------------
DROP TABLE IF EXISTS `{key}shipping`;
CREATE TABLE `{key}shipping` (
`id`  tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT ,
`code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`name`  varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`insure`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' ,
`support_cod`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`status`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`print`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=15

;

-- ----------------------------
-- Table structure for `{key}shipping_area`
-- ----------------------------
DROP TABLE IF EXISTS `{key}shipping_area`;
CREATE TABLE `{key}shipping_area` (
`id`  smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`shipping_id`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`config`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`base_fee`  varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`step_fee`  varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`free_money`  varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=12

;

-- ----------------------------
-- Table structure for `{key}shippingaddress`
-- ----------------------------
DROP TABLE IF EXISTS `{key}shippingaddress`;
CREATE TABLE `{key}shippingaddress` (
`id`  int(11) NOT NULL DEFAULT 0 ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`state`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`zip`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`country`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`telphone`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`fax`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`isNewsletter`  int(11) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for `{key}tag`
-- ----------------------------
DROP TABLE IF EXISTS `{key}tag`;
CREATE TABLE `{key}tag` (
`id`  mediumint(8) NOT NULL AUTO_INCREMENT ,
`member_id`  mediumint(8) UNSIGNED NOT NULL DEFAULT 0 ,
`product_id`  mediumint(8) UNSIGNED NOT NULL DEFAULT 0 ,
`tag`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`dateline`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=8

;

-- ----------------------------
-- Table structure for `{key}type_attr`
-- ----------------------------
DROP TABLE IF EXISTS `{key}type_attr`;
CREATE TABLE `{key}type_attr` (
`id`  smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
`type_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 ,
`name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`input_type`  tinyint(1) UNSIGNED NOT NULL DEFAULT 1 ,
`values`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`attr_index`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`status`  int(1) NULL DEFAULT 1 ,
`sort`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2

;

-- ----------------------------
-- Table structure for `{key}type_cate`
-- ----------------------------
DROP TABLE IF EXISTS `{key}type_cate`;
CREATE TABLE `{key}type_cate` (
`id`  smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`status`  tinyint(1) UNSIGNED NOT NULL DEFAULT 1 ,
`attr_group`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Table structure for `{key}user`
-- ----------------------------
DROP TABLE IF EXISTS `{key}user`;
CREATE TABLE `{key}user` (
`id`  smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
`account`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`nickname`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`password`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`bind_account`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`last_login_time`  int(11) UNSIGNED NULL DEFAULT 0 ,
`last_login_ip`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`login_count`  mediumint(8) UNSIGNED NULL DEFAULT 0 ,
`verify`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`email`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`create_time`  int(11) UNSIGNED NOT NULL ,
`update_time`  int(11) UNSIGNED NOT NULL ,
`status`  tinyint(1) NULL DEFAULT 0 ,
`type_id`  tinyint(2) UNSIGNED NULL DEFAULT 0 ,
`isadministrator`  tinyint(1) NULL DEFAULT 0 ,
`info`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=44

;

-- ----------------------------
-- Table structure for `{key}virtual_cat`
-- ----------------------------
DROP TABLE IF EXISTS `{key}virtual_cat`;
CREATE TABLE `{key}virtual_cat` (
`id`  int(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`filter`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`status`  int(2) NULL DEFAULT 1 ,
`pid`  int(8) UNSIGNED NULL DEFAULT 0 ,
`sort`  int(8) UNSIGNED NULL DEFAULT NULL ,
`pagetitle`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`pagekey`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`pagedec`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=19

;

-- ----------------------------
-- Indexes structure for table {key}access
-- ----------------------------
CREATE INDEX `groupId` USING BTREE ON `{key}access`(`role_id`) ;
CREATE INDEX `nodeId` USING BTREE ON `{key}access`(`node_id`) ;

-- ----------------------------
-- Auto increment value for `{key}ad`
-- ----------------------------
ALTER TABLE `{key}ad` AUTO_INCREMENT=44;

-- ----------------------------
-- Auto increment value for `{key}article`
-- ----------------------------
ALTER TABLE `{key}article` AUTO_INCREMENT=58;

-- ----------------------------
-- Auto increment value for `{key}article_cate`
-- ----------------------------
ALTER TABLE `{key}article_cate` AUTO_INCREMENT=14;

-- ----------------------------
-- Auto increment value for `{key}billsprintsetting`
-- ----------------------------
ALTER TABLE `{key}billsprintsetting` AUTO_INCREMENT=58;

-- ----------------------------
-- Auto increment value for `{key}brand`
-- ----------------------------
ALTER TABLE `{key}brand` AUTO_INCREMENT=17;

-- ----------------------------
-- Auto increment value for `{key}cart`
-- ----------------------------
ALTER TABLE `{key}cart` AUTO_INCREMENT=910;

-- ----------------------------
-- Indexes structure for table {key}cate
-- ----------------------------
CREATE INDEX `cid` USING BTREE ON `{key}cate`(`id`) ;
CREATE INDEX `classname` USING BTREE ON `{key}cate`(`name`) ;
CREATE INDEX `upid` USING BTREE ON `{key}cate`(`pid`) ;
CREATE INDEX `orderby` USING BTREE ON `{key}cate`(`sort`) ;
CREATE INDEX `ishot` USING BTREE ON `{key}cate`(`ishot`) ;

-- ----------------------------
-- Auto increment value for `{key}cate`
-- ----------------------------
ALTER TABLE `{key}cate` AUTO_INCREMENT=379;

-- ----------------------------
-- Indexes structure for table {key}countries
-- ----------------------------
CREATE INDEX `idx_countries_name_zen` USING BTREE ON `{key}countries`(`countries_name`) ;
CREATE INDEX `idx_address_format_id_zen` USING BTREE ON `{key}countries`(`address_format_id`) ;
CREATE INDEX `idx_iso_2_zen` USING BTREE ON `{key}countries`(`countries_iso_code_2`) ;
CREATE INDEX `idx_iso_3_zen` USING BTREE ON `{key}countries`(`countries_iso_code_3`) ;

-- ----------------------------
-- Auto increment value for `{key}countries`
-- ----------------------------
ALTER TABLE `{key}countries` AUTO_INCREMENT=241;

-- ----------------------------
-- Auto increment value for `{key}coupon`
-- ----------------------------
ALTER TABLE `{key}coupon` AUTO_INCREMENT=147;

-- ----------------------------
-- Auto increment value for `{key}currencies`
-- ----------------------------
ALTER TABLE `{key}currencies` AUTO_INCREMENT=7;

-- ----------------------------
-- Auto increment value for `{key}discount`
-- ----------------------------
ALTER TABLE `{key}discount` AUTO_INCREMENT=12;

-- ----------------------------
-- Auto increment value for `{key}down`
-- ----------------------------
ALTER TABLE `{key}down` AUTO_INCREMENT=24;

-- ----------------------------
-- Auto increment value for `{key}down_cate`
-- ----------------------------
ALTER TABLE `{key}down_cate` AUTO_INCREMENT=8;

-- ----------------------------
-- Auto increment value for `{key}fee`
-- ----------------------------
ALTER TABLE `{key}fee` AUTO_INCREMENT=8;

-- ----------------------------
-- Auto increment value for `{key}members`
-- ----------------------------
ALTER TABLE `{key}members` AUTO_INCREMENT=200;

-- ----------------------------
-- Auto increment value for `{key}members_group`
-- ----------------------------
ALTER TABLE `{key}members_group` AUTO_INCREMENT=6;

-- ----------------------------
-- Indexes structure for table {key}nav
-- ----------------------------
CREATE INDEX `type` USING BTREE ON `{key}nav`(`type`) ;
CREATE INDEX `ifshow` USING BTREE ON `{key}nav`(`status`) ;

-- ----------------------------
-- Auto increment value for `{key}nav`
-- ----------------------------
ALTER TABLE `{key}nav` AUTO_INCREMENT=36;

-- ----------------------------
-- Auto increment value for `{key}newsletter`
-- ----------------------------
ALTER TABLE `{key}newsletter` AUTO_INCREMENT=4;

-- ----------------------------
-- Indexes structure for table {key}node
-- ----------------------------
CREATE INDEX `level` USING BTREE ON `{key}node`(`level`) ;
CREATE INDEX `pid` USING BTREE ON `{key}node`(`pid`) ;
CREATE INDEX `status` USING BTREE ON `{key}node`(`status`) ;
CREATE INDEX `name` USING BTREE ON `{key}node`(`name`) ;

-- ----------------------------
-- Auto increment value for `{key}node`
-- ----------------------------
ALTER TABLE `{key}node` AUTO_INCREMENT=40;

-- ----------------------------
-- Indexes structure for table {key}orders
-- ----------------------------
CREATE INDEX `idx_status_orders_cust_zen` USING BTREE ON `{key}orders`(`orders_status`, `id`, `member_id`) ;
CREATE INDEX `idx_date_purchased_zen` USING BTREE ON `{key}orders`(`dateline`) ;

-- ----------------------------
-- Auto increment value for `{key}orders`
-- ----------------------------
ALTER TABLE `{key}orders` AUTO_INCREMENT=188;

-- ----------------------------
-- Indexes structure for table {key}orders_products
-- ----------------------------
CREATE INDEX `idx_orders_id_prod_id_zen` USING BTREE ON `{key}orders_products`(`orders_id`, `products_id`) ;

-- ----------------------------
-- Auto increment value for `{key}orders_products`
-- ----------------------------
ALTER TABLE `{key}orders_products` AUTO_INCREMENT=202;

-- ----------------------------
-- Auto increment value for `{key}orders_shippingbills`
-- ----------------------------
ALTER TABLE `{key}orders_shippingbills` AUTO_INCREMENT=69;

-- ----------------------------
-- Indexes structure for table {key}orders_status
-- ----------------------------
CREATE INDEX `idx_orders_status_name_zen` USING BTREE ON `{key}orders_status`(`orders_status_name`) ;

-- ----------------------------
-- Indexes structure for table {key}orders_status_history
-- ----------------------------
CREATE INDEX `idx_orders_id_status_id_zen` USING BTREE ON `{key}orders_status_history`(`orders_id`, `orders_status_id`) ;

-- ----------------------------
-- Auto increment value for `{key}orders_status_history`
-- ----------------------------
ALTER TABLE `{key}orders_status_history` AUTO_INCREMENT=1;

-- ----------------------------
-- Indexes structure for table {key}orders_total
-- ----------------------------
CREATE INDEX `idx_ot_orders_id_zen` USING BTREE ON `{key}orders_total`(`orders_id`) ;
CREATE INDEX `idx_ot_class_zen` USING BTREE ON `{key}orders_total`(`class`) ;

-- ----------------------------
-- Auto increment value for `{key}orders_total`
-- ----------------------------
ALTER TABLE `{key}orders_total` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `{key}payment`
-- ----------------------------
ALTER TABLE `{key}payment` AUTO_INCREMENT=18;

-- ----------------------------
-- Indexes structure for table {key}products
-- ----------------------------
CREATE INDEX `pid` USING BTREE ON `{key}products`(`id`) ;
CREATE INDEX `classid` USING BTREE ON `{key}products`(`cateid`) ;
CREATE INDEX `product_name` USING BTREE ON `{key}products`(`name`) ;
CREATE INDEX `isnew` USING BTREE ON `{key}products`(`isnew`) ;
CREATE INDEX `ishot` USING BTREE ON `{key}products`(`ishot`) ;
CREATE INDEX `isrec` USING BTREE ON `{key}products`(`isrec`) ;
CREATE INDEX `isprice` USING BTREE ON `{key}products`(`isprice`) ;
CREATE INDEX `dateline` USING BTREE ON `{key}products`(`dateline`) ;

-- ----------------------------
-- Auto increment value for `{key}products`
-- ----------------------------
ALTER TABLE `{key}products` AUTO_INCREMENT=95;

-- ----------------------------
-- Indexes structure for table {key}products_ask
-- ----------------------------
CREATE INDEX `id` USING BTREE ON `{key}products_ask`(`id`) ;

-- ----------------------------
-- Auto increment value for `{key}products_ask`
-- ----------------------------
ALTER TABLE `{key}products_ask` AUTO_INCREMENT=5632;

-- ----------------------------
-- Indexes structure for table {key}products_attr
-- ----------------------------
CREATE INDEX `goods_id` USING BTREE ON `{key}products_attr`(`products_id`) ;
CREATE INDEX `attr_id` USING BTREE ON `{key}products_attr`(`attr_id`) ;

-- ----------------------------
-- Auto increment value for `{key}products_attr`
-- ----------------------------
ALTER TABLE `{key}products_attr` AUTO_INCREMENT=1418;

-- ----------------------------
-- Indexes structure for table {key}products_gallery
-- ----------------------------
CREATE INDEX `goods_id` USING BTREE ON `{key}products_gallery`(`pid`) ;

-- ----------------------------
-- Auto increment value for `{key}products_gallery`
-- ----------------------------
ALTER TABLE `{key}products_gallery` AUTO_INCREMENT=107;

-- ----------------------------
-- Auto increment value for `{key}products_lot`
-- ----------------------------
ALTER TABLE `{key}products_lot` AUTO_INCREMENT=16;

-- ----------------------------
-- Auto increment value for `{key}products_related`
-- ----------------------------
ALTER TABLE `{key}products_related` AUTO_INCREMENT=5;

-- ----------------------------
-- Indexes structure for table {key}region
-- ----------------------------
CREATE INDEX `parent_id` USING BTREE ON `{key}region`(`pid`) ;
CREATE INDEX `region_type` USING BTREE ON `{key}region`(`type`) ;
CREATE INDEX `agency_id` USING BTREE ON `{key}region`(`agency_id`) ;

-- ----------------------------
-- Auto increment value for `{key}region`
-- ----------------------------
ALTER TABLE `{key}region` AUTO_INCREMENT=3726;

-- ----------------------------
-- Indexes structure for table {key}role
-- ----------------------------
CREATE INDEX `pid` USING BTREE ON `{key}role`(`pid`) ;
CREATE INDEX `status` USING BTREE ON `{key}role`(`status`) ;

-- ----------------------------
-- Auto increment value for `{key}role`
-- ----------------------------
ALTER TABLE `{key}role` AUTO_INCREMENT=17;

-- ----------------------------
-- Indexes structure for table {key}role_user
-- ----------------------------
CREATE INDEX `group_id` USING BTREE ON `{key}role_user`(`role_id`) ;
CREATE INDEX `user_id` USING BTREE ON `{key}role_user`(`user_id`) ;

-- ----------------------------
-- Auto increment value for `{key}session`
-- ----------------------------
ALTER TABLE `{key}session` AUTO_INCREMENT=9;

-- ----------------------------
-- Indexes structure for table {key}setting
-- ----------------------------
CREATE INDEX `valuename` USING BTREE ON `{key}setting`(`valuename`) ;

-- ----------------------------
-- Auto increment value for `{key}setting`
-- ----------------------------
ALTER TABLE `{key}setting` AUTO_INCREMENT=234;

-- ----------------------------
-- Indexes structure for table {key}shipping
-- ----------------------------
CREATE INDEX `shipping_code` USING BTREE ON `{key}shipping`(`code`) ;

-- ----------------------------
-- Auto increment value for `{key}shipping`
-- ----------------------------
ALTER TABLE `{key}shipping` AUTO_INCREMENT=15;

-- ----------------------------
-- Indexes structure for table {key}shipping_area
-- ----------------------------
CREATE INDEX `shipping_id` USING BTREE ON `{key}shipping_area`(`shipping_id`) ;

-- ----------------------------
-- Auto increment value for `{key}shipping_area`
-- ----------------------------
ALTER TABLE `{key}shipping_area` AUTO_INCREMENT=12;

-- ----------------------------
-- Indexes structure for table {key}tag
-- ----------------------------
CREATE INDEX `member_id` USING BTREE ON `{key}tag`(`member_id`) ;
CREATE INDEX `product_id` USING BTREE ON `{key}tag`(`product_id`) ;

-- ----------------------------
-- Auto increment value for `{key}tag`
-- ----------------------------
ALTER TABLE `{key}tag` AUTO_INCREMENT=8;

-- ----------------------------
-- Indexes structure for table {key}type_attr
-- ----------------------------
CREATE INDEX `cat_id` USING BTREE ON `{key}type_attr`(`type_id`) ;

-- ----------------------------
-- Auto increment value for `{key}type_attr`
-- ----------------------------
ALTER TABLE `{key}type_attr` AUTO_INCREMENT=2;

-- ----------------------------
-- Auto increment value for `{key}type_cate`
-- ----------------------------
ALTER TABLE `{key}type_cate` AUTO_INCREMENT=3;

-- ----------------------------
-- Indexes structure for table {key}user
-- ----------------------------
CREATE UNIQUE INDEX `account` USING BTREE ON `{key}user`(`account`) ;

-- ----------------------------
-- Auto increment value for `{key}user`
-- ----------------------------
ALTER TABLE `{key}user` AUTO_INCREMENT=44;

-- ----------------------------
-- Indexes structure for table {key}virtual_cat
-- ----------------------------
CREATE INDEX `ind_disabled` USING BTREE ON `{key}virtual_cat`(`status`) ;
CREATE INDEX `ind_p_order` USING BTREE ON `{key}virtual_cat`(`sort`) ;

-- ----------------------------
-- Auto increment value for `{key}virtual_cat`
-- ----------------------------
ALTER TABLE `{key}virtual_cat` AUTO_INCREMENT=19;
