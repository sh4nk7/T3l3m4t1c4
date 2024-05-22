<?php

class Prodotto
{
    private $cod_prodotto;
    private $cod_categoria;
    private $nome_prodotto;
    private $prezzo_vendita;
    private $scorte_magazzino;
    
    /*
     <tr>
     <td><input type="text" name="cod_prodotto" value="xxx"></td>
     <td><input type="text" name="cod_categoria" value="xxx"></td>
     <td><input type="text" name="nome_prodotto" value="xxx"></td>
     <td><input type="text" name="prezzo_vendita" value="xxx"></td>
     <th><input type="text" name="scorte_magazzino" value="xxx"></th>
     </tr>
     */
    
    public function __construct ( $cod_prodotto, $cod_categoria, $nome_prodotto, $prezzo_vendita, $scorte_magazzino ) {
        $this->cod_prodotto=$cod_prodotto;
        $this->cod_categoria=$cod_categoria;
        $this->nome_prodotto=$nome_prodotto;
        $this->prezzo_vendita=$prezzo_vendita;
        $this->scorte_magazzino=$scorte_magazzino;
    }
    
    /**
     * @return mixed
     */
    public function getCod_prodotto()
    {
        return $this->cod_prodotto;
    }

    /**
     * @return mixed
     */
    public function getCod_categoria()
    {
        return $this->cod_categoria;
    }

    /**
     * @return mixed
     */
    public function getNome_prodotto()
    {
        return $this->nome_prodotto;
    }

    /**
     * @return mixed
     */
    public function getPrezzo_vendita()
    {
        return $this->prezzo_vendita;
    }

    /**
     * @return mixed
     */
    public function getScorte_magazzino()
    {
        return $this->scorte_magazzino;
    }

    /**
     * @param mixed $cod_prodotto
     */
    public function setCod_prodotto($cod_prodotto)
    {
        $this->cod_prodotto = $cod_prodotto;
    }

    /**
     * @param mixed $cod_categoria
     */
    public function setCod_categoria($cod_categoria)
    {
        $this->cod_categoria = $cod_categoria;
    }

    /**
     * @param mixed $nome_prodotto
     */
    public function setNome_prodotto($nome_prodotto)
    {
        $this->nome_prodotto = $nome_prodotto;
    }

    /**
     * @param mixed $prezzo_vendita
     */
    public function setPrezzo_vendita($prezzo_vendita)
    {
        $this->prezzo_vendita = $prezzo_vendita;
    }

    /**
     * @param mixed $scorte_magazzino
     */
    public function setScorte_magazzino($scorte_magazzino)
    {
        $this->scorte_magazzino = $scorte_magazzino;
    }

}
