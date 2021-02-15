<?php 
$codePage=3;
$title='Informations Agent';
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
					<td><?=$total['HeuresPrevueTotales']?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="row justify-content-center">
	<div class="col-lg-5">
       	<table class="table table-striped table-hover">
          	<thead>
				<tr>
					<th colspan="2" scope="col">Heures Annualisées</th>
				</tr>
			</thead>
			<tbody>
				<tr class="tabInfos">
					<th class="leftTitle" rowspan="2">Total:</th>
					<th rowspan="2"><?=$totalAnn['HeuresPrevueTotales']?></th>
				</tr>
			</tbody>
			<tr class="tabInfos">
				<th class="leftTitle" rowspan="2">Total CLAE:</th>
				<td rowspan="2"><?= $totalClaeAnn['HeuresPrevueTotales']?></td>
			</tr>
			<tbody>
				<tr class="tabInfos">
					<th class="leftTitle" rowspan="2">Total Plan Mercredis:</th>
					<td rowspan="2"><?=$totalMercAnn['HeuresPrevueTotales']?></td>
				</tr>
			</tbody>
			<tbody>
				
			</tbody>
				<tr class="tabInfos">
					<th class="leftTitle" rowspan="2">Total ALSH Vacances:</th>
					<td rowspan="2"><?=$totalALSHAnn['HeuresPrevueTotales']?></td>
				</tr>
			</tboby>
			<tbody>
				<tr class="tabInfos">
					<th class="leftTitle" rowspan="2">Total Autres:</th>
					<td rowspan="2"><?=$totalAutresAnn['HeuresPrevueTotales']?></td>
				</tr>
			</tbody>
        </table>
    </div>
    <div class="col-lg-5">
    	<table class="table table-striped table-hover">
          	<thead>
				<tr>
					<th colspan="2" scope="col">Heures Complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<tr class="tabInfos">
					<th class="leftTitle">Total:</th>
					<th rowspan="2"><?=$totalComp['HeuresPrevueTotales']?></th>
				</tr>
			</tbody>
			<tr class="tabInfos">
				<th class="leftTitle" >Total CLAE:</th>
				<td rowspan="2"><?= $totalClaeComp['HeuresPrevueTotales']?></td>
			</tr>
			<tbody>
				<tr class="tabInfos">
					<th class="leftTitle" rowspan="2">Total Plan Mercredis:</th>
					<td rowspan="2"><?=$totalMercComp['HeuresPrevueTotales']?></td>
				</tr>
			</tbody>
			<tbody>
				
			</tbody>
				<tr class="tabInfos">
					<th class="leftTitle" rowspan="2">Total ALSH Vacances:</th>
					<td rowspan="2"><?=$totalALSHComp['HeuresPrevueTotales']?></td>
				</tr>
			</tboby>
			<tbody>
				<tr class="tabInfos">
					<th class="leftTitle" rowspan="2">Total Autres:</th>
					<td rowspan="2"><?=$totalAutresComp['HeuresPrevueTotales']?></td>
				</tr>
			</tbody>
        </table>
    </div>		
</div>



<?php 
$content = ob_get_clean();
require ('template.php');
?>