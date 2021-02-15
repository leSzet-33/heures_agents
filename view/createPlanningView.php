<?php
$codePage=2;  
$title ="Création d'emploi du temps";
ob_start();
?>
  <p class="h1">Création d'un horaire agent</p>
  <div class="row justify-content-center">
      <div class="col-lg-9">
        <form class="row g-3 formUser" action="index.php?action=addPlanning" method="post">
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
                <input id="hdebut" class=" form-control" type="time" name="hdebut" required="">
                <label for="hdebut">de</label> 
            </div>
            <div class="form-floating col-md-12 col-lg-3 timePlanning">
                <input id="hfin" class="form-control" type="time" name="hfin" required="">
                <label for="hfin">à</label>
            </div>
          </div>
          <div class="row g-2 justify-content-center">
            <div class="col-md-12 col-lg-4">
              <div class="form-floating">
               <select class="form-select" id="type-select" aria-label="Floating label select example" name="type">
                <option selected value="annualisées">Annualisées</option>
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
                    echo '<option value="'.$tache['tache'].'">'.ucfirst($tache['tache']).'</option>';
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
         <div class="row g-3 justify-content-center">
          <div class="col-lg-9 ">
            <div id="checkDay" class="form-group">
              <div class="form-check form-check-inline planningDay">
                <label class="form-check-label">Lundi</label>
                <input class="form-check-input jourCheck" type="checkbox" name="repeter_jour" value="1" checked="checked">
              </div>
              <div class="form-check form-check-inline planningDay">
                <label class="form-check-label">Mardi</label>
                <input class="form-check-input jourCheck" type="checkbox" name="repeter_jour" value="2" checked="checked">
              </div>
              <div class="form-check form-check-inline planningDay">
                <label class="form-check-label">Mercredi</label>
                <input class="form-check-input jourCheck" type="checkbox" name="repeter_jour" value="3" >
              </div>
              <div class="form-check form-check-inline planningDay">
                <label class="form-check-label">Jeudi</label>
                <input class="form-check-input jourCheck" type="checkbox"  name="repeter_jour" value="4" checked="checked">
              </div>
              <div class="form-check form-check-inline planningDay">
                <label class="form-check-label">Vendredi</label>
                <input  class="form-check-input jourCheck" type="checkbox"  name="repeter_jour" value="5" checked="checked">
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-center">
          <button type="submit" class="btn btn-success" id="btnPlanning" name="addPlanning"  disabled="true" >Ajouter</button>
        </div>
      </form>
       </div>
      </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script>
    
    $(document).ready(function(){

      $btn=$("#btnPlanning");
      $groupTache=$("#tache-select").find("optgroup");
      $taches=$('#tache-select').find("option");
      $taches.eq(10).attr('hidden','hidden');
      $("#periode-select").change(function(){
        
        if($('#periode-select').val()=='extrascolaire')
        {
          $groupTache.eq(0).attr('hidden',"hidden");
          $groupTache.eq(1).attr('hidden', "hidden");
          $taches.eq(10).removeAttr('hidden','hidden');
        }else{
          $groupTache.eq(0).removeAttr('hidden',"hidden");
          $groupTache.eq(1).removeAttr('hidden', "hidden");
          $taches.eq(10).attr('hidden','hidden');
        }
        
      });

      $("#tache-select").change(function(){
        var i = 0;
        // récupération du nombre de checkbox
        $nbJour=$("#checkDay").find("input");
        // si je selctionne Périscolaire Mercredis 
        if($("#tache-select").val()=="Périscolaire Mercredis")
        {
          //la checkbox correspondant à l'indice 2 (Mercredi) sera checké en lecture seule
          $nbJour.eq(2).attr({checked:'checked', onclick: 'return false'});
          for(i=0; i<$nbJour.length; i++)
          {
            if(i!=2)
            {
              
              $nbJour.eq(i).attr('disabled','disabled').removeAttr('checked','checked');
            }
          }
        }else
        {
          $nbJour.eq(2).removeAttr('checked','checked').attr('onclick', 'return true');
          for(i=0; i<$nbJour.length; i++)
          {
            if(i!=2)
            {
              
              $nbJour.eq(i).removeAttr('disabled','disabled').attr('checked','checked');
            }
          }
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

      $("#btnPlanning").click(function(e){
        e.preventDefault();
        var $jourCheck = [];
        $.each($("input[name='repeter_jour']:checked"), function() {
            $jourCheck.push($(this).val());
        });
        $.post(
          "index.php?action=addPlanning",
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
            "repeter_jour" : $jourCheck
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
$content=ob_get_clean();
require('template.php');
?>