<?php

switch($prognostic->reliability){
    case 1:
        $reliability = "fun";
        $colorReliability = "#D0021B";
        break;
    
    case 2:
        $reliability = "ose";
        $colorReliability = "#FF8D00";
        break;
    
    case 3:
        $reliability = "fiable";
        $colorReliability = "#1ED711";
        break;
}
?>

<div class="cardMatch <?php if(isset($key)) echo ($key==1?'floatR':''); ?>">
   <div class="row text-center">
      <?php echo $this->Html->image($reliability."_color.png",
        array(
            "alt" => "Icône qui représente la fiabilité du pronostic",
            "class" => "iconReliability icon".ucfirst($reliability)
        )); ?>
       <h5 class="gray"><?= $prognostic->competition; ?></h5>
       <h6 class="gray">
           <?=
                $prognostic->date_match->i18nFormat('dd/MM/YYYY à HH'); 
           ?>h<?php
               if($prognostic->date_match->i18nFormat('mm') != '00'){
                    echo $prognostic->date_match->i18nFormat('mm');
                }
           ?>
       </h6>
   </div>
    <?php echo $this->element('Matchs/match', ["prognostic" => $prognostic]); ?>
    <div class="row text-center">
        <div class="col-xs-4">
            <p class="white quotation 
            <?php 
                echo (($prognostic->my_prono == 1) ? "active" : "disables")
            ?>" 
            style="background-color: <?= $colorReliability ?>"
            >
                <?= $prognostic->quotation_home; ?>
            </p>
        </div>
        <div class="col-xs-4">
            <p class="white quotation 
            <?php 
                echo (($prognostic->my_prono == "N") ? "active" : "disables")
            ?>" 
            style="background-color: <?= $colorReliability ?>"
            >
                <?= $prognostic->quotation_draw; ?>
            </p>
        </div>
        <div class="col-xs-4">
            <p class="white quotation 
            <?php 
                echo (($prognostic->my_prono == 2) ? "active" : "disables")
            ?>" 
            style="background-color: <?= $colorReliability ?>"
            >
                <?= $prognostic->quotation_away; ?>
            </p>
        </div>
    </div>
</div>