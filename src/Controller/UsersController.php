<?php

/**



**/

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Response;

require_once(MODEL.'Api.php');

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }
    
    public function login()
    {
        if($this->Auth->user())
            $this->redirect($this->Auth->logout());
        
        if($this->request->is('post')){
            
            if(!empty($this->request->data['username']) && 
               !empty($this->request->data['password'])){
                    $user = $this->Auth->identify();

                if($user){
                    $this->Auth->setUser($user);
                    if($this->Auth->user('role') == 'admin'){
                        return $this->redirect($this->Auth->redirectUrl(['controller' => 'Users', 'action' => 'admin']));
                    }else{
                        return $this->redirect($this->Auth->redirectUrl(['controller' => 'Users', 'action' => 'account']));
                    }
                }else{
                    $this->Flash->error('Vos identifiants ne sont pas valides');
                }
            }else{
                $this->Flash->error('Veuillez saisir vos identifiants');
            }
        }
    }
    
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }
    
    public function account()
    {
        $user = $this->Auth->user();
        
        $this->set('user', $user);
    }
    
    public function admin()
    {
        $admin = $this->Auth->user();
        
        if($admin['role'] == 'admin'){
            $prognostics = TableRegistry::get('Prognostics');
            $tickets = TableRegistry::get('Tickets');
            $advices = TableRegistry::get('Advices');
            
            //List all matchs show
            $matchsAll = $prognostics->find()->order(['id' => 'DESC'])->limit(40);
            
            //List all matchs for ticket
            $matchs = $prognostics->find()->order(['id' => 'DESC']);
            
            //List all tickets
            $ticketsAll = $tickets->find()->order(['id' => 'DESC'])->limit(10);
            
            //List all matchs today no ticket
            $matchsToday = $prognostics->find()->where(['n_ticket' => 0])->order(['id' => 'DESC'])->limit(20);
            
            //All championship
            $championships = getChampionships();
            
            //Advice today
            $day = intval(date("N"));
            $advice = $advices->find()->where(['day' => $day])->first();
            
            //Advice default
            $adviceDefault = $advices->find()->where(['day' => 0])->first();
            
        }else{
            $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        
        $this->set('admin', $admin);
        $this->set('matchs', $matchs);
        $this->set('matchsAll', $matchsAll);
        $this->set('ticketsAll', $ticketsAll);
        $this->set('matchsToday', $matchsToday);
        $this->set('championships', $championships);
        $this->set('advice', $advice);
        $this->set('adviceDefault', $adviceDefault);
        
    }
    
    public function advices(){
        $admin = $this->Auth->user();
        
        if($admin['role'] == 'admin'){
            $advices = TableRegistry::get('Advices');
            
            //All advices
            $allAdvice = $advices->find();
            
            //Advice today
            $day = intval(date("N"));
            $adviceToday = $advices->find()->where(['day' => $day])->first();
            
            //Advice default
            $adviceDefault = $advices->find()->where(['day' => 0])->first();
            
        }else{
            $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        
        $this->set('advices', $allAdvice);
        $this->set('adviceToday', $adviceToday);
        $this->set('adviceDefault', $adviceDefault);
    }
    
    public function updateAdvice()
    {
        if($this->request->is('post')){
            
            if(isset($this->request->data['editIdAdvice']) &&
               isset($this->request->data['editAdviceText1']) &&
               isset($this->request->data['editAdviceText2']) &&
               isset($this->request->data['editAdvicePublished'])
              ){
                
                $advices = TableRegistry::get('Advices');
                
                $id = $this->request->data['editIdAdvice'];
                
                //Advice selected to edit
                $advice = $advices->find()->where(['id' => $id])->first();

                $advice->text1 = $this->request->data['editAdviceText1'];
                $advice->text2 = $this->request->data['editAdviceText2'];
                $advice->published = $this->request->data['editAdvicePublished'];

                $advices->save($advice);

                return $this->redirect(['controller' => 'Users', 'action' => 'advices']);
            }else{
                $this->Flash->error('Veuillez remplir tout le formulaire (score non obligatoire)');
                $this->setAction('advices');
            }
        }else{
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
    }
    
    public function listMatch()
    {
        if($this->request->isAjax())
            $this->layout = 'ajax';

        if(isset($this->request->data['championship'])){
            $championship = $this->request->data['championship'];
            
            //All championship
            $allChampionship = getChampionships();

            //infos championship
            foreach($allChampionship as $championships){
                if($championships['caption'] == $championship){
                    $thisChampionship = $championships;
                    break;
                }
            }
            
            if(!isset($this->request->data['home']) && 
               !isset($this->request->data['away']) &&
               !isset($this->request->data['date'])
              ){
                //All matchs championship
                $matchs = getGamesNoPlay($thisChampionship['fixtures']);
                $homePennant = '';
                $awayPennant = '';
                $colorHomeTeam = '';
                $colorAwayTeam = '';
                $odds = '';
            }else{
                $home = $this->request->data['home'];
                $away = $this->request->data['away'];
                $date = $this->request->data['date'];
                $matchs = '';
                
                $allTeams = TableRegistry::get('Teams');
                
                $homeTeam = $allTeams->find()->where(['name' => $home])->first();
                $awayTeam = $allTeams->find()->where(['name' => $away])->first();
                
                if(!empty($homeTeam))
                    $colorHomeTeam = $homeTeam->color;
                else
                    $colorHomeTeam = '';
                
                if(!empty($awayTeam))
                    $colorAwayTeam = $awayTeam->color;
                else
                    $colorAwayTeam = '';

                //Odds match
                $odds = getOdds($date, $home, $away);

                if(!isset($odds) || empty($odds))
                    $odds = '';
                
                //All teams championship
                $teams = getTeams($thisChampionship['teams']);

                //Pennants
                foreach($teams as $team){
                    if($team['name'] == $home)
                        $homePennant = $team['pennant'];

                    if($team['name'] == $away)
                        $awayPennant = $team['pennant'];

                    if(isset($homePennant) && isset($awayPennant)) break;
                }
            }
        }
      
        $output = array(
            'matchs' => $matchs,
            'homePennant' => $homePennant,
            'awayPennant' => $awayPennant,
            'colorHomeTeam' => $colorHomeTeam,
            'colorAwayTeam' => $colorAwayTeam,
            'odds' => $odds
        );
        
        return new Response([
            'status' => 200,
            'body'   => json_encode($output, JSON_FORCE_OBJECT)
        ]);
    }
    
    public function addMatch()
    {
        if($this->request->is('post')){
            
            if(!empty($this->request->data['matchDay']) && 
               !empty($this->request->data['homeTeam']) &&
               !empty($this->request->data['homePennant']) &&
               !empty($this->request->data['homeColor']) &&
               !empty($this->request->data['awayTeam']) &&
               !empty($this->request->data['awayPennant']) &&
               !empty($this->request->data['awayColor']) &&
               !empty($this->request->data['matchDate']) &&
               !empty($this->request->data['championship']) &&
               !empty($this->request->data['myProno']) &&
               !empty($this->request->data['quotationHome']) &&
               !empty($this->request->data['quotationDraw']) &&
               !empty($this->request->data['quotationAway']) &&
               !empty($this->request->data['analysis']) &&
               !empty($this->request->data['reliability'])
              ){
                
                $prognostics = TableRegistry::get('Prognostics');
                $prognostic = $prognostics->newEntity();

                $prognostic->matchday = $this->request->data['matchDay'];
                $prognostic->home_team = $this->request->data['homeTeam'];
                $prognostic->home_pennant = $this->request->data['homePennant'];
                $prognostic->home_color = $this->request->data['homeColor'];
                $prognostic->away_team = $this->request->data['awayTeam'];
                $prognostic->away_pennant = $this->request->data['awayPennant'];
                $prognostic->away_color = $this->request->data['awayColor'];
                $prognostic->date_match = $this->request->data['matchDate'];
                $prognostic->competition = $this->request->data['championship'];
                $prognostic->my_prono = $this->request->data['myProno'];
                $prognostic->quotation_home = $this->request->data['quotationHome'];
                $prognostic->quotation_draw = $this->request->data['quotationDraw'];
                $prognostic->quotation_away = $this->request->data['quotationAway'];
                $prognostic->analysis = $this->request->data['analysis'];
                $prognostic->reliability = $this->request->data['reliability'];
                $prognostic->n_ticket = 0;
                $prognostic->today = 0;
                $prognostic->match_of_the_day = 0;

                $prognostics->save($prognostic);
                
                $teams = TableRegistry::get('Teams');
                
                $homeTeam = $teams->find()->where(['name' => $this->request->data['homeTeam']])->first();
                $awayTeam = $teams->find()->where(['name' => $this->request->data['awayTeam']])->first();
                
                if(empty($homeTeam)){
                    $newHomeTeam = $teams->newEntity();
                    
                    $newHomeTeam->name = $this->request->data['homeTeam'];
                    $newHomeTeam->color = $this->request->data['homeColor'];
                    
                    $teams->save($newHomeTeam);
                }
                
                if(empty($awayTeam)){
                    $newAwayTeam = $teams->newEntity();
                    
                    $newAwayTeam->name = $this->request->data['awayTeam'];
                    $newAwayTeam->color = $this->request->data['awayColor'];
                    
                    $teams->save($newAwayTeam);
                }

                return $this->redirect(['controller' => 'Users', 'action' => 'admin']);
            }else{
                $this->Flash->error('Veuillez remplir tout le formulaire');
                $this->setAction('admin');
            }
        }else{
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
    }
    
    public function addTicket()
    {
        if($this->request->is('post')){
            if(isset($this->request->data['analysis']) &&
               isset($this->request->data['reliability']) &&
               isset($this->request->data['matchTicket'])){
            
                $prognostics = TableRegistry::get('Prognostics');
                $tickets = TableRegistry::get('Tickets');
                
                $ticket = $tickets->newEntity();
                
                $ticket->analysis = $this->request->data['analysis'];
                $ticket->reliability = $this->request->data['reliability'];
                $ticket->today = 0;
                
                if($tickets->save($ticket)){
                    $id = $ticket->id;
                }

                //List all matchs no ticket
                $matchsAll = $prognostics->find()->where(['n_ticket' => 0])->order(['id' => 'DESC']);

                foreach($matchsAll as $match){
                    if(!empty($this->request->data['matchTicket'.$match['id']])){
                        $match->n_ticket = $id;
                        $prognostics->save($match);
                    }
                }

                return $this->redirect(['controller' => 'Users', 'action' => 'admin']);
            }else{
                $this->Flash->error('Veuillez remplir tout le formulaire et sélectionner au moins 2 matchs');
                $this->setAction('admin');
            }
            
        }else{
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
    }
    
    public function updateMatch()
    {
        if($this->request->is('post')){
            $prognostics = TableRegistry::get('Prognostics');
            
            //List all matchs
            $matchsAll = $prognostics->find()->order(['id' => 'DESC'])->limit(40);
            
            foreach($matchsAll as $match){
                if($this->request->data['today'.$match['id']] == 'true')
                    $match->today = 1;
                else
                    $match->today = 0;
                
                if($this->request->data['matchOfTheDay'.$match['id']] == 'true')
                    $match->match_of_the_day = 1;
                else
                    $match->match_of_the_day = 0;

                $prognostics->save($match);
            }

            return $this->redirect(['controller' => 'Users', 'action' => 'admin']);

        }else{
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
    }
    
    public function updateTicket()
    {
        if($this->request->is('post')){
            $tickets = TableRegistry::get('Tickets');
            
            //List all tickets
            $ticketsAll = $tickets->find()->order(['id' => 'DESC'])->limit(10);
            
            foreach($ticketsAll as $ticket){
                if($this->request->data['today'.$ticket['id']] == 'true')
                    $ticket->today = 1;
                else
                    $ticket->today = 0;

                $tickets->save($ticket);
            }

            return $this->redirect(['controller' => 'Users', 'action' => 'admin']);

        }else{
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
    }
    
    public function editMatch()
    {
        if($this->request->is('post')){
            
            if(isset($this->request->data['editIdMatch']) &&
               isset($this->request->data['editHomePennant']) &&
               isset($this->request->data['editAwayPennant']) &&
               isset($this->request->data['editMyProno']) &&
               isset($this->request->data['editQuotationHome']) &&
               isset($this->request->data['editQuotationDraw']) &&
               isset($this->request->data['editQuotationAway']) &&
               isset($this->request->data['editReliability']) &&
               isset($this->request->data['editTicket']) &&
               isset($this->request->data['editAnalysis'])
              ){
                
                $prognostics = TableRegistry::get('Prognostics');
                
                $id = $this->request->data['editIdMatch'];
                
                //Match selected to edit
                $prognostic = $prognostics->find()->where(['id' => $id])->first();

                $prognostic->home_pennant = $this->request->data['editHomePennant'];
                $prognostic->away_pennant = $this->request->data['editAwayPennant'];
                $prognostic->my_prono = $this->request->data['editMyProno'];
                $prognostic->quotation_home = $this->request->data['editQuotationHome'];
                $prognostic->quotation_draw = $this->request->data['editQuotationDraw'];
                $prognostic->quotation_away = $this->request->data['editQuotationAway'];
                $prognostic->analysis = $this->request->data['editAnalysis'];
                $prognostic->reliability = $this->request->data['editReliability'];
                $prognostic->n_ticket = $this->request->data['editTicket'];
                
                if(isset($this->request->data['editHomeResult']) &&
                   isset($this->request->data['editAwayResult']) &&
                   $this->request->data['editHomeResult'] != '' &&
                   $this->request->data['editAwayResult'] != ''){
                    $prognostic->home_result = $this->request->data['editHomeResult'];
                    $prognostic->away_result = $this->request->data['editAwayResult'];
                }else{
                    $prognostic->home_result = -1;
                    $prognostic->away_result = -1;
                }

                $prognostics->save($prognostic);

                return $this->redirect(['controller' => 'Users', 'action' => 'admin']);
            }else{
                $this->Flash->error('Veuillez remplir tout le formulaire (score non obligatoire)');
                $this->setAction('admin');
            }
        }else{
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
    }
}

?>