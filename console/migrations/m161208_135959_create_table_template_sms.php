<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_template_sms`.
 */
class m161208_135959_create_table_template_sms extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql =<<<SQL
CREATE TABLE `template_sms` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `template_name` VARCHAR(500) NULL COMMENT '',
  `template_content` VARCHAR(500) NOT NULL COMMENT '',
  `status` INT NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `template_createby` INT COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
COMMENT = 'tao template tin nhan mau	'


SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('table_template_sms');
    }
}
