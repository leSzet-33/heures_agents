<?php 
$codePage=1;
$title='Création agent';
ob_start();
?>
  <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1  row-cols-lg-2">
      <div class="col">
        <h3>Création d'un agent</h3>
        <div class="panel panel-default">
            <div class="panel-body back-form">
             <form method="post" action="index.php?action=manageAgent" class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" value="<?=$agent['nom']?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input type="text" class="form-control" name="prenom" value="<?=$agent['prenom']?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="mailAgent" value="<?=$agent['mail']?>">
                <input hidden type="email" name="mailAgentTampon" value="<?=$agent['mail']?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Poste</label>
                <select name="poste" class="form-select">
                  
                    <option selected value="animatrice/teur">Animatrice/teur</option>
                    <option value="directrice/teur">Directrice/teur</option>
                </select>
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" name="updateAgent" class="btn btn-success">Mettre à jour</button>
                <button type='submitt' name='deleteAgent' id="delete-btn" value="<?=$mail?>" class='btn btn-danger'>Supprimer</button>
              </div>
            </form>
          </div>
      </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
  $(document).ready(function(){
    $("#delete-btn").click(function(e){
      confirm("Êtes-vous sûr de vouloir supprimer cette agent? (Données seront supprimées de la base, ainsi que l'historique de ses heures");
      $.post(
        "index.php?action=manageAgent",
        {
          "deleteAgent":$("#delete-btn").val()
        },
        function(data){
          alert(data);
          top.location.href='index.php';
        },
        "text"
        )
    });
  });
</script>


<?php 
$content = ob_get_clean();
require ('template.php');
?>