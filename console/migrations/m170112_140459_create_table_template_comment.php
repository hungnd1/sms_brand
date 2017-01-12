<?php

use yii\db\Migration;

class m170112_140459_create_table_template_comment extends Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `template_comment` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `comment` TEXT ,
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `created_by` INT COMMENT '',
  `status` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'chua nhan xet mau'
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170112_140459_create_table_template_comment cannot be reverted.\n";

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
