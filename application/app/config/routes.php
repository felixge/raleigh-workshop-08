<?php
Router::connect('/', array('controller' => 'snippets', 'action' => 'index'));
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
Router::connect('/tests', array('controller' => 'tests', 'action' => 'index'));
?>