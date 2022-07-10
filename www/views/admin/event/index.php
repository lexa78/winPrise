<?php /** @var array $events */ ?>
<h1>Events</h1>
<div class="container">
    <?php if (count($events) > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= $event['code'] ?></td>
                    <td><?= $event['name'] ?></td>
                    <td>
                        <a class="btn btn-success" href="/admin/events/edit?id=<?= $event['id'] ?>">Редактировать</a>
                        <a class="btn btn-danger delete"
                           href="/admin/events/delete?id=<?= $event['id'] ?>">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h3>Список с событиями пуст.</h3>
    <?php endif; ?>
    <div>
        <a class="btn btn-info" href="/admin/events/create">Добавить событие</a>
    </div>
</div>
