<?php

$db = mysqli_connect('localhost', 'root', '', 'Matematigo');

if(isset($_REQUEST['action'])){

    //This Action insert a game to Database
    if($_REQUEST['action'] == 'insertGame'){

        $userId = $_REQUEST['userId'];
        $difficulty = $_REQUEST['difficulty'];
        $gameMode = $_REQUEST['gameMode'];
        $date = date("Y-m-d H:i:s");
        $score = $_REQUEST['score'];
        $correctAnswersCount = $_REQUEST['correctAnswersCount'];
        $wrongAnswersCount = $_REQUEST['wrongAnswersCount'];
        $tinycopoSteps = $_REQUEST['tinycopoSteps'];
        $level = $_REQUEST['level'];
        $gameType = $_REQUEST['gameType'];

        $query = "INSERT INTO games(userId,difficulty,gameMode,date,score,correctAnswersCount,wrongAnswersCount,tinycopoSteps,level,gameType) 
            VALUES('$userId', '$difficulty', '$gameMode', '$date', '$score', '$correctAnswersCount', '$wrongAnswersCount', '$tinycopoSteps', '$level', '$gameType')";
        mysqli_query($db, $query);
    }


    //This Action Get All Games inside Games Database order Date Descending
    //EXAMPLE
    //http://localhost/Matematigo/games.php?action=displayGames
    if($_REQUEST['action'] == 'displayGames'){
        //echo date("d-m-Y H:i:s");
        $query = "SELECT * FROM `games` ORDER BY games.date DESC";
        PrintJson($db,$query);
    }

    //This action lists all games played by the user in date order
    //EXAMPLE
    //http://localhost/Matematigo/games.php?action=displayGames4User&userid=Test
    if($_REQUEST['action'] == 'displayGames4User'){
        
        $userId = $_REQUEST['userid'];
        $query = "SELECT * FROM games WHERE userId = '$userId' ORDER BY games.date DESC ";
        PrintJson($db,$query);
    }

    //This action lists Limit number games played by the user in date order
    //Bu kısım O oyuncunun yönelim kısmında kullanılabilir. Son 100 oyunu baz alarak kullanının yönelimini belirleriz.
    //EXAMPLE
    //http://localhost/Matematigo/games.php?action=displayGames4UserLimit&userid=Test&limit=1
    if($_REQUEST['action'] == 'displayGames4UserLimit'){
        
        $userId = $_REQUEST['userid'];
        $limit = $_REQUEST['limit'];
        $query = "SELECT * FROM games WHERE userId = '$userId' ORDER BY games.date DESC LIMIT $limit";
        PrintJson($db,$query);
    }

    //Kullanıcının Oyuna özel limitli şekilde getirilebilen aksiyonu
    //EXAMPLE
    //http://localhost/Matematigo/games.php?action=displaySpecificGames4UserLimit&userid=Test&limit=1&gameMode=Common
    if($_REQUEST['action'] == 'displaySpecificGames4UserLimit'){
        
        $userId = $_REQUEST['userid'];
        $gameMode = $_REQUEST['gameMode'];
        $limit = $_REQUEST['limit'];
        $query = "SELECT * FROM games WHERE userId = '$userId' AND gameMode = '$gameMode' ORDER BY games.date DESC LIMIT $limit";
        PrintJson($db,$query);
    }

    //Kullanıcıların Özel Modlarda Oyun Oynadıklarında onların çekilebilmesini sağlıyor.
    //EXAMPLE
    //http://localhost/Matematigo/games.php?action=displaySpecificGameType&gameType=0
    if($_REQUEST['action'] == 'displaySpecificGameType'){

        $gameType = $_REQUEST['gameType'];
        $query = "SELECT * FROM games WHERE gameType = $gameType ORDER BY games.date DESC";
        PrintJson($db,$query);
    }
}
else{
    echo "Not Set Action";
}

//Db ve query i alıp row yazdırıyor
function PrintJson($db,$query) {
    $result = mysqli_query($db,$query);
    $rows = array();

    while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
    }
    print json_encode($rows);
}
    
?>
