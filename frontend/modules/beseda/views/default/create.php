<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Beseda */

$this->title = 'Добавить беседу';
$this->params['breadcrumbs'][] = ['label' => 'Беседы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beseda-create">


    <?= $this->render('_form', [
        'model' => $model,
        'besedaAgreementArr' => $besedaAgreementArr
    ]) ?>

</div>
