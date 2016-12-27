<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_brandname`.
 */
class m161209_133326_create_table_brandname extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

        $sql =<<<SQL
CREATE TABLE `brandname` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `brandname` VARCHAR(500) NULL COMMENT '',
  `brand_username` VARCHAR(500) NOT NULL COMMENT '',
  `brand_password` VARCHAR(500) NOT NULL COMMENT '',
  `brand_hash_token` VARCHAR(500) NOT NULL COMMENT '',
  `status` INT NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `expired_at` INT COMMENT '',
  `created_by` INT COMMENT 'nguoi tao brand name',
  `brand_member` INT COMMENT 'nguoi dc gan brand name',
  `number_sms` INT COMMENT 'so tin nhan toi da',
  `price_sms` INT COMMENT 'gia 1 tin nhan',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
COMMENT = 'tao branch name	'


SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('table_brandname');
    }
}
