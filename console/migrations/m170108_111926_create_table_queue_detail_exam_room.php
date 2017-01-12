<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_queue_detail_exam_room`.
 */
class m170108_111926_create_table_queue_detail_exam_room extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `queue_detail_exam_room` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `subject_id` VARCHAR(50) NULL COMMENT '',
  `location` VARCHAR(50) NULL COMMENT '',
  `supervisory` VARCHAR(50) NULL COMMENT '',
  `exam_hour` VARCHAR(50) NULL COMMENT '',
  `exam_date` VARCHAR(50) NULL COMMENT '',
  `exam_room_id` INT COMMENT '',
  `ip` VARCHAR(50) NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `created_by` INT COMMENT '',
  `updated_by` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'create table queue_exam_room'
SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('queue_detail_exam_room');
    }
}
