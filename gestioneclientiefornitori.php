<?php
include 'connessione.php';
include 'Cliente.php';
include 'Fornitore.php';
session_start();
if (! isset($_SESSION["nickname"])) { // se risulta già un cookie loggato, entra
    header("location: index.php");
    exit();
}
else if ($_SESSION["nickname"]!='root' && $_SESSION["fornitore"] == false){
    header("location: InVendita.php");
    exit();
}
else if ($_SESSION["nickname"]!='root' && $_SESSION["fornitore"] == true){
    header("location: Fornitore.php");
    exit();
}

if (isset($_POST["logout"])) {
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 50000);
    }
    
    session_destroy();
    
    header("Location: index.php");
    exit();
}

/*
 * if (!isset($_POST['cerca']) || $_POST['categoria']=="Tutte le categorie"){
 * $query = $mysqli->query("SELECT * FROM Prodotto WHERE 1");
 * }
 * else{
 */
$nomeDaCercare = "";
$nomeDaCercareFornitore = "";
/*Le query per il cliente*/

if(!empty($_POST['nomeDaCercare'])){
    $categoria = $_POST["categoria"];
    $nomeDaCercare = $_POST['nomeDaCercare'];
    $query = $mysqli->query("SELECT Cod_Cliente, Nome, Cognome, Nickname, Password, Amministratore, Email, Indirizzo, Paese, Telefono FROM `Utenti` WHERE $categoria = '$nomeDaCercare'");
}
else{
    $query = $mysqli->query("SELECT Cod_Cliente, Nome, Cognome, Nickname, Password, Amministratore, Email, Indirizzo, Paese, Telefono FROM `Utenti` WHERE 1");
}

$queryCategorie = $mysqli->query("DESCRIBE Utenti;");

/*Le query per il fornitore*/

if(!empty($_POST['nomeDaCercareFornitore'])){
    $categoriaFornitore = $_POST["categoriaFornitore"];
    $nomeDaCercareFornitore = $_POST['nomeDaCercareFornitore'];
    $queryFornitore = $mysqli->query("SELECT Cod_Fornitore, Nome_Ditta, Nickname, Password, Email, Indirizzo, Paese, Telefono FROM Fornitore WHERE $categoriaFornitore = '$nomeDaCercareFornitore'");
}
else{
    $queryFornitore = $mysqli->query("SELECT Cod_Fornitore, Nome_Ditta, Nickname, Password, Email, Indirizzo, Paese, Telefono FROM Fornitore WHERE 1");
}

$queryFornitoreCategorie=$mysqli->query("DESCRIBE Fornitore;");

$num_rows = $query->num_rows;

$num_rows_fornitore = $queryFornitore->num_rows;

$clienti = array();
$nuovoCliente = null;
$clientiCambiati = array();

$nuovoFornitore = null;
$fornitori = array();
$fornitoriCambiati = array();

/*
 * echo "<pre>";
 * print_r($_POST);
 * echo "</pre>";
 */

