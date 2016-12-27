<?php

use yii\db\Migration;

class m161210_042428_add_column_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','is_send','int(2)');
        $this->addColumn('user','number_sms','int(11)');

    }

    public function down()
    {
        echo "m161210_042428_add_column_user cannot be reverted.\n";

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
