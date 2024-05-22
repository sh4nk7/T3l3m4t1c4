<?php
include 'connessione.php';
include 'Prodotto.php';

session_start();

if (!isset($_SESSION["nickname"])){ //se risulta già un cookie loggato, entra
    header("location: index.php");
    exit;
}
else if ($_SESSION["nickname"]!='root' && $_SESSION["fornitore"] == true){
    header("location: Fornitore.php");
    exit();
}


echo '<center><h1>Ciao ' . $_SESSION["nickname"] . '</h1></center>';

if (isset($_POST["logout"])){
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])){
        setcookie(session_name(),'',time()-50000);
    }
    
    session_destroy();
    
    header("Location: index.php");
    exit;
}

$nomeDaCercare="";

if(empty($_POST['nomeDaCercare'])){
    $categoria = $_POST["categoria"];
    $query= $mysqli->query("SELECT Prodotto.Cod_Prodotto, Prodotto.Cod_Categoria, Prodotto.Nome, Prodotto.Prezzo_Vendita, Prodotto.Scorte_Magazzino FROM Prodotto
    join Categoria_Prodotti on Categoria_Prodotti.Cod_Categoria = Prodotto.Cod_Categoria
    WHERE Categoria_Prodotti.Nome = '$categoria'");
    
    if(!($query->num_rows)>0){//se non esiste query con codice categoria, utilizzo questa query per far stampare tutti i prodotti
        $query = $mysqli->query("SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE 1");
        
    }
}
else {
    $categoria = $_POST["categoria"];
    $nomeDaCercare = $_POST['nomeDaCercare'];
    $query = $mysqli->query("SELECT Prodotto.Cod_Prodotto, Prodotto.Cod_Categoria, Prodotto.Nome, Prodotto.Prezzo_Vendita, Prodotto.Scorte_Magazzino FROM Prodotto
join Categoria_Prodotti on Categoria_Prodotti.Cod_Categoria = Prodotto.Cod_Categoria
WHERE LOCATE('$nomeDaCercare',Prodotto.Nome) AND Categoria_Prodotti.Nome = '$categoria'");
    
    if(!($query->num_rows)>0){//se non trova in quella categoria, cerca in tutte le altre
        $query = $mysqli->query("SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE LOCATE('$nomeDaCercare',Prodotto.Nome) ");
        echo '<p>Non è stato trovato nulla nella categoria selezionata</p>';
        
    }
}

$queryCategorie = $mysqli->query("SELECT Nome FROM Categoria_Prodotti WHERE 1");
$NickS = $_SESSION['nickname'];
//$queryCarrello = $mysqli->query("SELECT Carrello FROM Utenti WHERE Nickname = '$NickS'");
$num_rows = $query->num_rows;
$products = array();
$product = null;
$productsInCarrello = array();

$oggettiGiustoOrdine = array();


/*
if (!isset($_SESSION["nickname"])){ //se risulta già un cookie loggato, entra
    header("location: index.php");
    exit;
}
*/
?>
<html>
<head></head>
<body>
<a href="gestioneprofilo.php" style="float:right;">Gestisci il tuo profilo</a>

    <br>   
         
    <form action="InVendita.php" method="POST">
    	<input type="text" name="nomeDaCercare" value="<?php echo $nomeDaCercare?>">
    	<select name="categoria"> 
        <option>Tutte le categorie</option>
            <?php
                
            while ($row = $queryCategorie->fetch_row()) {
                if($_POST['categoria'] == $row[0]){
                    echo '<option selected="selected">'.$row[0].'</option>';
                }
                else{
                    echo '<option>'.$row[0].'</option>';
                }
                
            }
                
            ?>
            
            </select>
            <input type="submit" name="cerca" value="cerca" />
            
    	    <table style="width:100%" border="1">
            <tr>
            	<th>Codice Prodotto</th><th>Codice categoria</th> <th>Nome</th> <th>Prezzo vendita</th> <th>Scorte magazzino</th> <th>Carrello</th>
    		</tr>

    		<?php
    		$y=0;
    		
    		while ($row = $query->fetch_row()) {
    		    $product = new Prodotto($row[0], $row[1], $row[2], $row[3], $row[4]);
    		    $products[] = $product;

    		    echo"<tr>\n";
    		    if(!isset($_POST["modifica"]) && !isset($_POST["YES"]))
    		    {
        		    for($x=0;$x<6;$x++){
        		        if($x<5){
            		        echo '<td><input type="text" name="cod_prodotto['.$y.']['.$x.']" value="'.$row[$x].'" readonly></td>';//chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni prodotto
            		        echo "\n";
        		        }
        		        else{
        		            echo '<td><center><input type="submit" name="bottone['.$y.']" value="Aggiungi" ></center></td>';//chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni prodotto
        		            echo "\n";
        		        }
        		    }
        		    echo"</tr>\n";
    		    }
    		    $y++;
    		}
    		?>
    		</table>
    	<br>
    	<hr />
    	<h2>Carrello:</h2>
    	<table style="width:100%" border="1">
        <tr>
        	<th>Codice Prodotto</th><th>Codice categoria</th> <th>Nome</th> <th>Prezzo vendita</th> <th>Scorte magazzino</th> <th>Quantità</th> <th>Carrello</th>
    	</tr>
    	<?php
         /*
    	 echo "<pre>";
    	 print_r($_POST);
    	 echo "</pre>";
    	 */
    	
    	if(isset($_POST[bottone])){
    	     $carrelloBottone = $_POST[bottone];
    	     
    	     if (!isset($_SESSION['prodottoInCarrello'])) {//costruisce il carrello
    	         $_SESSION['prodottoInCarrello'] = array();
    	     }
    	     
    	     $y = 0;
    	     for($x=0;$x<$query->num_rows; $x++){
    	         
        	     if($carrelloBottone[$x] == 'Aggiungi'){
        	         
        	         $product = $products[$x];
        	         $productsInCarrello[] = $product;
        	         
        	         if(!in_array($product,$_SESSION['prodottoInCarrello'])){//vede se esiste già un oggetto product
        	               array_push($_SESSION['prodottoInCarrello'],$product);//costruisce il carrello
        	               echo 'Il prodoto "'.$products[$x]->getNome_prodotto().'" è stato aggiunto al carrello';
        	               
        	               
        	               $carrello = $_SESSION['prodottoInCarrello'];//qui creo il mio array di oggetti "carrello" che successivamente verrà salvato nel database come query
        	               
        	               //genero la query che salverà il carrello dell'utente
        	               $carrelloInDB = "SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE ";
        	               for ($y = 0; $y<count($carrello);$y++){
        	                   if ($y<count($carrello)-1){
        	                       $carrelloInDB .= "Cod_Prodotto = " . $carrello[$y]->getCod_prodotto() . " OR ";
        	                   }
        	                   else {
        	                       $carrelloInDB .= "Cod_Prodotto = " . $carrello[$y]->getCod_prodotto() . " ";//lo spazio finale è importante
        	                   }
        	               }
        	                       	               
        	               //echo $carrelloInDB;
        	               $nickSessione = $_SESSION["nickname"];//solo per la query
        	               
        	               //------//Mi servono solo per verificare di non stare sovrascrivendo un carrello precedentemente memorizzato
        	               $queryCarrello2 = $mysqli->query("SELECT Carrello FROM Utenti WHERE Nickname = '$NickS'");
        	               $carrelloSalvato2 =  $queryCarrello2->fetch_row();
        	               //------//
        	               
        	               
        	               //memorizzo il carrello nel database
        	               if($carrelloSalvato2[0][0]!='S'){
        	                   $queryCambiamento = $mysqli->query("UPDATE Utenti SET Carrello = '$carrelloInDB' WHERE Nickname = '$nickSessione'");
        	               }
        	               
        	               else{
        	                   //
        	                   $carrelloSalvato2[0] .= "OR Cod_Prodotto = " . $product->getCod_prodotto() . " ";//lo spazio finale è importante
        	                   $queryCambiamento2 = $mysqli->query("UPDATE Utenti SET Carrello = '$carrelloSalvato2[0]' WHERE Nickname = '$nickSessione'");
        	               }
        	               
        	         }
        	         else{
        	             echo 'Il prodoto "'.$products[$x]->getNome_prodotto().'" è già presente nel carrello';
        	             
        	         }
        	         
        	         echo '<br>';
        	         break;
        	     }
    	     }
    	        
    	 }
    	 
    	 
    	 if(isset($_POST[bottone_carrello])){
  	     
    	     $nickSessione = $_SESSION["nickname"];//solo per la query
    	     
    	     $queryCarrello3 = $mysqli->query("SELECT Carrello FROM Utenti WHERE Nickname = '$nickSessione'");
    	     $carrelloSalvato =  $queryCarrello3->fetch_row();
    	     
    	     $ottengoCarrello2 = $mysqli->query($carrelloSalvato[0]);
    	     
    	     
    	     while ($row = $ottengoCarrello2->fetch_row()) {
    	         
    	         $prodottoNelCarrello = new Prodotto($row[0], $row[1], $row[2], $row[3], $row[4]);
    	         $oggettiGiustoOrdine[]=$prodottoNelCarrello;//un array che segna gli oggetti nel giusto ordine del carrello
    	     } 	     
    	     
    	     $carrelloBottoneElimina = $_POST[bottone_carrello];
    	     
    	     
    	     $carrelloPulito = array();
    	     for($x = 0; $x < count($oggettiGiustoOrdine); $x++){
    	         if($carrelloBottoneElimina[$x] == 'Rimuovi'){
    	             
    	             echo "Hai rimosso dal carrello: " . $oggettiGiustoOrdine[$x]->getNome_prodotto();
    	             
    	             for($y = 0; $y < count($oggettiGiustoOrdine); $y++){
    	                 if($oggettiGiustoOrdine[$y] != $oggettiGiustoOrdine[$x]){
    	                     $carrelloPulito[]=$oggettiGiustoOrdine[$y];
    	                 }
    	             }
    	             
    	             $oggettiGiustoOrdine = $carrelloPulito;
    	             
    	             $_SESSION['prodottoInCarrello'] = $oggettiGiustoOrdine;//è importante aggiornare il valore dei prodotti in carrello, ordinandola di nuovo
    	             
    	             //creo e aggiorno la nuova query:
    	             $carrelloInDB = "SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE ";
    	             
    	             
    	             for ($y = 0; $y<count($oggettiGiustoOrdine);$y++){
    	                     $carrelloInDB .= "Cod_Prodotto = " . $oggettiGiustoOrdine[$y]->getCod_prodotto() . " OR ";    	           
    	             }
    	             
    	             if(count($oggettiGiustoOrdine)==0){
    	                 echo '<br>';
    	                 echo "Il tuo carrello è al momento vuoto!\n";
    	             }
    	             
    	             $carrelloInDB .= "Cod_Prodotto = 0 "; //non esistono prodotti con chiave 0, lo utilizzo per terminare la query
    	     	             
    	             $queryCambiamento = $mysqli->query("UPDATE Utenti SET Carrello = '$carrelloInDB' WHERE Nickname = '$nickSessione'");
    	             
    	             
    	         }
    	     }
    	     
    	     
    	 }

    	 
    	 
    	 $queryCarrello = $mysqli->query("SELECT Carrello FROM Utenti WHERE Nickname = '$NickS'");
    	 $carrelloSalvato =  $queryCarrello->fetch_row();
    	 
    	 
    	 if($carrelloSalvato[0][0] == 'S'){//Dato che nel carrello viene sempre memorizzata la query, confronto se la prima lettera è S per stabilire se è pieno
    	     
    	     // $carrelloSalvato[0]; qui c'è la query salvata, relativa al carrello
    	     
    	     $ottengoCarrello = $mysqli->query($carrelloSalvato[0]);
    	     $y = 0;
    	     while ($row = $ottengoCarrello->fetch_row()) {
    	         
    	         if (!isset($_SESSION['prodottoInCarrello'])) {//costruisce il carrello
    	             $_SESSION['prodottoInCarrello'] = array();
    	         }
    	         
    	         $prodottoNelCarrello = new Prodotto($row[0], $row[1], $row[2], $row[3], $row[4]);
    	         if(!in_array($prodottoNelCarrello,$_SESSION['prodottoInCarrello'])){//vede se esiste già un oggetto product
    	             
    	               array_push($_SESSION['prodottoInCarrello'],$prodottoNelCarrello);//costruisce il carrello
    	         }
    	         
    	         $oggettiGiustoOrdine[]=$prodottoNelCarrello;//un array che segna gli oggetti nel giusto ordine del carrello
    	         
    	         
    	         echo"<tr>\n";
    	         
    	         for($x=0;$x<7;$x++){
    	             if($x<6 && $x != 5){
    	                 echo '<td><input type="text" name="cod_prodotto_carrello['.$y.']['.$x.']" value="'.$row[$x].'" readonly></td>';//chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni prodotto
    	                 echo "\n";
    	             }
    	             else if($x == 5){
    	                 
    	                 if (isset($_POST['aggiorna'])){
    	                     $quantita = $_POST[cod_prodotto_carrello];
    	                     echo '<td><input type="text" name="cod_prodotto_carrello['.$y.']['.$x.']" value="'.$quantita[$y][5].'"></td>';//chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni prodotto
    	                     echo "\n";
    	               
    	                 }
    	                 else{
    	                     echo '<td><input type="text" name="cod_prodotto_carrello['.$y.']['.$x.']" value="1"></td>';//chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni prodotto
    	                     echo "\n";
    	                 }
    	                 
    	             }
    	             else{
    	                 echo '<td><center><input type="submit" name="bottone_carrello['.$y.']" value="Rimuovi" ></center></td>';//chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni prodotto
    	                 echo "\n";
    	             }
    	         }    	         
    	         echo"</tr>\n";
    	         
    	         $y++;
    	     }
    	     
    	     $totaleAcquisto=0;
    	     //$oggettiPerTotale = $_SESSION['prodottoInCarrello']; //in questo caso l'ordine sarebbe stato sbagliato
    	     $oggettiPerTotale = $oggettiGiustoOrdine;
    	     
    	     for($x=0;$x<count($oggettiPerTotale);$x++){
    	         if (isset($_POST['aggiorna'])){
    	             $quantita = $_POST[cod_prodotto_carrello];
    	             if($quantita[$x][5]<=$oggettiPerTotale[$x]->getScorte_magazzino()){
    	                   $totaleAcquisto+=$oggettiPerTotale[$x]->getPrezzo_vendita()*$quantita[$x][5];
    	             }
    	             else{
    	                 echo "Attenzione! Non ci sono scorte sufficienti per l'oggetto: " . $oggettiPerTotale[$x]->getNome_prodotto();
    	                 echo '<br>';
    	                 $totaleAcquisto+=$oggettiPerTotale[$x]->getPrezzo_vendita();
    	             }
    	             
    	         }
    	         else{
    	               $totaleAcquisto+=$oggettiPerTotale[$x]->getPrezzo_vendita();
    	         }
    	     }
    	     echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><center>-</center></td></tr>';
    	     echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><center><input type="submit" name="aggiorna" value="Aggiorna" /></center></td></tr>';
    	     echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><th>Totale:</th></tr>';
    	     echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><center>'.$totaleAcquisto.'€</center></td></tr>';
    	     
    	 }
    	 else{
    	     echo "Il tuo carrello è al momento vuoto!\n";
       	 }
       	 
    	?>
    	</table>
        <input type="submit" name="acquista" value="Acquista" style="float: right;"/>

    	<br>
    	
    	<hr />
    	<?php 
    	
    	if (isset($_POST['acquista'])){
        	require 'PHPMailerAutoload.php';
            //require 'credential.php';

            //$mail = new PHPMailer;
            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            //$mail->isSMTP();                                      // Set mailer to use SMTP
            //$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            //$mail->SMTPAuth = true;                               // Enable SMTP authentication
            //$mail->SMTPAutoTLS = false;
            //$mail->Username = "t3l3m4t1c4.mail@gmail.com";                 // SMTP username
            //$mail->Password = "9hwnANhzvMJE";                           // SMTP password
            //$mail->Priority    = 1;
            //$mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
            //$mail->Port = 80;                                    // TCP port to connect to

            //$mail->setFrom('t3l3m4t1c4.mail@gmail.com', 't3l3m4t1c4');
            //$mail->addAddress('t3l3m4t1c4.mail@gmail.com' , 't3l3m4t1c4');     // Add a recipient
            //$mail->addReplyTo('t3l3m4t1c4.mail@gmail.com');
            ////$mail->addCC('cc@example.com');
            ////$mail->addBCC('bcc@example.com');

            ////$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            ////$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //$mail->isHTML(true);                                  // Set email format to HTML

            //$mail->Subject = 'Cliente';
            //$mail->Body    = '<div style="borger:2px solid red;">This is the HTML message body <b>in bold!</b></div>';
            //$mail->AltBody = 'Complimenti per l acquisto ';

            //if(!$mail->send()) {
            //    echo 'Message could not be sent'.'<br >';
            //    echo 'Mailer Error: ' . $mail->ErrorInfo;
            //    echo '<br />';
            //} else {
            //    echo 'Message has been sent';
            //}
            
            $email = $mysqli->query("SELECT Email FROM Utenti WHERE Nickname = '$NickS'");
        	$to = $email->fetch_row();
            
            $subject = "Acquisto";
            $txt = "Complimenti per aver acquistato da t3l3m4t1c4";
            $headers = "From: t3l3m4t1c4.mail@gmail.com" . "\r\n" . "CC: t3l3m4t1c4.mail@gmail.com";
            mail($to[0],$subject,$txt,$headers);

    	    echo "Complimenti per l'acquisto";
    	}
    	
    	?>
    	<br>
    	<input type="submit" name="logout" value="logout" />
    	
    </form>
        

</body>
</html>