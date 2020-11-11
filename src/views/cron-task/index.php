<?php

use yii\console\ExitCode;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel gaxz\crontab\models\CronTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cron Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cron Task', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Logs', ['cron-task-log/index'], ['class' => 'btn btn-success']) ?>

    </p>

    <div class="grid-wrapper">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['style' => 'width:5%'],
                ],
                [
                    'attribute' => 'name',
                    'headerOptions' => ['style' => 'width:14%']
                ],
                [
                    'attribute' => 'updated_at',
                    'filter' => false,
                    'headerOptions' => ['style' => 'width:14%'],
                ],
                [
                    'attribute' => 'schedule',
                    'headerOptions' => ['style' => 'width:10%'],

                ],
                [
                    'attribute' => 'route',
                    'value' => function ($model) use ($routesList) {
                        return !empty($routesList[$model->route]) ? $routesList[$model->route] : $model->route;
                    },
                    'filter' => $routesList
                ],
                [
                    'label' => 'Latest Result',
                    'value' => function ($model) {
                        return !empty($model->latestLog) ? ExitCode::getReason($model->latestLog->exit_code) : null;
                    },
                ],
                [
                    'attribute' => 'is_enabled',
                    'label' => 'Status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->is_enabled ? 'Enabled' : "Disabled";
                    },
                    'filter' => [0 => 'Disabled', 1 => 'Enabled'],
                    'headerOptions' => ['style' => 'width:10%'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:10%'],
                    'template' => '{run} {changeStatus} {view} {update} {delete}',
                    'buttons' => [
                        'run' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-console" title="Execute" aria-hidden="true"></span>',
                                Url::to(['execute', 'id' => $model->id])
                            );
                        },
                        'changeStatus' => function ($url, $model, $key) {
                            if (!$model->is_enabled) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-play" title="Enable" aria-hidden="true"></span>',
                                    Url::to(['change-status', 'id' => $model->id])
                                );
                            }

                            return Html::a(
                                '<span class="glyphicon glyphicon-stop" title="Disable" aria-hidden="true"></span>',
                                Url::to(['change-status', 'id' => $model->id])
                            );
                        },
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-list" title="Logs" array-hidden="true"></span>',
                                Url::to(['view', 'id' => $model->id])
                            );
                        }
                    ],

                ],
            ],
        ]); ?>
    </div>

</div>