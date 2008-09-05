<h1>Popular tools</h1>
<?php echo $this->element('command_cloud')?>

<h1><?php echo $this->pageTitle = 'Popular Snippets'; ?></h1>
<?php echo $html->link('Add Snippet', array('controller' => 'snippets', 'action' => 'add'), array('class' => 'btn')); ?>
<div class="clear"></div>

<?php if (empty($snippets)) : ?>
	<p>Sorry, there aren't any snippets yet.</p>
<?php return; endif; ?>

<ul class="snippets">
	<?php foreach ($snippets as $snippet) : ?>
		<li>
			<span>
				 <?php echo $html->link('Edit', array('controller' => 'snippets', 'action' => 'edit', $snippet['Snippet']['id'])) ?> | 
				<?php echo $html->link('Delete', array('controller' => 'snippets', 'action' => 'delete', $snippet['Snippet']['id']), null, 'Are you sure?') ?>
			</span>
			<?php echo $html->link($snippet['Snippet']['name'], array('controller' => 'snippets', 'action' => 'view', $snippet['Snippet']['id']), array('class' => 'snippet')) ?>
		</li>
	<?php endforeach; ?>
</ul>