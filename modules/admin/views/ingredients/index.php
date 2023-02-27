<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\IngredientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ингредиенты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ingredients-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить ингредиент', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'status',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {index}',
                'buttons' => [
                    'index' => function($model, $key, $index) {
                        return Html::a(
                                Html::img('/images/show-hide.png'),
                                Url::to([$model, 'id' => $key]),
                                ['title' => 'Статус']
                        );
                    }
                ]
            ],
        ],
    ]);
    ?>
</div>