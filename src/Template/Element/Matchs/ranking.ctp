<?php

foreach($ranking as $team){
    if($team['teamName'] == $match['home_team'])
        $homeTeamRanking = $team['position'];
    
    if($team['teamName'] == $match['away_team'])
        $awayTeamRanking = $team['position'];
}

if(empty($homeTeamRanking))
    $homeTeamRanking = 0;

if(empty($awayTeamRanking))
    $awayTeamRanking = 0;

$homeSizeRanking = ($homeTeamRanking < 6 ? 170 : (200-5*$homeTeamRanking));
$awaySizeRanking = ($awayTeamRanking < 6 ? 170 : (200-5*$awayTeamRanking));

$homeFontSizeRanking = round(8-$homeTeamRanking/5, 2);
$awayFontSizeRanking = round(8-$awayTeamRanking/5, 2);

$homeFontSizeSupRanking = round($homeFontSizeRanking/1.55, 2);
$awayFontSizeSupRanking = round($awayFontSizeRanking/1.55, 2);

if($homeTeamRanking != 0 && $awayTeamRanking !=0){
?>


<h3 class="col-xs-12 black text-center">Classement actuel des équipes</h3>
<h6 class="col-xs-12 blue text-center">- <?= $match->competition; ?> -</h6>

<div class="row rankingTeams">
   <div class="col-xs-6 text-right">
        <p class="text-center homeRanking" 
           style="color: <?= $prognostic['home_color']; ?>;
                border: 6px solid <?= $prognostic['home_color']; ?>;
                font-size: <?= $homeFontSizeRanking; ?>rem;
                width: <?= $homeSizeRanking; ?>px;
                height: <?= $homeSizeRanking; ?>px;
                line-height: <?= $homeSizeRanking-12; ?>px;"
        >
            <?= $homeTeamRanking.'<sup style="font-size: '.$homeFontSizeSupRanking.'rem">'.($homeTeamRanking==1?'er':'e').'</sup>'; ?>
        </p>
    </div>
    <div class="col-xs-6 text-left">
        <p class="text-center awayRanking"
           style="color: <?= $prognostic['away_color']; ?>;
                border: 6px solid <?= $prognostic['away_color']; ?>;
                font-size: <?= $awayFontSizeRanking; ?>rem;
                width: <?= $awaySizeRanking; ?>px;
                height: <?= $awaySizeRanking; ?>px;
                line-height: <?= $awaySizeRanking-12; ?>px;"
        >
            <?= $awayTeamRanking.'<sup style="font-size: '.$awayFontSizeSupRanking.'rem">'.($awayTeamRanking==1?'er':'e').'</sup>'; ?>
        </p>
    </div>
</div>



<div class="panel-group rankingFull" id="accordionRanking" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingRanking">
            <h4 class="panel-title text-center">
                <a class="text-center collapsed" role="button" data-toggle="collapse" data-parent="#accordionRanking" data-close="Voir le classement complet" data-open="Fermer le classement" href="#collapseRanking" aria-expanded="true" aria-controls="collapseRanking">
                    Voir le classement complet
                </a>
            </h4>
        </div>
        <div id="collapseRanking" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRanking">
            <div class="panel-body rankingFullDetails">
               <div class="categorieRanking">
                    <p class="text-uppercase text-center general">Général</p>
                    <p class="text-uppercase text-center mobile stats">Domicile</p>
                    <p class="text-uppercase text-center mobile stats">Extérieur</p>
                    <p class="text-uppercase text-center mobile stats">Buts</p>
                </div>
                <table class="table table-responsive">
                    <tr>
                        <th class="mediumCase"></th>
                        <th class="text-uppercase text-left bigCase">Equipe</th>
                        <th class="text-uppercase text-center mediumCase">Pts</th>
                        <th class="text-uppercase text-center mediumCase">Joués</th>
                        <th class="text-uppercase text-center">V</th>
                        <th class="text-uppercase text-center">N</th>
                        <th class="text-uppercase text-center borderR">D</th>
                        <th class="text-uppercase text-center mobile">V</th>
                        <th class="text-uppercase text-center mobile">N</th>
                        <th class="text-uppercase text-center mobile borderR">D</th>
                        <th class="text-uppercase text-center mobile">V</th>
                        <th class="text-uppercase text-center mobile">N</th>
                        <th class="text-uppercase text-center mobile borderR">D</th>
                        <th class="text-uppercase text-center mobile">BP</th>
                        <th class="text-uppercase text-center mobile">BC</th>
                        <th class="text-uppercase text-center mobile">DB</th>
                    </tr>
        <?php foreach($ranking as $team){
            if(is_array($team)){
                if($team['teamName'] == $match['home_team']){
                    echo '<tr class="teamMatch" style="background-color: '.$match["home_color"].'">';
                }else if($team['teamName'] == $match['away_team']){
                    echo '<tr class="teamMatch" style="background-color: '.$match["away_color"].'">';
                }else{
                    echo '<tr>';
                }
            ?>
                        <td class="text-center"><?= $team['position']; ?>.</td>
                        <td class="text-left"><?= $team['teamShortName']; ?></td>
                        <td class="text-center"><strong><?= $team['points']; ?></strong></td>
                        <td class="text-center"><?= $team['playedGames']; ?></td>
                        <td class="text-center"><?= $team['win']; ?></td>
                        <td class="text-center"><?= $team['drawn']; ?></td>
                        <td class="text-center borderR"><?= $team['lost']; ?></td>
                        <td class="text-center mobile"><?= $team['homeWin']; ?></td>
                        <td class="text-center mobile"><?= $team['homeDrawn']; ?></td>
                        <td class="text-center mobile borderR"><?= $team['homeLost']; ?></td>
                        <td class="text-center mobile"><?= $team['awayWin']; ?></td>
                        <td class="text-center mobile"><?= $team['awayDrawn']; ?></td>
                        <td class="text-center mobile borderR"><?= $team['awayLost']; ?></td>
                        <td class="text-center mobile"><?= $team['goals']; ?></td>
                        <td class="text-center mobile"><?= $team['goalsAgainst']; ?></td>
                        <td class="text-center mobile"><strong><?= $team['goalDifference']; ?></strong></td>
                    </tr>
        <?php   }
            } ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>