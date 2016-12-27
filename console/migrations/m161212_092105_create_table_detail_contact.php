<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_detail_contact`.
 */
class m161212_092105_create_table_detail_contact extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql =<<<SQL
CREATE TABLE `contact_detail` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `fullname` VARCHAR(500) NULL COMMENT '',
  `phone_number` VARCHAR(20) NOT NULL COMMENT '',
  `status` INT NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `gender` int,
  `address` VARCHAR (250),
  `birthday` int,
  `email` VARCHAR (100),
  `company` VARCHAR (100),
  `notes` VARCHAR (500),
  `created_by` INT,
  `contact_id` int,
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
COMMENT = 'tao chi tiet danh ba	'


SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('table_detail_contact');
    }
}
