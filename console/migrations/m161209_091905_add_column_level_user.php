<?php

use yii\db\Migration;

class m161209_091905_add_column_level_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','level','int(11)');
    }

    public function down()
    {
        echo "m161209_091905_add_column_level_user cannot be reverted.\n";

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
