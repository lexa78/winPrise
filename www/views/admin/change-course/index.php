<?php
    /** @var array $changeCourses */
    /** @var array $prises */
?>
<h1>Courses of change</h1>
<div class="container">
    <?php if (count($changeCourses) > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Приз</th>
                    <th>Курс</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($changeCourses as $changeCourse): ?>
                <tr>
                    <td><?= $prises[$changeCourse['thing_id']]['name'] ?></td>
                    <td><?= $changeCourse['course'] ?></td>
                    <td>
                        <a class="btn btn-success" href="/admin/courses/edit?id=<?= $changeCourse['id'] ?>">Редактировать</a>
                        <a class="btn btn-danger delete"
                           href="/admin/courses/delete?id=<?= $changeCourse['id'] ?>">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h3>Список с курсами обмена пуст.</h3>
    <?php endif; ?>
    <div>
        <a class="btn btn-info" href="/admin/courses/create">Добавить новый курс для новой вещи</a>
    </div>
</div>
