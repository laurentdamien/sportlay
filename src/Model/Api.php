<?php

// CONNEXION API
function connectAPI($url){
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: e17f61fa09ae420989afa792bf4d51b9';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($url, false, $stream_context);
    $fixtures = json_decode($response, true);
    return $fixtures;
}

// GET All Championship 
function getChampionships(){
    $url = 'http://api.football-data.org/alpha/soccerseasons';
    $fixtures = connectAPI($url);

    $listChampionships = array();
    $i = 1;
    foreach($fixtures as $championships){
        foreach($championships as $key=>$championship){

            if(is_array($championship)){
                foreach($championship as $key1=>$links){
                    if($key1 == "self"){
                        foreach($links as $link)
                            //Link for this championship
                            ${'league'.$i}["championship"] = $link;
                    }else if($key1 == "teams"){
                        foreach($links as $link)
                            //Link for all teams
                            ${'league'.$i}["teams"] = $link;
                    }else if($key1 == "fixtures"){
                        foreach($links as $link)
                            //Link for all macth
                            ${'league'.$i}["fixtures"] = $link;

                    }else if($key1 == "leagueTable"){
                        foreach($links as $link)
                            //Link for the ranking
                            ${'league'.$i}["leagueTable"] = $link;
                    }
                }
            }else if(!is_array($championship)){
                if($key == "caption")
                    //Name of championship
                    ${'league'.$i}["caption"] = str_replace(' 2015/16', '', $championship);

                if($key == "numberOfTeams"){
                    //Number of day in the championship
                    $numberDay = ($championship*2)-2;
                    ${'league'.$i}["numberOfTeams"] = $numberDay;
                    break;
                }
            }
        }
        array_push($listChampionships, ${'league'.$i});
        $i++;
    }
    return $listChampionships;
}


//Infos all teams
function getTeams($url){
    $result = connectAPI($url);

    $listTeams = array();
    $i = 1;
    foreach($result as $fixtures){
        if(is_array($fixtures)){
            foreach($fixtures as $teams){
                foreach($teams as $key=>$team){
                    if($key == "_links"){
                        foreach($team as $key1=>$links){
                            if($key1 == "fixtures"){
                                foreach($links as $link){
                                    //Team's link fixtures
                                    ${'team'.$i}["fixtures"] = $link;
                                    break;
                                }
                                break;
                            }
                        }
                    }else if($key == "name"){
                        //Team's name
                        ${'team'.$i}["name"] = $team;
                    }else if($key == "shortName"){
                        //Team's short name
                        if($team == null)
                            ${'team'.$i}["shortName"] = ${'team'.$i}["name"];
                        else
                            ${'team'.$i}["shortName"] = $team;
                    }else if($key == "squadMarketValue"){
                        //Team's market value
                        ${'team'.$i}["value"] = $team;
                    }else if($key == "crestUrl"){
                        //Team's pennant
                        ${'team'.$i}["pennant"] = $team;
                        array_push($listTeams, ${'team'.$i});
                        $i++;
                        break;
                    }
                }
            }
        }
    }
    return $listTeams;
}


