<?php
/* SVN FILE: $Id: tests_apps_controller.php 7490 2008-08-23 17:28:08Z mark_story $ */
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
 * @version			$Revision: 7490 $
 * @modifiedby		$LastChangedBy: mark_story $
 * @lastmodified	$Date: 2008-08-23 19:28:08 +0200 (Sa, 23 Aug 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
class TestsAppsController extends AppController {
	var $name = 'TestsApps';
	var $uses = array();

	function index() {
	}

	function some_method() {
		return 5;
	}
	
	function set_action() {
		$this->set('var', 'string');
		$this->render('index');
	}
}
?>