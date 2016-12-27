<?php

use yii\db\Migration;

class m161211_031549_subject extends Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `subject` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `code` VARCHAR(50) NOT NULL COMMENT 'subject code',
  `name` VARCHAR(255) NOT NULL COMMENT 'subject name',
  `description` VARCHAR(500) NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `created_by` INT COMMENT '',
  `updated_by` INT COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
COMMENT = 'creat table subject'
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m161211_031549_subject cannot be reverted.\n";

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
