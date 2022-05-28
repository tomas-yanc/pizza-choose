<?php

namespace app\modules\admin\models;

/**
 * This is the model class for table "dishes_ingredients".
 *
 * @property int $dishes_id
 * @property int $ingredients_id
 *
 * @property Dishes $dishes
 * @property Ingredients $ingredients
 */
class DishesIngredients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dishes_ingredients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dishes_id', 'ingredients_id'], 'required'],
            [['dishes_id', 'ingredients_id'], 'integer'],
            [['dishes_id', 'ingredients_id'], 'unique', 'targetAttribute' => ['dishes_id', 'ingredients_id']],
            [['dishes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dishes::className(), 'targetAttribute' => ['dishes_id' => 'id']],
            [['ingredients_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredients::className(), 'targetAttribute' => ['ingredients_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dishes_id' => 'ID блюда',
            'ingredients_id' => 'ID ингредиента',
        ];
    }

    /**
     * Gets query for [[Dishes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDishes()
    {
        return $this->hasOne(Dishes::className(), ['id' => 'dishes_id']);
    }

    /**
     * Gets query for [[Ingredients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngredients()
    {
        return $this->hasOne(Ingredients::className(), ['id' => 'ingredients_id']);
    }
}
