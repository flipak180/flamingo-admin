<?php

use yii\db\Migration;

/**
 * Class m241106_201704_extend_users
 */
class m241106_201704_extend_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'avatar', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241106_201704_extend_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241106_201704_extend_users cannot be reverted.\n";

        return false;
    }
    */
}
