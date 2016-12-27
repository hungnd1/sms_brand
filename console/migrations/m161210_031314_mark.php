<?php

use yii\db\Migration;

class m161210_031314_mark extends Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `mark` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `student_id` INT NOT NULL COMMENT 'as contact_id',
  `subject_id` INT NOT NULL COMMENT '',
  `class_id` INT NOT NULL COMMENT 'as category_id',
  `type` INT NOT NULL COMMENT '',
  `part` INT NOT NULL COMMENT '',
  `semester` INT NOT NULL COMMENT '',
  `mark` FLOAT NOT NULL COMMENT '',
  `description` VARCHAR(500) NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `created_by` INT COMMENT '',
  `updated_by` INT COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
COMMENT = 'creat table mark'
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m161210_031314_mark cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
