<?php

include 'connessione.php';

class Fornitore
{
    private $cod_fornitore;
    private $nome_fornitore;
    private $nickname;
    private $password;
    private $email;
	private $indirizzo;
	private $paese;
	private $telefono;
	
    
    /*
     <tr>
     <td><input type="text" name="cod_fornitore" value="xxx"></td>
     <td><input type="text" name="nome_fornitore" value="xxx"></td>
     <td><input type="text" name="nickname" value="xxx"></td>
     <td><input type="text" name="password" value="xxx"></td>
     <th><input type="text" name="email" value="xxx"></th>
	 <th><input type="text" name="indirizzo" value="xxx"></th>
	 <th><input type="text" name="paese" value="xxx"></th>
	 <th><input type="text" name="telefono" value="xxx"></th>
     </tr>
     */
    
    public function __construct ( $cod_fornitore, $nome_fornitore, $nickname, $password, $email, $indirizzo , $paese , $telefono ) {
        $this->cod_fornitore=$cod_fornitore;
        $this->nome_fornitore=$nome_fornitore;
        $this->nickname=$nickname;
        $this->password=$password;
        $this->email=$email;
		$this->indirizzo=$indirizzo;
		$this->paese=$paese;
		$this->telefono=$telefono;
    }
    
    /**
     * @return mixed
     */
    public function getCod_fornitore()
    {
        return $this->cod_fornitore;
    }

    /**
     * @return mixed
     */
    public function getNome_fornitore()
    {
        return $this->nome_fornitore;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
	
	public function getIndirizzo()
    {
        return $this->indirizzo;
    }
	
	public function getPaese()
    {
        return $this->paese;
    }
	
	public function getTelefono()
    {
        return $this->Telefono;
    }

    /**
     * @param mixed $cod_fornitore
     */
    public function setCod_fornitore($cod_fornitore)
    {
        $this->cod_fornitore = $cod_fornitore;
    }

    /**
     * @param mixed $nome_fornitore
     */
    public function setNome_fornitore($nome_fornitore)
    {
        $this->nome_fornitore = $nome_fornitore;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
	
	public function setIndirizzo($indirizzo)
    {
        $this->indirizzo = $indirizzo;
    }
	
	public function setPaese($paese)
    {
        $this->paese= $paese;
    }
	
	public function setTelefono($Telefono)
    {
        $this->telefono = $telefono;
    }

}


session_start();
if (! isset($_SESSION["nickname"])) { // se risulta già un cookie loggato, entra
    header("location: index.php");
    exit();
}
else if ($_SESSION["nickname"]!='root' && $_SESSION["fornitore"] == false){
    header("location: InVendita.php");
    exit();
}


if (isset($_POST["logout"])){
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])){
        setcookie(session_name(),'',time()-50000);
    }
    
    session_destroy();
    
    header("Location: index.php");
    exit;
}

$myNick = $_SESSION["nickname"];
$id_fattura = rand(1000000, 9999999);
$queryCod_fornitore = $mysqli->query("SELECT Cod_Fornitore FROM Fornitore WHERE Nickname = '$myNick'");
$cod_fornitore = $queryCod_fornitore->fetch_row();
$cod_fornitore[0];

if (isset($_POST["aggiungi"])){
    
    //$id_fattura
    //$cod_fornitore[0]
    $data = $_POST['data'];
    $quantita = $_POST['quantita'];
    $totale = $_POST['totale'];
    
    
    //qui ottengo il codice prodotto e la scorta di magazzino, necessaria per aggiornare successivamente il valore del prodotto rifornito
    $prodottoDaFornire = $_POST['prodottoDaFornire'];

    $queryCod_prodotto = $mysqli->query("SELECT Cod_Prodotto, Scorte_magazzino FROM Prodotto WHERE Nome = '$prodottoDaFornire'");
    $prodotto = $queryCod_prodotto->fetch_row();
    //$prodotto[0];//codice prodotto
    //$prodotto[1];//scorte magazzino
        
    if(!$mysqli->query("INSERT INTO Fattura_Dal_Fornitore (ID_Fattura, Cod_Fornitore, Data_Ordine, Totale) VALUES ('$id_fattura', '$cod_fornitore[0]', '$data', '$totale');")){
        die($mysqli->error);
    }
    if(!$mysqli->query("INSERT INTO Fornitura (ID_Fattura, Cod_Fornitore, Cod_Prodotto, Quantita) VALUES ('$id_fattura', '$cod_fornitore[0]', '$prodotto[0]', '$quantita');")){
        die($mysqli->error);
        
    }
    
    $nuovaQuantita = $prodotto[1] + $quantita;//la quantità viene aggiornata di conseguenza
    $queryAggiornaMagazzino = $mysqli->query("UPDATE Prodotto SET Scorte_Magazzino = '$nuovaQuantita' WHERE Prodotto.Cod_Prodotto = '$prodotto[0]'");
    
    
}

?>

<html>
<head></head>
<body>
<form action="Fornitore.php" method="POST">
  <center>
  <h1>Benvenuto <?php echo $_SESSION['nickname']?></h1> 
  <h3>qui potrai caricare le tue fatture</h3>
  
	<h4>Fattura:</h4>
	<table>


         <tr><td>Prodotto:</td><td>
		<select name="prodottoDaFornire"> 		
            <?php
            $queryCategorieAggiungi = $mysqli->query("SELECT Nome FROM Prodotto WHERE 1");
            while ($rowAggiungi = $queryCategorieAggiungi->fetch_row()) {
                    echo '<option>'.$rowAggiungi[0].'</option>';               
            }
                
            ?>
            
            </select>	
		<br></td></tr>
         <tr><td>Data ordine:</td><td><input type="date" name="data" value="2021-01-01"></td></tr>   
         <tr><td>Quantità</td><td><input type="text" name="quantita"></td></tr>
         <tr><td>Totale:</td><td><input type="text" name="totale"></td></tr>
	</table>
    	<input type="submit" name="aggiungi" value="aggiungi fattura" />
	
	</center>
	<hr />
	
    <input type="submit" name="logout" value="logout" />
    </form>
    
</body>
</html>