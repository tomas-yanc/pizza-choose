<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\DishesIngredients */

$this->title = $model->dishes_id;
$this->params['breadcrumbs'][] = ['label' => 'Dishes Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dishes-ingredients-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'dishes_id' => $model->dishes_id, 'ingredients_id' => $model->ingredients_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'dishes_id' => $model->dishes_id, 'ingredients_id' => $model->ingredients_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dishes_id',
            'ingredients_id',
        ],
    ]) ?>

</div>
