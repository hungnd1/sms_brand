<?php

use yii\db\Migration;

class m161220_160142_add_column_total_sms extends Migration
{
    public function up()
    {
        $this->addColumn('history_contact','total_sms','int(11)');
    }

    public function down()
    {
        echo "m161220_160142_add_column_total_sms cannot be reverted.\n";

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
