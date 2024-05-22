<?php
include 'connessione.php';
//require_once 'sessioni.php';
session_start();
if (!isset($_SESSION["nickname"])){
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

if (isset($_POST["logout"])){
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])){
        setcookie(session_name(),'',time()-50000);
    }
    
    session_destroy();
    
    header("Location: index.php");
    exit;
}

$categoria = $_POST['categoria'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];

$categoriaA = $_POST['categoriaA'];
$fromDateA = $_POST['fromDateA'];
$toDateA = $_POST['toDateA'];

if($categoria != 'Tutte le categorie'){
    $query = $mysqli->query("SELECT Fattura_Al_Cliente.Data_Ordine, Fattura_Al_Cliente.Cod_Cliente, Utenti.Nickname, Fattura_Al_Cliente.ID_Fattura, Campionario_Prodotti.Prezzo_Totale
    FROM Fattura_Al_Cliente
    JOIN Utenti on Utenti.Cod_Cliente = Fattura_Al_Cliente.Cod_Cliente
    JOIN Campionario_Prodotti on Campionario_Prodotti.ID_Fattura = Fattura_Al_Cliente.ID_Fattura
    JOIN Prodotto on Prodotto.Cod_Prodotto = Campionario_Prodotti.Cod_Prodotto
    JOIN Categoria_Prodotti on Categoria_Prodotti.Cod_Categoria = Prodotto.Cod_Categoria
    WHERE (Fattura_Al_Cliente.Data_Ordine between '$fromDate' and '$toDate') AND Categoria_Prodotti.Nome = '$categoria'");
}
else{
    $query = $mysqli->query("SELECT Fattura_Al_Cliente.Data_Ordine, Fattura_Al_Cliente.Cod_Cliente, Utenti.Nickname, Fattura_Al_Cliente.ID_Fattura, Campionario_Prodotti.Prezzo_Totale
    FROM Fattura_Al_Cliente
    JOIN Utenti on Utenti.Cod_Cliente = Fattura_Al_Cliente.Cod_Cliente
    JOIN Campionario_Prodotti on Campionario_Prodotti.ID_Fattura = Fattura_Al_Cliente.ID_Fattura
    WHERE Fattura_Al_Cliente.Data_Ordine between '$fromDate' and '$toDate'");
}


if($categoriaA != 'Tutte le categorie'){
    $query2 = $mysqli->query("SELECT Fattura_Dal_Fornitore.Data_Ordine, Fornitore.Cod_Fornitore, Fornitore.Nickname, Fattura_Dal_Fornitore.ID_Fattura, Fattura_Dal_Fornitore.Totale 
FROM Fattura_Dal_Fornitore
JOIN Fornitore on Fornitore.Cod_Fornitore = Fattura_Dal_Fornitore.Cod_Fornitore
JOIN Fornitura on Fornitura.ID_Fattura = Fattura_Dal_Fornitore.ID_Fattura
JOIN Prodotto on Prodotto.Cod_Prodotto = Fornitura.Cod_Prodotto
JOIN Categoria_Prodotti on Categoria_Prodotti.Cod_Categoria = Prodotto.Cod_Categoria
WHERE (Fattura_Dal_Fornitore.Data_Ordine between '$fromDateA' and '$toDateA') AND Categoria_Prodotti.Nome = '$categoriaA'");
}
else{
    $query2 = $mysqli->query("SELECT Fattura_Dal_Fornitore.Data_Ordine, Fornitore.Cod_Fornitore, Fornitore.Nickname, Fattura_Dal_Fornitore.ID_Fattura, Fattura_Dal_Fornitore.Totale
FROM Fattura_Dal_Fornitore
JOIN Fornitore on Fornitore.Cod_Fornitore = Fattura_Dal_Fornitore.Cod_Fornitore
JOIN Fornitura on Fornitura.ID_Fattura = Fattura_Dal_Fornitore.ID_Fattura
JOIN Prodotto on Prodotto.Cod_Prodotto = Fornitura.Cod_Prodotto
JOIN Categoria_Prodotti on Categoria_Prodotti.Cod_Categoria = Prodotto.Cod_Categoria
WHERE (Fattura_Dal_Fornitore.Data_Ordine between '$fromDateA' and '$toDateA')");
}



$queryCategorie = $mysqli->query("SELECT Nome FROM Categoria_Prodotti WHERE 1");
$queryCategorie2 = $mysqli->query("SELECT Nome FROM Categoria_Prodotti WHERE 1");


?>
<html>
	<body>
        <form action="visualizzafatture.php" method="POST">
        	<center><h1>Visualizza le fatture:</h1></center>
        
            <a href="gestioneprodotti.php">Gestisci i prodotti</a>
            <br>
	        <a href="gestioneclientiefornitori.php">Gestisci clienti e fornitori</a>
        	<hr />
        	<center><h2>Visualizza le fatture di vendita</h2></center>
        	
            <label for="FromDate">Enter a Date:</label>
            <br>
    
            da: <input type="date" name="fromDate" value="2020-01-01" />      
            a: <input type="date" name="toDate" value="2021-01-01" />

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
            
            <input type="submit" name="cerca_vendite" value="Cerca vendite" />
            <br>
                             
            <hr />
            
            
            <table style="width:100%" border="1">
              <tr>
                <th>Data</th><th>Codice Cliente</th> <th>Nickname</th> <th>Codice Fattura</th> <th>Importo</th>
              </tr>
              <?php
              if (isset($_POST["cerca_vendite"]) || $_SESSION['cerca_vendite'] == true){
                  
                  if (!isset($_SESSION['cerca_vendite'])) {
                      $_SESSION['cerca_vendite'] = true;
                  }
                  
                  $total = 0;
                  while ($row = $query->fetch_row()) {
                      echo '<tr>'.'<td>'.$row[0].'</td>'.'<td>'.$row[1].'</td>'.'<td>'.$row[2].'</td>'.'<td>'.$row[3].'</td>'.'<td>'.$row[4].'</td>'.'</tr>';
                      $total+=$row[4];
                  }
                  if(!($query->num_rows)>0){
                    echo "Non ci sono fatture in queste date.";
                  }
              }
    
              echo '<tr><td></td><td></td><td></td><td></td><th>Totale</th></tr>';
      
              echo '<tr><td></td><td></td><td></td><td></td><td>'.$total.'</td></tr>';
              
              
              ?>
             
            </table>            
            <br>  
            <hr />
            <center><h2>Visualizza le fatture di acquisto</h2></center>
            
            da: <input type="date" name="fromDateA" value="2020-01-01" />      
            a: <input type="date" name="toDateA" value="2021-01-01" />

            <select name="categoriaA"> 
            <option>Tutte le categorie</option>
            <?php
                
            while ($row = $queryCategorie2->fetch_row()) {
                if($_POST['categoriaA'] == $row[0]){
                    echo '<option selected="selected">'.$row[0].'</option>';
                }
                else{
                    echo '<option>'.$row[0].'</option>';
                }
                
            }
                
            ?>
            
            </select>
            
            <input type="submit" name="cerca_acquisti" value="Cerca acquisti" />
            <br>
                             
            <hr />
            
            
            <table style="width:100%" border="1">
              <tr>
                <th>Data</th><th>Codice Fornitore</th> <th>Nickname</th> <th>Codice Fattura</th> <th>Importo</th>
              </tr>
              <?php
              if (isset($_POST["cerca_acquisti"]) || $_SESSION['cerca_acquisti'] == true){
                  
                  if (!isset($_SESSION['cerca_acquisti'])) {
                      $_SESSION['cerca_acquisti'] = true;
                  }
                  
                  $total = 0;
                  while ($row = $query2->fetch_row()) {
                      echo '<tr>'.'<td>'.$row[0].'</td>'.'<td>'.$row[1].'</td>'.'<td>'.$row[2].'</td>'.'<td>'.$row[3].'</td>'.'<td>'.$row[4].'</td>'.'</tr>';
                      $total+=$row[4];
                  }
                  
                  if(!($query2->num_rows)>0){
                      echo "Non ci sono fatture in queste date.";
                  }
              }
         
              echo '<tr><td></td><td></td><td></td><td></td><th>Totale</th></tr>';
      
              echo '<tr><td></td><td></td><td></td><td></td><td>'.$total.'</td></tr>';
              
              
              ?>
             
            </table>            
            <br>  
            <hr />
                      
            <input type="submit" name="logout" value="logout" />
            
        </form>
        
		
	</body>


</html>
