<?php 
$codePage=1;
$title='Accueil - Validation heures';
ob_start();
?>
	<div class="row g-3 justify-content-center">
	<div class="col-lg-8">
		<div class="progress">
  			<div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $progress."%"; ?> " aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100"> <?=$progress?>%</div>
  			<div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: <?php echo $rest."%"; ?> " aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100"> <?=$rest?>%</div>
		</div>
		<label>Avancement des heures effectuées</label>
	</div>	
</div>
	<div class="row justify-content-center">
		<table class="table table-check-heures">
			<thead class="thead-title-check">  
			<tr><th><h3>Validation des heures</h3></th></tr>
			</thead>
		</table>
	</div>
	<?php 
	foreach($heuresToCheck as $key=>$value)
	{
		$dateOfDay = new DateTime ($heuresToCheck[$key]['jour']);
		$dateOfDay = $dateOfDay->format("d/m/Y");
	?>	
		<div class="row justify-content-center ">
			<table class="table table-check-heures shadow p-3 mb-5 bg-white border ">
			<tr>
			<th class="th-left bg-primary bg-gradient">Date: <?=$dateOfDay ?></th>
			</tr>
			<tr>
				<td class="table-secondary">
					<form class="row g-3 justify-content-center form-check-heures" method="post" action="index.php?action=checkDay">
  					<div class="col-md-4">
    					<input type="time" name="hdebut" readonly value="<?=$heuresToCheck[$key]['debut']?>" class="form-control input-check-heures input-time">
  					</div>
  					<div class="col-md-4">
   			 			<input type="time" name="hfin" id="hfin" readonly value="<?=$heuresToCheck[$key]['fin']?>" class="form-control input-check-heures input-time">
 		 			</div>
 		 			<div class="col-md-4">
    					<input type="texte" name="type" readonly  value="<?=ucfirst($heuresToCheck[$key]['type'])?>" class="form-control input-check-heures">
  					</div>
  					<div class="col-md-4">
   						<input type="text" name="lieu" readonly  value="<?=ucfirst($heuresToCheck[$key]['lieu'])?>" class="form-control input-check-heures">
 		 			</div>
 		 			<div class="col-md-4">
   			 			<input type="text" name="tache" readonly  value="<?=ucfirst($heuresToCheck[$key]['tache'])?>" class="form-control input-check-heures">
 		 			</div>
 		 			<input hidden type="text" name="id_heures" value="<?=$heuresToCheck[$key]['id']?>">
 		 			<div class="col-md-4">
   			 			<input type="text" name="poste" readonly placeholder="Poste occupé" value="<?=ucfirst($heuresToCheck[$key]['poste'])?>" class="form-control input-check-heures">
 		 			</div>
 		 			<div class="col-2 align-self-center col-check-heures">
   			 			<button type="submit" name="validateThisDay"  value="en poste" class="form-control button-valider-heures"></button>
 		 			</div>
 		 			<div class="col-2 button-check-heures">
   			 			<button type="submit" name="editThisDay"  value="en poste"  class="form-control button-modifier-heures editThisDay" disabled></button>
 		 			</div>
 		 			<div class="col-2 button-check-heures "> 
   						<button type="submit" name="refuseThisDay" value="absence" class="form-control button-refuser-heures" disabled></button>
 		 			</div>
					</form>
				</td>
			</tr>
			</table>
		</div>	
		<?php
		}
		?>

<?php
$content = ob_get_clean();
require ('template.php');

?>