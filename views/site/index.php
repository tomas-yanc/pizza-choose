<?php

if (session_status() == PHP_SESSION_NONE) session_start();

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
$counter = null;
?>

<div class="jumbotron text-center bg-transparent">
    <h6 class="display-4">Пицца на выбор</h6>
    <p>Выберите ингредиенты и получите блюдо</p>
</div>

<div class="row">
    <div class="col-md-3">
        <h4>Все блюда</h4><hr>
        <?php

        $arrIngredients = array();
        $arrIngredientsOn = array();

        if (!empty($allDishes)) { // Все названия блюд
            foreach ($allDishes as $dish) {
                $ingredients = $dish->ingredients; // Ингредиенты для каждого блюда

//                echo'<pre>'; var_dump(count($ingredients)); echo '</pre>';

                foreach ($ingredients as $ingredient) { // Ингредиенты для каждого блюда

                    $arrIngredients[] = $ingredient; // Ингедиенты для каждого блюда
//                    $count = count($ingredients);
                    $count = count($arrIngredients); // Количество ингредиентов для каждого блюда
//                    echo'<pre>'; var_dump(count($ingredients)); echo '</pre>';
//                    echo'<pre>'; var_dump(count($arrIngredients)); echo '</pre>';
////////////
                    if (!empty($enabledIngredients)) {
                        if ($ingredient->status == $enabledIngredients) { // Если статус ингредиента On
                            $arrIngredientsOn[] = $ingredient; // Ингредиенты для каждого блюда со статусом On
                            $countOn = count($arrIngredientsOn); // Количество ингредиентов для каждого блюда со статусом On
                        }
                    }
                }
                // Если кол-во ингредиентов в блюде равно количеству ингредиентов в блюде со статусом On
                if (!empty($count) && !empty($countOn)) {
                    if ($count == $countOn) {
                        $enableDishes[] = $dish->name;
                        echo "<p>$dish->name</p>";
                    }
                }
            }
            if (empty($enableDishes)) echo '<p>Нет доступных блюд</p>';
        }
        if (empty($allDishes)) echo '<p>Нет доступных блюд</p>';
        ?>
    </div>

    <div class="col-md-3">
        <h4>Все ингредиенты</h4><hr>
        <?php
        // Добавлен элемент fix, чтобы существовал $_SESSION['ingredients']
        $_SESSION['ingredients']['fix'] = 'fix';

        if (!empty($maxIngredients)) {
            if (count($_SESSION['ingredients']) < ($maxIngredients - 1)) {

                if (!empty($allIngredients)) {
                    foreach ($allIngredients as $allIngredient) { // Все ингредиенты
                    // Ссылка на каждый ингредиент и GET параметры для сохранения выбранного ингредиента в сессию
                    echo '<p>' . Html::a($allIngredient->name, [
                            '/',
                            'ingredient' => ['id' => $allIngredient->id, 'name' => $allIngredient->name]
                        ]) . '</p>';
                    }
                }
            }
            if (count($_SESSION['ingredients']) >= ($maxIngredients - 1)) {
                echo '<p>Выбрано максимум ингредиентов</p>';
            }
        }
        if (empty($allIngredients)) {
            echo '<p>Нет доступных ингредиентов</p>';
        }
        ?>
    </div>

    <div class="col-md-3">
        <h4>Ингредиенты</h4><hr>
        <?php
        // Сохраняем ингредиенты в $_SESSION['ingredients'] пока он меньше 5 выбранных элементов и 1 элемента fix
        // Делаем разные ключи, чтобы элементы не перезаписывались. Ключи такие как значения
        if (!empty($_GET['ingredient']) && count($_SESSION['ingredients']) < $maxIngredients) {
            $_SESSION['ingredients'][$_GET['ingredient']['name']] = [
                    'id' => $_GET['ingredient']['id'],
                    'name' => $_GET['ingredient']['name']
            ];
        }

        if (!empty($minIngredients)) {
            if (count($_SESSION['ingredients']) < $minIngredients) {
                echo '<p>Здесь появятся выбранные ингредиенты</p>';
            }
            if (!empty($_SESSION['ingredients'])) {
                foreach ($_SESSION['ingredients'] as $key => $arrValues) {
                    if ($key != 'fix') echo '<p>' . $arrValues['name'] . '</p>'; // Выбранные ингредиенты
                }
                // Показывает кнопку, когда в сессию записывается первый элемент
                if (count($_SESSION['ingredients']) > ($minIngredients - 1)) {
                    echo Html::a('Сбросить', ['sess-ingredients'], ['class' => 'btn btn-primary']);
                }
            }
        }
        ?>
    </div>

    <div class="col-md-3">
        <h4>Блюда</h4><hr>
        <?php

        if (!empty($minIngredients)) {
            if (!empty($_SESSION['ingredients'])) {
                if (count($_SESSION['ingredients']) < $minIngredients) {
                    echo '<p>Здесь появятся подходящие блюда</p>';
                }

                foreach ($_SESSION['ingredients'] as $key => $arrElementsChoose) {

                    if (!empty($allIngredients)) {
                        foreach ($allIngredients as $oneIngredient) { // Все ингредиенты

                            if ($key != 'fix') {
                                // Сопоставил элементы из $_SESSION и все ингредиенты,
                                // чтобы получить объекты ингредиентов для запроса выборки блюд
                                if ($oneIngredient->id == $arrElementsChoose['id']) {
                                    $ingredientsChosen[] = $oneIngredient; // Выбранные ингредиенты(объекты)
                                }
                            }
                        }
                    }
                }
                // Сколько выбрано ингредиентов
                if (!empty($ingredientsChosen)) $countIngredientsChosen = count($ingredientsChosen);
            }
        }

        if (!empty($ingredientsChosen)) {
            foreach ($ingredientsChosen as $ingredientChosen) { // Выбранные ингредиенты(объекты)
                $dishes = $ingredientChosen->dishes; // Блюда для каждого выбранного ингредиента

                foreach ($dishes as $dish) {
                     $dishesForAllIngredients[] = $dish->name; // Все блюда для всех выбранных ингредиентов
                }
            }
            $countDishesRepeat = array();

            // Сколько раз блюдо повторилось, т.е. сколько выбранных ингредиентов есть в этом блюде
            $countDishesRepeat = array_count_values($dishesForAllIngredients);

            if ($countIngredientsChosen > 1) { // Кол-во выбранных ингредиентов больше 1
                foreach ($countDishesRepeat as $name => $countDishRepeat) { // Кол-во повторов блюд

                    if ($countDishRepeat == $countIngredientsChosen) {
                        $fullCoincident[] = $name; // Блюда в которых есть все выбранные ингредиенты
                    }else {
                        // Блюда в которых есть некоторые выбранные ингредиенты
                        $incompletely[$name] = [$name => $countDishRepeat];
                    }
                    if ($countDishRepeat > 1) $counter++; // Если совпадений больше одного
                }
                if ($counter < 1) echo '<p>Ничего не найдено</p>';

            } elseif ($countIngredientsChosen == 1) echo '<p>Выберите больше ингредиентов</p>';

            // Блюда в которых есть все выбранные ингредиенты
            if (!empty($fullCoincident)) {
                foreach ($fullCoincident as $dishName) {
                    echo "<p>$dishName</p>";
                }
            }
            // Блюда в которых есть все выбранные ингредиенты
            // в порядке уменьшения совпадения ингредиентов вплоть до 2х
            if (empty($fullCoincident)) {
                for ($i = 0; $i < $countIngredientsChosen; $i++) {

                    if (!empty($incompletely)) {
                        foreach ($incompletely as $items) {

                            foreach ($items as $dishName => $quantity) {

                                if ($quantity > 1 && $quantity == ($countIngredientsChosen - $i)) {
                                    echo "<p>$dishName</p>";
                                }
                            }
                        }
                    }
                }
            }
        }
        ?>            
    </div>
</div>