<?php
/** @var \app\models\ChangeCourse $changeCourse */
/** @var array $prises */
?>
<h1>Create course of change</h1>
<?php

use app\core\form\SelectField;
use app\core\form\Form;

$form = Form::begin('', 'post');
?>
<?= new SelectField($changeCourse, 'thing_id', $prises)?>
<?= $form->field($changeCourse, 'course')?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>