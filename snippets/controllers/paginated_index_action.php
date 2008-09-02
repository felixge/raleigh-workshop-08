<?php
function index() {
	$snippets = $this->paginate('Snippet');
	$this->set(compact('snippets'));
}
?>