<p>Delete <?php echo $delete_user->username; ?>?</p>
<?php echo anchor('admin/delete_user_confirm/' . $delete_user->id, 'Yes, I\'m sure', 'class="button"'); ?>
<?php echo anchor('admin/edit_user/' . $delete_user->id, 'No, Let me go back', 'class="button"'); ?>