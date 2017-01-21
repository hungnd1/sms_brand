<?php

use yii\db\Migration;

class m170120_132543_create_table_news extends Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `news` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `display_name` VARCHAR(100)  COMMENT '',
  `short_description` TEXT COMMENT '',
  `description` TEXT COMMENT '',
  `content` TEXT COMMENT '',
  `image` VARCHAR(500) COMMENT '',
  `type` INT NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `status` INT COMMENT '',
  `updated_by` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'create table news'
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170120_132543_create_table_news cannot be reverted.\n";

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
