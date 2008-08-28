<?php

App::import(array('Model', 'AppModel', 'File'));
class NixScrapeShell extends Shell {
	var $paths = array('/usr/bin', '/usr/local/git/bin');

	function main() {
		$commands = array();
		foreach ($this->paths as $path) {
			$r = trim(shell_exec(sprintf('ls %s', escapeshellarg($path))));
			$commands = array_merge($commands, explode("\n", $r));
		}
		$commands = array_unique($commands);
		$Command = ClassRegistry::init('Command');
		$Command->query('TRUNCATE '.$Command->table);
		foreach ($commands as $name) {
			$Command->create(compact('name'));
			$Command->save();
		}
	}
}

?>