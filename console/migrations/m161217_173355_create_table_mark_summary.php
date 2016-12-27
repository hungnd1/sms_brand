<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_mark_summary`.
 */
class m161217_173355_create_table_mark_summary extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `mark_summary` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `student_id` INT NOT NULL COMMENT 'as contact_id',
  `class_id` INT NOT NULL COMMENT 'as category_id',
  `semester` INT NOT NULL COMMENT '',
  `marks` VARCHAR(1024) NOT NULL COMMENT '',
  `description` VARCHAR(500) NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `created_by` INT COMMENT '',
  `updated_by` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'creat table mark_summary'
SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('table_mark_summary');
    }
}
