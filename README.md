<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>
<p>
Выборка блюд по заданным пользователем ингредиентам.<br>
    
Административная часть: modules/admin/views/default/index.php<br>
CRUD добавления ингредиентов.<br>
CRUD формирования блюд из этих ингредиентов.<br>
Администратор может скрыть один из ингредиентов, в этом случае блюдо содержащее этот ингредиент тоже должно быть скрыто.<br>

Пользовательская часть: views/site/index.php<br>
Пользователь может выбрать до 5ти ингредиентов для приготовления блюда, при этом:<br>
Если найдены блюда с полным совпадением ингредиентов вывести только их.<br>
Если найдены блюда с частичным совпадением ингредиентов вывести в порядке уменьшения совпадения ингредиентов вплоть до 2х.<br>
Если найдены блюда с совпадением менее чем 2 ингредиента или не найдены вовсе вывести “Ничего не найдено”.<br>
Если выбрано менее 2х ингредиентов не производить поиск, выдать сообщение: “Выберите больше ингредиентов”.<br>

Для работы менеджера админка еще не готова.
</p>
