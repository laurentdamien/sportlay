<?php

switch($ticket->reliability){
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

$totalQuotation = 1;

foreach($othersMatchsAll as $prognostic){
    if($prognostic->n_ticket == $ticket->id){
        switch($prognostic->my_prono){
            case 1:
                $quotation = $prognostic->quotation_home;
                break;
            case 'N':
                $quotation = $prognostic->quotation_draw;
                break;
            case 2:
                $quotation = $prognostic->quotation_away;
                break;
        }
        $totalQuotation = $totalQuotation * $quotation;
    }
}

?>

<div class="cardTicket">
   <div class="row text-center globaleQuotation">
       <p class="titleQuotation white" style="background-color: <?= $colorReliability ?>">Cote totale</p>
        <p class="white quotation" 
            style="background-color: <?= $colorReliability ?>">
                <?= round($totalQuotation, 2); ?>
        </p>
   </div>
    <?php
        $i = 2;
        foreach($othersMatchsAll as $otherMatch){
            if($otherMatch->n_ticket == $ticket->id){
                echo '<div class="matchTicket bg'.($i%2==0?'White':'Gray').'" data-match="'.($i-1).'">';
                echo $this->element('Matchs/match', ["prognostic" => $otherMatch, "ticket" => $ticket]);
                echo '</div>';
                $i++;
            }
        }
    ?>
</div>