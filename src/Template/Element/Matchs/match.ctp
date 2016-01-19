<?php

switch($prognostic->reliability){
    case 1:
        $reliability = "_fun";
        break;
    
    case 2:
        $reliability = "_ose";
        break;
    
    case 3:
        $reliability = "_fiable";
        break;
}

if(isset($ticket->id)){
    $reliability = '';
}

?>

<div class="row text-center match">
    <div class="col-xs-4">
       <?php echo $this->Html->image("check".$reliability.".png", 
        array(
            "alt" => "Icône check de validation du pronostic",
            "class" => "check home ".(($prognostic->my_prono == 1) ? "active" : "disables")
        )); ?>
        <img src="<?= $prognostic->home_pennant; ?>" 
            alt="Fanion de <?= $prognostic->home_team; ?>" 
            class="pennant" />
        <p class="gray nameTeam"><?= $prognostic->home_team; ?></p>
    </div>
    <div class="col-xs-1">
        <span class="red vs result">
        <?php
            if($prognostic->home_result >= 0)
                echo $prognostic->home_result; 
        ?>
        </span>
    </div>
    <div class="col-xs-2">
       <?php echo $this->Html->image("check".$reliability.".png",
        array(
            "alt" => "Icône check de validation du pronostic",
            "class" => "check vs ".(($prognostic->my_prono == "N") ? "active" : "disables")
        )); ?>
        <span class="red vs">VS</span>
    </div>
    <div class="col-xs-1">
        <span class="red vs result">
        <?php
            if($prognostic->away_result >= 0)
                echo $prognostic->away_result;
        ?>
        </span>
    </div>
    <div class="col-xs-4">
       <?php echo $this->Html->image("check".$reliability.".png",
        array(
            "alt" => "Icône check de validation du pronostic",
            "class" => "check away ".(($prognostic->my_prono == 2) ? "active" : "disables")
        ));
        ?>
        <img src="<?= $prognostic->away_pennant; ?>" 
            alt="Fanion de <?= $prognostic->away_team; ?>" 
            class="pennant" />
        <p class="gray nameTeam"><?= $prognostic->away_team; ?></p>
    </div>
</div>