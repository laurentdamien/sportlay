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

/* Configure le script en français */
setlocale (LC_TIME, 'fr_FR','fra');
//Définit le décalage horaire par défaut de toutes les fonctions date/heure  
date_default_timezone_set("Europe/Paris");
//Definit l'encodage interne
mb_internal_encoding("UTF-8");

$strDate = mb_convert_encoding('%d %B %Y','UTF-8','UTF-8');
$strDateMobile = mb_convert_encoding('%d/%m/%Y','UTF-8','UTF-8');


if($adviceDefault->published > 0){
    $text = "text".($adviceDefault->published);
    $myAdvice = $adviceDefault->$text;
}else{
    $text = "text".($advice->published);
    $myAdvice = $advice->$text;
}

?>

<body class="page admin">
   <?php echo $this->element('header'); ?>
   <section class="content gray text-center">
       <a href="<?= HOME; ?>">
            <svg width="50" height="50">
              <circle id="circleArrowReturn" r="20" cx="25" cy="25" stroke="white" stroke-width="3" fill="none" />
            </svg>
            <?php echo $this->Html->image("arrow_left.png", array(
                "alt" => "Flèche qui permet le retour à la page précédente",
                "class" => "arrowReturn pointer"
            )); ?>
        </a>
        
        <?php echo $this->element('menu_admin'); ?>
        
        <section>
            <h2>Bienvenue administrateur <?= $admin['username']; ?></h2>
            <div class="contentPronostic">
                <div class="panel-group" id="addMatch" role="tablist" aria-multiselectable="true">
                    <ul class="listError"></ul>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingAddMatch">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#addMatch" href="#collapseAddMatch" aria-expanded="true" aria-controls="collapseAddMatch">
                                Ajouter un match
                                </a>
                            </h4>
                        </div>
                        <div id="collapseAddMatch" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingAddMatch">
                            <div class="panel-body">
                                <form method="post" action="<?= $this->Url->build('/admin/addMatch', true); ?>">
                                    <div class="row">
                                       <select name="championship">
                                           <option value="default">Choix du championnat</option>
                                        <?php foreach($championships as $championship){ ?>
                                            <option value="<?= $championship['caption']; ?>"><?= $championship['caption']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <select name="match" id="selectMatch">
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="text" name="matchDay" placeholder="Journée" class="text-center" />
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="matchDate" placeholder="Date du match" class="text-center" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <input type="text" name="homeTeam" placeholder="Equipe à domicile" />
                                        <input type="text" name="homePennant" placeholder="Lien du fanion de l'équipe à domicile" />
                                        <select name="homeColor" class="selectColors cs-select cs-skin-elastic">
                                            <option value="default" disabled selected>Couleur</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <input type="text" name="awayTeam" placeholder="Equipe à l'extérieur" />
                                        <input type="text" name="awayPennant" placeholder="Lien du fanion de l'équipe à extérieur" />
                                        <select name="awayColor" class="selectColors cs-select cs-skin-elastic">
                                            <option value="default" disabled selected>Couleur</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <select name="myProno">
                                                <option value="default">Mon pronostic</option>
                                                <option value="1"></option>
                                                <option value="N">Match nul</option>
                                                <option value="2"></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <select name="reliability">
                                                <option value="default">Fiabilité</option>
                                                <option value="1">Fun</option>
                                                <option value="2">Osé</option>
                                                <option value="3">Fiable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="text" name="quotationHome" placeholder="Côte domicile" class="text-center" />
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="quotationDraw" placeholder="Côte match nul" class="text-center" />
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="quotationAway" placeholder="Côte extérieur" class="text-center" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <textarea name="analysis" placeholder="Mon analyse" ></textarea>
                                    </div>
                                    <div class="row">
                                        <input type="submit" value="Ajouter" />
                                    </div>
                               </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingAddTicket">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#addTicket" href="#collapseAddTicket" aria-expanded="true" aria-controls="collapseAddTicket">
                                Ajouter un ticket
                                </a>
                            </h4>
                        </div>
                        <div id="collapseAddTicket" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingAddTicket">
                            <div class="panel-body">
                                <form method="post" action="<?= $this->Url->build('/admin/addTicket', true); ?>">
                                    <table class="table table-responsive tableTicket">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-center littleCase">#</th>
                                                <th class="text-uppercase text-center"><i class="fa fa-futbol-o"></i></th>
                                                <th class="text-uppercase text-center littleCase"><i class="fa fa-list-ul"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($matchsToday as $matchToday){
                                           ?>
                                            <tr>
                                                <td class="text-center"><?= $matchToday['id']; ?>.</td>
                                                <td class="text-center"><?= $matchToday['home_team'].' '.($matchToday['home_result'] >= 0 ? $matchToday['home_result'] : '').' - '.($matchToday['away_result'] >= 0 ? $matchToday['away_result'] : '').' '.$matchToday['away_team']; ?></td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="matchTicket" />
                                                    <input name="matchTicket<?= $matchToday['id']; ?>" type="hidden" />
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <select name="reliability">
                                        <option value="default">Fiabilité</option>
                                        <option value="1">Fun</option>
                                        <option value="2">Osé</option>
                                        <option value="3">Fiable</option>
                                    </select>
                                    <textarea name="analysis" placeholder="Mon analyse"></textarea>
                                    <input type="submit" value="Ajouter" />
                               </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->Flash->render(); ?>
            </div>
        </section>
        <section class="listMatch">
            <form method="post" action="<?= $this->Url->build('/admin/updateMatch', true); ?>">
               <input type="submit" value="Mettre à jour" />
               <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-center littleCase">#</th>
                            <th class="text-uppercase text-center mediumCase"><i class="fa fa-calendar-o"></i></th>
                            <th class="text-uppercase text-center mediumCase desktop"><i class="fa fa-trophy"></i></th>
                            <th class="text-uppercase text-center"><i class="fa fa-futbol-o"></i></th>
                            <th class="text-uppercase text-center littleCase"><i class="fa fa-thumbs-up"></i></th>
                            <th class="text-uppercase text-center littleCase desktop"><i class="fa fa-line-chart"></i></th>
                            <th class="text-uppercase text-center littleCase"><i class="fa fa-balance-scale"></i></th>
                            <th class="text-uppercase text-center littleCase"><i class="fa fa-calendar-check-o"></i></th>
                            <th class="text-uppercase text-center littleCase"><i class="fa fa-calendar-plus-o"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($matchsAll as $match){
                            $dateMatch = iconv("ISO-8859-1","UTF-8",strftime($strDate ,strtotime($match['date_match'])));
                            $dateMatchMobile = iconv("ISO-8859-1","UTF-8",strftime($strDateMobile ,strtotime($match['date_match'])));

                            switch($match['reliability']){
                                case '1':
                                    $colorReliability = '#D0021B';
                                    break;
                                case '2':
                                    $colorReliability = '#FF8D00';
                                    break;
                                case '3':
                                    $colorReliability = '#1ED711';
                                    break;
                            }

                            switch($match['my_prono']){
                                case '1':
                                    $quotation = $match['quotation_home'];
                                    break;
                                case 'N':
                                    $quotation = $match['quotation_draw'];
                                    break;
                                case '2':
                                    $quotation = $match['quotation_away'];
                                    break;
                            }
                       ?>
                        <tr class="Prono<?= $match['id']; ?>">
                            <td class="text-center">
                                <p class="idMatch"><?= $match['id']; ?>.</p>
                                <div class="contentEditResult">
                                    <input class="editMatch text-center desktop hide" name="editHomeResult" type="text" value="<?= ($match['home_result'] >= 0 ? $match['home_result'] : ''); ?>" />
                                    <input class="editMatch text-center desktop hide" name="editAwayResult" type="text" value="<?= ($match['away_result'] >= 0 ? $match['away_result'] : ''); ?>" />
                                </div>
                            </td>
                            <td class="text-center mediumCase desktop">
                                <?= $dateMatch; ?>
                                <input class="editMatch text-center hide" name="editHomePennant" type="text" value="<?= $match['home_pennant']; ?>" />
                            </td>
                            <td class="text-center mediumCase mobile">
                                <?= $dateMatchMobile; ?>
                                <div class="contentEditResult">
                                    <input class="editMatch text-center hide" name="editHomeResult" type="text" value="<?= ($match['home_result'] >= 0 ? $match['home_result'] : ''); ?>" />
                                    <input class="editMatch text-center hide" name="editAwayResult" type="text" value="<?= ($match['away_result'] >= 0 ? $match['away_result'] : ''); ?>" />
                                </div>
                            </td>
                            <td class="text-center desktop">
                                <?= $match['competition']; ?>
                                <input class="editMatch text-center hide" name="editAwayPennant" type="text" value="<?= $match['away_pennant']; ?>" />
                            </td>
                            <td class="text-center">
                                <?= $match['home_team'].' '.($match['home_result'] >= 0 ? $match['home_result'] : '').' - '.($match['away_result'] >= 0 ? $match['away_result'] : '').' '.$match['away_team']; ?>
                                <textarea class="editMatch text-center desktop hide" name="editAnalysis"><?= $match['analysis']; ?></textarea>
                                <p data-id="<?= $match['id']; ?>" class="editMatch mobile hide">Editer</p>
                            </td>
                            <td class="text-center">
                                <?= $match['my_prono']; ?>
                                <input class="editMatch text-center desktop hide" name="editMyProno" type="text" value="<?= $match['my_prono']; ?>" />
                            </td>
                            <td class="text-center desktop">
                                <?= $quotation; ?>
                                <input class="editMatch text-center hide" name="editQuotationHome" type="text" value="<?= $match['quotation_home']; ?>" />
                                <input class="editMatch text-center hide" name="editQuotationDraw" type="text" value="<?= $match['quotation_draw']; ?>" />
                                <input class="editMatch text-center hide" name="editQuotationAway" type="text" value="<?= $match['quotation_away']; ?>" />
                            </td>
                            <td class="roundCase">
                                <div class="roundWin" style="background-color: <?= $colorReliability; ?>"></div>
                                <input class="editMatch text-center desktop hide" name="editReliability" type="text" value="<?= $match['reliability']; ?>" />
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="matchOfTheDay" <?= ($match['match_of_the_day'] == 1 ? 'checked' : ''); ?> />
                                <input name="matchOfTheDay<?= $match['id']; ?>" type="hidden" class="checkboxHidden" value="<?= ($match['match_of_the_day'] == 1 ? 'true' : ''); ?>" />
                                <input class="editMatch text-center desktop hide" name="editTicket" type="text" value="<?= $match['n_ticket']; ?>" />
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="today" <?= ($match['today'] == 1 ? 'checked' : ''); ?> />
                                <input name="today<?= $match['id']; ?>" type="hidden" class="checkboxHidden" value="<?= ($match['today'] == 1 ? 'true' : ''); ?>" />
                                <p data-id="<?= $match['id']; ?>" class="editMatch desktop hide">Editer</p>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
            <form id="formEditMatch" class="hide" method="post" action="<?= $this->Url->build('/admin/editMatch', true); ?>">
               <input type="hidden" name="editIdMatch" />
               <input type="hidden" name="editHomeResult" />
               <input type="hidden" name="editAwayResult" />
               <input type="hidden" name="editHomePennant" />
               <input type="hidden" name="editAwayPennant" />
               <input type="hidden" name="editMyProno" />
               <input type="hidden" name="editQuotationHome" />
               <input type="hidden" name="editQuotationDraw" />
               <input type="hidden" name="editQuotationAway" />
               <input type="hidden" name="editReliability" />
               <input type="hidden" name="editTicket" />
               <input type="hidden" name="editAnalysis" />
               <input type="submit" />
            </form>
        </section>
        <section class="listTicket">
            <form method="post" action="<?= $this->Url->build('/admin/updateTicket', true); ?>">
               <input type="submit" value="Mettre à jour" />
               <table class="table table-responsive tableTicket">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-center littleCase">#</th>
                            <th class="text-uppercase text-center"><i class="fa fa-futbol-o"></i></th>
                            <th class="text-uppercase text-center littleCase"><i class="fa fa-calendar-plus-o"></i></th>
                        </tr>
                   </thead>
                   <tbody>
                        <?php foreach($ticketsAll as $ticket){ ?>
                        <tr valign=baseline>
                            <td valign=baseline class="text-center"><?= $ticket['id']; ?>.</td>
                            <td class="text-center">
                            <?php foreach($matchs as $match){
                                    if($match['n_ticket'] == $ticket['id']){
                                        echo $match['home_team'].' '.($match['home_result'] >= 0 ? $match['home_result'] : '').' - '.($match['away_result'] >= 0 ? $match['away_result'] : '').' '.$match['away_team'].'<br/>'; 
                                    }
                            } ?>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="today" <?= ($ticket['today'] == 1 ? 'checked' : ''); ?> />
                                <input name="today<?= $ticket['id']; ?>" type="hidden" value="<?= ($ticket['today'] == 1 ? 'true' : ''); ?>" />
                            </td>
                        </tr>
                    <?php } ?>
                   </tbody>
                </table>
            </form>
        </section>
   </section>
<?php echo $this->element('footer'); ?>