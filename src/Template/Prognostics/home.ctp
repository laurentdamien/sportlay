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

<body class="home">
   <?php echo $this->element('header'); ?>
    <section class="content">
      <section class="pronoDay">
          <?php echo $this->Html->image("mapdotted.png", array(
                "alt" => "Carte du monde en point",
                "class" => "map"
            )); ?>
            <div class="matchOfTheDay">
                <h3 class="white">Match du jour : </h3>
                <h4 class="blue">Le match le plus fiable, avec le meilleur rapport fiabilité/côte</h4>
                <a class="pointer" href="./prognostic/<?= $match0fTheDay->id; ?>">
                <?php echo $this->element('Matchs/cardMatch', ["prognostic" => $match0fTheDay]); ?>
                </a>
            </div>
            <?php echo $this->Html->image("round_border.png", array(
                "alt" => "Image blanche arrondie",
                "class" => "roundBorder"
            )); ?>
      </section>
      
      <section class="advice">
          <div class="container">
              <div class="row">
                <h3 class="black">Conseils du jour</h3>
                <h4 class="gray">A prendre en compte... Ou pas !</h4>
                 <p class="gray text-center">
                  <?php
                    if($adviceDefault->published > 0){
                        $text = "text".($adviceDefault->published);
                        echo $adviceDefault->$text;
                    }else{
                        $text = "text".($advice->published);
                        echo $advice->$text;
                    }
                  ?>
                  </p>
              </div>
          </div>
      </section>
      
      <section class="otherPronos">
         <div class="container">
              <div class="row">
                  <div class="col-sm-4">
                     <h3 class="black">Tous les pronostics du jour</h3>
                     <h4 class="gray">Fiables, osés, funs... Fais ton choix !</h4>
                  </div>
                  <div class="col-sm-8 text-right filters">
                      <ul class="row">
                          <li class="col-xs-6 col-sm-3">
                              <p class="pointer black filter filterAll styleAll" data-reliability="0">Tous</p>
                          </li>
                          <li class="col-xs-6 col-sm-3">
                              <p class="pointer black filter filterReliability" data-reliability="3">Fiables</p>
                          </li>
                          <li class="col-xs-6 col-sm-3">
                              <p class="pointer black filter filterRacy" data-reliability="2">Osés</p>
                          </li>
                          <li class="col-xs-6 col-sm-3">
                              <p class="pointer black filter filterFun" data-reliability="1">Funs</p>
                          </li>
                      </ul>
                  </div>
              </div>
              <div class="row">
                 <div id="sliderAll" class="col-sm-12 owl-carousel active" data-reliability="0">
                    <?php
                    if($othersMatchsAll->count() > 0){
                        foreach($othersMatchsAll as $key => $match){
                            echo '<div class="singleProno" data-reliability="'.$match->reliability.'">';
                            echo '<a class="pointer" href="./prognostic/'. $match->id.'">';
                            echo $this->element('Matchs/cardMatch', ["prognostic" => $match, "key" => $key]);
                            echo '</a>';
                            echo '</div>';
                        }
                    }else{
                    ?>
                    <div class="col-xs-12">
                        <p class="text-center gray noMatch">Aucun match supplémentaire proposé aujourd'hui</p>
                    </div>
                <?php } ?>
                </div>
                <div id="sliderFiable" class="col-sm-12 owl-carousel disables" data-reliability="3">
                    <?php
                    if($othersMatchsFiable->count() > 0){
                        foreach($othersMatchsFiable as $key => $match){
                            echo '<div class="singleProno" data-reliability="'.$match->reliability.'">';
                            echo '<a class="pointer" href="./prognostic/'. $match->id.'">';
                            echo $this->element('Matchs/cardMatch', ["prognostic" => $match, "key" => $key]);
                            echo '</a>';
                            echo '</div>';
                        }
                    }else{
                    ?>
                    <div class="col-xs-12">
                        <p class="text-center gray noMatch">Aucun match fiable proposé aujourd'hui</p>
                    </div>
                <?php } ?>
                </div>
                <div id="sliderOse" class="col-sm-12 owl-carousel disables" data-reliability="2">
                    <?php
                    if($othersMatchsOse->count() > 0){
                        foreach($othersMatchsOse as $key => $match){
                            echo '<div class="singleProno" data-reliability="'.$match->reliability.'">';
                            echo '<a class="pointer" href="./prognostic/'. $match->id.'">';
                            echo $this->element('Matchs/cardMatch', ["prognostic" => $match, "key" => $key]);
                            echo '</a>';
                            echo '</div>';
                        }
                    }else{
                    ?>
                    <div class="col-xs-12">
                        <p class="text-center gray noMatch">Aucun match osé proposé aujourd'hui</p>
                    </div>
                <?php } ?>
                </div>
                <div id="sliderFun" class="col-sm-12 owl-carousel disables" data-reliability="1">
                    <?php
                    if($othersMatchsFun->count() > 0){
                        foreach($othersMatchsFun as $key => $match){
                            echo '<div class="singleProno" data-reliability="'.$match->reliability.'">';
                            echo '<a class="pointer" href="./prognostic/'. $match->id.'">';
                            echo $this->element('Matchs/cardMatch', ["prognostic" => $match, "key" => $key]);
                            echo '</a>';
                            echo '</div>';
                        }
                    }else{
                    ?>
                    <div class="col-xs-12">
                        <p class="text-center gray noMatch">Aucun match fun proposé aujourd'hui</p>
                    </div>
                <?php } ?>
                </div>
              </div>
          </div>
      </section>
      
      <section class="ticketsProno">
          <div class="container">
              <div class="row">
                  <div class="col-sm-12">
                     <h3 class="black">Les tickets du jour</h3>
                     <h4 class="gray">Fiables, osés, funs... Fais ton choix !</h4>
                  </div>
              </div>
              <div class="row listTickets">
                <?php
                if($ticketsAll->count() > 0){
                    foreach($ticketsAll as $ticket){
                        echo '<div class="col-md-4 col-sm-6">';
                        echo '<a class="pointer" href="./ticket/'.$ticket->id.'/match1">';
                        echo $this->element('Matchs/cardTicket', ["ticket" => $ticket, "othersMatchsAll" => $matchs]);
                        echo '</a>';
                        echo '</div>';
                    }
                }else{
                ?>
                    <div class="col-xs-12">
                        <p class="text-center gray">Aucun ticket proposé aujourd'hui</p>
                    </div>
                <?php } ?>
              </div>
          </div>
      </section>
    </section>
<?php echo $this->element('footer'); ?>

