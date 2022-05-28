<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "ingredients".
 *
 * @property int $id
 * @property string $name
 * @property string $status
 *
 * @property Dishes[] $dishes
 * @property DishesIngredients[] $dishesIngredients
 */

class Ingredients extends \yii\db\ActiveRecord
{
    /**
     * To hide ingredients and dishes
     */
    CONST ENABLED = 'On';
    CONST DISABLED = 'Off';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['status'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID ингредиента',
            'name' => 'Название ингредиента',
            'status' => 'Статус',
        ];
    }

    /**
     * Gets query for [[Dishes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDishes()
    {
        return $this->hasMany(Dishes::className(), ['id' => 'dishes_id'])->viaTable('dishes_ingredients', ['ingredients_id' => 'id']);
    }

    /**
     * Gets query for [[DishesIngredients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDishesIngredients()
    {
        return $this->hasMany(DishesIngredients::className(), ['ingredients_id' => 'id']);
    }

    static function Hide($id) // Скрыть/Показать ингредиент
    {
        $Ingredient = Ingredients::findOne($id);

        if($Ingredient->status == Ingredients::ENABLED) {
            $Ingredient->status = Ingredients::DISABLED;
        }
        elseif($Ingredient->status == Ingredients::DISABLED) {
            $Ingredient->status = Ingredients::ENABLED;
        }
        $Ingredient->save();
    }

    static function allIngredients() // Все ингредиенты
    { 
        $Ingredients = Ingredients::find()->all();
        return $Ingredients;
    }
}
