<?php
/** @var \app\models\Role $role */
?>
<h1>Create role</h1>
<?php
use app\core\form\Form;
$form = Form::begin('', 'post');
?>
<?= $form->field($role, 'code')?>
<?= $form->field($role, 'name')?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>