<?php if ($this->session->flashdata('message')): ?>
	<div class="alert alert-success alert-dismissible fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?php echo $this->session->flashdata('message') ?>
	</div>
<?php endif ?>
<table class="table">
	<tr>
		<th>#</th>
		<th>Title</th>
		<th>Publish at</th>
		<th>Edit</th>
		<th>Remove</th>
	</tr>
	<?php foreach ($posts as $key => $post): ?>
	<tr>
		<td><?php echo $key + 1 ?></td>
		<td><a href="<?php echo site_url(['post/show', $post->id]) ?>"><?php echo $post->title ?></a></td>
		<td><?php echo $post->published_at ?></td>
		<td><a href="<?php echo site_url(['post/edit', $post->id]) ?>" class="btn btn-info btn-xs">Edit</a></td>
		<td><a href="<?php echo site_url(['post/destory', $post->id]) ?>" class="btn btn-danger btn-xs">Remove</a></td>
	</tr>
	<?php endforeach ?>
</table>
