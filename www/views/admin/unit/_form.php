<?php
/** @var \app\models\Unit $unit */
?>
<h1>Create unit</h1>
<?php
use app\core\form\Form;
$form = Form::begin('', 'post');
?>
<?= $form->field($unit, 'code')?>
<?= $form->field($unit, 'name')?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>