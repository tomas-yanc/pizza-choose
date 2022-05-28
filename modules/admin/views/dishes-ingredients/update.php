<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\DishesIngredients */

$this->title = 'Изменить: ' . $model->dishes_id;
$this->params['breadcrumbs'][] = ['label' => 'Dishes Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dishes_id, 'url' => ['view', 'dishes_id' => $model->dishes_id, 'ingredients_id' => $model->ingredients_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dishes-ingredients-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
