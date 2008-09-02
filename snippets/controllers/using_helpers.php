<?php
echo $html->link('Snippets', array(
	'controller' => 'snippets', 'action' => 'index'
));
echo $form->create(...);
echo $plural->ize('Snippet', count($snippets));
echo $session->flash();
echo $javascript->codeBlock(...);
?>