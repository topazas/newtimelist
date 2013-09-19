<div class="box box-color box-bordered">
    <div class="box-title">
        <h3><i class="glyphicon-spade"></i><?php echo Yii::t('admin', 'Projektų'), ' ', Yii::t('admin', 'sąrašas'); ?>
        </h3>

        <div class="actions">
            <?php $this->widget('bootstrap.widgets.TopazGridDropDown', array('c' => 'project', 'a' => 'invoice')); ?>
        </div>

    </div>
    <?php

    Yii::app()->clientScript->registerScript('re-install-date-picker', "
        function reinstallDatePicker(id, data) {
            $('#project_start').datepicker();
            $('#project_end').datepicker();
        }
        ");

    $this->breadcrumbs = array(
        Yii::t('admin', 'Projektai') => array('index'),
        Yii::t('admin', 'Sąrašas'),
    );
    /*
    $this->menu = array(
        array('label' => 'List Project', 'url' => array('index')),
        array('label' => 'Create Project', 'url' => array('create')),
    );
    */

    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('project-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
    ?>

    <!--
    <p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
    </p>
    -->


    <div class="search-form" style="display:none">
        <?php $this->renderPartial('_search', array(
            'model' => $model,
        )); ?>
    </div>
    <!-- search-form -->

    <?php $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'project-grid',
        'ajaxUpdate' => false,
        'template' => '<div class="table table-nomargin table-condensed table-bordered dataTable">{items}{pager}{summary}</div>',
        'dataProvider' => $model->invoice(),
        'filter' => $model,
        'filterCssClass' => 'thefilter',
        'summaryCssClass' => 'table-pagination',
        'pagerCssClass' => 'table-pagination',
        'pager' => array('class' => 'bootstrap.widgets.TbPager', 'header' => ''),
        'summaryText' => Yii::t('app', 'Rodomi įrašai:  <span>{start}</span> - <span>{end}</span> iš <span>{count}</span>'),
        'htmlOptions' => array(
            'class' => 'box-content nopadding',
        ),
        'rowCssClassExpression' => '$data["is_checkout"] ? "" : "red"',
        'afterAjaxUpdate' => 'reinstallDatePicker',
        'itemsCssClass' => 'table table-hover table-nomargin table-bordered dataTable-columnfilter dataTable',
        'columns' => array(
            array(
                'class' => 'bootstrap.widgets.TbGridColumn',
                'name' => 'pid',
                'type' => 'html',
                'value' => function ($data)
                {
                    return CHtml::link($data->pid, CHtml::normalizeUrl(array('/Project/update/' . $data->id)));
                },
                'headerHtmlOptions' => array(
                    'class' => 'sorting',
                    'role' => 'columnheader'
                )),

            array(
                'class' => 'bootstrap.widgets.TbGridColumn',
                'name' => 'title',
                'type' => 'html',
                'value' => function ($data)
                {
                    return CHtml::link($data->title, CHtml::normalizeUrl(array('/Project/update/' . $data->id)));
                },
                'headerHtmlOptions' => array(
                    'class' => 'sorting',
                    'role' => 'columnheader'
                )),
            /*
                                array(
                                    'class' => 'bootstrap.widgets.TbGridColumn',
                                    'name' => 'adress',
                                    'headerHtmlOptions' => array(
                                        'class' => 'sorting',
                                        'role' => 'columnheader'
                                    )),
            */
            array(
                'class' => 'bootstrap.widgets.TbGridColumn',
                'name' => 'customer_id',
                'filter' => CHtml::listData(Project::model()->findAll(), 'id', 'title'),
                'value' => '$data->customer->title',
                'headerHtmlOptions' => array(
                    'class' => 'sorting',
                    'role' => 'columnheader'
                )
            ),
            array(
                'class' => 'bootstrap.widgets.TbGridColumn',
                'name' => 'project_end',
                'value' => function ($data)
                {
                    return ($data->project_end === "0000-00-00") ? "" : $data->project_end;
                },
                'headerHtmlOptions' => array(
                    'class' => 'sorting',
                    'role' => 'columnheader'
                ),
                'filterInputHtmlOptions' => array(
                    'input_class' => 'datepick',
                    'input_id' => 'project_end'
                )),
            /*
                                    array(
                                'class'=>'bootstrap.widgets.TbGridColumn',
                                'name'=>'status_id',
                                'headerHtmlOptions' => array(
                                    'class' => 'sorting',
                                    'role' => 'columnheader'
                            )),
*/
            array(
                'class' => 'bootstrap.widgets.TbGridColumn',
                'name' => 'is_checkout',
                'type' => 'html',
                'filter' => CHtml::listData(array(array('id' => 1, 'title' => 'Taip'), array('id' => 0, 'title' => 'Ne')), 'id', 'title'),
                'value' => function ($data)
                {
                    return ($data->is_checkout == "1") ? 'Taip' : 'Ne';
                },
                'headerHtmlOptions' => array(
                    'class' => 'sorting',
                    'role' => 'columnheader'
                )),


            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('admin', 'Veiksmai'),
                'viewButtonIcon' => false
            ),
        ),
    )); ?>
</div>
