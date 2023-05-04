<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

if (session_status() == PHP_SESSION_NONE) session_start();

Pjax::begin(['timeout' => 5000]);

/** @var yii\web\View $this */

$this->title = Yii::$app->name;

$counter = null;
?>

<div class="jumbotron text-center bg-transparent">
    <h6 class="display-4">Пицца на выбор!</h6>
    <p>Выберите ингредиенты и получите блюдо</p>
</div>

<div class="row">
    <div class="col-md-3">
        <h4>Все блюда</h4><hr>
        <?php

        if (!empty($allDishes)) { // Все названия блюд
            foreach ($allDishes as $dish) {
                $ingredientsOn = array();

                $ingredients = $dish->ingredients; // Ингредиенты для каждого блюда
                $count = count($ingredients);

                foreach ($ingredients as $ingredient) { // Ингредиенты для каждого блюда
                    if (!empty($enabledIngredients)) {
                        if ($ingredient->status == $enabledIngredients) { // Если статус ингредиента On
                            $ingredientsOn[] = $ingredient; // Ингредиенты для каждого блюда со статусом On
                        }
                    }
                    if (!empty($ingredientsOn)) {
                        $countOn = count($ingredientsOn); // Количество ингредиентов для каждого блюда со статусом On
                    }
                }
                // Если кол-во ингредиентов в блюде равно количеству ингредиентов в блюде со статусом On
                if (!empty($count) && !empty($countOn)) {
                    if ($count == $countOn) {
                        $enableDishes[] = $dish->name;
                    }
                }
            }
            foreach ($enableDishes as $enableDish) {
                echo "<p>$enableDish</p>";
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

                if (!empty($allIngredientsOn)) {
                    foreach ($allIngredientsOn as $ingredientOn) { // Все ингредиенты

                        $id = $ingredientOn->id;
                        $name = $ingredientOn->name;
                    // Ссылка на каждый ингредиент и GET параметры для сохранения выбранного ингредиента в сессию
                    echo '<p>' . Html::a($ingredientOn->name, [
                            '/'
                        ],
                            ['data-method' => 'POST',
                                'data-params' => [
                                    'ingredient-id' => $id,
                                    'ingredient-name' => $name,
                                ],
                                'data-pjax' => 1
                            ]) . '</p>';
                    }
                }
            }
            if (count($_SESSION['ingredients']) >= ($maxIngredients - 1)) {
                echo '<p>Выбрано максимум ингредиентов</p>';
            }
        }
        if (empty($allIngredientsOn)) {
            echo '<p>Нет доступных ингредиентов</p>';
        }
        ?>
    </div>

    <div class="col-md-3">
        <h4>Ингредиенты</h4><hr>
        <?php
        // Сохраняем ингредиенты в $_SESSION['ingredients'] пока он меньше 5 выбранных элементов и 1 элемента fix
        // Делаем разные ключи, чтобы элементы не перезаписывались. Ключи такие как значения
        if (!empty($_POST['ingredient-name']) && count($_SESSION['ingredients']) < $maxIngredients) {
            $_SESSION['ingredients'][$_POST['ingredient-name']] = [
                    'id' => $_POST['ingredient-id'],
                    'name' => $_POST['ingredient-name']
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
                    echo Html::a('Сбросить',
                        ['/'],
                        ['data-method' => 'POST',
                            'data-params' => [
                                'drop-ingredients' => true
                            ],
                            'data-pjax' => 1,
                            'class' => 'btn btn-primary'
                        ]
                    );
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

                    if (!empty($allIngredientsOn)) {
                        foreach ($allIngredientsOn as $ingredientOn) { // Все ингредиенты

                            if ($key != 'fix') {
                                // Сопоставил элементы из $_SESSION и все ингредиенты,
                                // чтобы получить объекты ингредиентов для запроса выборки блюд
                                if ($ingredientOn->id == $arrElementsChoose['id']) {
                                    $ingredientsChosen[] = $ingredientOn; // Выбранные ингредиенты(объекты)
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

                    foreach ($enableDishes as $enableDish) {
                        if ($enableDish == $dishName) {
                            echo "<p>$dishName</p>";
                        }
                    }
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

                                    foreach ($enableDishes as $enableDish) {
                                        if ($enableDish == $dishName) {
                                            echo "<p>$dishName</p>";
                                        }
                                    }
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
<div class="jumbotron text-center bg-transparent">
    <h6 class="display-4">Все блюда и их ингредиенты</h6>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Представленные блюда</th>
                <th scope="col">Состав ингредиентов</th>
            </tr>
            </thead>
            <tbody>
            <?php

            if (!empty($allDishes)) {
                foreach ($allDishes as $dish) { // Все названия блюд
                    echo "<tr><td><p>$dish->name</p></td><td>";

                    $ingredients = $dish->ingredients; // Все ингридиенты для конкретного блюда
                    foreach ($ingredients as $ingredient) {
                        echo "<p>$ingredient->name</p>"; // Все ингридиенты для конкретного блюда
                    }
                    echo "</td></tr>";
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php Pjax::end(); ?>