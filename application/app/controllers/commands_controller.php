<?php
class CommandsController extends AppController {
/**
 * undocumented function
 *
 * @param string $id 
 * @return void
 * @access public
 */
	function view($id = null) {
		$conditions = array('Command.id' => $id);
		$contain = array('Snippet');
		$command = $this->Command->find('first', compact('conditions', 'contain'));

		if (empty($command)) {
			$this->Session->setFlash('Sorry, this is an invalid snippet');
			return $this->redirect(array('controller' => 'snippets', 'action' => 'index'));
		}
		$this->set(compact('command'));
	}
}

?>