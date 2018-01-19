<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\SideAgr */

$this->title = $agreement->shortName . ': Редактировать сторону соглашения';;
$this->params['breadcrumbs'][] = ['label' => $agreement->shortName, 'url'=> Url::to(['/agreement/default/view', 'id' => $agreement->id])];
$this->params['breadcrumbs'][] = ['label' => 'Стороны соглашения', 'url' => Url::to(['/agreement/side/index', 'agreementId' => $agreement->id])];
$this->params['breadcrumbs'][] = 'Редактировать сторону соглашения';
?>
<div class="side-agr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
