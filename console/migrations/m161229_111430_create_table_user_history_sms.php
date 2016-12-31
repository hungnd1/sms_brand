<?php

use yii\db\Migration;

class m161229_111430_create_table_user_history_sms extends Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `user_history` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `type` INT NOT NULL COMMENT '1 la cham soc khach hang, 2 la tin nhan quang cao',
  `brandname_id` INT NOT NULL COMMENT 'brandname',
  `content` VARCHAR(1024) NOT NULL COMMENT '',
  `api_sms_id` VARCHAR(1024)  COMMENT '',
  `created_at` INT COMMENT '',
  `content_number` INT COMMENT '',
  `history_contact_status` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `member_by` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'gui thong tin cho user'
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m161229_111430_create_table_user_history_sms cannot be reverted.\n";

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
