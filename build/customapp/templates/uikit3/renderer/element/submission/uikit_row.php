<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$params = $this->app->data->create($params);

// add tooltip
$tooltip = '';
if ($params->get('show_tooltip') && ($description = $element->config->get('description'))) {
	$tooltip = ' data-uk-tooltip="pos: top-left" title="'.$description.'"';
}

// create label
$label  = '<label class="uk-form-label"'.$tooltip.'>';
$label .= $params->get('altlabel') ? $params->get('altlabel') : $element->config->get('name');
$label .= '</label>';

// create error
$error = '';
if (@$element->error) {
    $error = '<div class="uk-alert uk-alert-danger">'.(string) $element->error.'</div>';
}

// create class attribute
$class = 'element element-'.$element->getElementType().($params->get('required') ? ' required' : '').(@$element->error ? ' error' : '');

$element->loadAssets();

?>
<div class="uk-margin uk-form-horizontal <?php echo $class; ?>">
    <?php echo $label; ?>
	<div class="uk-form-controls">
        <?php echo $element->renderSubmission($params).$error; ?>
    </div>
</div>
