<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */


// no direct access
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-'.$this->submission->alias; ?>">

	<h1><?php echo Text::_('My Submissions'); ?></h1>

	<p><?php echo sprintf(Text::_('Hi %s, here you can edit your submissions and add new submission.'), $this->user->name); ?></p>

	<?php

		echo $this->partial('mysubmissions');

	?>

</div>
