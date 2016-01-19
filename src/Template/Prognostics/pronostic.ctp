<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;

if(DEBUG != 'false'){
    if (!Configure::read('debug'))
        throw new NotFoundException();
}

echo $this->element('head');

?>

<body class="page prognostic">
   <?php echo $this->element('header'); ?>
    <section class="content">
       <section class="pronoDay">
            <a href="<?= HOME; ?>">
                <svg width="50" height="50">
                  <circle id="circleArrowReturn" r="20" cx="25" cy="25" stroke="white" stroke-width="3" fill="none" />
                </svg>
                <?php echo $this->Html->image("arrow_left.png", array(
                    "alt" => "Flèche qui permet le retour à la page précédente",
                    "class" => "arrowReturn pointer"
                )); ?>
            </a>
            <div class="homeColor" style="background-color: <?= $match['home_color']; ?>"></div>
            <div class="awayColor" style="background-color: <?= $match['away_color']; ?>"></div>
            <div class="matchOfTheDay">
                <?php echo $this->element('Matchs/cardDetailsMatch', ["prognostic" => $match]); ?>
            </div>
            <?php echo $this->Html->image("round_border.png", array(
                "alt" => "Image blanche arrondie",
                "class" => "roundBorder"
            )); ?>
        </section>
        
        <section class="row ranking">
            <?php echo $this->element('Matchs/ranking', ["prognostic" => $match, "ranking" => $ranking]); ?>
        </section>
        
        <section class="lastResults">
            <?php echo $this->element('Matchs/stats', ["prognostic" => $match, "lastMatchs" => $lastMatchs, "teams" => $ranking]); ?>
        </section>
        
        <section class="lastResultsTeams">
            <div>
               <div class="lastResultsHomeTeam">
                    <?php echo $this->element('Matchs/lastMatchs', ["prognostic" => $match, "teamStats" => $homeTeamStats, "teamLastMatchs" => $homeLastMatchs]); ?>
                </div>
                <div class="lastResultsAwayTeam">
                    <?php echo $this->element('Matchs/lastMatchs', ["prognostic" => $match, "teamStats" => $awayTeamStats, "teamLastMatchs" => $awayLastMatchs]); ?>
                </div>
            </div>
        <?php if(isset($homeLastMatchs) || isset($awayLastMatchs)){ ?>
            <div class="legend">
                <p class="legendWin">Victoire</p>
                <p class="legendDrawn">Nul</p>
                <p class="legendDefeat">Défaite</p>
            </div>
        <?php } ?>
        </section>
   </section>
<?php echo $this->element('footer'); ?>