// GET All Games in a Championship for current year
function getGames($url){
    $result = connectAPI($url);

    $listGames = array();
    $i = 1;
    foreach($result as $fixtures){
        if(is_array($fixtures)){
            foreach($fixtures as $games){
                foreach($games as $key=>$game){
                    if(is_array($game) && $key == "_links"){
                        foreach($game as $key1=>$links){
                            if($key1 == "self"){
                                foreach($links as $link)
                                    //Link for the stats of match
                                    ${'match'.$i}["showdown"] = $link;
                            }else if($key1 == "homeTeam"){
                                foreach($links as $link)
                                    //Link for the infos of home team
                                    ${'match'.$i}["homeTeam"] = $link;
                            }else if($key1 == "awayTeam"){
                                foreach($links as $link)
                                    //Link for the stats of away match
                                    ${'match'.$i}["awayTeam"] = $link;
                            }
                        }
                    }else if(!is_array($game)){
                        if($key == "date"){
                            //Date of the match
                            $api_date = date_create($game);
                            date_add($api_date, date_interval_create_from_date_string(JETLAG));
                            $new_date = (date_format($api_date, 'Y-m-d') . "T" . date_format($api_date, 'H:i:s') . "Z");
                            ${'match'.$i}["date"] = $new_date;
                        }
                        
                        if($key == "matchday")
                            //Number of the day
                            ${'match'.$i}["matchday"] = $game;

                        if($key == "homeTeamName")
                            //Name's home team
                            ${'match'.$i}["homeTeamName"] = $game;

                        if($key == "awayTeamName")
                            //Name's away team
                            ${'match'.$i}["awayTeamName"] = $game;
                    }else if(is_array($game) && $key == "result"){
                        foreach($game as $key2=>$result){
                            if($key2 == "goalsHomeTeam")
                                //Nb goals home team
                                ${'match'.$i}["goalsHomeTeam"] = $result;

                            if($key2 == "goalsAwayTeam"){
                                //Nb goals away team
                                ${'match'.$i}["goalsAwayTeam"] = $result;
                                array_push($listGames, ${'match'.$i});
                                $i++;
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
    return $listGames;
}


// GET All Games in a Championship for current year not play
function getGamesNoPlay($url){
    $result = connectAPI($url);

    $listGames = array();
    $date = '';
    $i = 1;
    foreach($result as $fixtures){
        if(is_array($fixtures)){
            foreach($fixtures as $games){
                foreach($games as $key=>$game){
                    if($key == "date"){
                        //Date of the match
                        $api_date = date_create($game);
                        date_add($api_date, date_interval_create_from_date_string(JETLAG));
                        $new_date = (date_format($api_date, 'Y-m-d') . "T" . date_format($api_date, 'H:i:s') . "Z");
                    }
                    
                    if($key == "status" && $game == "FINISHED")
                        break;

                    if($key == "matchday")
                        //Number of the day
                        ${'match'.$i}["matchday"] = $game;

                    if($key == "homeTeamName")
                        //Name's home team
                        ${'match'.$i}["homeTeamName"] = $game;

                    if($key == "awayTeamName"){
                        //Name's away team
                        ${'match'.$i}["awayTeamName"] = $game;
                        ${'match'.$i}["date"] = $new_date;
                        array_push($listGames, ${'match'.$i});
                        $i++;
                    }
                }
            }
        }
    }
    return $listGames;
}



// GET ranking of championship
function getRanking($urlRanking, $urlNameTeams, $urlRankingDetails){

    $listTeam = array();
    /*** Ranking complet ***/
    $result = connectAPI($urlRanking);
    $i = 1;
    if(!empty($result)){
        foreach($result as $option=>$fixtures){
            if($option == "matchday")
                $listTeam["matchday"] = $fixtures;

            if(is_array($fixtures)){
                foreach($fixtures as $teams){
                    if(is_array($teams)){
                        foreach($teams as $key=>$team){
                            if($key == "position"){
                                //Team's position
                                ${'team'.$i}["position"] = $team;
                            }else if($key == "teamName"){
                                //Team's name
                                ${'team'.$i}["teamName"] = $team;

                                //Team's name
                                ${'team'.$i}["teamShortName"] = '';
                            }else if($key == "playedGames"){
                                //Match played by team
                                ${'team'.$i}["playedGames"] = $team;

                                //Team's win
                                ${'team'.$i}["win"] = 0;

                                //Team's drawn
                                ${'team'.$i}["drawn"] = 0;

                                //Team's lost
                                ${'team'.$i}["lost"] = 0;

                                //Team's home win
                                ${'team'.$i}["homeWin"] = 0;

                                //Team's home drawn
                                ${'team'.$i}["homeDrawn"] = 0;

                                //Team's home lost
                                ${'team'.$i}["homeLost"] = 0;

                                //Team's away win
                                ${'team'.$i}["awayWin"] = 0;

                                //Team's drawn
                                ${'team'.$i}["awayDrawn"] = 0;

                                //Team's lost
                                ${'team'.$i}["awayLost"] = 0;
                            }else if($key == "points"){
                                //Team's points
                                ${'team'.$i}["points"] = $team;
                            }else if($key == "goals"){
                                //Team's goals 
                                ${'team'.$i}["goals"] = $team;
                            }else if($key == "goalsAgainst"){
                                //Team's goalsAgainst
                                ${'team'.$i}["goalsAgainst"] = $team;
                            }else if($key == "goalDifference"){
                                //Team's goalDifference
                                ${'team'.$i}["goalDifference"] = $team;
                                array_push($listTeam, ${'team'.$i});
                                $i++;
                            }
                        }
                    }
                }
            }
        }
    
        /*** Short name teams ***/
        $teams = getTeams($urlNameTeams);
        foreach($teams as $team){
            foreach($listTeam as $key=>$rank){
                if($listTeam[$key]['teamName'] == $team['name']){
                    $listTeam[$key]['teamShortName'] = $team['shortName'];
                }
            }
        }

        /*** Ranking details ***/
        $response = connectAPI($urlRankingDetails);
        foreach($response as $option=>$fixtures){
            if(is_array($fixtures) && $option == 'fixtures'){
                foreach($fixtures as $games){
                    foreach($games as $key=>$game){
                        if($key == "matchday"){
                            //Match day
                            $matchday = $game;

                            if($matchday > $listTeam["matchday"])
                                break 2;
                        }

                        if($key == "homeTeamName")
                            //Name's home team
                            $homeTeam = $game;

                        if($key == "awayTeamName")
                            //Name's away team
                            $awayTeam = $game;

                        if($key == "result"){
                            foreach($game as $key2=>$result){
                                if($key2 == "goalsHomeTeam")
                                    //Nb goals home team
                                    $goalsHomeTeam = $result;

                                if($key2 == "goalsAwayTeam"){
                                    //Nb goals away team
                                    $goalsAwayTeam = $result;
                                }
                            }

                            if($goalsHomeTeam > $goalsAwayTeam){
                                foreach($listTeam as $key=>$teams){
                                    if($listTeam[$key]['teamName'] == $homeTeam){
                                        $listTeam[$key]['win'] = intval($teams['win'])+1;
                                        $listTeam[$key]['homeWin'] = intval($teams['homeWin'])+1;
                                    }

                                    if($listTeam[$key]['teamName'] == $awayTeam){
                                        $listTeam[$key]['lost'] = intval($teams['lost'])+1;
                                        $listTeam[$key]['awayLost'] = intval($teams['awayLost'])+1;
                                    }
                                }
                            }else if($goalsHomeTeam == $goalsAwayTeam){
                                foreach($listTeam as $key=>$teams){
                                    if($listTeam[$key]['teamName'] == $homeTeam){
                                        $listTeam[$key]['drawn'] = intval($teams['drawn'])+1;
                                        $listTeam[$key]['homeDrawn'] = intval($teams['homeDrawn'])+1;
                                    }

                                    if($listTeam[$key]['teamName'] == $awayTeam){
                                        $listTeam[$key]['drawn'] = intval($teams['drawn'])+1;
                                        $listTeam[$key]['awayDrawn'] = intval($teams['awayDrawn'])+1;
                                    }
                                }
                            }else if($goalsHomeTeam < $goalsAwayTeam){
                                foreach($listTeam as $key=>$teams){
                                    if($listTeam[$key]['teamName'] == $homeTeam){
                                        $listTeam[$key]['lost'] = intval($teams['lost'])+1;
                                        $listTeam[$key]['homeLost'] = intval($teams['homeLost'])+1;
                                    }

                                    if($listTeam[$key]['teamName'] == $awayTeam){
                                        $listTeam[$key]['win'] = intval($teams['win'])+1;
                                        $listTeam[$key]['awayWin'] = intval($teams['awayWin'])+1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $listTeam;
    }
}

//Stats match
function getStatsMatch($url){
    $result = connectAPI($url);

    $matchStats = array();
    $i = 1;
    
    foreach($result as $option=>$fixtures){
        if($option == 'head2head'){
            foreach($fixtures as $key=>$stats){
                if($key == "count"){
                    //Number matchs played between the teams
                    $matchStats["count"] = $stats;
                }else if($key == "homeTeamWins"){
                    //Team's home win
                    $matchStats["homeTeamWins"] = $stats;
                }else if($key == "awayTeamWins"){
                    //Team's away win
                    $matchStats["awayTeamWins"] = $stats;
                }else if($key == "draws"){
                    //Match's draws between the teams
                    $matchStats["draws"] = $stats;
                }else if($key == "fixtures"){
                    foreach($stats as $stat){
                        ${'match'.$i} = array();
                        
                        foreach($stat as $key1=>$match){
                            if($key1 == "date"){
                                //Match's date
                                ${'match'.$i}["date"] = $match;
                            }else if($key1 == 'homeTeamName'){
                                //Home team name
                                ${'match'.$i}["homeTeamName"] = $match;
                            }else if($key1 == 'awayTeamName'){
                                //Away team name
                                ${'match'.$i}["awayTeamName"] = $match;
                            }else if($key1 == 'result'){
                                foreach($match as $goal=>$result){
                                    if($goal == 'goalsHomeTeam'){
                                        //Goal home team
                                        ${'match'.$i}["goalsHomeTeam"] = $result;
                                    }else if($goal == 'goalsAwayTeam'){
                                        //Goal home team
                                        ${'match'.$i}["goalsAwayTeam"] = $result;
                                        array_push($matchStats, ${'match'.$i});
                                        $i++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $matchStats;
}

//Last match played by a team
function getLastMatchs($url, $soccerseason, $matchday, $urlNameTeams){
    $result = connectAPI($url);

    $lastMatchs = array();
    $i = 1;

    foreach($result as $option=>$fixtures){
        if($option == 'fixtures'){
            foreach($fixtures as $matchs){
                ${'match'.$i} = array();
                foreach($matchs as $key=>$match){
                    if($key == '_links'){
                        foreach($match as $key1=>$links){
                            if($key1 == 'soccerseason'){
                                foreach($links as $link){
                                    //Link season
                                    ${'match'.$i}["soccerseason"] = $link;
                                    if($link != $soccerseason){
                                        ${'match'.$i}["nameSoccerseason"] = 'C1';
                                    }else{
                                        ${'match'.$i}["nameSoccerseason"] = '';
                                    }
                                    break 2;
                                }
                            }
                        }
                    }else if($key == "date"){
                        //Match's date
                        ${'match'.$i}["date"] = $match;
                    }else if($key == 'matchday'){
                        //Match day
                        ${'match'.$i}["matchday"] = $match;
                        if($match > $matchday)
                            break 2;

                    }else if($key == 'homeTeamName'){
                        //Home team name
                        ${'match'.$i}["homeTeamName"] = $match;
                        ${'match'.$i}["homeTeamShortName"] = '';
                    }else if($key == 'awayTeamName'){
                        //Away team name
                        ${'match'.$i}["awayTeamName"] = $match;
                        ${'match'.$i}["awayTeamShortName"] = '';
                    }else if($key == 'result'){
                        foreach($match as $goal=>$results){
                            if($goal == 'goalsHomeTeam'){
                                //Goal home team
                                ${'match'.$i}["goalsHomeTeam"] = $results;
                            }else if($goal == 'goalsAwayTeam'){
                                //Goal home team
                                ${'match'.$i}["goalsAwayTeam"] = $results;
                                array_push($lastMatchs, ${'match'.$i});
                                $i++;
                            }
                        }
                    }
                }
            }
        }
    }
    
    /*** Short name teams ***/
    $teams = getTeams($urlNameTeams);
    foreach($teams as $team){
        foreach($lastMatchs as $key=>$match){
            if($lastMatchs[$key]['homeTeamName'] == $team['name']){
                $lastMatchs[$key]['homeTeamShortName'] = $team['shortName'];
            }
            
            if($lastMatchs[$key]['awayTeamName'] == $team['name']){
                $lastMatchs[$key]['awayTeamShortName'] = $team['shortName'];
            }
        }
    }
    
    return $lastMatchs;
}


function getOdds($date, $home_team, $away_team){
    $xml = simplexml_load_file("http://xml.cdn.betclic.com/odds_en.xml");
    
    //Match's date
    $api_date = date_create($date);
    date_add($api_date, date_interval_create_from_date_string("-".JETLAG));
    $new_date = (date_format($api_date, 'Y-m-d') . "T" . date_format($api_date, 'H:i:s') . "Z");
    
    $listOdds = array();
    $i = 0;
    $new_date = substr($new_date, 0, -1);

    foreach ($xml->children() as $child){
        foreach ($child->children() as $child2){
            foreach ($child2->children() as $child3){
                foreach($child3->attributes() as $a => $b){
                    if($a == "name")
                        $teams = explode('+', str_replace(' - ', '+', $b));

                    if($a == "start_date" &&
                        $b == $new_date &&
                       (stristr($home_team, $teams[0]) || stristr($away_team, $teams[1]))
                      ){
                        foreach ($child3->children() as $child4){
                            foreach ($child4->children() as $child5){
                                foreach($child5->attributes() as $a1 => $b1){
                                    if($a1 == "name" && $b1 == "Match Result"){
                                        foreach ($child5->children() as $child6){
                                            foreach($child6->attributes() as $a2 => $b2){
                                                if($a2 == "odd"){
                                                    array_push($listOdds, $b2);
                                                    $i++;

                                                    if($i == 3) break 9;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $listOdds;
}


?>