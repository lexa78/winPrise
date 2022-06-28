<?php
/** @var \app\models\User $model */
?>
<h1>Login</h1>
<?php
use app\core\form\Form;
$form = Form::begin('', 'post');
?>
<?= $form->field($model, 'email')?>
<?= $form->field($model, 'password')->passwordField()?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>