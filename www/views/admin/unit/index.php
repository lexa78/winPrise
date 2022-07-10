<?php /** @var array $units */ ?>
<h1>Units</h1>
<div class="container">
    <?php if (count($units) > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($units as $unit): ?>
                <tr>
                    <td><?= $unit['code'] ?></td>
                    <td><?= $unit['name'] ?></td>
                    <td>
                        <a class="btn btn-success" href="/admin/units/edit?id=<?= $unit['id'] ?>">Редактировать</a>
                        <a class="btn btn-danger delete"
                           href="/admin/units/delete?id=<?= $unit['id'] ?>">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h3>Список с единицами измерения пуст.</h3>
    <?php endif; ?>
    <div>
        <a class="btn btn-info" href="/admin/units/create">Добавить единицу измерения</a>
    </div>
</div>
