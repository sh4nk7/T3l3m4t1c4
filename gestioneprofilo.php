<?php
include 'connessione.php';
//require_once 'sessioni.php';//include una volta sola il metodo sessioni
session_start();


if (!isset($_SESSION["nickname"])){ //se risulta già un cookie loggato, entra
    header("location: index.php");
    exit;
}
else if ($_SESSION["nickname"]!='root' && $_SESSION["fornitore"] == true){
    header("location: Fornitore.php");
    exit();
}


?>
<html>
<head></head>
<body>
    <a href="InVendita.php" style="float:right;">Torna agli acquisti</a>
    <br>
    <center>
        <h1>Benvenuto <?php echo $_SESSION['nickname']?></h1> 
        <h3>qui potrai modificare alcune delle tue informazioni base</h3>
    </center>
    <form action="gestioneprofilo.php" method="POST">
        <center>
        <table>
            <tr><td>Nuovo username:</td><td><input type="text" name="nickname"></td></tr>
            <tr><td>Nuovo email:</td><td><input type="text" name="email"></td></tr>
            <tr><td>Nuovo indirizzo:</td><td><input type="text" name="indirizzo"></td></tr>
            <tr><td>Nuovo paese:</td><td><input type="text" name="paese"></td></tr>
            
            <tr><td>Nuovo numero:</td><td><input type="text" name="numero"></td></tr>
            <tr><td></td><td></td></tr>            
            <tr><td>cambia o conferma password:</td><td></td></tr>
            <tr><td>password:</td><td><input type="password" name="password"></td></tr>
            <tr><td>Ripeti password:</td><td><input type="password" name="ripeti_password"></td></tr>				  
        </table>
        </center>
        <?php 
        
        if (isset($_POST["modifica"])){ //se è stato premuto il pulsante submitx, manda la query al db
            
            $nickname = $_POST['nickname'];
            $email = $_POST['email'];
            $indirizzo = $_POST['indirizzo'];
            $numero = $_POST['numero'];
            $paese = $_POST['paese'];
            $password = $_POST['password'];
            $ripeti_password = $_POST['ripeti_password'];
            $query = "";
            if($password == $ripeti_password && !empty($password)){
                $query = "UPDATE Utenti SET ";
                
                if(!empty($nickname)){
                    $query .= "Nickname = '$nickname'". ", ";
                }   
                if(!empty($email)){
                    $query .= "Email = '$email'" . ", ";
                }
                if(!empty($indirizzo)){
                    $query .= "Indirizzo = '$indirizzo'" . ", ";
                }
                
                if(!empty($numero)){
                    $query .= "Telefono = '$numero'". ", ";
                }
                if(!empty($paese)){
                    $query .= "Paese = '$paese'". ", ";
                }
                if(!empty($password)){
                    $query .= "Password = '$password'". " ";
                }
                $nickS = $_SESSION['nickname'];
                $query .= "WHERE Nickname = '$nickS'";
                
        
                //echo $query;
                $queryCambiamento = $mysqli->query($query);
                echo "Tutte le modifiche sono state salvate!";
            
                
            }
            else{
                echo '<br>';
                echo "Devi inserire una password e farla coincidere!";
                echo '<br>';
            }
        }
        
        ?>
        
        <center><input type="submit" name="modifica" id="submit" value="modifica informazioni"></center>
    </form>

</body>
</html>