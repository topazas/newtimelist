<?php

/**
 * This is the model class for table "extra_job".
 *
 * The followings are the available columns in table 'extra_job':
 * @property integer $id
 * @property string $title_lt
 * @property string $title_no
 */
class ExtraJob extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'extra_job';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title_lt, title_no, title_en, unit', 'required'),
            array('title_lt, title_no, title_en', 'length', 'max' => 1024),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title_lt, title_no', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title_lt' => Yii::t('admin', 'Pavadinimas') . ' ' . Yii::t('admin', '(LT)'),
            'title_no' => Yii::t('admin', 'Pavadinimas') . ' ' . Yii::t('admin', '(NO)'),
            'title_en' => Yii::t('admin', 'Pavadinimas') . ' ' . Yii::t('admin', '(EN)'),
            'unit' => Yii::t('admin', 'Matavimo vnt.'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title_lt', $this->title_lt, true);
        $criteria->compare('title_no', $this->title_no, true);
        $criteria->compare('title_en', $this->title_en, true);
        $criteria->compare('unit', $this->unit, true);

        $size = Yii::app()->user->getState('grid');
        $size = isset($size[$this->tableName() . '/admin']) ? $size[$this->tableName() . '/admin'] : 25;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => $size),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ExtraJob the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return mixed
     */
    public function getTitleByLang()
    {
        $lang = 'title_' . Yii::app()->language;
        return $this->$lang;
    }

    /**
     * Check foreign key
     */
    public function canBeDeleted($attribute)
    {
        $count = Project::Model()->count("customer_id=:field", array("field" => $this->id));

        if ($count > 0)
        {
            $this->addError('id', 'foreign key violation');
            return false;
        }

        return true;
    }
}
