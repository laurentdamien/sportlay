<?php

/**



**/

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;

require_once(MODEL.'Api.php');

class PrognosticsController extends AppController
{   
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }
    
    public function home()
    {
        $prognostics = TableRegistry::get('Prognostics');
        $tickets = TableRegistry::get('Tickets');
        $advices = TableRegistry::get('Advices');
        
        //Data for the match of the day
        $match0fTheDay = $prognostics->find()->where(['today' => 1, 'match_of_the_day' => 1])->first();
        
        //List all matchs today
        $matchsAll = $prognostics->find()->where(['today' => 1]);
            
        //List all matchs
        $matchs = $prognostics->find();
        
        //List matchs today
        $othersMatchsAll = $prognostics->find()->where(['today' => 1, 'match_of_the_day' => 0]);
        
        //List fun matchs today
        $othersMatchsFun = $prognostics->find()->where(['today' => 1, 'match_of_the_day' => 0, 'reliability' => 1]);
        
        //List ose matchs today
        $othersMatchsOse = $prognostics->find()->where(['today' => 1, 'match_of_the_day' => 0, 'reliability' => 2]);
        
        //List fiable matchs today
        $othersMatchsFiable = $prognostics->find()->where(['today' => 1, 'match_of_the_day' => 0, 'reliability' => 3]);
        
        
        //List tickets today
        $ticketsAll = $tickets->find()->where(['today' => 1]);
        
        //Advice today
        $day = intval(date("N"));
        $advice = $advices->find()->where(['day' => $day])->first();
        
        //Advice default
        $adviceDefault = $advices->find()->where(['day' => 0])->first();
        
        //Return all data fot all matchs today
        $this->set('match0fTheDay', $match0fTheDay);
        $this->set('matchsAll', $matchsAll);
        $this->set('matchs', $matchs);
        $this->set('othersMatchsAll', $othersMatchsAll);
        $this->set('othersMatchsFun', $othersMatchsFun);
        $this->set('othersMatchsOse', $othersMatchsOse);
        $this->set('othersMatchsFiable', $othersMatchsFiable);
        $this->set('adviceDefault', $adviceDefault);
        
        //Return advice today
        $this->set('advice', $advice);
        
        //Return all tickets
        $this->set('ticketsAll', $ticketsAll);
    }
    
    public function pronostic($id)
    {
        $prognostics = TableRegistry::get('Prognostics');

        //Match today selected
        $match = $prognostics->find()->where(['id' => $id, 'today' => 1])->first();
        
        if(!isset($match)) {
            return $this->redirect(
                ['controller' => 'Pages', 'action' => 'page404']
            );
        }
        
        //All championship
        $allChampionship = getChampionships();
        
        //Championship of the match
        foreach($allChampionship as $championship){
            if($championship['caption'] == $match['competition']){
                $thisChampionship = $championship;
                break;
            }
        }
        
        //Ranking of the match
        $ranking = getRanking($thisChampionship['leagueTable'], $thisChampionship['teams'], $thisChampionship['fixtures']);
        
        //All matchs championship
        $allMatchsChampionship = getGames($thisChampionship['fixtures']);
        
        //All teams championship
        $allTeamsChampionship = getTeams($thisChampionship['teams']);
        
        //Match's stats between the teams
        foreach($allMatchsChampionship as $matchChampionship){
            if($matchChampionship['homeTeamName'] == $match['home_team'] &&
              $matchChampionship['awayTeamName'] == $match['away_team']){
                $matchStats = $matchChampionship['showdown'];
                break;
            }
        }
        
        //Team's stats
        foreach($allTeamsChampionship as $teamChampionship){
            if($teamChampionship['name'] == $match['home_team'])
                $homeTeamStats = $teamChampionship;
                
            if($teamChampionship['name'] == $match['away_team'])
                $awayTeamStats = $teamChampionship;
        }
        
        //Last match between the teams
        $lastMatchs = getStatsMatch($matchStats);
        
        if(!empty($ranking)){
            foreach($ranking as $team){
                if(!is_array($team)){
                    //Home last matchs
                    $homeLastMatchs = getLastMatchs($homeTeamStats['fixtures'], $thisChampionship['championship'], $team, $thisChampionship['teams']);

                    //Home last matchs
                    $awayLastMatchs = getLastMatchs($awayTeamStats['fixtures'], $thisChampionship['championship'], $team, $thisChampionship['teams']);

                    break;
                }
            }
            $this->set('homeLastMatchs', $homeLastMatchs);
            $this->set('awayLastMatchs', $awayLastMatchs);
            $this->set('ranking', $ranking);
        }
        
        $this->set('match', $match);
        $this->set('lastMatchs', $lastMatchs);
        $this->set('homeTeamStats', $homeTeamStats);
        $this->set('awayTeamStats', $awayTeamStats);
        
    }
    
    public function ticket($id, $nbMatch)
    {
        $tickets = TableRegistry::get('Tickets');
        $prognostics = TableRegistry::get('Prognostics');
        
        //The ticket focus
        $ticket = $tickets->find()->where(['id' => $id])->first();
        
        if(!isset($ticket)) {
            return $this->redirect(
                ['controller' => 'Pages', 'action' => 'page404']
            );
        }
        
        //List ticket's matchs today
        $matchs = $prognostics->find()->where(['n_ticket' => $ticket->id])->order(['date_match' => 'ASC']);

        $matchSelected = substr($nbMatch, 5);
        
        //Match Selected
        foreach($matchs as $key=>$ticketMatch){
            if($key == 0){
                $firstMatch = $ticketMatch;
            }
            if($key == $matchSelected-1){
                $match = $ticketMatch;
                break;
            }
        }
        
        if(!isset($match)) {
            return $this->redirect(
                ['controller' => 'Pages', 'action' => 'page404']
            );
        }
        
        //All championship
        $allChampionship = getChampionships();
        
        //$championship of the match
        foreach($allChampionship as $championship){
            if($championship['caption'] == $match['competition']){
                $thisChampionship = $championship;
                break;
            }
        }
        
        //All matchs championship
        $allMatchsChampionship = getGames($thisChampionship['fixtures']);
        
        //Match's stats between the teams
        foreach($allMatchsChampionship as $matchChampionship){
            if($matchChampionship['homeTeamName'] == $match['home_team'] &&
              $matchChampionship['awayTeamName'] == $match['away_team']){
                $matchStats = $matchChampionship['showdown'];
                break;
            }
        }
        
        //Last match between the teams
        $lastMatchs = getStatsMatch($matchStats);
        
        //All teams championship
        $allTeamsChampionship = getTeams($thisChampionship['teams']);
        
        //Team's stats
        foreach($allTeamsChampionship as $teamChampionship){
            if($teamChampionship['name'] == $match['home_team'])
                $homeTeamStats = $teamChampionship;
                
            if($teamChampionship['name'] == $match['away_team'])
                $awayTeamStats = $teamChampionship;
        }
        
        //Ranking of the match
        $ranking = getRanking($thisChampionship['leagueTable'], $thisChampionship['teams'], $thisChampionship['fixtures']);

        if(!empty($ranking)){
            foreach($ranking as $team){
                if(!is_array($team)){
                    if(!empty($homeTeamStats['fixtures'])){
                        //Home last matchs
                        $homeLastMatchs = getLastMatchs($homeTeamStats['fixtures'], $thisChampionship['championship'], $team, $thisChampionship['teams']);

                        $this->set('homeLastMatchs', $homeLastMatchs);
                    }

                    if(!empty($awayTeamStats['fixtures'])){
                        //Home last matchs
                        $awayLastMatchs = getLastMatchs($awayTeamStats['fixtures'], $thisChampionship['championship'], $team, $thisChampionship['teams']);

                        $this->set('awayLastMatchs', $awayLastMatchs);
                    }
                    break;
                }
            }
            $this->set('ranking', $ranking);
        }
        
        $this->set('firstMatch', $firstMatch);
        $this->set('match', $match);
        $this->set('lastMatchs', $lastMatchs);
        $this->set('homeTeamStats', $homeTeamStats);
        $this->set('awayTeamStats', $awayTeamStats);
        $this->set('matchs', $matchs);
        $this->set('ticket', $ticket);
    }
}

?>