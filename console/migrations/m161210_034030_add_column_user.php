<?php

use yii\db\Migration;

class m161210_034030_add_column_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','address','varchar(250)');
        $this->addColumn('user','type_kh','int(2)');
    }

    public function down()
    {
        echo "m161210_034030_add_column_user cannot be reverted.\n";

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
