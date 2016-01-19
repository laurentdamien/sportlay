<?php

/* Configure le script en français */
    setlocale (LC_TIME, 'fr_FR','fra');
    //Définit le décalage horaire par défaut de toutes les fonctions date/heure  
    date_default_timezone_set("Europe/Paris");
    //Definit l'encodage interne
    mb_internal_encoding("UTF-8");

    $strDate = mb_convert_encoding('%d %B %Y','UTF-8','UTF-8');
    $strDateMobile = mb_convert_encoding('%d/%m/%Y','UTF-8','UTF-8');
    $strHour = mb_convert_encoding('%Hh%M','UTF-8','UTF-8');
    
    $matchShow = -1;
    $nbMatch = -1;
    $max = 5;
?>


<h3 class="col-xs-12 black text-center">Derniers matchs de <?= $teamStats['name']; ?></h3>
<h6 class="col-xs-12 blue text-center">En match officiel pour la saison en cours</h6>

<?php if(isset($teamLastMatchs)){ ?>
<table class="table table-responsive">
<?php foreach(array_reverse($teamLastMatchs, TRUE) as $match){
        $nbMatch ++;
        
        if($nbMatch % $max == 0)
            $matchShow ++;
        
        $dateMatch = iconv("ISO-8859-1","UTF-8",strftime($strDate ,strtotime($match['date'])));
        $dateMatchMobile = iconv("ISO-8859-1","UTF-8",strftime($strDateMobile ,strtotime($match['date'])));
        $hourMatch = iconv("ISO-8859-1","UTF-8",strftime($strHour ,strtotime($match['date'])));

        if($teamStats['name'] == $match['homeTeamName']){
            if($match['goalsHomeTeam'] > $match['goalsAwayTeam'])
                $colorResult = '#1ED711';
            else if($match['goalsHomeTeam'] == $match['goalsAwayTeam'])
                $colorResult = '#222222';
            else if($match['goalsHomeTeam'] < $match['goalsAwayTeam'])
                $colorResult = '#D0021B';
        }else if($teamStats['name'] == $match['awayTeamName']){
            if($match['goalsHomeTeam'] < $match['goalsAwayTeam'])
                $colorResult = '#1ED711';
            else if($match['goalsHomeTeam'] == $match['goalsAwayTeam'])
                $colorResult = '#222222';
            else if($match['goalsHomeTeam'] > $match['goalsAwayTeam'])
                $colorResult = '#D0021B';
        }

?>
    <tr class="oldMatch" data-match="<?= $matchShow; ?>">
        <td class="text-left mediumCase desktop"><?= $dateMatch; ?></td>
        <td class="text-left mediumCase mobile"><?= $dateMatchMobile; ?></td>
        <td class="text-left smallCase hour"><?= $hourMatch; ?></td>
        <td class="text-right smallCase"><?= $match['nameSoccerseason']; ?></td>
        <td class="roundCase">
            <div class="roundWin roundWinHome" style="background-color: <?= $colorResult; ?>"></div>
        </td>
        <td class="text-right bigCase desktop"><?= $match['homeTeamName']; ?></td>
        <td class="text-right bigCase mobile"><?= ($match['homeTeamShortName'] == '' ? $match['homeTeamName'] : $match['homeTeamShortName']); ?></td>
        <td class="text-center smallCase">
            <?= ($match['goalsHomeTeam'] < 0 ? '-' : $match['goalsHomeTeam']); ?>
             : 
            <?= ($match['goalsAwayTeam'] < 0 ? '-' : $match['goalsAwayTeam']); ?>
        </td>
        <td class="text-left bigCase desktop"><?= $match['awayTeamName']; ?></td>
        <td class="text-left bigCase mobile"><?= ($match['awayTeamShortName'] == '' ? $match['awayTeamName'] : $match['awayTeamShortName']); ?></td>

    </tr>
<?php 
    }
?>
</table>


<?php if($nbMatch > ($max-1)){ ?>
<div class="text-center">
    <span class="gray pointer moreOldMatchs show" data-match="1">Voir plus de matchs</span>
</div>
<?php }

}else{
    echo '<p class="text-center gray">Pas de matchs recensés dans cette compétition cette saison.</p>';
}?>