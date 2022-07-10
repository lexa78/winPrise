<?php
/** @var \app\models\Limit $limit */
/** @var array $prises */
?>
<h1>Create limit</h1>
<?php

use app\core\form\SelectField;
use app\core\form\Form;

$form = Form::begin('', 'post');
?>
<?= new SelectField($limit, 'thing_id', $prises)?>
<?= $form->field($limit, 'min_value')?>
<?= $form->field($limit, 'max_value')?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>