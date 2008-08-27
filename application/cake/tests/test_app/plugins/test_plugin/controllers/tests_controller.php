<?php
/* SVN FILE: $Id: tests_controller.php 7413 2008-08-01 16:23:16Z mark_story $ */
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
 * @package			cake.tests
 * @subpackage		cake.tests.test_app.plugins.test_plugin.views.helpers
 * @since			CakePHP(tm) v 1.2.0.4206
 * @version			$Rev: 7413 $
 * @modifiedby		$LastChangedBy: mark_story $
 * @lastmodified	$Date: 2008-08-01 18:23:16 +0200 (Fr, 01 Aug 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
class TestsController extends TestPluginAppController {
	var $name = 'Tests';
	var $uses = array();
	var $helpers = array('TestPlugin.OtherHelper', 'Html');
	var $components = array('TestPlugin.PluginsComponent');

	function index() {
	}

	function some_method() {
		return 25;
	}
}
?>