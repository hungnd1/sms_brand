<?php

use yii\db\Migration;

class m161212_020252_add_column_brandname_id extends Migration
{
    public function up()
    {
        $this->addColumn('user','brandname_id','int(11)');
        $this->dropColumn('brandname','brand_member');
    }

    public function down()
    {
        echo "m161212_020252_add_column_brandname_id cannot be reverted.\n";

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
