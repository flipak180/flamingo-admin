<?php

use yii\db\Migration;

/**
 * Class m241120_130323_extend_categories
 */
class m241120_130323_extend_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('categories', 'icon', $this->string());
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
        echo "m241120_130323_extend_categories cannot be reverted.\n";

        return false;
    }
    */
}
