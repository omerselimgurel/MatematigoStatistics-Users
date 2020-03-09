<?php
//setupgame(userName,gameType,gameTitle,gameText,difficulty,gameDuration,gameExpired,date,gameQHash,status)
//Pass koyulabilir, her oyuncu sadece aynı game type türüne 

//Gametype a göre de bir action yazılacak, sonra galiba bitmiş oluyor. 5 dk lik birşey iyi geceler herkese

$db = mysqli_connect('localhost', 'root', '', 'Matematigo');


if(isset($_REQUEST['action'])){

    //http://localhost/Matematigo/setupgame.php?action=setupGame&userName=HOCA&gameType=NEWGAME1&gameTitle=GAMETITLE&gameText=GAMETEXT&difficulty=HARD&gameDuration=1&gameExpired=2020-03-15%2020:44:29
    if($_REQUEST['action'] == 'setupGame'){
        if( isset($_REQUEST['userName']) && isset($_REQUEST['gameType']) && isset($_REQUEST['gameTitle']) && isset($_REQUEST['gameText'])&&
        isset($_REQUEST['difficulty'])&& isset($_REQUEST['gameDuration'])&& isset($_REQUEST['gameExpired']))
        {
            $userName = $_REQUEST['userName'];
            $gameType = $_REQUEST['gameType'];
            $gameTitle = $_REQUEST['gameTitle'];
            $gameText = $_REQUEST['gameText'];
            $difficulty = $_REQUEST['difficulty'];
            $gameDuration = $_REQUEST['gameDuration'];
            $gameExpired = $_REQUEST['gameExpired'];
            $date = date("Y-m-d H:i:s");
            $gameQHash = "HASH";

            $existgametype= "SELECT * FROM setupgame WHERE gameType = '$gameType'";
            $result = mysqli_query($db,$existgametype);
            if(mysqli_num_rows($result) == 0)
            {
                $query ="INSERT INTO setupgame(userName,gameType,gameTitle,gameText,difficulty,gameDuration,gameExpired,date,gameQHash,status)
                VALUES ('$userName','$gameType','$gameTitle','$gameText','$difficulty',$gameDuration,'$gameExpired','$date','$gameQHash',1)";
                    
                $result = mysqli_query($db,$query);
                echo "success";
            }else{
                echo "Game Name is already used";
            }
        }else{
            echo "Some Value missing";
        }
    }

    if($_REQUEST['action'] == 'userNameGames'){
        if( isset($_REQUEST['userName'])){

            $userName = $_REQUEST['userName'];
            $query= "SELECT * FROM setupgame WHERE userName = '$userName'";
            PrintJson($db,$query);

        }else{
            echo "No userName ";
        }
    }
    
}else{
    echo "No Action to Set";
}



function PrintJson($db,$query) {
    $result = mysqli_query($db,$query);
    $rows = array();

    while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
    }
    print json_encode($rows);
}

?>
