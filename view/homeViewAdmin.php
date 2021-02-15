<?php 
$codePage=1;
$title='Création agent';
ob_start();
?>
<div  class="container">
  <div class="row">
    <div class="col-lg-6">
      <h3>Création d'un agent</h3>
      <form class="form-user" action="index.php?action=addAgent" method="post">
        <div class="row g-2 justify-content-center  input-form-user">
          <div class="col-md-6">
            <div class="form-floating">
              <input class="form-control" type="text" name="nom" id="nom" required  aria-label="Floating label select example">
              <label for="nom">Nom</label>
              <span hidden class="invalid-input" id="invalid-nom">Minimum 3 lettres</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input class="form-control" type="text" name="prenom" id="prenom" required  aria-label="Floating label select example">
              <label for="prenom">Prénom</label>
              <span hidden class="invalid-input" id="invalid-prenom">Minimum 3 lettres</span>
            </div>
          </div>
        </div>
        <div class="row g-2 justify-content-center input-form-user">
          <div class="col-md-6">
            <div class="form-floating">
              <input class="form-control" type="mail" name="mailAgent" id="mailAgent" required  aria-label="Floating label select example">
              <label for="mailAgent">Email</label>
              <span hidden class="invalid-input" id="invalid-mail">Adresse mail invalide</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
               <select class="form-select" id="poste-select" aria-label="Floating label select example" name="poste">
                <?php 
                  foreach ($listePostes as $poste) 
                {
                ?>
                  <option value="<?=$poste['poste']?>"><?=ucfirst($poste['poste'])?></option>
                <?php
                }  
                ?>
                </select>
                <label for="poste-select">Poste</label>
              </div>
          </div>
        </div>
        <div class="row g-2 justify-content-center input-form-user">
          <div class="col-md-6">
            <div class="form-floating">
              <input class="form-control" type="password" name="pass" id="pass" required  aria-label="Floating label select example">
              <label for="pass">Mot de passe </label>
              <span hidden class="invalid-input" id="invalid-pass">Minimum 1 majuscule et 1 chiffre</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input class="form-control" type="password" name="passRepeat" id="passRepeat" required  aria-label="Floating label select example">
              <label for="passRepeat">Confirmer mot de passe</label>
              <span hidden class="invalid-input" id="invalid-passRepeat">Pas de coorespondance avec le mot de passe choisi</span>
            </div>
          </div>
        </div>
        <div class="row g-2 justify-content-center">
          <div class="d-flex justify-content-center">
            <button type="submit" id="submitAgent" name="createAgent" class="btn btn-primary" disabled="true" >Créer</button>
          </div>
        </div>
      </form>
    </div>
    <div class="col-lg-4">
      <?php
      if(isset($_GET['page']))
      {
        if(($_GET['page']=='planningCreate')||($_GET['page']=='planningEdit')||($_GET['page']=='infosAgent')||($_GET['page']=='etatHeures'))
        {
        ?>
          <span class="badge rounded-pill bg-warning text-dark"><?=$message?></span>
        <?php
        }
    
      }
     ?>
      <h3>Rechercher un agent</h3>
        <div class="search-agent">
              <div class="input-group">
                <input name="searchAgent" id="search-barre" type="text" class="form-control" placeholder="Recherche par nom de famille">
              </div>
        </div> 
          <table style= "display: none;" id="searchAgent" class="table">
            <thead>
              <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Poste</th>
              </tr>
            </thead>
            <tbody id="resultSearchAgent">
              
            </tbody>
          </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>

$(document).ready(function(){

$passValid=false; $passRepeatValid=false; $nomValid=false; $prenomValid=false; $mailValid=false;
  function validPass(pass){
   return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/.test(pass);  
  }
  function validText(text)
  {
    return /^[a-zA-Zs àéèêëîùç]*$/.test(text);
  }
  $("#search-barre").keyup(function(){ 
    if($('#search-barre').val().length>0)
    {
      $.post(
      'index.php?action=searchAgent',
      {
        'searchAgent': $('#search-barre').val()
      },
      function(data){
        $("#searchAgent").show();
        $("#resultSearchAgent").html(data);
      },
      'text'
      );
    }
    
  });
  $("#nom").keyup(function(){
    if(($("#nom").val().length>3)&&($("#nom").val().length<30)&&validText($("#nom").val()))
    {
      $('#nom').addClass("is-valid").removeClass("is-invalid");
      $('#invalid-nom').attr('hidden','hidden');
      $nomValid=true;
    }else
    {
      $('#nom').addClass("is-invalid").removeClass("is-valid");
      $('#invalid-nom').removeAttr('hidden','hidden');
      $nomValid=false;
    }
  });
  $("#prenom").keyup(function(){
    if(($("#prenom").val().length>3)&&($("#prenom").val().length<30)&&validText($("#prenom").val()))
    {
      $('#prenom').addClass("is-valid").removeClass("is-invalid");
      $('#invalid-prenom').attr('hidden','hidden');
      $prenomValid=true;
    }else
    {
      $('#prenom').addClass("is-invalid").removeClass("is-valid");
      $('#invalid-prenom').removeAttr('hidden','hidden');
      $prenomValid=false;
    }
  });
  $("#pass").keyup(function(){
    $pass= $("#pass").val();
    if(validPass($pass))
    {
      $('#pass').addClass("is-valid").removeClass("is-invalid");
      $('#invalid-pass').attr('hidden','hidden');
      $passValid=true;
    }else{
      $('#pass').addClass("is-invalid").removeClass("is-valid");
      $('#invalid-pass').removeAttr('hidden','hidden');
      $passValid=false;
    }
  });

  $("#passRepeat").keyup(function()
  {
    $passRepeat= $("#passRepeat").val();
    if($passRepeat==$("#pass").val())
    {
      $('#passRepeat').addClass("is-valid").removeClass("is-invalid");
      $('#invalid-passRepeat').attr('hidden','hidden');
      $passRepeatValid=true;
    }else
    {
      $('#passRepeat').addClass("is-invalid").removeClass("is-valid");
      $('#invalid-passRepeat').removeAttr('hidden','hidden');
      $passRepeatValid=false;
    }
  });
  $(".form-user").change(function(e)
  {
    if($passValid && $passRepeatValid && $nomValid && $prenomValid )
    {
      $("#submitAgent").attr('disabled', false);
    }else{
      $("#submitAgent").attr('disabled', true);
    }
  });

  $("#submitAgent").click(function(e){
    e.preventDefault();
    $.post(
      "index.php?action=addAgent",
      {
        nom : $("#nom").val(),
        prenom : $("#prenom").val(),
        mailAgent:$("#mailAgent").val(),
        poste : $("#poste-select").val(),
        pass : $("#pass").val(),
        passRepeat : $("#passRepeat").val()
      },
      function(data){
        alert(data);
        location.reload(true);
      },
      'text'
      );
  });
});
</script>
<?php 
$content = ob_get_clean();
require ('template.php');

?>