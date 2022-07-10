<?php
/** @var \app\models\PriseType $prisesType */
?>
<h1>Create type of prises</h1>
<?php

use app\core\form\CheckboxField;
use app\core\form\Form;

$form = Form::begin('', 'post');
?>
<?= $form->field($prisesType, 'code')?>
<?= $form->field($prisesType, 'name')?>
<?= new CheckboxField($prisesType, 'is_limited')?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>