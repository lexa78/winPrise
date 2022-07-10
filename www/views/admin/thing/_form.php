<?php
/** @var \app\models\Thing $thing */
/** @var array $prisesTypes */
/** @var array $units */
?>
<h1>Create thing</h1>
<?php
use app\core\form\Form;
use app\core\form\SelectField;

$form = Form::begin('', 'post');
?>
<?= $form->field($thing, 'code')?>
<?= $form->field($thing, 'name')?>
<?= new SelectField($thing, 'prise_id', $prisesTypes)?>
<?= new SelectField($thing, 'unit_id', $units)?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>