<?php

use yii\console\ExitCode;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTask */

$this->title = $model->getPrettyName();
    
$this->params['breadcrumbs'][] = ['label' => 'Cron Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-task-view">

    <h1><?= Html::encode($this->title) ?></h1>
        
    <p>

        <?= Html::a('Execute', ['execute', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a($model->is_enabled ? 'Stop' : 'Enable', ['change-status', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description',
            'created_at',
            'updated_at',
            'schedule',
            'route',
            'is_enabled:boolean',
        ],
    ]) ?>


    <h1>Logs</h1>

    <?= GridView::widget([
        'dataProvider' => $logDataProvider,
        'filterModel' => $logSearchModel,
        'columns' => [
            'id',
            'created_at',
            'output:ntext',
            [
                'attribute' => 'exit_code',
                'value' => function ($model) {
                    return ExitCode::getReason($model->exit_code);
                },
                'filter' => ExitCode::$reasons
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open" title="View" aria-hidden="true"></span>',
                            Url::to(['cron-task-log/view', 'id' => $model->id])
                        );
                    }
                ]
            ],
        ],
    ]); ?>

</div>