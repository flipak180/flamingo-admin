<?php

use yii\db\Migration;

/**
 * Class m241202_113919_extend_compilations
 */
class m241202_113919_extend_compilations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('compilations', 'show_on_homepage', $this->boolean());
        $this->addColumn('compilations', 'is_actual', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241202_113919_extend_compilations cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241202_113919_extend_compilations cannot be reverted.\n";

        return false;
    }
    */
}
