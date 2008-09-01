<?php
class CommandTest extends CakeTestCase {
	var $fixtures = array('command', 'snippet');
	function setUp() {
		$this->sut = ClassRegistry::init('Command');
	}

	function testFindCloud() {
		$this->loadFixtures('Snippet', 'Command');

		$commands = $this->sut->find('cloud');
		$this->assertTrue(!empty($commands));
		$nestings = Set::extract('/Command/Snippet', $commands);
		$this->assertIdentical(array(), $nestings);
		$this->assertEqual(50, count($commands));

		$commands = $this->sut->find('cloud', array('limit' => 15));
		$this->assertEqual(15, count($commands));

		$scaleMin = 999;
		$scaleMax = 0;
		foreach ($commands as $c) {
			if ($c['Command']['scale'] < $scaleMin) {
				$scaleMin = $c['Command']['scale'];
			}
			if ($c['Command']['scale'] > $scaleMax) {
				$scaleMax = $c['Command']['scale'];
			}
		}

		$this->assertEqual($scaleMin, 0.5);
		$this->assertEqual($scaleMax, 2);

		$this->sut->deleteAll(array('Command.id <>' => 0));
		$commands = $this->sut->find('cloud');
		$this->assertEqual(array(), $commands);
	}
}
?>