<?php

use yii\db\Migration;

class m161226_172642_alter_table_history_contact extends Migration
{
    public function up()
    {
        $this->addColumn('history_contact','total_success','int(11)');
    }

    public function down()
    {
        echo "m161226_172642_alter_table_history_contact cannot be reverted.\n";

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
