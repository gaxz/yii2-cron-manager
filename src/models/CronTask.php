<?php

namespace gaxz\crontab\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cron_task".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $schedule
 * @property string|null $route
 * @property string|null $params
 * @property int|null $is_enabled
 */
class CronTask extends ActiveRecord
{
    /**
     * @var SCHEDULE_REGEX Regular expression of cron schedule
     */
    const SCHEDULE_REGEX = "/^((?:[1-9]?\d|\*)\s*(?:(?:[\/-][1-9]?\d)|(?:,[1-9]?\d)+)?\s*){5}$/";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['name', 'description'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'safe'],
            [['is_enabled'], 'integer'],
            [['params'], 'validateJson'],
            [['schedule', 'route', 'description'], 'string', 'max' => 255],
            [['schedule'], 'match', 'pattern' => self::SCHEDULE_REGEX],
            [['schedule', 'route'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'schedule' => 'Schedule',
            'route' => 'Route',
            'params' => 'Params',
            'is_enabled' => 'Is Enabled',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCronTaskLogs()
    {
        return $this->hasMany(CronTaskLog::className(), ['cron_task_id' => 'id']);
    }

    /**
     * Takes ActiveQuery of CronTaskLogs and filters latest log
     * {@inheritdoc}
     */
    public function getLatestLog()
    {
        return $this->getCronTaskLogs()->orderBy(['created_at' => SORT_DESC])->one();
    }

    /**
     * @param string $attribute
     * @return boolean
     */
    public function validateJson($attribute)
    {
        if (empty($this->$attribute) || json_decode($this->$attribute)) {
            return true;
        }

        $this->addError($attribute, 'The field must contain a valid JSON string');
        return false;
    }

    /**
     * Make crontab line
     *
     * @param string $phpBin 
     * @param string $yiiBootstrap 
     * @param string $route
     * @return string
     */
    public function getLine($phpBin, $yiiBootstrap, $route, $outputSetting): string
    {
        return "{$this->schedule} {$phpBin} {$yiiBootstrap} {$route} {$this->id} {$outputSetting}";
    }

    public function getPrettyName()
    {
        if (empty($this->name)) {
            return "ID: {$this->id} ({$this->route})";
        }

        return "ID: {$this->id} - {$this->name}";
    }
}
