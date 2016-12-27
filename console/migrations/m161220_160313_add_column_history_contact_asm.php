<?php

use yii\db\Migration;

class m161220_160313_add_column_history_contact_asm extends Migration
{
    public function up()
    {
        $this->addColumn('history_contact_asm','api_sms_id','varchar(50)');
        $this->addColumn('history_contact_asm','content_number','int(11)');
        $this->addColumn('history_contact_asm','history_contact_status','int(11)');
    }

    public function down()
    {
        echo "m161220_160313_add_column_history_contact_asm cannot be reverted.\n";

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
