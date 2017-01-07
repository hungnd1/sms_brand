<?php

use yii\db\Migration;

class m170107_070010_add_column_time_send extends Migration
{
    public function up()
    {
        $this->addColumn('user','time_send','int(11) default(1)');

    }

    public function down()
    {
        echo "m170107_070010_add_column_time_send cannot be reverted.\n";

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
