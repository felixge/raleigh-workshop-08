<?php

App::import(array('Model', 'AppModel', 'File'));
class FixturizeShell extends Shell{
	function main() {
		if (empty($this->args)) {
			return $this->err('Usage: ./cake fixturize <table>');
		}
		if ($this->args[0] == '?') {
			return $this->out('Usage: ./cake fixturize <table> [-force] [-reindex]');
		}
		$options = array(
			'force' => false,
			'reindex' => false,
		);
		foreach ($this->params as $key => $val) {
			foreach ($options as $name => $option) {
				if (isset($this->params[$name]) || isset($this->params['-'.$name]) || isset($this->params[$name{0}])) {
					$options[$name] = true;
				}
			}
		}

		foreach ($this->args as $table) {
			$name = Inflector::classify($table);
			$Model = new AppModel(array(
				'name' => $name,
				'table' => $table,
			));
	
			$file = sprintf('%stests/fixtures/%s_fixture.php', APP, Inflector::underscore($name));
			$File = new File($file);
			if ($File->exists() && !$options['force']) {
				$this->err(sprintf('File %s already exists, use --force option.', $file));
				continue;
			}
			$records = $Model->find('all');
	
			$out = array();
			$out[] = '<?php';
			$out[] = '';
			$out[] = sprintf('class %sFixture extends CakeTestFixture {', $name);
			$out[] = sprintf('	var $name = \'%s\';', $name);
			$out[] = '	var $records = array(';
			foreach ($records as $record) {
				$out[] = '		array(';
				if ($options['reindex']) {
					foreach (array('old_id', 'vendor_id') as $field) {
						if ($Model->hasField($field)) {
							$record[$name][$field] = $record[$name]['id'];
							break;
						}
					}
					$record[$name]['id'] = String::uuid();
				}
				foreach ($record[$name] as $field => $val) {
					$out[] = sprintf('			\'%s\' => \'%s\',', addcslashes($field, "'"), addcslashes($val, "'"));
				}
				$out[] = '		),';
			}
			$out[] = '	);';
			$out[] = '}';
			$out[] = '';
			$out[] = '?>';
	
			$File->write(join("\n", $out));
			$this->out(sprintf('-> Create %sFixture with %d records (%d bytes) in "%s"', $name, count($records), $File->size(), $file));
		}
	}
}

?>