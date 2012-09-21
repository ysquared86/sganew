<p>Manage outlines here.</p>

<?php if($pending) { ?>
<table class="sga-table">
    <tr>
        <th>Number</th>
        <th>Course</th>
        <th>Instructor</th>
        <th>Semester</th>
        <th>Year</th>
        <th>Link</th>
        <th>Uploaded</th>
    </tr>
<?php
    $i = 0;
    foreach($pending as $outline) {
	$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
?>
	<tr class="<?php echo $evenodd; ?>">
		<td><?php echo $outline->course_number; ?></td>
		<td><?php echo $outline->course_title; ?></td>
		<td><?php echo $outline->instructor; ?></td>
		<td><?php echo $outline->semester; ?></td>
		<td><?php echo $outline->year; ?></td>
		<td><?php echo anchor( site_url() . 'uploads/outlines/' . $outline->path . '.pdf', 'Download', 'class="outline-download" target="_blank"'); ?></td>
		<td><?php echo date('Y-m-d', $outline->created) . ' by ' . $outline->firstname . ' ' . $outline->lastname; ?></td>
	</tr>	
<?php $i++; } // endforeach ?>
<?php } // endif ?>

<table class="sga-table">
    <tr>
        <th>Number</th>
        <th>Course</th>
        <th>Instructor</th>
        <th>Semester</th>
        <th>Year</th>
        <th>Link</th>
        <th>Uploaded</th>
    </tr>
<?php
    $i = 0;
    foreach($results as $outline) {
		$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
?>
    <tr class="<?php echo $evenodd; ?>">
        <td><?php echo $outline->course_number; ?></td>
        <td><?php echo $outline->course_title; ?></td>
        <td><?php echo $outline->instructor; ?></td>
        <td><?php echo $outline->semester; ?></td>
        <td><?php echo $outline->year; ?></td>
        <td><?php echo anchor( site_url() . 'uploads/outlines/' . $outline->path . '.pdf', 'Download', 'class="outline-download" target="_blank"'); ?></td>
        <td><?php echo date('Y-m-d', $outline->created) . ' by ' . $outline->firstname . ' ' . $outline->lastname; ?></td>
    </tr>
<?php
        $i++;
    }
?>
</table>
<?php echo $page_links; ?>