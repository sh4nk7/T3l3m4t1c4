<?php
include 'connessione.php';
include 'Prodotto.php';
session_start();
if (!isset($_SESSION["nickname"])){ //se risulta già un cookie loggato, entra
    header("location: index.php");
    exit;
}
else if ($_SESSION["nickname"]!='root' && $_SESSION["fornitore"] == false){
    header("location: InVendita.php");
    exit();
}
else if ($_SESSION["nickname"]!='root' && $_SESSION["fornitore"] == true){
    header("location: Fornitore.php");
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

/*
if (!isset($_POST['cerca']) || $_POST['categoria']=="Tutte le categorie"){
    $query = $mysqli->query("SELECT * FROM Prodotto WHERE 1");
}
else{
*/
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

$num_rows = $query->num_rows;
$products = array();
$product = null;

$productsChanged = array();

/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

?>
<html>
	<body>
	
		<center><h2>Gestione dei prodotti</h2></center>
	    <a href="visualizzafatture.php">Visualizza le fatture</a>
	    <br>
	    <a href="gestioneclientiefornitori.php">Gestisci clienti e fornitori</a>
	    <hr />
	 	<form action="gestioneprodotti.php" method="POST">
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
            	<th>Codice Prodotto</th><th>Codice categoria</th> <th>Nome</th> <th>Prezzo vendita</th> <th>Scorte magazzino</th>
    		</tr>
    		<?php
    		$y=0;
    		
    		while ($row = $query->fetch_row()) {
    		    $product = new Prodotto($row[0], $row[1], $row[2], $row[3], $row[4]);
    		    $products[] = $product;
    		    /*
    		     creo un oggetto per ogni prodotto, mi servirà per verificare eventuali cambiamenti nei textbox
    		     in alternativa, avrei dovuto utilizzare javascript per controllare i cambiamenti nei textbox
    		     */
    		    echo"<tr>\n";
    		    if(!isset($_POST["modifica"]) && !isset($_POST["YES"]))
    		    {
    		        
        		    for($x=0;$x<5;$x++){
        		        echo '<td><input type="text" name="cod_prodotto['.$y.']['.$x.']" value="'.$row[$x].'"></td>';//chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni prodotto
        		        echo "\n";
        		    }
        		    echo"</tr>\n";
    		    }
    		    
    		    $y++;
    		    
    		}
    		if(isset($_POST["modifica"])|| isset($_POST["YES"])){
    		    
    		    echo "<p>sei sicuro di voler modificare i seguenti prodotti? </p>";
    		    //echo'<h1>'.$products[$num_rows-1]->getNome_prodotto().'</h1>';
    		    
    		    $textboxMatrix = $_POST[cod_prodotto];

    		    
    		    for($y = 0; $y<$num_rows; $y++){//per comodità creo una lista di oggetti per ogni riga cambiata
    		        
    		        if($products[$y]->getCod_prodotto() != $textboxMatrix[$y][0]){
    		            $productsChangedIndex[]=$y;
    		            $product = new Prodotto($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4]);
    		            $productsChanged[]=$product;
    		            echo $products[$y]->getNome_prodotto().', ';
    		        }
    		        elseif($products[$y]->getCod_categoria() != $textboxMatrix[$y][1]){
    		            $productsChangedIndex[]=$y;
    		            $product = new Prodotto($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4]);
    		            $productsChanged[]=$product;
    		            echo $products[$y]->getNome_prodotto().', ';
    		        }
    		        elseif($products[$y]->getNome_prodotto() != $textboxMatrix[$y][2]){
    		            $productsChangedIndex[]=$y;
    		            $product = new Prodotto($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4]);
    		            $productsChanged[]=$product;
    		            echo $products[$y]->getNome_prodotto().', ';
    		        }
    		        elseif($products[$y]->getPrezzo_vendita() != $textboxMatrix[$y][3]){
    		            $productsChangedIndex[]=$y;
    		            $product = new Prodotto($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4]);
    		            $productsChanged[]=$product;
    		            echo $products[$y]->getNome_prodotto().', ';
    		        }
    		        elseif($products[$y]->getScorte_magazzino() != $textboxMatrix[$y][4]){
    		            $productsChangedIndex[]=$y;
    		            $product = new Prodotto($textboxMatrix[$y][0], $textboxMatrix[$y][1], $textboxMatrix[$y][2], $textboxMatrix[$y][3], $textboxMatrix[$y][4]);
    		            $productsChanged[]=$product;
    		            echo $products[$y]->getNome_prodotto().', ';
    		        }
    		        
    		        
    		    }
    		    $z=$y;
    		    for ($y=0;$y<$z;$y++){
    		        
        		    for($x=0;$x<5;$x++){
        		        echo '<td><input type="text" name="cod_prodotto['.$y.']['.$x.']" value="'.$textboxMatrix[$y][$x].'"></td>';//chiamo i textbox con i loro rispettivi nomi a matrice, e ne salvo ogni prodotto
        		        echo "\n";
        		    }
    		      echo"</tr>\n";
    		      
    		    }
    		    if(!isset($_POST["YES"])){
        		    echo'<input type="submit" name="YES" value="YES" />';
        		    echo"\n";
        		    echo'<input type="submit" name="NO" value="NO" />';
    		    }
    		       		    
    		    if(!isset($_SESSION['prodottiCambiati'])){
    		        $_SESSION['prodottiCambiati']=$productsChanged;
    		    }
    		    if(!isset($_SESSION['productsChangedIndex'])){
    		        $_SESSION['productsChangedIndex']=$productsChangedIndex;
    		    }
    		    if(!isset($_SESSION['products'])){
    		        $_SESSION['products']=$products;
    		        $products = null;
    		        
    		       
    		    }
    		}
    		
    		if(isset($_POST["YES"])){//conferma di modifica
    		    //$queryModifica = $mysqli->query("SELECT Nome FROM Categoria_Prodotti WHERE 1");
    		    $productsChanged = $_SESSION['prodottiCambiati'];
    		    $productsChangedIndex = $_SESSION['productsChangedIndex'];
    		    $products = $_SESSION['products'];
    		    echo"<p>Modifica avvenuta correttamente</p>";
    		    
    		    for($x=0;$x<count($productsChanged);$x++){
    		        
    		        //query per ogni riga cambiata
    		        
    		        $codiceP = $productsChanged[$x]->getCod_prodotto();
    		        $codiceC = $productsChanged[$x]->getCod_categoria();
    		        $nomeP = $productsChanged[$x]->getNome_prodotto();
    		        $prezzoV = $productsChanged[$x]->getPrezzo_vendita();
    		        $scorteM = $productsChanged[$x]->getScorte_magazzino();
    		        $vecchioIndex = $products[$productsChangedIndex[$x]]->getCod_prodotto();//eventualmente cambiato
    		        
    		        $queryCambiamento = $mysqli->query("UPDATE Prodotto SET Cod_Prodotto = '$codiceP',Cod_Categoria = '$codiceC' , Nome = '$nomeP', Prezzo_Vendita = '$prezzoV', Scorte_Magazzino = '$scorteM' WHERE Prodotto.Cod_Prodotto = '$vecchioIndex'");
    		        
    		        echo"<pre>";
    		        print_r($products[$productsChangedIndex[$x]]->getCod_prodotto());
    		        echo"</pre>";
    		        
    		         
    		        //il codice della chiave è uguale all'indice products changed 
    		    }
    		    /*
    		    echo "<pre>";
    		    print_r($productsChanged);
    		    echo"<p>".count($productsChanged)."</p>";
    		    echo "</pre>";
    		    */
    		    
    		    $_SESSION['prodottiCambiati']=null;//azzero le variabili di sessione
    		    $_SESSION['productsChangedIndex']=null;
    		    $_SESSION['products'] = null;
    		}
            ?>
            </table>
           
            <input type="submit" name="modifica" value="modifica" />
            <hr />
            
        
        <?php 
        
        if (isset($_POST["elimina"])){
            $elimina = $_POST["eliminaCodice"];
            $query = $mysqli->query("DELETE FROM Prodotto WHERE Prodotto.Cod_Prodotto = $elimina");
            
        }
        if (isset($_POST["aggiungi"])){
            $nomeProdotto = $_POST['nomeProdotto'];
            $prezzoVendita = $_POST['prezzoVendita'];
            $scorteMagazzino = $_POST['scorteMagazzino'];
            $codProdotto = $_POST['codProdotto'];
            $categoriaAggiungi = $_POST['categoriaAggiungi'];
            
            $queryCategorieAggiungi = $mysqli->query("SELECT Cod_Categoria FROM Categoria_Prodotti WHERE Nome = '$categoriaAggiungi'");
            $codCat = $queryCategorieAggiungi->fetch_row();
            $codCat[0];
            
            $queryAggiungi = $mysqli->query("INSERT INTO Prodotto (Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino) VALUES ('$codProdotto', '$codCat[0]', '$nomeProdotto', '$prezzoVendita', '$scorteMagazzino');");
                
                
            
          
            
        }
      
        ?>
        <br>
        <center><p>Inserisci un nuovo prodotto</p></center>
        <table>
        <tr><td>Codice prodotto:</td><td><input type="text" name="codProdotto" value=""></td></tr>
        <tr><td>Categoria:</td><td>
		<select name="categoriaAggiungi"> 
            <?php
            $queryCategorieAggiungi = $mysqli->query("SELECT Nome FROM Categoria_Prodotti WHERE 1");
            while ($rowAggiungi = $queryCategorieAggiungi->fetch_row()) {
                    echo '<option>'.$rowAggiungi[0].'</option>';               
            }
                
            ?></td></tr>
            
            </select>	
	
        <tr><td>Nome prodotto:</td><td><input type="text" name="nomeProdotto" value=""></td></tr>
		
        <tr><td>Prezzo vendita:</td><td><input type="text" name="prezzoVendita" value=""></td></tr>
		
        <tr><td>Scorte nel magazzino:</td><td><input type="text" name="scorteMagazzino" value=""></td></tr>
		</table>
        <input type="submit" name="aggiungi" value="Aggiungi prodotto" />
        
        <hr />
        <center><p>Elimina un prodotto</p></center>
        Codice del prodotto:<input type="text" name="eliminaCodice" value="">
        <input type="submit" name="elimina" value="Elimina prodotto" />
        <hr />
        <input type="submit" name="logout" value="logout" />
        
        </form>
              
              
		
	</body>
</html>