<?php
//Contain 2 Table
//mkeys(keyId, keyPeriod, keyExpired,date, hmUser,userCount, keyType, keyValue, commet, status) Mkeys tablosu
//ukeys(userId, keyId, uKeyExpired, date,keyType,keyValue,status)

// IMPORTANT
// https://stackoverflow.com/questions/1676551/best-way-to-test-if-a-row-exists-in-a-mysql-table/1676573#1676573

$db = mysqli_connect('localhost', 'root', '', 'Matematigo');


if(isset($_REQUEST['action'])){


    //Insert User Key 
    //Firsty is there a key than is the key used by the user before, if no used, inserting success
    if($_REQUEST['action'] == 'insertuKey'){
    
        if(isset($_REQUEST['keyId']) && isset($_REQUEST['userId']))
        {
            $userId = $_REQUEST['userId'];
            $keyId = $_REQUEST['keyId'];
            $date = date("Y-m-d H:i:s");
            $dateString = date_format($date, 'Y-m-d H:i:s');
            $status= 1;

            //Look key exist and enough count and status 1 from mKeys Table
            $exissql= "SELECT * FROM mkeys WHERE keyId = '$keyId' AND hmUser>userCount AND status = '1'";
            $result = mysqli_query($db,$exissql);

            if(mysqli_num_rows($result) == 0)
            {
                echo "No Key! or Key Expired - Error";
            }else{
                //Look user has never used key
                //User can only use the key one time
                $uKeySQL= "SELECT * FROM mkeys WHERE keyId = '$keyId' AND userId = '$userId'";
                $uresult = mysqli_query($db,$uKeySQL);
                if(mysqli_num_rows($uresult) == 0)
                {
                    $mkey = mysql_fetch_row($result);
                    $uKeyExpired = date('Y-m-d H:i:s', strtotime($dateString. $mkey['keyPeriod'])) ;
                    $keyType = $mkey['keyType'];
                    $keyValue = $mkey['keyValue'];
    
                    $query ="INSERT INTO ukeys(userId, keyId,uKeyExpired, date,keyType,keyValue, status) 
                    VALUES ('$userId','$keyId','$uKeyExpired',$date','$keyType','$keyValue','$status')"; 
                    echo "Success";
                }else{
                    echo "Key already used by you";
                }
            }
        }else{
            echo "Error Some Value is no Set";
        }
    }

    //All Users Keys Enable or Disable
    if($_REQUEST['action'] == 'allUsersKeys')
    {
        $query = "SELECT * FROM musers ORDER BY musers.date DESC";
        PrintJson($db,$query);
    }

    //All Enable User's Keys
    if($_REQUEST['action'] == 'allEnableUsersKeys')
    {
        $query = "SELECT * FROM musers WHERE status = '1' ORDER BY musers.date DESC";
        PrintJson($db,$query);
    }


    //all the keys of user
    if($_REQUEST['action'] == 'searchUserKeys'){

        $userId = $_REQUEST['userId'];
        $query = "SELECT * FROM musers WHERE userId = '$userId'";
        $result = mysqli_query($db,$query);

            if(mysqli_num_rows($result) == 0)
            {
                echo "Users Has no Keys";
            }else{
                PrintJson($db,$query);
            }  
    }

    //User's spesific Key search
    if($_REQUEST['action'] == 'searchSpecificUserKeys'){

        $userId = $_REQUEST['userId'];
        $keyId = $_REQUEST['keyId'];
        $query = "SELECT * FROM musers WHERE userId = '$userId' AND keyId = '$keyId'";
        $result = mysqli_query($db,$query);

            if(mysqli_num_rows($result) == 0)
            {
                echo "Users Has no Keys";
            }else{
                PrintJson($db,$query);
            }  
    }


    //http://localhost/Matematigo/mkeys.php?action=insertKey&keyId=XEEEEZWX&keyPeriod=+1%20week%202%20days%204%20hours%202%20seconds&keyExpired=2020-05-20%2019:01:09&hmUser=5&userCount=2&keyType=0&keyValue=0&commet=Test%20dir&status=1
    //This Action insert a game to Database
    if($_REQUEST['action'] == 'insertKey'){
    
        if(isset($_REQUEST['keyId']) && isset($_REQUEST['keyPeriod']) && isset($_REQUEST['keyExpired']) && isset($_REQUEST['hmUser']) && isset($_REQUEST['userCount']) && isset($_REQUEST['keyType']) && isset($_REQUEST['keyValue']) && isset($_REQUEST['commet']) && isset($_REQUEST['status']))
        {
            $keyId = $_REQUEST['keyId'];
            $keyPeriod = $_REQUEST['keyPeriod'];
            $keyExpired = $_REQUEST['keyExpired'];
            $date = date("Y-m-d H:i:s");
            $hmUser = $_REQUEST['hmUser'];
            $userCount = $_REQUEST['userCount'];
            $keyType = $_REQUEST['keyType'];
            $keyValue = $_REQUEST['keyValue'];
            $commet = $_REQUEST['commet'];
            $status = $_REQUEST['status'];

            //Look key exist 
            $exissql= "SELECT * FROM mkeys WHERE keyId = '$keyId'";
            $result = mysqli_query($db,$exissql);

            if(mysqli_num_rows($result) == 0)
            {
                $query ="INSERT INTO mkeys(keyId, keyPeriod, keyExpired,date, hmUser,userCount, keyType, keyValue, commet, status) 
                VALUES ('$keyId','$keyPeriod','$keyExpired','$date','$hmUser','$userCount','$keyType','$keyValue','$commet','$status')";
            
                mysqli_query($db, $query);
                echo "Success";
            }else{
                echo "Key Exist";
            }

        }else{
            echo "Error Same Value is no Set";
        }
    }


    if($_REQUEST['action'] == 'displayKeys'){
        //echo date("d-m-Y H:i:s");
        $query = "SELECT * FROM mkeys ORDER BY mkeys.date DESC";
        PrintJson($db,$query);
    }

    if($_REQUEST['action'] == 'displayKeysUsage'){

        $query = "SELECT * FROM mkeys ORDER BY mkeys.userCount DESC";
        PrintJson($db,$query);
    }


    //http://localhost/Matematigo/mkeys.php?action=searchKey&keyId=AASFFQWX
    if($_REQUEST['action'] == 'searchKey'){

        $keyId = $_REQUEST['keyId'];
        $query = "SELECT * FROM mkeys WHERE keyId = '$keyId'";
        $result = mysqli_query($db,$query);

            if(mysqli_num_rows($result) == 0)
            {
                PrintJson($db,$query);
            }else{
                echo "Key Exist";
            }  
    }


}
else{
    echo "Not Set Action";
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
