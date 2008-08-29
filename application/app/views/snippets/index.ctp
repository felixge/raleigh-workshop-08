<h1><?php echo $this->pageTitle = 'The Snippet List'; ?></h1>

<?php echo $html->link('Add', array('controller' => 'snippets', 'action' => 'add')); ?>

<?php if (empty($snippets)) : ?>
	<p>Sorry, there aren't any snippets yet.</p>
<?php return; endif; ?>

<ul>
	<?php foreach ($snippets as $snippet) : ?>
		<li>
			<?php echo $html->link($snippet['Snippet']['name'], array('controller' => 'snippets', 'action' => 'view', $snippet['Snippet']['id'])) ?>
			 &raquo; <?php echo $html->link('Edit', array('controller' => 'snippets', 'action' => 'edit', $snippet['Snippet']['id'])) ?> | 
			<?php echo $html->link('Delete', array('controller' => 'snippets', 'action' => 'delete', $snippet['Snippet']['id']), null, 'Are you sure?') ?>
		</li>
	<?php endforeach; ?>
</ul>