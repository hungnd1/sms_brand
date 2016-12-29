<?php

use yii\db\Migration;

class m161229_143130_add_column_userId extends Migration
{
    public function up()
    {
        $this->addColumn('user_history','user_id','int(11)');
    }

    public function down()
    {
        echo "m161229_143130_add_column_userId cannot be reverted.\n";

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
