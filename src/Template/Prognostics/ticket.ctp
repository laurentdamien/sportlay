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

<body class="page ticket">
   <?php echo $this->element('header'); ?>
    <section class="content">
        <section class="ticketDay">
            <a href="<?= HOME; ?>">
                <svg width="50" height="50">
                    <circle id="circleArrowReturn" r="20" cx="25" cy="25" stroke="white" stroke-width="3" fill="none" />
                </svg>
                <?php echo $this->Html->image("arrow_left.png", array(
                "alt" => "Flèche qui permet le retour à la page précédente",
                "class" => "arrowReturn pointer"
                )); ?>
            </a>
            <div id="globalInfosTicket" class="row">
                <div class="col-xs-12 col-sm-5 col-md-4">
                    <h3 class="black text-center">Informations générales</h3>
                    <h6 class="blue text-center">Analyse globale du ticket</h6>              
                    <div class="analysis gray">
                        <p class="text-center"><?= $ticket->analysis; ?></p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-7 col-md-8">
                    <h3 class="black text-center">Premier match à jouer du ticket</h3>
                    <h6 class="blue text-center">
                        Coup d'envoi à 
                        <?= 
                            $firstMatch->date_match->i18nFormat('HH')
                            .'h'.
                            $firstMatch->date_match->i18nFormat('mm'); 
                        ?>
                    </h6>
                    <div class="firstMatch gray">
                       <div class="row gray">
                            <div class="col-xs-5">
                                <img src="<?= $firstMatch->home_pennant; ?>" 
                                     alt="Fanion de <?= $firstMatch->home_team; ?>" 
                                     class="pennant" />
                            </div>
                            <p class="text-center col-xs-2">VS</p>
                            <div class="col-xs-5">
                                <img src="<?= $firstMatch->away_pennant; ?>" 
                                     alt="Fanion de <?= $firstMatch->away_team; ?>" 
                                     class="pennant" />
                           </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="detailsInfosTicket" class="row">
                <div class="col-xs-12 col-sm-5 col-md-4" id="listTicket">
                    <?php
                        echo $this->element('Matchs/cardTicket', ["ticket" => $ticket, "othersMatchsAll" => $matchs]);
                    ?>
                </div>
                <div class="col-xs-12 col-sm-7 col-md-8" id="detailsMatchTicket">
                    <section class="detailsMatch">
                       <?php echo $this->element('Matchs/cardDetailsMatch', ["prognostic" => $match]); ?>
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
                </div>
            </div>
        </section>
    </section>
<?php echo $this->element('footer'); ?>