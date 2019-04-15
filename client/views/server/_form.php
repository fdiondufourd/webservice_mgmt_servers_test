<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Servers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'friendly_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipv4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'pending' => 'Pending', 'active' => 'Active', 'inactive' => 'Inactive', 'deleted' => 'Deleted', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
