<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_queue_exam_student_room`.
 */
class m170108_111909_create_table_queue_exam_student_room extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `queue_exam_student_room` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `student_id` INT COMMENT '',
  `student_name` VARCHAR(255) NULL COMMENT '',
  `identification` VARCHAR(25) NULL COMMENT '',
  `exam_room_id` INT COMMENT '',
  `ip` VARCHAR(50) NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `created_by` INT COMMENT '',
  `updated_by` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'create table queue_exam_student_room'
SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('queue_exam_student_room');
    }
}
