<?php

use yii\db\Migration;

class m170102_085732_create_table_comment extends Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `comment` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `id_contact_detail` int NOT NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `is_month` INT COMMENT '',
  `content` TEXT,
  `content_bonus` TEXT,
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'chua nhan xet theo ngay va theo thang'
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170102_085732_create_table_comment cannot be reverted.\n";

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
