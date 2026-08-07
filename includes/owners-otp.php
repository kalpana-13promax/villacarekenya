<?php 
require_once('config.php');
$boj->check_session();

?>

<table class="table table-bordered table-striped mb-none" id="datatable-default">
									<thead>
										<tr>
											<th>Property ID</th>
											<th>Name</th>
											<th>OTP</th>
											<th>Requested By</th>
											
										</tr>
									</thead>
									<tbody>
									
<?php 
$qry = $boj->getQuery("SELECT * FROM owner where otp >='0' order by id desc "); 
// print_r($qry);
$i=1;
if( $qry ){
foreach($qry as $value){
	

	
?>										
			<tr class="gradeC">
				<td><?php echo $value->id; ?></td>
				<td> <?php echo ucwords($value->name); ?> </td>
				<td> <?php echo ucwords($value->otp); ?> </td>
				<td> <?php 
				$user = $boj->get_staff((int)$value->viewer);
				echo ucwords($user);
				
				?> </td>

				
			</tr>
										
									<?php $i++; } }else{
									echo '<tr><td colspan="4"><center>No data available!</center></td></tr>';
									}?>
									
									</tbody>
								</table>