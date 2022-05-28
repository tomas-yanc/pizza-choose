<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dishes_ingredients}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%dishes}}`
 * - `{{%ingredients}}`
 */
class m220504_145642_create_junction_table_for_dishes_and_ingredients_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dishes_ingredients}}', [
            'dishes_id' => $this->integer(),
            'ingredients_id' => $this->integer(),
            'PRIMARY KEY(dishes_id, ingredients_id)',
        ]);

        // creates index for column `dishes_id`
        $this->createIndex(
            '{{%idx-dishes_ingredients-dishes_id}}',
            '{{%dishes_ingredients}}',
            'dishes_id'
        );

        // add foreign key for table `{{%dishes}}`
        $this->addForeignKey(
            '{{%fk-dishes_ingredients-dishes_id}}',
            '{{%dishes_ingredients}}',
            'dishes_id',
            '{{%dishes}}',
            'id',
            'CASCADE'
        );

        // creates index for column `ingredients_id`
        $this->createIndex(
            '{{%idx-dishes_ingredients-ingredients_id}}',
            '{{%dishes_ingredients}}',
            'ingredients_id'
        );

        // add foreign key for table `{{%ingredients}}`
        $this->addForeignKey(
            '{{%fk-dishes_ingredients-ingredients_id}}',
            '{{%dishes_ingredients}}',
            'ingredients_id',
            '{{%ingredients}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%dishes}}`
        $this->dropForeignKey(
            '{{%fk-dishes_ingredients-dishes_id}}',
            '{{%dishes_ingredients}}'
        );

        // drops index for column `dishes_id`
        $this->dropIndex(
            '{{%idx-dishes_ingredients-dishes_id}}',
            '{{%dishes_ingredients}}'
        );

        // drops foreign key for table `{{%ingredients}}`
        $this->dropForeignKey(
            '{{%fk-dishes_ingredients-ingredients_id}}',
            '{{%dishes_ingredients}}'
        );

        // drops index for column `ingredients_id`
        $this->dropIndex(
            '{{%idx-dishes_ingredients-ingredients_id}}',
            '{{%dishes_ingredients}}'
        );

        $this->dropTable('{{%dishes_ingredients}}');
    }
}
