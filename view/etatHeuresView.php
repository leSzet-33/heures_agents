<?php 
$codePage=3;
$title='Etats des heures';
ob_start();
?>
<div class="row">
	<div class="col">
		<table class="table table-striped table-infos-agent">
			<thead>
				<tr class="tabInfos">
					<th scope="col">Nom</th>
					<th scope="col">Prénom</th>
					<th scope="col">Poste</th>
					<th scope="col">Heures à effectuer (par an)</th>
				</tr>
			</thead>
			<tbody>
				<tr class="tabInfos">
					<td><?=$infos['nom']?></td>
					<td><?=$infos['prenom']?></td>
					<td><?=ucfirst($infos['poste'])?></td>
					<td><?=$totalPrevue['HeuresPrevueTotales']?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="row justify-content-center">
	<div class="col-lg-8">
		<div class="progress">
  			<div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $progress."%"; ?> " aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100"> <?=$progress?>%</div>
		</div>
		<label>Avancement des heures effectuées</label>
	</div>	
</div>
<div class="row g-3 justify-content-center">
	<div class="col-lg-2">
		<form action="index.php?page=etatHeures" method="post">
			<div class="form-floating" >
			<select class="form-select" id="month-select" aria-label="Floating label select example"  name="month">
                <option value="1">Janvier</option>
                <option value="2">Février</option>
                <option value="3">Mars</option>
                <option value="4">Avril</option>
                <option value="5">Mai</option>
                <option value="6">Juin</option>
                <option value="7">Juillet</option>
                <option value="8">Août</option>
                <option value="9">Septembre</option>
                <option value="10">Octobre</option>
                <option value="11">Novembre</option>
                <option value="12">Décembre</option>
            </select>
            <label for="floatingInputGrid">Sectionner mois</label>
        </div>
		</form>
		
	</div>	
</div>
<div class="row g-3 justify-content-center">
	<div class="col-lg-8">
		<table class="table">
			<tr>
				<th>Heures Annualisées</th>
				<td id="annualisées"><?= $heuresAnnByMonth['reellesThisMonthByType']?></td>
			</tr>
			<tr>
				<th>Heures Complémentaires</th>
				<td id="complémentaires"><?= $heuresCompByMonth['reellesThisMonthByType']?></td>
			</tr>
			<tr>
				<th>Heures Totales</th>
				<td id="totales"><?= $heuresTotalesByMonth['reellesThisMonth']?></td>
			</tr>
		</table>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
	$(document).ready(function(){
		$today = new Date()
		$monthToday=$today.getMonth()+1;
		$listMonth=$("#month-select").find("option");
	
		
		for (var i =0; i<$listMonth.length; i++) {
			
			if($listMonth.eq(i).val()==$monthToday)
			{
				$listMonth.eq(i).attr("selected", "selected");
			}
		}
		
		$("#month-select").change(function(){
			$month=$("#month-select").val();
		 
                         
        	$.post(
        		"index.php?page=etatHeures", 
        		{
        			month : $month
        		},
        		function(data){
                $("#annualisées").text(data.annualisées);
                $("#complémentaires").text(data.complémentaires);
                $("#totales").text(data.totales);
        		}, 
        	"json"
        	);
      	});
	});
</script>
<?php 
$content = ob_get_clean();
require ('template.php');
?>