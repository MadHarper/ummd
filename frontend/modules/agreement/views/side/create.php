<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\SideAgr */

$this->title = $agreement->shortName . ': Добавить сторону соглашения';
$this->params['breadcrumbs'][] = ['label' => $agreement->shortName, 'url'=> Url::to(['/agreement/default/view', 'id' => $agreement->id])];
$this->params['breadcrumbs'][] = ['label' => 'Стороны соглашения', 'url' => Url::to(['/agreement/side/index', 'agreementId' => $agreement->id])];
$this->params['breadcrumbs'][] = 'Добавить сторону соглашения';
?>
<div class="side-agr-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
