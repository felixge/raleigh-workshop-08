<?php
class MessageComponent extends Object {
	var $Controller;
	var $components = array('Session');
	var $sessKey = 'MessageComponent.messages';
	var $controllerVar = 'flashMessages';
	var $messages = array();
	var $sessMessages = array();

	function startup(&$Controller) {
		$this->Controller = &$Controller;
		$sessMessages = $this->Session->read($this->sessKey);

		if (empty($sessMessages)) {
			return $this->Controller->set($this->controllerVar, array());
		}

		$this->messages = $sessMessages;
		$this->Controller->set($this->controllerVar, $this->messages);
		$this->Session->delete($this->sessKey);
	}

	function add($text, $type = 'success', $session = false) {
		$message = array('type' => $type, 'text' => $text);
		if ($session == true) {
			$this->sessMessages[] = $message;
			return $this->Session->write($this->sessKey, $this->sessMessages);
		}

		$this->messages[] = $message;
		$this->Controller->set($this->controllerVar, $this->messages);
		return true;
	}
}
?>