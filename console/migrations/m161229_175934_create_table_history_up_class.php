<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_history_up_class`.
 */
class m161229_175934_create_table_history_up_class extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `history_up_class` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `old_class_id` INT COMMENT '',
  `old_class_name` VARCHAR(255) NULL COMMENT '',
  `number_old_class_students` INT COMMENT '',
  `new_class_id` INT COMMENT '',
  `new_class_name` VARCHAR(255) NULL COMMENT '',
  `number_new_class_students` INT COMMENT '',
  `year` INT COMMENT '',
  `status` INT COMMENT '',
  `description` VARCHAR(500) NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `created_by` INT COMMENT '',
  `updated_by` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'create table history_contact'
SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('history_up_class');
    }
}
