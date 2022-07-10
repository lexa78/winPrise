<?php /** @var array $prisesTypes */ ?>
<h1>Prises type</h1>
<div class="container">
    <?php if (count($prisesTypes) > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Название</th>
                    <th>Может ли закончиться</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($prisesTypes as $prisesType): ?>
                <tr>
                    <td><?= $prisesType['code'] ?></td>
                    <td><?= $prisesType['name'] ?></td>
                    <td>
                        <?php if ($prisesType['is_limited']): ?>
                            Да
                        <?php else: ?>
                            Нет
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="btn btn-success" href="/admin/prise-types/edit?id=<?= $prisesType['id'] ?>">Редактировать</a>
                        <a class="btn btn-danger delete"
                           href="/admin/prise-types/delete?id=<?= $prisesType['id'] ?>">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h3>Список с типами призов пуст.</h3>
    <?php endif; ?>
    <div>
        <a class="btn btn-info" href="/admin/prise-types/create">Добавить тип призов</a>
    </div>
</div>
