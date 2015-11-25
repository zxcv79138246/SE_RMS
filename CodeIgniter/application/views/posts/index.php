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
		<td><a href="#" data-url="<?php echo site_url(['post/edit', $post->id]) ?>" class="btn btn-info btn-xs edit-btn"  data-toggle="modal" data-target=".edit-modal">Edit</a></td>
		<td><a href="<?php echo site_url(['post/destory', $post->id]) ?>" class="btn btn-danger btn-xs">Remove</a></td>
	</tr>
	<?php endforeach ?>
</table>
<div class="modal fade edit-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="modal-body">
        <p></p>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
	$(document).ready(function() {
		$('.edit-btn').on('click', function() {
			$.ajax({
				url: $(this).data('url'),
				type: 'get',
				success: function (response) {
					$('#modal-body').html(response);
				}
			})
		})
	})
</script>