<?php

use yii\db\Migration;

class m161230_042345_create_table_network extends Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `network` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(1024) NOT NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `status` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'quan ly thong tin nha mang'
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m161230_042345_create_table_network cannot be reverted.\n";

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
