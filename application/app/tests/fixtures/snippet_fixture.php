<?php

class SnippetFixture extends CakeTestFixture {
	var $name = 'Snippet';
	var $records = array(
		array(
			'id' => '48c2570e-dfa8-4c32-a35e-0d71cbdd56cb',
			'name' => 'mysql raleigh-workshop-08 < 2008-09-05.sql ',
			'description' => 'Importing an sql dump',
			'user_id' => '',
			'created' => '2008-09-06 06:10:22',
			'modified' => '2008-09-06 06:10:22',
		),
		array(
			'id' => '48c257a8-cf7c-4af2-ac2f-114ecbdd56cb',
			'name' => 'pbpaste | grep -i Unpaid | pbcopy',
			'description' => 'Remove all lines that say "Unpaid" from the current clipboard text. OSX only.',
			'user_id' => '',
			'created' => '2008-09-06 06:12:56',
			'modified' => '2008-09-06 06:41:48',
		),
		array(
			'id' => '48c257ef-3388-4bc0-a9fb-115dcbdd56cb',
			'name' => 'bzcat debuggable.sql.bz2 | mysql debuggable',
			'description' => 'Load a compressed sql dump into a database.',
			'user_id' => '',
			'created' => '2008-09-06 06:14:07',
			'modified' => '2008-09-06 06:14:07',
		),
		array(
			'id' => '48c258e2-4e44-4aca-b9c2-114ecbdd56cb',
			'name' => 'find app/tmp -type f | xargs rm',
			'description' => 'Clear CakePHP cache folder.',
			'user_id' => '',
			'created' => '2008-09-06 06:18:10',
			'modified' => '2008-09-06 06:18:10',
		),
		array(
			'id' => '48c2592d-ada4-48b5-b1ce-0d7fcbdd56cb',
			'name' => 'find .  -name "*.jpg" | xargs git rm',
			'description' => 'Recursively remove jpg files from a git repository.',
			'user_id' => '',
			'created' => '2008-09-06 06:19:25',
			'modified' => '2008-09-06 06:19:35',
		),
		array(
			'id' => '48c259ba-f8c8-4551-9433-0d74cbdd56cb',
			'name' => 'ps aux | grep Keynote | awk \'{print $2}\'',
			'description' => 'List the process ids of anything named "Keynote".',
			'user_id' => '',
			'created' => '2008-09-06 06:21:46',
			'modified' => '2008-09-06 06:21:46',
		),
		array(
			'id' => '48c25a28-2d18-41d8-9f0b-115dcbdd56cb',
			'name' => 'tar -czf folder.tar.gz folder',
			'description' => 'Create a tar.gz archive of a given folder.',
			'user_id' => '',
			'created' => '2008-09-06 06:23:36',
			'modified' => '2008-09-06 06:23:36',
		),
		array(
			'id' => '48c25a48-98a8-4aa7-aa51-0d72cbdd56cb',
			'name' => 'tar -xcf folder.tar.gz',
			'description' => 'Decompress a tar.gz archive',
			'user_id' => '',
			'created' => '2008-09-06 06:24:08',
			'modified' => '2008-09-06 06:24:08',
		),
		array(
			'id' => '48c25a74-193c-4270-b35b-0d7fcbdd56cb',
			'name' => 'find -type f -print0 | xargs -0 rm',
			'description' => 'Delete lots of files recursively',
			'user_id' => '',
			'created' => '2008-09-06 06:24:52',
			'modified' => '2008-09-06 06:24:52',
		),
	);
}

?>