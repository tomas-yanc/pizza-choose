<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Ingredients;
use app\modules\admin\models\DishesIngredients;
use app\modules\admin\models\search\DishesIngredientsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\Dishes;

/**
 * DishesIngredientsController implements the CRUD actions for DishesIngredients model.
 */
class DishesIngredientsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DishesIngredients models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DishesIngredientsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $allDishes = Dishes::allDishes();
        $enabledIngredients = Ingredients::ENABLED_INGREDIENTS;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'allDishes' => $allDishes,
            'enabledIngredients' => $enabledIngredients,
        ]);
    }

    /**
     * Displays a single DishesIngredients model.
     * @param int $dishes_id Dishes ID
     * @param int $ingredients_id Ingredients ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($dishes_id, $ingredients_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($dishes_id, $ingredients_id),
        ]);
    }

    /**
     * Creates a new DishesIngredients model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new DishesIngredients();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'dishes_id' => $model->dishes_id, 'ingredients_id' => $model->ingredients_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DishesIngredients model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $dishes_id Dishes ID
     * @param int $ingredients_id Ingredients ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($dishes_id, $ingredients_id)
    {
        $model = $this->findModel($dishes_id, $ingredients_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'dishes_id' => $model->dishes_id, 'ingredients_id' => $model->ingredients_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DishesIngredients model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $dishes_id Dishes ID
     * @param int $ingredients_id Ingredients ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($dishes_id, $ingredients_id)
    {
        $this->findModel($dishes_id, $ingredients_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DishesIngredients model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $dishes_id Dishes ID
     * @param int $ingredients_id Ingredients ID
     * @return DishesIngredients the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($dishes_id, $ingredients_id)
    {
        if (($model = DishesIngredients::findOne(['dishes_id' => $dishes_id, 'ingredients_id' => $ingredients_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
