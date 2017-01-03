<?php

use yii\db\Migration;

class m161230_063949_add_column_number_network extends Migration
{
    public function up()
    {
        $this->addColumn('network','number_network','varchar(100)');
    }

    public function down()
    {
        echo "m161230_063949_add_column_number_network cannot be reverted.\n";

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
