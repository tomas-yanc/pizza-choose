<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

use app\modules\admin\models\Ingredients;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\DishesIngredientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Блюда';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dishes-ingredients-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">Блюда</th>
            <th scope="col">Ингредиенты</th>
            <th scope="col">Всего</th>
            <th scope="col">Статус On</th>
        </tr>
        </thead>
        <tbody>
        <?php

        if (!empty($allDishes)) {
            foreach ($allDishes as $dish) { // Все названия блюд
                $ingredientsOn = array();

                echo "<tr><th>$dish->name</th><td>";

                $ingredients = $dish->ingredients; // Все ингридиенты для конкретного блюда
                $count = count($ingredients); // Кол-во ингредиентов для конкретного блюда

                foreach ($ingredients as $ingredient) {
                    if (!empty($enabledIngredients)) {
                        if ($ingredient->status == $enabledIngredients) { // Если статус On

                            $ingredientsOn[] = $ingredient;
                            $countOn = count($ingredientsOn); // Кол-во ингредиентов для конкретного блюда со статусом On

                            echo $ingredient->name . '<br>'; // Все ингридиенты для конкретного блюда
                        }
                    }
                }
                echo "</td><td>$count</td><td>$countOn</td></tr>";
            }
        }?>
        </tbody>
    </table>

    <p>
        <?= Html::a('Составить блюдо', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dishes_id',
            'ingredients_id',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'dishes_id' => $model->dishes_id, 'ingredients_id' => $model->ingredients_id]);
                 }
            ],
        ],
    ]); ?>
</div>
