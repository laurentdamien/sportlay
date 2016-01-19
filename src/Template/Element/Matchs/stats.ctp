<?php

$homeTeamWin = (intval($lastMatchs["homeTeamWins"]) > 0 ? intval($lastMatchs["homeTeamWins"]) : 1);

$draws = (intval($lastMatchs["draws"]) > 0 ? intval($lastMatchs["draws"]) : 1);
$legendeDraw = (intval($lastMatchs["draws"]) > 1 ? "Nuls" : "Nul");

$awayTeamWin = (intval($lastMatchs["awayTeamWins"]) > 0 ? intval($lastMatchs["awayTeamWins"]) : 1);

$homeSizeWin = 20*$homeTeamWin;
$drawsSize = 20*$draws;
$awaySizeWin = 20*$awayTeamWin;

if($homeTeamWin < 3){
    $homeFontSizeWin = 3.5;
    $homeSizeWin = 60;
}else if($homeTeamWin > 9){
    $homeFontSizeWin = 8;
    $homeSizeWin = 180;
}else{
    $homeFontSizeWin = $homeTeamWin;
    $homeSizeWin = 20*$homeTeamWin;
}

if($draws < 3){
    $drawsFontSize = 3.5;
    $drawsSize = 60;
}else if($draws > 9){
    $drawsFontSize = 8;
    $drawsSize = 180;
}else{
    $drawsFontSize = $draws;
    $drawsSize = 20*$draws;
}

if($awayTeamWin < 3){
    $awayFontSizeWin = 3.5;
    $awaySizeWin = 60;
}else if($awayTeamWin > 9){
    $awayFontSizeWin = 8;
    $awaySizeWin = 180;
}else{
    $awayFontSizeWin = $awayTeamWin;
    $awaySizeWin = 20*$awayTeamWin;
}

foreach($teams as $team){
    if($team['teamName'] == $prognostic['home_team'])
        $homeTeam = $team;
    
    if($team['teamName'] == $prognostic['away_team'])
        $awayTeam = $team;
}

/* Configure le script en français */
    setlocale (LC_TIME, 'fr_FR','fra');
    //Définit le décalage horaire par défaut de toutes les fonctions date/heure  
    date_default_timezone_set("Europe/Paris");
    //Definit l'encodage interne
    mb_internal_encoding("UTF-8");

    $strDate = mb_convert_encoding('%d %B %Y','UTF-8','UTF-8');
    $strDateMobile = mb_convert_encoding('%d/%m/%Y','UTF-8','UTF-8');
    $strHour = mb_convert_encoding('%Hh%M','UTF-8','UTF-8');

?>

