<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<div class="country-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::textInput('testSession') ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <hr />
    <h2>Session</h2>
    <pre>
	    <?= print_r($_SESSION, true) ?>
    </pre>
    <h2>Cookies</h2>
    <pre>
	    <?= print_r($_COOKIE, true) ?>
    </pre>
    <h2>\Yii::$app->request->cookies</h2>
    <pre>
	    <?= print_r(\Yii::$app->request->cookies, true) ?>
    </pre>
</div>
