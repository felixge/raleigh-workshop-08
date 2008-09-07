<h2>Popular tools</h2>
<?php echo $this->element('command_cloud')?>

<h2><?php echo $this->pageTitle = 'Popular Snippets'; ?></h2>
<?php echo $html->link('Add Snippet', array('controller' => 'snippets', 'action' => 'add'), array('class' => 'button')); ?>
<div class="clear"></div>

<?php if (empty($snippets)) : ?>
	<p>Sorry, there aren't any snippets yet.</p>
<?php return; endif; ?>

<ul class="snippets">
	<?php foreach ($snippets as $i => $snippet) : ?>
		<li class="<?php echo ($i % 2) ? 'alt' : ''; ?>">
			<span>
				<?php echo $html->link('Edit', array('controller' => 'snippets', 'action' => 'edit', $snippet['Snippet']['id'])) ?> | 
				<?php echo $html->link('Delete', array('controller' => 'snippets', 'action' => 'delete', $snippet['Snippet']['id']), null, 'Are you sure?') ?>
			</span>
			<?php echo $ajax->link($snippet['Snippet']['name'], array('controller' => 'snippets', 'action' => 'view', $snippet['Snippet']['id']), array('update' => 'snippet-preview')) ?>
		</li>
	<?php endforeach; ?>
</ul>

<div id="snippet-preview"></div>