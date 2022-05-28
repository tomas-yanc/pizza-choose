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

    <p>
        <?= Html::a('Составить блюдо', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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

<div>
    <h2>Все блюда и их ингредиенты</h2>

    <?php
    foreach($allDishes as $dish) { // Все названия блюд
        echo '<b>Пицца: </b>'.$dish->name.'<br><br>';

        echo '<b>Ингредиенты в блюде: </b><br>';
        $ingredients = $dish->ingredients; // Все ингридиенты для конкретного блюда

        foreach($ingredients as $ingredient) {
            $arrIngredients[] = $ingredient;
            $count = count($arrIngredients); // Кол-во ингредиентов для конкретного блюда

            if($ingredient->status == Ingredients::ENABLED) { // Если статус On
                $arrIngredientsOn[] = $ingredient;
                $countOn = count($arrIngredientsOn); // Кол-во ингредиентов для конкретного блюда со статусом On

                echo $ingredient->name.'<br>'; // Все ингридиенты для конкретного блюда
            }
        }
        echo '<b>Ингредиентов всего: </b>'.$count.'<br>';
        $arrIngredients = array();

        echo '<b>Ингредиентов со статусом On: </b>'.$countOn.'<br><hr>';
        $arrIngredientsOn = array();
    } ?>
</div>
