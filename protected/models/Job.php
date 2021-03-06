<?php

/**
 * This is the model class for table "job".
 *
 * The followings are the available columns in table 'job':
 * @property integer $id
 * @property string $title
 * @property string $created
 * @property integer $work_hour
 * @property integer $travel_time
 * @property integer $parking_cost
 * @property string $comment
 * @property integer $project_id
 * @property integer $employee_id
 * @property integer $value
 * @property integer $holiday
 * @property integer $bonus
 * @property integer $foodmoney
 * @property integer $fuel
 * @property integer $punishment
 * @property integer $road
 * @property integer $parking
 * @property integer $taxi
 *
 * The followings are the available model relations:
 * @property Project $project
 * @property Employee $employee
 */
class Job extends CActiveRecord
{
    /**
     * @var store concrete value in many_to_many relation
     */
    public $value;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'job';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created, project_id, employee_id', 'required'),
            array('parking_cost, project_id, employee_id', 'numerical', 'integerOnly' => true),
            array('work_start, work_end, travel_time', 'date', 'format' => 'H:m'),
            array('created, holiday', 'date', 'format' => 'dd.mm.yyyy'),
            //array('title', 'length', 'max' => 1024),
            array('comment', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, created, work_start, work_end, travel_time, parking_cost, comment, project_id, employee_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),

            'nameValue' => array(self::HAS_MANY, 'JobToJobName', 'job_id'),
            'jobNameValues' => array(self::HAS_MANY, 'JobName', 'job_name_id', 'through' => 'nameValue'),

            'jobNames' => array(self::MANY_MANY, 'JobName', 'job_to_job_name(job_id, job_name_id)'),

            'materialValue' => array(self::HAS_MANY, 'JobToMaterial', 'job_id'),
            'materialValues' => array(self::HAS_MANY, 'Material', 'material_id', 'through' => 'materialValue'),

            'materials' => array(self::MANY_MANY, 'Material', 'job_to_material(job_id, material_id)'),

            'extraJobsValue' => array(self::HAS_MANY, 'JobToExtraJob', 'job_id'),
            'extraJobsValues' => array(self::HAS_MANY, 'ExtraJob', 'extra_job_id', 'through' => 'extraJobsValue'),

            'extraJobs' => array(self::MANY_MANY, 'ExtraJob', 'job_to_extra_job(job_id, extra_job_id)')
        );
    }

    /**
     * Added custom behavior for MANY_MANY handling
     * @return array
     *
     */
    public function behaviors()
    {
        return array(
            'CAdvancedArBehavior' => array('class' => 'application.extensions.CAdvancedArBehavior'),
            'datetimeI18NBehavior' => array('class' => 'ext.DateTimeI18NBehavior')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'bonus' => 'Bonusas',
            'created' => 'Atlikimo data',
            'work_start' => 'Darbo pradžia',
            'work_end' => 'Darbo pabaiga',
            'travel_time' => 'Kelionės laikas',
            'parking_cost' => 'Parkavimo išlaidos',
            'comment' => 'Komentaras',
            'project_id' => 'Projektas',
            'employee_id' => 'Darbuotojas',
            'holiday' => 'iki (imtinai)',
            'foodmoney' => 'Maistpinigiai',
            'fuel' => 'Kuras',
            'punishment' => 'Bauda',
            'road' => 'Kelio mokestis',
            'parking' => 'Parkavimas',
            'taxi' => 'Taxi',
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
        //$criteria->compare('title', $this->title, true);
        $criteria->compare('created', $this->created, true);
        //$criteria->compare('work_hour', $this->work_hour);
        $criteria->compare('travel_time', $this->travel_time);
        $criteria->compare('parking_cost', $this->parking_cost);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('project_id', $this->project_id);

        $criteria->with = array("project", "employee");

        if (Yii::app()->user->getId() !== "admin")
            $criteria->compare('employee_id', (int)Yii::app()->user->getState("id"));
        else
            $criteria->compare('employee_id', $this->employee_id);

        $size = Yii::app()->user->getState('grid');
        $size = isset($size[$this->tableName() . '/admin']) ? $size[$this->tableName() . '/admin'] : 25;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'created DESC',
            ),
            'pagination' => array('pageSize' => $size),
        ));
    }

    /***
     * Add jobName to job
     * @param $job
     */
    public function setJobNames($job)
    {
        $this->jobNames = $job;
    }

    /***
     * Add jobName to job
     * @param $mat
     */
    public function setMaterials($mat)
    {
        $this->materials = $mat;
    }

    /***
     * Add jobName to job
     * @param $ext
     */
    public function setExtraJobs($ext)
    {
        $this->extraJobs = $ext;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Job the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


}
