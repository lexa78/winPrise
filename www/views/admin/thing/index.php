<?php
    /** @var array $things */
    /** @var array $prisesTypes */
    /** @var array $units */
?>
<h1>Things</h1>
<div class="container">
    <?php if (count($things) > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Название</th>
                    <th>Тип приза</th>
                    <th>Единица измерения</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($things as $thing): ?>
                <tr>
                    <td><?= $thing['code'] ?></td>
                    <td><?= $thing['name'] ?></td>
                    <td><?= $prisesTypes[$thing['prise_id']]['name'] ?></td>
                    <td><?= $units[$thing['unit_id']]['name'] ?></td>
                    <td>
                        <a class="btn btn-success" href="/admin/things/edit?id=<?= $thing['id'] ?>">Редактировать</a>
                        <a class="btn btn-danger delete"
                           href="/admin/things/delete?id=<?= $thing['id'] ?>">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h3>Список с ценными призами пуст.</h3>
    <?php endif; ?>
    <div>
        <a class="btn btn-info" href="/admin/things/create">Добавить ценный приз</a>
    </div>
</div>
