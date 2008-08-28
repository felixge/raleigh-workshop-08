<?php
/**
 * undocumented class
 *
 * @package default
 * @access public
 */
class AppController extends Controller {
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

/**
 * Used to test whether a controller will redirect in the test suite
 *
 * @var string
 * @access public
 */
	var $redirectUrl = false;
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
	function beforeFilter() {
		$ajax = $isAjax = false;
		if ($this->isAjax()) {
			$this->layout = 'ajax';
			$ajax = $isAjax = true;
		}

		$this->set(compact('ajax', 'isAjax'));
	}
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
	function beforeRender() {
		$Command = ClassRegistry::init('Command');
		$this->set('commandCloud', $Command->cloud());
	}
/**
 * undocumented function
 *
 * @param string $url 
 * @param string $status 
 * @param string $exit 
 * @return void
 * @access public
 */
	function redirect($url, $status = null, $exit = true) {
		if (!isset($this->doRedirect) || !$this->doRedirect) {
			$this->redirectUrl = Router::url($url);
			return false;
		}
		return parent::redirect($url, $status, $exit);
	}
/**
 * undocumented function
 *
 * @param unknown $return
 * @return void
 * @access public
 */
	function isGet() {
		return $this->RequestHandler->isGet();
	}
/**
 * undocumented function
 *
 * @param unknown $return
 * @return void
 * @access public
 */
	function isPost() {
		return $this->RequestHandler->isPost();
	}
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
	function isPut() {
		return $this->RequestHandler->isPut();
	}
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
	function isDelete() {
		return $this->RequestHandler->isDelete();
	}
/**
 * undocumented function
 *
 * @param unknown $return
 * @return void
 * @access public
 */
	function isAjax() {
		return $this->RequestHandler->isAjax();
	}
}

?>