<?php if (validation_errors()): ?>
	<div class="alert alert-danger alert-dismissible fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?php echo validation_errors() ?>
	</div>
<?php endif ?>
<?php echo form_open('post/store') ?>
	<div class="form-group">
		<label for="">Title</label>
		<input type="text" class="form-control" name="title" placeholder="Title" value="<?php echo set_value('title') ?>">
	</div>
	<div class="form-group">
		<label for="">Body</label>
		<textarea class="form-control" rows="10" name="body" placeholder="leaving your message..."><?php echo set_value('body') ?></textarea>
	</div>
	<button type="submit" class="btn btn-primary">Publish</button>
<?php echo form_close() ?>