<?php 
$codePage=2;
$title='Paramétrage Planning';
ob_start();
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <form method="post" action="index.php?action=addAgent" class="row g-2">
       <div class="col-md-6">
        <label class="form-label">Type</label>
        <select type="text" class="form-select" name="typePeriode">
          <option id="extrascolaire" value="Extrascolaire">Vacances Scolaires</option>
          <option id="ferier" value="Jour Ferier">Jour Fériers</option>
          <option id="pont" value="Pont">Pont</option>
          <option id="maire" value="Jour du Maire">Jour du Maire </option>
        </select>
      </div>
      <div class="col-md-8" >
        <select type="text" class="form-select" name="typePeriode" id="nomPeriode"></select>
      </div>
    </div>
  </div>
        
</div>

<?php 
$content = ob_get_clean();
require ('template.php');

?>