<?php

if (session_status() == PHP_SESSION_NONE) session_start();

use app\modules\admin\models\Ingredients;
use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
?>

<div class="jumbotron text-center bg-transparent">
    <h3 class="display-4">Выберите ингредиенты для блюда!</h3>
</div>

<div class="row">
    <div class="col-md-3">
        <h4><b>Все блюда:</b></h4><br>

        <?php
        foreach($allDishes as $dish) { // Все названия блюд
            $ingredients = $dish->ingredients; // Ингредиенты для каждого блюда

            foreach($ingredients as $ingredient) { // Ингредиенты для каждого блюда

                $arrIngredients[] = $ingredient; // Ингедиенты для каждого блюда
                $count = count($arrIngredients); // Количество ингредиентов для каждого блюда
                
                if($ingredient->status == Ingredients::ENABLED) { // Если статус ингредиента On
                    $arrIngredientsOn[] = $ingredient; // Ингредиенты для каждого блюда со статусом On
                    $countOn = count($arrIngredientsOn); // Количество ингредиентов для каждого блюда со статусом On
                }
            }
            $arrIngredients = array();
            $arrIngredientsOn = array();

            if($count == $countOn) { // Если кол-во ингредиентов в блюде равно количеству ингредиентов в блюде со статусом On
                echo '<b>Пицца: </b>'.$dish->name.'<br><br>';
            }
        } 
        ?>
    </div>

    <div class="col-md-3">
        <h4><b>Все ингредиенты:</b></h4><br>

        <?php
        foreach($allIngredients as $allIngredient) { // Все ингредиенты
            if($allIngredient->status == Ingredients::ENABLED) { // Если статус ингредиента On

                // Ссылка на каждый ингредиент и GET параметры для сохранения выбранного ингредиента
                echo Html::a($allIngredient->name, ['/',
                        'ingredient' => ['id' => $allIngredient->id, 'name' => $allIngredient->name]
                    ]).'<br>';
            }
        }
        ?>
    </div>

    <div class="col-md-3">
        <h4><b>Ингредиенты:</b></h4><br>

        <?php
        // Добавлен элемент fix, чтобы существовал $_SESSION['ingredients']
        // Сохраняем ингредиенты в $_SESSION['ingredients'] пока он меньше 5 выбранных элементов и 1 элемента fix
        // Делаем разные ключи, чтобы элементы не перезаписывались. Ключи такие как значения

        $_SESSION['ingredients']['fix'] = 'fix';

        if(!empty($_GET['ingredient']) && count($_SESSION['ingredients']) < 6) {
            $_SESSION['ingredients'][$_GET['ingredient']['name']] = [
                    'id' => $_GET['ingredient']['id'], 'name' => $_GET['ingredient']['name']];
        }

        if(!empty($_SESSION['ingredients'])) {

            foreach($_SESSION['ingredients'] as $key => $arrValues) {
                if($key != 'fix') echo $arrValues['name'].'<br>'; // Выбранные ингредиенты
            }
            if(count($_SESSION['ingredients']) == 6) { // Показывает надпись когда в $_SESSION['ingredients'] 5 пользовательских элементов и 1 'fix'
                echo '<br><i>Выбрано максимум ингредиентов</i><br>';
            }
            if(count($_SESSION['ingredients']) > 1) { // Показывает кнопку, когда в сессию записывается первый элемент
                echo '<br>'.Html::a('Сбросить', ['sess-ingredients'], ['class' => 'btn btn-primary']);
            } 
        }
        ?>
    </div>

    <div class="col-md-3">
        <h4><b>Блюда:</b></h4><br>

        <?php
        if(!empty($_SESSION['ingredients'])) {
            foreach($_SESSION['ingredients'] as $key => $arrElementsChoose) {
                foreach ($allIngredients as $oneIngredient) { // Все ингредиенты
                    if($key != 'fix') {
                        // Сопоставил элементы из $_SESSION и все ингредиенты,
                        // чтобы получить объекты ингредиентов для запроса выборки блюд
                        if ($oneIngredient->id == $arrElementsChoose['id']) {
                            $ingredientsChosen[] = $oneIngredient; // Выбранные ингредиенты(объекты)
                        }
                    }
                }
            }
            if(!empty($ingredientsChosen)) $countIngredientsChosen = count($ingredientsChosen); // Сколько выбрано ингредиентов
        }

        if(!empty($ingredientsChosen)) {
            foreach($ingredientsChosen as $ingredientChosen) { // Выбранные ингредиенты(объекты)
                $dishes = $ingredientChosen->dishes; // Блюда для каждого выбранного ингредиента

                foreach($dishes as $dish) {
                     $dishesForAllIngredients[] = $dish->name; // Все блюда для всех выбранных ингредиентов
                }
            }
            $countDishesRepeat = array();

            // Сколько раз блюдо повторилось, т.е. сколько выбранных ингредиентов есть в этом блюде
            $countDishesRepeat = array_count_values($dishesForAllIngredients);

//            echo '<pre>'; print_r($countDishesRepeat); echo '</pre>';

            if($countIngredientsChosen > 1) { // Кол-во выбранных ингредиентов больше 1
                foreach($countDishesRepeat as $name => $countDishRepeat) { // Кол-во повторов блюд

                    if($countDishRepeat == $countIngredientsChosen) {
                        $fullCoincident[] = $name; // Блюда в которых есть все выбранные ингредиенты
                    }else {
                        $incompletely[$name] = [$name => $countDishRepeat]; // Блюда в которых есть некоторые выбранные ингредиенты
                    }
                    if($countDishRepeat > 1) $counter++; // Если совпадений больше одного
                }
                if($counter < 1) echo '<i>Ничего не найдено</i>';

            } elseif($countIngredientsChosen == 1) echo '<i>Выберите больше ингредиентов</i>';

            // Блюда в которых есть все выбранные ингредиенты
            if(!empty($fullCoincident)) {
                foreach($fullCoincident as $dishName) {
                    echo 'Пицца: ' . $dishName . '<br>';
                }
            }

            // Блюда в которых есть все выбранные ингредиенты
            // в порядке уменьшения совпадения ингредиентов вплоть до 2­х
            for($i = 0; $i < $countIngredientsChosen; $i++) {
                if(!empty($incompletely)) {
                    foreach($incompletely as $items) {
                        foreach($items as $dishName => $quantity) {
                            if($quantity > 1 && empty($fullCoincident) && $quantity == ($countIngredientsChosen - $i)) {
                                echo 'Пицца: ' . $dishName . '<br>';
                            }
                        }
                    }
                }
            }
        }
        ?>            
    </div>
</div>