<?php
/* SVN FILE: $Id: i18n.php 7100 2008-06-03 05:11:04Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *							  1785 E. Sahara Avenue, Suite 490-204
 *							  Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright	   Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package		 cake
 * @subpackage	  cake.cake.console.libs
 * @since		   CakePHP(tm) v 1.2.0.5669
 * @version		 $Revision: 7100 $
 * @modifiedby	  $LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-06-03 07:11:04 +0200 (Di, 03 Jun 2008) $
 * @license		 http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Shell for I18N management.
 *
 * @package		cake
 * @subpackage	cake.cake.console.libs
 */
class I18nShell extends Shell {
/**
 * Contains database source to use
 *
 * @var string
 * @access public
 */
	var $dataSource = 'default';
/**
 * Contains tasks to load and instantiate
 *
 * @var array
 * @access public
 */
	var $tasks = array('DbConfig', 'Extract');

/**
 * Override startup of the Shell
 *
 * @access public
 */
	function startup() {
		$this->_welcome();
		if (isset($this->params['datasource'])) {
			$this->dataSource = $this->params['datasource'];
		}

		if ($this->command && !in_array($this->command, array('help'))) {
			if (!config('database')) {
				$this->out(__('Your database configuration was not found. Take a moment to create one.', true), true);
				return $this->DbConfig->execute();
			}
		}
	}
/**
 * Override main() for help message hook
 *
 * @access public
 */
	function main() {
		$this->out(__('I18n Shell', true));
		$this->hr();
		$this->out(__('[E]xtract POT file from sources', true));
		$this->out(__('[I]nitialize i18n database table', true));
		$this->out(__('[H]elp', true));
		$this->out(__('[Q]uit', true));

		$choice = strtoupper($this->in(__('What would you like to do?', true), array('E', 'I', 'H', 'Q')));
		switch($choice) {
			case 'E':
				$this->Extract->execute();
			break;
			case 'I':
				$this->initdb();
			break;
			case 'H':
				$this->help();
			break;
			case 'Q':
				exit(0);
			break;
			default:
				$this->out(__('You have made an invalid selection. Please choose a command to execute by entering E, I, H, or Q.', true));
		}
		$this->hr();
		$this->main();
	}
/**
 * Initialize I18N database.
 *
 * @access public
 */
	function initdb() {
		$this->Dispatch->args = array('schema', 'run', 'create', 'i18n');
		$this->Dispatch->dispatch();
	}
/**
 * Show help screen.
 *
 * @access public
 */
	function help() {
		$this->hr();
		$this->out(__('I18n Shell:', true));
		$this->hr();
		$this->out(__('I18n Shell initializes i18n database table for your application', true));
		$this->out(__('and generates .pot file(s) with translations.', true));
		$this->hr();
		$this->out(__('usage:', true));
		$this->out('   cake i18n help');
		$this->out('   cake i18n initdb [-datasource custom]');
		$this->out('');
		$this->hr();

		$this->Extract->help();
	}
}

?>