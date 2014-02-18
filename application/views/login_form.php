<p>Please Login to continue</p>
<table>
	<?php echo Form::open(''); ?>
	<tr>
		<td>Username:</td>
		<td><?php echo Form::input('id'); ?></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><?php echo Form::input('password'); ?></td>
	</tr>
	<tr>
		<td><?php echo Form::submit('', 'Login'); ?></td>
	</tr>
</table>