<?php
class AppController extends Controller{
	var $components = array(
		'RequestHandler',
		'Cookie',
	);

	var $helpers = array(
		'Html',
		'Javascript',
		'Time',
		'Form',
		'Text',
	);

	var $redirectUrl = false;

	function beforeRender() {
		$Command = ClassRegistry::init('Command');
		$this->set('commandCloud', $Command->find('cloud'));
	}

	function redirect($url, $status = null, $exit = true) {
		if (defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
			$this->redirectUrl = Router::url($url);
			return false;
		}

		return parent::redirect($url, $status, $exit);
	}

	function isGet() {
		return $this->RequestHandler->isGet();
	}

	function isPost() {
		return $this->RequestHandler->isPost();
	}

	function isPut() {
		return $this->RequestHandler->isPut();
	}

	function isDelete() {
		return $this->RequestHandler->isDelete();
	}

	function isAjax() {
		return $this->RequestHandler->isAjax();
	}
}

?>