<?php
/* SVN FILE: $Id: content.php 7062 2008-05-30 11:29:53Z nate $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake
 * @subpackage		cake.cake.tests.lib
 * @since			CakePHP(tm) v 1.2.0.4433
 * @version			$Revision: 7062 $
 * @modifiedby		$LastChangedBy: nate $
 * @lastmodified	$Date: 2008-05-30 13:29:53 +0200 (Fr, 30 Mai 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
?>
<div class="test-menu">
<ul>
	<li>
		<span style="font-size: 18px">Core</span>
		<ul>
			<li><a href='<?php echo $groups;?>'>Test Groups</a></li>
			<li><a href='<?php echo $cases;?>'>Test Cases</a></li>
		</ul>
	</li>
	<li style="padding-top: 10px">
		<span  style="font-size: 18px">App</span>
		<ul>
			<li><a href='<?php echo $groups;?>&amp;app=true'>Test Groups</a></li>
			<li><a href='<?php echo $cases;?>&amp;app=true'>Test Cases</a></li>
		</ul>
	</li>
<?php
if (!empty($plugins)):
?>
	<li style="padding-top: 10px">
		<span  style="font-size: 18px">Plugins</span>
	<?php foreach($plugins as $plugin):
			$pluginPath = Inflector::underscore($plugin);
	?>
			<ul>
				<li style="padding-top: 10px">
					<span  style="font-size: 18px"><?php echo $plugin;?></span>
					<ul>
						<li><a href='<?php echo $groups;?>&amp;plugin=<?php echo $pluginPath; ?>'>Test Groups</a></li>
						<li><a href='<?php echo $cases;?>&amp;plugin=<?php echo $pluginPath; ?>'>Test Cases</a></li>
					</ul>
				</li>
			</ul>
	<?php endforeach; ?>
<?php endif;?>
</ul>
</div>
<div  class="test-results">
