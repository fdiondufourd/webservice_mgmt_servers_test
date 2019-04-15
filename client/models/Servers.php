<?php

namespace app\models;

use yii\base\Model;

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
class Servers extends Model
{

    public $id;
    public $internal_name;
    public $friendly_name;
    public $ipv4;
    public $status;
    
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
            [['friendly_name', 'ipv4', 'status'], 'required'],
            [['status'], 'string'],
            [['friendly_name'], 'string', 'max' => 100],
            [['friendly_name','ipv4', 'status'],'filter','filter'=>'\yii\helpers\HtmlPurifier::process'],
            [['internal_name'], 'string', 'max' => 150],
            [['ipv4'], 'string', 'max' => 25],
            [['id'], 'number']
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