<?php if(!empty($lastMatchs['count'])){ ?>
<h3 class="col-xs-12 black text-center">Nombre de victoires entre <?= (empty($homeTeam['teamShortName']) ? $prognostic['home_team'] : $homeTeam['teamShortName']); ?> et <?= (empty($awayTeam['teamShortName']) ? $prognostic['away_team'] : $awayTeam['teamShortName']); ?></h3>
<h6 class="col-xs-12 blue text-center">Sur les <?= $lastMatchs['count']; ?> dernières confrontations en <?= $match->competition; ?> entre les 2 équipes</h6>

<div class="row winTeams">
   <div class="col-xs-5 text-right">
        <div class="divHomeWin"
             style="width: <?= $homeSizeWin+7; ?>px;">
             <div class="divTeamWin">
                <p class="text-center homeWin" 
                   style="color: <?= $prognostic['home_color']; ?>;
                        border: 6px solid <?= $prognostic['home_color']; ?>;
                        font-size: <?= $homeFontSizeWin; ?>rem;
                        width: <?= $homeSizeWin; ?>px;
                        height: <?= $homeSizeWin; ?>px;
                        line-height: <?= $homeSizeWin*0.75; ?>px;"
                >
                    <?= $lastMatchs["homeTeamWins"]; ?>
                </p>
            </div>
            <p class="gray text-center legendeTeam"><?= (empty($homeTeam['teamShortName']) ? $prognostic['home_team'] : $homeTeam['teamShortName']); ?></p>
        </div>
    </div>
    <div class="col-xs-2 text-right">
        <div>
            <div class="divTeamWin">
                <p class="text-center black draws" 
                   style="border: 6px solid #222222;
                        font-size: <?= $drawsFontSize; ?>rem;
                        width: <?= $drawsSize; ?>px;
                        height: <?= $drawsSize; ?>px;
                        line-height: <?= $drawsSize*0.75; ?>px;"
                >
                    <?= $lastMatchs["draws"]; ?>
                </p>
            </div>
            <p class="gray text-center legendeTeam"><?= $legendeDraw; ?></p>
        </div>
    </div>
    <div class="col-xs-5 text-left">
        <div class="divAwayWin"
             style="width: <?= $awaySizeWin+7; ?>px;">
             <div class="divTeamWin">
                <p class="text-center awayWin"
                   style="color: <?= $prognostic['away_color']; ?>;
                        border: 6px solid <?= $prognostic['away_color']; ?>;
                        font-size: <?= $awayFontSizeWin; ?>rem;
                        width: <?= $awaySizeWin; ?>px;
                        height: <?= $awaySizeWin; ?>px;
                        line-height: <?= $awaySizeWin*0.75; ?>px;"
                >
                    <?= $lastMatchs['awayTeamWins']; ?>
                </p>
            </div>
            <p class="gray text-center legendeTeam"><?= (empty($awayTeam['teamShortName']) ? $prognostic['away_team'] : $awayTeam['teamShortName']); ?></p>
        </div>
    </div>
</div>

<?php if(isset($homeTeam) && isset($awayTeam)){ ?>
<div class="panel-group statsFull" id="accordionStats" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingStats">
            <h4 class="panel-title text-center">
                <a class="text-center collapsed" role="button" data-toggle="collapse" data-parent="#accordionStats" data-close="Voir le détail des rencontres" data-open="Fermer les détails" href="#collapseStats" aria-expanded="true" aria-controls="collapseStats">
                    Voir le détail des rencontres
                </a>
            </h4>
        </div>
        <div id="collapseStats" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingStats">
            <div class="panel-body text-center statsFullDetails">
               <table class="table table-responsive">
                <?php foreach($lastMatchs as $match){
                        if(is_array($match)){
                            foreach($teams as $team){
                                if($team['teamName'] == $match['homeTeamName'])
                                    $homeTeam = $team;

                                if($team['teamName'] == $match['awayTeamName'])
                                    $awayTeam = $team;
                            }
                            
                            $dateMatch = iconv("ISO-8859-1","UTF-8",strftime($strDate ,strtotime($match['date'])));
                            $dateMatchMobile = iconv("ISO-8859-1","UTF-8",strftime($strDateMobile ,strtotime($match['date'])));
                            $hourMatch = iconv("ISO-8859-1","UTF-8",strftime($strHour ,strtotime($match['date'])));

                            if(intval($match['goalsHomeTeam']) > intval($match['goalsAwayTeam']) && $match['homeTeamName'] == $prognostic['home_team']){
                                $colorHome = $prognostic['home_color'];
                                $colorAway = 'transparent';
                            }else if(intval($match['goalsHomeTeam']) > intval($match['goalsAwayTeam']) && $match['homeTeamName'] == $prognostic['away_team']){
                                $colorHome = $prognostic['away_color'];
                                $colorAway = 'transparent';
                            }else if(intval($match['goalsHomeTeam']) < intval($match['goalsAwayTeam']) && $match['awayTeamName'] == $prognostic['home_team']){
                                $colorHome = 'transparent';
                                $colorAway = $prognostic['home_color'];
                            }else if(intval($match['goalsHomeTeam']) < intval($match['goalsAwayTeam']) && $match['awayTeamName'] == $prognostic['away_team']){
                                $colorHome = 'transparent';
                                $colorAway = $prognostic['away_color'];
                            }else{
                                $colorHome = 'transparent';
                                $colorAway = 'transparent';
                            }
                ?>
                        <tr class="oldMatch">
                            <td class="text-left bigCase desktop"><?= $dateMatch; ?></td>
                            <td class="text-left bigCase mobile"><?= $dateMatchMobile; ?></td>
                            <td class="text-left smallCase desktop"><?= $hourMatch; ?></td>
                            <td class="roundCase"><div class="roundWin roundWinHome" style="background-color: <?= $colorHome; ?>"></div></td>
                            <td class="text-right mediumCase"><?= $homeTeam['teamShortName']; ?></td>
                            <td class="text-center smallCase"><?= $match['goalsHomeTeam']; ?> : <?= $match['goalsAwayTeam']; ?></td>
                            <td class="text-left mediumCase"><?= $awayTeam['teamShortName']; ?></td>
                            <td class="roundCase"><div class="roundWin roundWinAway" style="background-color: <?= $colorAway; ?>"></div></td>
                        </tr>
                <?php
                        }
                      }
                ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php 
}
    }else{
        echo '<p class="text-center gray">Aucune confrontation recensée dans cette compétition entre les 2 équipes.</p>';
    } ?>