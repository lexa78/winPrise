<?php
/** @var \app\models\User $model */
?>
<h1>Create an account</h1>
<?php
use app\core\form\Form;
$form = Form::begin('', 'post');
?>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'firstName')?>
        </div>
        <div class="col">
            <?= $form->field($model, 'lastName')?>
        </div>
    </div>
    <?= $form->field($model, 'email')?>
    <?= $form->field($model, 'password')->passwordField()?>
    <?= $form->field($model, 'passwordConfirm')->passwordField()?>

    <button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>