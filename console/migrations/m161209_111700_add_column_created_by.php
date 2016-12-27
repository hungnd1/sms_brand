<?php

use yii\db\Migration;

class m161209_111700_add_column_created_by extends Migration
{
    public function up()
    {
        $this->addColumn('user','created_by','int(11)');
    }

    public function down()
    {
        echo "m161209_111700_add_column_created_by cannot be reverted.\n";

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
