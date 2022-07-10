<?php
/** @var \app\models\Event $event */
?>
<h1>Create event</h1>
<?php
use app\core\form\Form;
$form = Form::begin('', 'post');
?>
<?= $form->field($event, 'code')?>
<?= $form->field($event, 'name')?>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end()?>