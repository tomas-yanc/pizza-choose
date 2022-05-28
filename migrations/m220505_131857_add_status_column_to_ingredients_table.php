<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ingredients}}`.
 */
class m220505_131857_add_status_column_to_ingredients_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ingredients}}', 'status', $this->string(8)->defaultValue('On')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ingredients}}', 'status');
    }
}
