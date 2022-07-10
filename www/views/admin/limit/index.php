<?php
    /** @var array $limits */
    /** @var array $prises */
?>
<h1>Limits</h1>
<div class="container">
    <?php if (count($limits) > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Приз</th>
                    <th>Минимальное количество, которое можно выиграть</th>
                    <th>Максимальное количество, которое можно выиграть</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($limits as $limit): ?>
                <tr>
                    <td><?= $prises[$limit['thing_id']]['name'] ?></td>
                    <td><?= $limit['min_value'] ?></td>
                    <td><?= $limit['max_value'] ?></td>
                    <td>
                        <a class="btn btn-success" href="/admin/limits/edit?id=<?= $limit['id'] ?>">Редактировать</a>
                        <a class="btn btn-danger delete"
                           href="/admin/limits/delete?id=<?= $limit['id'] ?>">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h3>Список с ограничениями пуст.</h3>
    <?php endif; ?>
    <div>
        <a class="btn btn-info" href="/admin/limits/create">Добавить новое ограничение для новой вещи</a>
    </div>
</div>
