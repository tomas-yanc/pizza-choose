<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\DishesIngredients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dishes-ingredients-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dishes_id')->textInput() ?>

    <?= $form->field($model, 'ingredients_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
