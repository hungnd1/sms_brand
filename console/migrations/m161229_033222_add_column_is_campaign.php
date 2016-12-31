<?php

use yii\db\Migration;

class m161229_033222_add_column_is_campaign extends Migration
{
    public function up()
    {
        $this->addColumn('history_contact','is_campaign','int(1)');
    }

    public function down()
    {
        echo "m161229_033222_add_column_is_campaign cannot be reverted.\n";

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
