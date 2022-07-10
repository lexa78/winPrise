<?php /** @var array $roles */ ?>
<h1>Roles</h1>
<div class="container">
    <?php if (count($roles) > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td><?= $role['code'] ?></td>
                    <td><?= $role['name'] ?></td>
                    <td>
                        <a class="btn btn-success" href="/admin/roles/edit?id=<?= $role['id'] ?>">Редактировать</a>
                        <a class="btn btn-danger delete"
                           href="/admin/roles/delete?id=<?= $role['id'] ?>">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h3>Список с ролями пуст.</h3>
    <?php endif; ?>
    <div>
        <a class="btn btn-info" href="/admin/roles/create">Добавить роль</a>
    </div>
</div>
