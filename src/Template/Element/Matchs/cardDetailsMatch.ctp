<?php

switch($prognostic->reliability){
    case 1:
        $colorReliability = "#D0021B";
        break;
    
    case 2:
        $colorReliability = "#FF8D00";
        break;
    
    case 3:
        $colorReliability = "#1ED711";
        break;
}

$colorNeutral = '#111111';

/* Configure le script en français */
    setlocale (LC_TIME, 'fr_FR','fra');
    //Définit le décalage horaire par défaut de toutes les fonctions date/heure  
    date_default_timezone_set("Europe/Paris");
    //Definit l'encodage interne
    mb_internal_encoding("UTF-8");

    $strDate = mb_convert_encoding('%A %d %B %Y <br/> - %Hh%M -','UTF-8','UTF-8');  

    $dateMatch = iconv("ISO-8859-1","UTF-8",strftime($strDate ,strtotime($match['date_match'])));

?>

<div class="cardDetailsMatch <?php if(isset($key)) echo ($key==1?'floatR':''); ?>">
   <div class="row text-center">
       <h3 class="black"><?= $prognostic->competition.' : '.$prognostic->matchday.'<sup>'.($prognostic->matchday==1?'er':'e').'</sup> journée'; ?></h3>
       <h6 class="blue">
           <?= $dateMatch; ?>
       </h6>
   </div>
    <?php echo $this->element('Matchs/match', ["prognostic" => $prognostic]); ?>
    <div class="row text-center">
        <div class="col-xs-4">
            <p class="white quotation" 
            style="background-color: <?= (($prognostic->my_prono == 1) ? $colorReliability : $colorNeutral) ?>">
                <?= $prognostic->quotation_home; ?>
            </p>
        </div>
        <div class="col-xs-4">
            <p class="white quotation" 
            style="background-color: <?= (($prognostic->my_prono == 'N') ? $colorReliability : $colorNeutral) ?>">
                <?= $prognostic->quotation_draw; ?>
            </p>
        </div>
        <div class="col-xs-4">
            <p class="white quotation" 
            style="background-color: <?= (($prognostic->my_prono == 2) ? $colorReliability : $colorNeutral) ?>">
                <?= $prognostic->quotation_away; ?>
            </p>
        </div>
    </div>
    <div class="row analysis">
        <p class="col-xs-12 text-center gray"><?= $prognostic->analysis; ?></p>
    </div>
</div>