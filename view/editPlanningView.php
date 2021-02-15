<?php
$codePage=2;  
$title ="Modification d'emploi du temps";
ob_start();
?>
<p class="h3">Modification d'horaires</p>
  <div class="row g-3 justify-content-start">
    <div class="col-lg-9">
      <p class="h5">Recherche</p>
      <form class="row g-3 search-planning"  action="index.php?action=searchPlanning" method="post">
        <div class="row g-2 justify-content-start ">
          <div class="form-floating col-md-12 col-lg-3 ">
            <input id="searchJour" class="form-control search-Planning date-planning-input" type="date" name="jour" required="">
            <label for="searchJour">Date</label>
          </div>
          <div class="form-floating col-md-12 col-lg-3 ">
            <input id="searchDebut" class=" form-control search-Planning date-planning-input"  type="time" name="debut" required="">
            <label for="searchDebut">Heure d'embauche</label>
          </div>
          <div class="form-floating col-md-12 col-lg-3 ">
            <button id="submit-search-planning" class="btn btn-primary">Chercher</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="row g-1 justify-content-start" id="search-planning" hidden="hidden">
    <p>Aucune donnée trouvée...</p>
  </div>
<?php
if((isset($_GET['action']))&&($_GET['action']=='edit'))
{
?>
<div class="row g-3 justify-content-start">
  <div class="col-lg-9">
    <table class='table'>
        <tr>
          
          <th>Début</th>
          <th>Fin</th>
          <th>Tache</th>
          <th>Lieu</th>
          <th>Type</th>
          <th>Poste</th>
        </tr>
        <tr>
          <td>"<?=$hdebutTamp?>"</td>
          <td>"<?=$hfinTamp?>"</td>
          <td>"<?=$tacheTamp?>"</td>
          <td>"<?=$lieuTamp?>"</td>
          <td>"<?=$typeTamp?>"</td>
          <td>"<?=$posteTamp?>"</td>
        </tr>
      </table>
  </div>
</div>
<div class="row g-3 justify-content-start" id="form-edit-planning">
      <div class="col-lg-9">
        <form class="row g-3 formUser" action="index.php?action=updatePlanning" method="post">
          <div class="row g-2 justify-content-center">
            <div class="col-md-12 col-lg-4">
              <div class="form-floating">
               <select class="form-select" id="periode-select" aria-label="Floating label select example" name="periode">
                <option selected value="scolaire">Scolaire</option>
                <option value="extrascolaire">Extrascolaire</option>
                </select>
                <label for="floatingInputGrid">Période</label>
              </div>
            </div>
          </div>
          <div class="row g-2 justify-content-center ">
            <div class="form-floating col-md-12 col-lg-3 datePlanning">
              <input id="debut" class="form-control date-planning-input" type="date" name="debut" required="">
              <label for="debut">Du</label>
            </div>
            <div class="form-floating col-md-12 col-lg-3 datePlanning">
                <input id="fin" class=" form-control date-planning-input"  type="date" name="fin" required="">
                <label for="fin">au</label>
            </div>
            
            <div class="form-floating col-md-12 col-lg-3 timePlanning">
                <input id="hdebut" class=" form-control" type="time" name="hdebut" value="<?=$hdebutTamp?>" required="">
                <label for="hdebut">de</label> 
            </div>
            <div class="form-floating col-md-12 col-lg-3 timePlanning">
                <input id="hfin" class="form-control" type="time" name="hfin" value="<?=$hfinTamp?>" required="">
                <label for="hfin">à</label>
            </div>
          </div>
          <div class="row g-2 justify-content-center">
            <div class="col-md-12 col-lg-4">
              <div class="form-floating">
               <select class="form-select" id="type-select" aria-label="Floating label select example" name="type">

                <option value="annualisées">Annualisées</option>
                <option value="complémentaires">Complémentaires</option>
                </select>
                <label for="type-select">Type heures</label>
              </div>
            </div>
          </div>
            <div class="col-md-12 col-lg-4"> 
              <div class="form-floating">
               <select class="form-select" id="tache-select" aria-label="Floating label select example" name="tache">
                <?php 
                  $tache_service = '';
                  $compt=0;
                  foreach ($lists['listeTache'] as $tache) 
                  {
                    if ($tache_service != $tache['service']) 
                    {
                      if ($tache_service != '') 
                      {
                        echo '</optgroup>';
                      }
                      echo '<optgroup id='.$compt.' label="'.ucfirst($tache['service']).'">';
                      $compt++; 
                    }
                    echo '<option value="'.$tache['tache'].'">'.htmlspecialchars($tache['tache']).'</option>';
                    $tache_service = $tache['service'];  
                     
                  }
                  if ($tache_service != '') 
                  {
                    echo '</optgroup>';
                    
                  }
                ?>
                </select>
                <label for="floatingInputGrid">Tache</label>
              </div>
            </div>
        
            <div class="col-md-12 col-lg-4">
              <div class="form-floating">
               <select class="form-select" id="lieu-select" aria-label="Floating label select example" name="lieu">
                <?php 
                  foreach ($lists['listeLieux'] as $lieu) 
                {
                ?>
                  <option value="<?=$lieu['lieu']?>"><?=ucfirst($lieu['lieu'])?></option>
                <?php
                }  
                ?>
                </select>
                <label for="lieu-select">Lieu</label>
              </div>
            </div>
            <div class="col-md-12 col-lg-4">
              <div class="form-floating">
               <select class="form-select" id="poste-select" aria-label="Floating label select example" name="poste">
                <?php 
                  foreach ($lists['listePoste'] as $poste) 
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
        	<div class="d-flex justify-content-center">
    		    <input hidden="hidden" type="text" id="jour" name="jour" value="<?=$jour?>">
          	<button type="submit" class="btn btn-success btnEditPlanning" id="editPlanning" name="updatePlanning"  disabled="true" >Mettre à jour</button>
            <button type="submit" class="btn btn-success btnEditPlanning" id="deletePlanning" name="deletePlanning"  disabled="true" >Supprimer</button>
        	</div>
      	</form>
     </div>
    </div>
</div>
<?php
} 
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script>
    
    $(document).ready(function(){
    	$btn=$(".btnEditPlanning");
      	
      $("#deletePlanning").click(function(e){
        confirm('Êtes-vous sur de vouloir supprimer ces horaires?');
      });

      $("#periode-select").change(function(){
        $groupTache=$("#tache-select").find("optgroup");
        if($('#periode-select').val()=='extrascolaire')
        {
          $groupTache.eq(0).attr('hidden',"hidden");
          $groupTache.eq(1).attr('hidden', "hidden");
        }else{
          $groupTache.eq(0).removeAttr('hidden',"hidden");
          $groupTache.eq(1).removeAttr('hidden', "hidden");
        } 
      });
		$(".datePlanning").change(function(){
        var i=0;
        $nbDate=$(".datePlanning").find('input');
        $debut=$('#debut').val(); $fin=$('#fin').val();
        if($debut<=$fin)
        {
          for(i=0; i<$nbDate.length; i++) 
          {
            $nbDate.eq(i).addClass("is-valid").removeClass("is-invalid");
          }
        }else{
          for(i=0; i<$nbDate.length; i++)
          {
            $nbDate.eq(i).addClass("is-invalid").removeClass("is-valid");
          }
        }
      	});
        $("#form-edit-planning").change(function(){
          var i=0;
          $nbTime=$(".timePlanning").find('input');
        
          $hdebut=$('#hdebut').val(); $hfin=$('#hfin').val();
        if($hdebut<$hfin)
        {
          for(i=0; i<$nbTime.length; i++) 
          {
            $nbTime.eq(i).addClass("is-valid").removeClass("is-invalid");
          }
        }else{
          for(i=0; i<$nbTime.length; i++)
          {
            $nbTime.eq(i).addClass("is-invalid").removeClass("is-valid");
          }
        }
      });
      	$(".timePlanning").change(function(){
       		var i=0;
        	$nbTime=$(".timePlanning").find('input');
        
        	$hdebut=$('#hdebut').val(); $hfin=$('#hfin').val();
        if($hdebut<$hfin)
        {
          for(i=0; i<$nbTime.length; i++) 
          {
            $nbTime.eq(i).addClass("is-valid").removeClass("is-invalid");
          }
        }else{
          for(i=0; i<$nbTime.length; i++)
          {
            $nbTime.eq(i).addClass("is-invalid").removeClass("is-valid");
          }
        }
      });

      $('form').change(function(){
        if(($debut<=$fin)&&($hdebut<=$hfin)){
          $btn.attr('disabled',false);
        }else{
          $btn.attr('disabled',true);
        }
      });

      $("#deletePlanning").click(function(e){
        e.preventDefault();
        $.post(
          "index.php?action=updatePlanning",
          {
            "deletePlanning" : $("#deletePlanning").val(),
            "periode": $("#periode-select").val(),
            "debut": $("#debut").val(),
            "fin": $("#fin").val(),
            "hdebut":$("#hdebut").val(),
            "hfin": $("#hfin").val(),
            "type": $("#type-select").val(),
            "tache": $("#tache-select").val(),
            "lieu":$("#lieu-select").val(),
            "poste":$("#poste-select").val(),
            "jour" : $("#jour").val()
          },
          function(data){
            alert(data);
          },
          'text'
        );
      });

       $("#editPlanning").click(function(e){
        e.preventDefault();
        $.post(
          "index.php?action=updatePlanning",
          {
            "periode": $("#periode-select").val(),
            "debut": $("#debut").val(),
            "fin": $("#fin").val(),
            "hdebut":$("#hdebut").val(),
            "hfin": $("#hfin").val(),
            "type": $("#type-select").val(),
            "tache": $("#tache-select").val(),
            "lieu":$("#lieu-select").val(),
            "poste":$("#poste-select").val(),
            "jour" : $("#jour").val()
          },
          function(data){
            alert(data);
            top.location.href='index.php?page=planningEdit';
          },
          'text'
        );
      });

      $("#submit-search-planning").click(function(e){
        e.preventDefault();
          if(($("#searchJour").val()!="")&&($("#searchDebut").val()!=""))
          {
            $.post(
              "index.php?action=searchPlanning",
              {
                "jour": $("#searchJour").val(),
                "debut": $("#searchDebut").val()
              },
              function(data){
               if(data=="Fail")
                {
                  $("#search-planning").removeAttr("hidden","hidden");
                }else
                {
                  $("#search-planning").removeAttr("hidden","hidden");
                  $("#search-planning").html(data);
                } 
              },
              "text"

            );
          }
      });
    });
  </script>

<?php
$content = ob_get_clean();
require ('template.php');
?>