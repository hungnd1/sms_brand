<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_contact`.
 */
class m161211_043555_create_table_contact extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql =<<<SQL
CREATE TABLE `contact` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `contact_name` VARCHAR(500) NULL COMMENT '',
  `description` TEXT NOT NULL COMMENT '',
  `status` INT NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `path` INT COMMENT '',
  `created_by` INT COMMENT '',
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
        $this->dropTable('table_contact');
    }
}
