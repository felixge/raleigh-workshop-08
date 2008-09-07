<?php echo $javascript->link('snippets/index', false); ?>
<h2>Popular tools</h2>
<?php echo $this->element('command_cloud')?>

<h2><?php echo $this->pageTitle = 'Popular Snippets'; ?></h2>
<?php echo $html->link('Add Snippet', array('controller' => 'snippets', 'action' => 'add'), array('class' => 'button')); ?>
<div class="clear"></div>

<?php if (empty($snippets)) : ?>
	<p>Sorry, there aren't any snippets yet.</p>
<?php return; endif; ?>
<ul id="snippets"></ul>
<a id="load-snippets" href="#load-snippets">Load Snippets</a>
<div id="preview"></div>