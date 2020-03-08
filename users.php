
<?php

///NOT///LOGIN KISMI YAZILACAK. YARIN YAZACAĞIM.


//users(userName,realName,county,city,town,school,mail,phone,pass,status)
$db = mysqli_connect('localhost', 'root', '', 'Matematigo');

if(isset($_REQUEST['action'])){
    //Mail Activation, and Insert User UserName mail and pass
    if($_REQUEST['action'] == 'initialInsertUser'){
        if(isset($_REQUEST['userName']) && isset($_REQUEST['mail']) && isset($_REQUEST['pass']))
        {
            $userName = $_REQUEST['userName'];
            $existusername = "SELECT * FROM users WHERE userName = '$userName'";
            $result = mysqli_query($db,$existusername);
            if(mysqli_num_rows($result) == 0){
                $mail = $_REQUEST['mail'];
                $pass = $_REQUEST['pass'];
                $existmail= "SELECT * FROM users WHERE mail = '$mail'";
                $result = mysqli_query($db,$existmail);
                if(mysqli_num_rows($result) == 0){
                    $query = "INSERT INTO users(userName,realName,mail,pass,phone,county,city,town,school,status)
                    VALUES('$userName','Enter Real Name', '$mail' , '$pass', 'Enter Phone', 'Enter County' , 'Enter City', 'Enter Town','Enter School' ,0)";
                    mysqli_query($db, $query);
                    //Mail sending 
                    send_email_activation($userName,$mail);

                    echo "<br>Thank you for registered, You can check your email and confim to your account";

                }else{
                    echo "Mail is already used";
                }
            }else{
                echo "Username is already used";
            }
            
        }else{
            echo "Some Error no userName, pass or email";
        }

    }

    //Account u gelen maile tıkladığında status u set ediyor. ve aykırı studio web sitesine gönderiyor.
    if($_REQUEST['action'] == 'confirmAccount'){
        if(isset($_REQUEST['userName'])){
            $userName = $_REQUEST['userName'];
            $query = "UPDATE users SET status= 1 WHERE userName='$userName'";
            mysqli_query($db, $query);
            echo "Your account is confirmed";
            
            ?><script> window.location="http://www.aykiri.studio/"</script><?php 
             
        }
    }

    //Bu güncelleme fonksiyonu
    //Uygulama içerisinde giriş yaptıkdan sonra update edebilecekleri alanlar.
    //Email için ayrı bir eşy yazılacak, ayrı bir sayfa gibi, çünkü email kısmında yine konfirmasyon yapılması gerekiyor. ve Mail Update gerekiyor.
    if($_REQUEST['action'] == 'updateAccount'){
        if(isset($_REQUEST['userName']) && isset($_REQUEST['realName']) && isset($_REQUEST['phone']) && isset($_REQUEST['county']) && 
            isset($_REQUEST['city']) && isset($_REQUEST['town']) && isset($_REQUEST['school'])){

            $userName = $_REQUEST['userName'];
            $realName = $_REQUEST['realName'];
            $phone = $_REQUEST['phone'];
            $county = $_REQUEST['county'];
            $city = $_REQUEST['city'];
            $town = $_REQUEST['town'];
            $school = $_REQUEST['school'];

            $query = "UPDATE users SET realName = '$realName', phone = '$phone', county='$county', city='$city', town ='$town' ,school='$school'  WHERE userName='$userName'";
            mysqli_query($db, $query);
            echo "Your account is updated";
             
        }else{
            echo "Some Information Field is empty";
        }
    }

    // Istenilen acoount u gösterir, başkasının da account una bakılabilir.
    if($_REQUEST['action'] == 'displayAccount'){
        if(isset($_REQUEST['userName'])){
            $userName = $_REQUEST['userName'];
            $query = "SELECT * FROM users WHERE userName = '$userName'";
            PrintJson($db,$query);
        }
    }



}else{
    echo "No Action to Set";
}

//Mailing fonksiyonu, buradan SMTP konfirigasyonu yaptığımda mail aktive işlemi başkıyor. TEST EDİLDİ.
function send_email_activation($userName,$userMail){
    require 'C:\xampp\htdocs\Matematigo\PHPMailer-5.2-stable (1)\PHPMailer-5.2-stable\PHPMailerAutoload.php';
    $mail = new PHPMailer;

    //$mail->SMTPDebug = 2;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'friend.guzelhosting.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = '***@**.com';                 // SMTP username
    $mail->Password = '****';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom('info@matematigo.com', 'Mailer');
    $mail->addAddress($userMail);     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $link = "http://localhost/Matematigo/user.php?action=confirmAccount&userName=".$userName;
    $mail->Subject = 'Welcome Matematigo';
    $mail->Body    = '<center><br><font size="7">Welcome to Matematigo</font><br><font size="5">You can click and confim your account</font><br>'.$link.'</center>';
    $mail->AltBody = '<center><br><font size="7">Welcome to Matematigo</font><br><font size="5">You can click and confim your account</font><br></center>'.$link.'</center>';

    if(!$mail->send()) {
        echo '<br>Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo '<br>Message has been sent';
    }

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
