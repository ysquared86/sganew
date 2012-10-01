<p>The Student Government Association (SGA) is the governing body for students at BU Law. The SGA is responsible for planning school-wide events, advocating on behalf of students, and allocating student activity fees funds to student groups. The SGA office is located on the ground floor of the law tower in the lobby.</p>
<p>
	<strong>Address: </strong><br />
	Student Government Association<br />
	Boston University School of Law<br />
	765 Commonweath Avenue<br />
	Boston, MA 02215
</p>
<p>
	<strong>Phone:</strong><br />
</p>
<p>
	<strong>E-mail:</strong><br />
	<a href="mailto:sgalaw@bu.edu">sgalaw@bu.edu</a><br />
</p>
<hr />
<p>The SGA consists of two main bodies:
	<ul>
		<li>The Community Affairs Council, which has an elected chair, is open to any member of the law school community who wishes to get involved in any issue related to student life. The Council works regularly with faculty and the administration on a variety of issues, such as improving facilities, providing input on changes in the curriculum and providing feedback on services offered to students in their job searches.</li>
		<li>The Programming & Budgeting Council members are elected by the student body. Their responsibilities include allocating funding to law student organizations, working with organizations to assist them with programming and building community through school-wide social events.</li>
	</ul>
</p>
<p>Between both Councils is an Executive Committee, which meets regularly to update members on recent developments. The committee also votes on major school-wide issues that require a unified student voice.</p>
<p>If you have any questions about SGA, please write to <a href="mailto:sgalaw@bu.edu">sgalaw@bu.edu</a>.</p>
<hr />
<h2>2012-2013 SGA Council Members</h2>
<table class="sga-table">
	<?php if(!empty($officers)) { 
		foreach($officers as $officer)
		{ ?>
			<tr>
				<td class="role"><?php echo $officer->role; ?></td>
				<td><?php echo mailto($officer->email, $officer->firstname . ' ' .$officer->lastname); ?></td>
			</tr>
		<?php }
	} else { ?>
		N/A
	<?php } ?>
</table>