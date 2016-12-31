<?php

use yii\db\Migration;

class m161228_084142_create_table_config_system extends Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `config_system` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `code` VARCHAR(100) NOT NULL COMMENT 'ma code cau hinh',
  `content` VARCHAR(500) NOT NULL COMMENT 'noi dung cau hinh',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `description` VARCHAR (250),
  `status` INT(11),
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'bang cau hinh he thong'
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m161228_084142_create_table_config_system cannot be reverted.\n";

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
