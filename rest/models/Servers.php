<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "servers".
 *
 * @property int $id
 * @property string $internal_name
 * @property string $friendly_name
 * @property string $ipv4
 * @property string $status
 */
class Servers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['internal_name', 'friendly_name', 'ipv4'], 'required'],
            [['friendly_name','ipv4', 'status'],'filter','filter'=>'\yii\helpers\HtmlPurifier::process'],
            [['status'], 'string'],
            [['internal_name', 'friendly_name'], 'string', 'max' => 150],
            [['ipv4'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'internal_name' => 'Internal Name',
            'friendly_name' => 'Friendly Name',
            'ipv4' => 'Ipv4',
            'status' => 'Status',
        ];
    }
}
