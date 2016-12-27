<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_history_contact_asm`.
 */
class m161219_152759_create_table_history_contact_asm extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `history_contact_asm` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'luu lai danh sach campain va danh ba',
  `history_contact_id` INT NOT NULL COMMENT 'id cua chien dich',
  `contact_id` INT NOT NULL COMMENT 'id cua danh ba',
  `created_at` INT COMMENT '',
  `updated_at` INT COMMENT '',
PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB CHARACTER SET = UTF8
COMMENT = 'creat table bang map campain va danh ba'
SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('table_history_contact_asm');
    }
}
