<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_exam`.
 */
class m170101_085816_create_table_exam extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `exam` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NULL COMMENT '',
  `semester` INT COMMENT '',
  `start_date` INT COMMENT '',
  `status` INT COMMENT '',
  `description` VARCHAR(500) NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `created_by` INT COMMENT '',
  `updated_by` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'create table exam'
SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('exam');
    }
}