?>
<html>
<body>

	<center>
		<h2>Gestione dei clienti e fornitori</h2>
	</center>
	<a href="visualizzafatture.php">Visualizza le fatture</a>
	<br>
	<a href="gestioneprodotti.php">Gestisci i prodotti</a>
	<hr />
	<h4>Lista Clienti registrati al postale:</h4>
	<form action="gestioneclientiefornitori.php" method="POST">
		<input type="text" name="nomeDaCercare" value="<?php echo $nomeDaCercare?>">
			ricerca per:<select name="categoria">
            <?php
            
            while ($row = $queryCategorie->fetch_row()) {
                if ($_POST['categoria'] == $row[0]) {
                    echo '<option selected="selected">' . $row[0] . '</option>';
                } else {
                    echo '<option>' . $row[0] . '</option>';
                }
            }
            
            ?>
            <input type="submit" name="cerca" value="cerca" />

		</select>

		<table style="width: 100%" border="1">
			<tr>
				<th>Codice cliente</th>
				<th>Nome</th>
				<th>Cognome</th>
				<th>Nickname</th>
				<th>Password</th>
				<th>Amministratore</th>
				<th>Email</th>
				<th>Indirizzo</th>
				<th>Paese</th>
				<th>Telefono</th>
			</tr>
    		<?php
    $y = 0;
    
    while ($row = $query->fetch_row()) {
        $nuovoCliente = new Cliente($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9]);
        $clienti[] = $nuovoCliente;
        
        /*
         * creo un oggetto per ogni cliente, mi servirà per verificare eventuali cambiamenti nei textbox
         * in alternativa, avrei dovuto utilizzare javascript per controllare i cambiamenti nei textbox
         */
        echo "<tr>\n";
        if (! isset($_POST["modifica"]) && ! isset($_POST["YES"])) {
            
            for ($x = 0; $x < 10; $x ++) {
                echo '<td><input type="text" name="cod_cliente_m[' . $y . '][' . $x . ']" value="' . $row[$x] . '"></td>'; // chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni cliente
                echo "\n";
            }
            
            echo "</tr>\n";
        }
        
        $y ++;
    }
    if (isset($_POST["modifica"]) || isset($_POST["YES"])) {
        
        echo "<p>sei sicuro di voler modificare i seguenti Clienti? </p>";
        
        $textboxMatrix = $_POST[cod_cliente_m];
        
        for ($y = 0; $y < $num_rows; $y ++) { // per comodità creo una lista di oggetti per ogni riga cambiata
            
            if ($clienti[$y]->getCod_cliente() != $textboxMatrix[$y][0]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } 
            elseif ($clienti[$y]->getNome_cliente() != $textboxMatrix[$y][1]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } elseif ($clienti[$y]->getCognome_cliente() != $textboxMatrix[$y][2]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } elseif ($clienti[$y]->getNickname() != $textboxMatrix[$y][3]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } elseif ($clienti[$y]->getPassword() != $textboxMatrix[$y][4]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } elseif ($clienti[$y]->getAmministratore() != $textboxMatrix[$y][5]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } elseif ($clienti[$y]->getEmail() != $textboxMatrix[$y][6]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } elseif ($clienti[$y]->getIndirizzo() != $textboxMatrix[$y][7]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } elseif ($clienti[$y]->getPaese() != $textboxMatrix[$y][8]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            } elseif ($clienti[$y]->getTelefono() != $textboxMatrix[$y][9]) {
                $clientiCambiatiIndex[] = $y;
                $nuovoCliente = new Cliente($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4], $textboxMatrix[$y][5], $textboxMatrix[$y][6], $textboxMatrix[$y][7], $textboxMatrix[$y][8], $textboxMatrix[$y][9]);
                $clientiCambiati[] = $nuovoCliente;
                echo $clienti[$y]->getNickname() . ', ';
            }
        }
        
        $z = $y;
        for ($y = 0; $y < $z; $y ++) {
            
            for ($x = 0; $x < 10; $x ++) {
                echo '<td><input type="text" name="cod_cliente_m[' . $y . '][' . $x . ']" value="' . $textboxMatrix[$y][$x] . '"></td>'; // chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni cliente
                echo "\n";
            }
            echo "</tr>\n";
        }
        if (! isset($_POST["YES"])) {
            echo '<input type="submit" name="YES" value="YES" />';
            echo "\n";
            echo '<input type="submit" name="NO" value="NO" />';
        }
        
        if (! isset($_SESSION['uteCamb'])) {
            $_SESSION['uteCamb'] = $clientiCambiati;
        }
        
        if (! isset($_SESSION['clientiCambiatiIndex'])) {
            $_SESSION['clientiCambiatiIndex'] = $clientiCambiatiIndex;
        }
        if (! isset($_SESSION['clienti'])) {
            $_SESSION['clienti'] = $clienti;
            $clienti = null;
        }
    }
    /*
     * echo "<pre>";
     * print_r($_POST[cod_cliente_m]);
     * echo "</pre>";
     */
    
    if (isset($_POST["YES"])) { // conferma di modifica
                                // $queryModifica = $mysqli->query("SELECT Nome FROM Categoria_Prodotti WHERE 1");
        $clientiCambiati = $_SESSION['uteCamb'];
        
        $clientiCambiatiIndex = $_SESSION['clientiCambiatiIndex'];
        $clienti = $_SESSION['clienti'];
        
        for ($x = 0; $x < count($clientiCambiati); $x ++) {
            
            // query per ogni riga cambiata
            
            $codice_cli = $clientiCambiati[$x]->getCod_cliente();
            
            $nome_cli = $clientiCambiati[$x]->getNome_cliente();
            
            $cognome_cli = $clientiCambiati[$x]->getCognome_cliente();
  
            $nickname_cli = $clientiCambiati[$x]->getNickname();
            $password_cli = $clientiCambiati[$x]->getPassword();
            $admin = $clientiCambiati[$x]->getAmministratore();
            $email_cli = $clientiCambiati[$x]->getEmail();
            $indirizzo_cli = $clientiCambiati[$x]->getIndirizzo();
            $paese_cli = $clientiCambiati[$x]->getPaese();
            $telefono_cli = $clientiCambiati[$x]->getTelefono();
            
            $vecchioIndex = $clienti[$clientiCambiatiIndex[$x]]->getCod_cliente(); // eventualmente cambiato
            
            if (! $queryCambiamento = $mysqli->query("UPDATE Utenti SET Cod_Cliente = '$codice_cli', Nome = '$nome_cli', Cognome = '$cognome_cli', Nickname = '$nickname_cli', Password = '$password_cli', Amministratore = '$admin', Email = '$email_cli', Indirizzo = '$indirizzo_cli', Paese = '$paese_cli', Telefono = '$telefono_cli' WHERE Utenti.Cod_Cliente = '$vecchioIndex'")) {
                die($mysqli->error);
            }
            echo "<p>Modifica avvenuta correttamente</p>";
            
            echo "<pre>";
            print_r($clienti[$clientiCambiatiIndex[$x]]->getCod_cliente());
            echo "</pre>";
            
            // il codice della chiave è uguale all'indice clienti changed
        }
        /*
         * echo "<pre>";
         * print_r($clientiChanged);
         * echo"<p>".count($clientiChanged)."</p>";
         * echo "</pre>";
         */
        
        $_SESSION['uteCamb'] = null; // azzero le variabili di sessione
        $_SESSION['clientiCambiatiIndex'] = null;
        $_SESSION['clienti'] = null;
    }
    
    ?>
            </table>

		<input type="submit" name="modifica" value="modifica" />
		<hr />
 
 	<h4>Lista Fornitori registrati al postale:</h4>
	<form action="gestioneclientiefornitori.php" method="POST">
		<input type="text" name="nomeDaCercareFornitore" value="<?php echo $nomeDaCercareFornitore?>"> 
		ricerca per:<select name="categoriaFornitore">
            <?php
            
            while ($row = $queryFornitoreCategorie->fetch_row()) {
                if ($_POST['categoriaFornitore'] == $row[0]) {
                    echo '<option selected="selected">' . $row[0] . '</option>';
                } else {
                    echo '<option>' . $row[0] . '</option>';
                }
            }
            
            ?>
            <input type="submit" name="cercaFornitore" value="cerca" />

		</select>

		<table style="width: 100%" border="1">
			<tr>
				<th>Codice fornitore</th>
				<th>Nome ditta</th>
				<th>Nickname</th>
				<th>Password</th>
				<th>Email</th>
				<th>Indirizzo</th>
				<th>Paese</th>
				<th>Telefono</th>
			</tr>
    		<?php
    $y = 0;
    
    while ($row = $queryFornitore->fetch_row()) {
        $nuovoFornitore = new Fornitore($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
        $fornitori[] = $nuovoFornitore;
        
        /*
         * creo un oggetto per ogni fornitore, mi servirà per verificare eventuali cambiamenti nei textbox
         * in alternativa, avrei dovuto utilizzare javascript per controllare i cambiamenti nei textbox
         */
        echo "<tr>\n";
        if (! isset($_POST["modificaFornitore"]) && ! isset($_POST["YESfornitore"])) {
            
            for ($x = 0; $x < 8; $x ++) {
                if($x!=0){
                    echo '<td><input type="text" name="cod_fornitore_m[' . $y . '][' . $x . ']" value="' . $row[$x] . '"></td>'; // chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni fornitore
                    echo "\n";
                }
                else{
                    echo '<td><input type="text" name="cod_fornitore_m[' . $y . '][' . $x . ']" value="' . $row[$x] . '" style="background-color:LightGray" readonly ></td>'; // chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni fornitore
                    echo "\n";
                }
            }
            
            echo "</tr>\n";
        }
        
        $y ++;
    }
    
    if (isset($_POST["modificaFornitore"]) || isset($_POST["YESfornitore"])) {
        
        echo "<p>sei sicuro di voler modificare i seguenti fornitori? </p>";
        
        $textboxMatrixFornitore = $_POST[cod_fornitore_m];
        
        for ($y = 0; $y < $num_rows_fornitore; $y ++) { // per comodità creo una lista di oggetti per ogni riga cambiata
            
            if ($fornitori[$y]->getCod_fornitore() != $textboxMatrixFornitore[$y][0]) {
                $fornitoriCambiatiIndex[] = $y;
                $nuovoFornitore = new Fornitore($textboxMatrixFornitore[$y][0], $textboxMatrixFornitore[$y][1], $textboxMatrixFornitore[$y][2], $textboxMatrixFornitore[$y][3], $textboxMatrixFornitore[$y][4], $textboxMatrixFornitore[$y][5], $textboxMatrixFornitore[$y][6], $textboxMatrixFornitore[$y][7]);
                $fornitoriCambiati[] = $nuovoFornitore;
                echo $fornitori[$y]->getNickname().', ';
            } 
            
            elseif ($fornitori[$y]->getNome_fornitore() != $textboxMatrixFornitore[$y][1]) {
                $fornitoriCambiatiIndex[] = $y;
                $nuovoFornitore = new Fornitore($textboxMatrixFornitore[$y][0], $textboxMatrixFornitore[$y][1], $textboxMatrixFornitore[$y][2], $textboxMatrixFornitore[$y][3], $textboxMatrixFornitore[$y][4], $textboxMatrixFornitore[$y][5], $textboxMatrixFornitore[$y][6], $textboxMatrixFornitore[$y][7]);
                $fornitoriCambiati[] = $nuovoFornitore;
                echo $fornitori[$y]->getNickname().', ';
               
            } 
            elseif ($fornitori[$y]->getNickname() != $textboxMatrixFornitore[$y][2]) {
                $fornitoriCambiatiIndex[] = $y;
                $nuovoFornitore = new Fornitore($textboxMatrixFornitore[$y][0], $textboxMatrixFornitore[$y][1], $textboxMatrixFornitore[$y][2], $textboxMatrixFornitore[$y][3], $textboxMatrixFornitore[$y][4], $textboxMatrixFornitore[$y][5], $textboxMatrixFornitore[$y][6], $textboxMatrixFornitore[$y][7]);
                $fornitoriCambiati[] = $nuovoFornitore;
                echo $fornitori[$y]->getNickname().', ';
            }
            elseif ($fornitori[$y]->getPassword() != $textboxMatrixFornitore[$y][3]) {
                $fornitoriCambiatiIndex[] = $y;
                $nuovoFornitore = new Fornitore($textboxMatrixFornitore[$y][0], $textboxMatrixFornitore[$y][1], $textboxMatrixFornitore[$y][2], $textboxMatrixFornitore[$y][3], $textboxMatrixFornitore[$y][4], $textboxMatrixFornitore[$y][5], $textboxMatrixFornitore[$y][6], $textboxMatrixFornitore[$y][7]);
                $fornitoriCambiati[] = $nuovoFornitore;
                echo $fornitori[$y]->getNickname().', ';
                
            } 
            elseif ($fornitori[$y]->getEmail() != $textboxMatrixFornitore[$y][4]) {
                $fornitoriCambiatiIndex[] = $y;
                $nuovoFornitore = new Fornitore($textboxMatrixFornitore[$y][0], $textboxMatrixFornitore[$y][1], $textboxMatrixFornitore[$y][2], $textboxMatrixFornitore[$y][3], $textboxMatrixFornitore[$y][4], $textboxMatrixFornitore[$y][5], $textboxMatrixFornitore[$y][6], $textboxMatrixFornitore[$y][7]);
                $fornitoriCambiati[] = $nuovoFornitore;
                echo $fornitori[$y]->getNickname().', ';
            }
            elseif ($fornitori[$y]->getIndirizzo() != $textboxMatrixFornitore[$y][5]) {
                $fornitoriCambiatiIndex[] = $y;
                $nuovoFornitore = new Fornitore($textboxMatrixFornitore[$y][0], $textboxMatrixFornitore[$y][1], $textboxMatrixFornitore[$y][2], $textboxMatrixFornitore[$y][3], $textboxMatrixFornitore[$y][4], $textboxMatrixFornitore[$y][5], $textboxMatrixFornitore[$y][6], $textboxMatrixFornitore[$y][7]);
                $fornitoriCambiati[] = $nuovoFornitore;
                echo $fornitori[$y]->getNickname().', ';
            }
            elseif ($fornitori[$y]->getPaese() != $textboxMatrixFornitore[$y][6]) {
                $fornitoriCambiatiIndex[] = $y;
                $nuovoFornitore = new Fornitore($textboxMatrixFornitore[$y][0], $textboxMatrixFornitore[$y][1], $textboxMatrixFornitore[$y][2], $textboxMatrixFornitore[$y][3], $textboxMatrixFornitore[$y][4], $textboxMatrixFornitore[$y][5], $textboxMatrixFornitore[$y][6], $textboxMatrixFornitore[$y][7]);
                $fornitoriCambiati[] = $nuovoFornitore;
                echo $fornitori[$y]->getNickname().', ';
            }
            elseif ($fornitori[$y]->getTelefono() != $textboxMatrixFornitore[$y][7]) {
                $fornitoriCambiatiIndex[] = $y;
                $nuovoFornitore = new Fornitore($textboxMatrixFornitore[$y][0], $textboxMatrixFornitore[$y][1], $textboxMatrixFornitore[$y][2], $textboxMatrixFornitore[$y][3], $textboxMatrixFornitore[$y][4], $textboxMatrixFornitore[$y][5], $textboxMatrixFornitore[$y][6], $textboxMatrixFornitore[$y][7]);
                $fornitoriCambiati[] = $nuovoFornitore;
                echo $fornitori[$y]->getNickname().', ';
            }
            

        }
        
        
        $z = $y;
        for ($y = 0; $y < $z; $y ++) {
            
            for ($x = 0; $x < 8; $x ++) {
                if($x != 0){
                    echo '<td><input type="text" name="cod_fornitore_m[' . $y . '][' . $x . ']" value="' . $textboxMatrixFornitore[$y][$x] . '"></td>'; // chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni fornitore
                    echo "\n";
                }
                else{
                    echo '<td><input type="text" name="cod_fornitore_m[' . $y . '][' . $x . ']" value="' . $textboxMatrixFornitore[$y][$x] . '" style="background-color:LightGray" readonly></td>'; // chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni fornitore
                    echo "\n";
                }
            }
            echo "</tr>\n";
            

        }
        if (! isset($_POST["YESfornitore"])) {
            echo '<input type="submit" name="YESfornitore" value="YES" />';
            echo "\n";
            echo '<input type="submit" name="NOfornitore" value="NO" />';
        }
        
        if (! isset($_SESSION['fornCamb'])) {
            $_SESSION['fornCamb'] = $fornitoriCambiati;

        }
        
        if (! isset($_SESSION['fornitoriCambiatiIndex'])) {
            $_SESSION['fornitoriCambiatiIndex'] = $fornitoriCambiatiIndex;
        }
        if (! isset($_SESSION['fornitori'])) {
            $_SESSION['fornitori'] = $fornitori;
            $fornitori = null;
        }
        
       
    }
    
    if (isset($_POST["YESfornitore"])) { // conferma di modifica

        $fornitoriCambiati = $_SESSION['fornCamb'];
        
        $fornitoriCambiatiIndex = $_SESSION['fornitoriCambiatiIndex'];
        $fornitori = $_SESSION['fornitori'];
        

        
        for ($x = 0; $x < count($fornitoriCambiati); $x ++) {
            
            // query per ogni riga cambiata
            
            $codice_forn = $fornitoriCambiati[$x]->getCod_fornitore();;
            $nome_forn = $fornitoriCambiati[$x]->getNome_fornitore();;
            $nickname_forn = $fornitoriCambiati[$x]->getNickname();
            $password_forn = $fornitoriCambiati[$x]->getPassword();
            $email_forn = $fornitoriCambiati[$x]->getEmail();
            $indirizzo_forn = $fornitoriCambiati[$x]->getIndirizzo();
            $paese_forn = $fornitoriCambiati[$x]->getPaese();
            $telefono_forn = $fornitoriCambiati[$x]->getTelefono();
            //non è possibile modificare il codice fornitore, altrimenti si violerebbe l'integrità referenziale del database con le fatture
            if(!$queryCambiamentoFornitore = $mysqli->query("UPDATE Fornitore SET Nome_Ditta = '$nome_forn', Nickname = '$nickname_forn', Password = '$password_forn', Email = '$email_forn', Indirizzo = '$indirizzo_forn', Paese = '$paese_forn', Telefono = '$telefono_forn' WHERE Fornitore.Cod_Fornitore = '$codice_forn'")){
                die($mysqli->error);
            }
            echo"<p>Modifica avvenuta correttamente</p>";
            
            echo"<pre>";
            print_r($fornitori[$fornitoriCambiatiIndex[$x]]->getCod_fornitore());
            echo"</pre>";
            
            
            
            // il codice della chiave è uguale all'indice fornitori changed
        }

        
        $_SESSION['fornCamb'] = null; // azzero le variabili di sessione
        $_SESSION['fornitoriCambiatiIndex'] = null;
        $_SESSION['fornitori'] = null;
    }
    
    ?>
            </table>

		<input type="submit" name="modificaFornitore" value="modifica" />
		<hr />

        <?php

        if (isset($_POST["elimina"])) {
            $elimina = $_POST["eliminaCodice"];
            if($_POST['utenteOfornitore'] == "Utente"){
                $queryElimina = $mysqli->query("DELETE FROM Utenti WHERE Utenti.Cod_Cliente = $elimina");
            }
            elseif($_POST['utenteOfornitore'] == "Fornitore"){
                $queryElimina = $mysqli->query("DELETE FROM Fornitore WHERE Fornitore.Cod_Fornitore = $elimina");
                
            }
            
        }
        
        ?>
        <br>

		<center>
			<p>Elimina un Utente o Fornitore</p>
		</center>

		Codice identificativo:<input type="text" name="eliminaCodice" value="">
		<select name="utenteOfornitore">
			<option>Utente</option>
			<option>Fornitore</option>
		</select> <input type="submit" name="elimina" value="Elimina" />
		<hr />
		<input type="submit" name="logout" value="logout" />

	</form>



</body>
</html>