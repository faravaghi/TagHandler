<?php
/**
 * TagHandler widget class
 *
 * @author: Moahammad Ebrahim Amini <faravaghi@gmail.com>
 * @copyright Copyright &copy; 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package TagHandler.widgets
 *
 * Example :
 *	$this->widget('ext.TagHandler.TagHandler',array(
 * 		'name'=>'Entry[Associates]',
 *		'options'=>array(
 *			//'getURL'=>$ajaxURL,
 *			'assigned'=> Site::model()->findAll(),
 *			'available'=> Example::model()->findAll(),
 *			'autocomplete'=>true,
 *		),
 *	));
 */
class TagHandler extends CInputWidget
{
	/**
	 * @var TbActiveForm when created via TbActiveForm, this attribute is set to the form that renders the widget
	 * @see TbActionForm->inputRow
	 */
	public $form;
 
	/**
     * Html ID
     * @var string
     */
    public $id = 'tagWidget';

    /**
     * Initial options
     * @var array
     */
	public $options = array();

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		/* $this->htmlOptions['autocomplete'] = true; */
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		list($this->name, $this->id) = $this->resolveNameId();

		echo '<ul id="'.$this->id.'_0"></ul>';
		if ($this->hasModel())
		{
			if($this->form)
				echo $this->form->hiddenField($this->model, $this->attribute, $this->htmlOptions);
			else
				echo CHtml::hiddenField($this->model, $this->attribute, $this->htmlOptions);
		} else
			echo CHtml::hiddenField($this->name, '', $this->htmlOptions);
		
		$this->registerClientScript($this->id);
	}

	/**
	 * Registers required client script for bootstrap datepicker. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript($id)
	{
		$baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.TagHandler').'/assets');

		$cs=Yii::app()->getClientScript();
		$cs->registerCssFile($baseScriptUrl.'/css/jquery.taghandler.css');
		$cs->registerCssFile($baseScriptUrl.'/css/Aristo/Aristo.css');
		$cs->registerScriptFile($baseScriptUrl.'/js/jquery.taghandler.'.(YII_DEBUG ? '' : 'min.').'js');
		//$cs->registerScriptFile($baseScriptUrl.'/js/jquery-ui-1.8.23.custom.'.(YII_DEBUG ? '' : 'min.').'js');
		$cs->registerScriptFile($baseScriptUrl.'/js/jquery-ui-1.8.23.custom.min.js');
		
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';
		ob_start();

		/* echo "jQuery(\"#{$id}\").tagHandler({$options})"; */
		echo "$(\"#{$id}_0\").tagHandler({".
				(isset($this->options['assigned']) ? "assignedTags: ". CJavaScript::encode($this->options['assigned']) ."," : "").
				(isset($this->options['available']) ? "availableTags:". CJavaScript::encode($this->options['available']) ."," : "").
				(isset($this->options['autocomplete']) ? "autocomplete:". $this->options['autocomplete'] ."," : "").
			"})";
		
		Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, ob_get_clean() . ';');

	}
}
