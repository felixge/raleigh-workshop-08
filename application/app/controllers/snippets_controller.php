<?php
class SnippetsController extends AppController {
	var $paginate = array('limit' => 2);
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
	function index() {
		$snippets = $this->Snippet->find('all');
		$this->set(compact('snippets'));
	}
/**
 * undocumented function
 *
 * @param string $id 
 * @return void
 * @access public
 */
	function view($id = null) {
		$conditions = array('Snippet.id' => $id);
		$contain = array('Command');
		$snippet = $this->Snippet->find('first', compact('conditions', 'contain'));

		if (empty($snippet)) {
			$this->Session->setFlash('Sorry, this is an invalid snippet');
			return $this->redirect(array('action' => 'index'));
		}
		$this->set(compact('snippet'));
	}
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
	function add() {
		if ($this->isGet()) {
			return;
		}

		$this->Snippet->create($this->data);
		if (!$this->Snippet->save()) {
			return $this->Session->setFlash('Sorry, please correct the errors below!');
		}

		$snippetId = $this->Snippet->getLastInsertId();
		$this->Snippet->insertCommands($snippetId, $this->data['Snippet']['commands']);

		$this->Session->setFlash('Snippet, successfully added!');
		return $this->redirect(array('action' => 'index'));
	}
/**
 * undocumented function
 *
 * @param string $id 
 * @return void
 * @access public
 */
	function edit($id = null) {
		$conditions = array('Snippet.id' => $id);
		$contain = array('Command');
		$snippet = $this->Snippet->find('first', compact('conditions', 'contain'));
		if (empty($snippet)) {
			$this->Session->setFlash('Sorry, this is an invalid snippet');
			return $this->redirect(array('action' => 'index'));
		}

		$snippet['Snippet']['commands'] = implode(', ', Set::extract('/Command/name', $snippet));
		if ($this->isGet()) {
			return $this->data = $snippet;
		}

		$this->Snippet->set($this->data);
		if (!$this->Snippet->save()) {
			return $this->Session->setFlash('Sorry, please correct the errors below!');
		}
		$this->Snippet->insertCommands($id, $this->data['Snippet']['commands']);
		$this->Session->setFlash('Snippet, successfully saved!');
		$this->redirect(array('action' => 'index'));
	}
/**
 * undocumented function
 *
 * @param string $id 
 * @return void
 * @access public
 */
	function delete($id = null) {
		$conditions = array('Snippet.id' => $id);
		$contain = false;
		$snippet = $this->Snippet->find('first', compact('conditions', 'contain'));

		if (empty($snippet)) {
			$this->Session->setFlash('Sorry, this is an invalid snippet');
			return $this->redirect(array('action' => 'index'));
		}

		if ($this->Snippet->del($id, true)) {
			$this->Session->setFlash('The snippet has been deleted.');
		} else {
			$this->Session->setFlash('Error deleting the snippet.');
		}

		$this->redirect(array('action' => 'index'));
	}
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
	function search() {
		$page = 1;
		$sessionKey = 'snippet_search_query';

		$formData = array();
		if ($this->isPost()) {
			$formData = $this->data;
			$this->Session->write($sessionKey, $formData);
		} elseif ($this->Session->check($sessionKey)) {
			$formData = $this->Session->read($sessionKey);
		} else {
			return;
		}

		$this->set(compact('formData'));

		if (!empty($formData)) {
			if (strlen($formData['Snippet']['contains']) < 2) {
				$this->Snippet->invalidate('contains');
			}

			if (isset($this->params['named']['page'])) {
				$page = $this->params['named']['page'];
			}

			$query = $formData['Snippet']['contains'];
			$conditions = array(
				'or' => array(
					'Snippet.name like' => "%{$query}%",
					'Snippet.description like' => "%{$query}%"
				)
			);

			$this->paginate['conditions'] = $conditions;
			$this->paginate['page'] = $page;
			$snippets = $this->paginate('Snippet');
			$this->set(compact('snippets', 'query'));
		}
	}
}

?>