<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_history_contact`.
 */
class m161219_135806_create_table_history_contact extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `history_contact` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `type` INT NOT NULL COMMENT '1 la cham soc khach hang, 2 la tin nhan quang cao',
  `brandname_id` INT NOT NULL COMMENT 'brandname',
  `template_id` INT  COMMENT 'tin nhan mau',
  `content` VARCHAR(1024) NOT NULL COMMENT '',
  `campain_name` VARCHAR(1024) NOT NULL COMMENT '',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
  `send_schedule` INT COMMENT '',
  `member_by` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'creat table gui tin danh ba'
SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('table_history_contact');
    }
}
