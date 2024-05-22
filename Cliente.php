<?php

class Cliente
{
    
    private $cod_cliente;
    private $nome_cliente;
    private $cognome_cliente;
    private $nickname;
    private $password;
    private $amministratore;
    private $email;
    private $indirizzo;
    private $paese;
    private $telefono;
    
    
    public function __construct ( $cod_cliente, $nome_cliente, $cognome_cliente, $nickname, $password, $amministratore, $email, $indirizzo, $paese, $telefono){

            $this->cod_cliente=$cod_cliente;
            $this->nome_cliente=$nome_cliente;
            $this->cognome_cliente=$cognome_cliente;
            $this->nickname=$nickname;
            $this->password=$password;
            $this->amministratore=$amministratore;
            $this->email=$email;
            $this->indirizzo=$indirizzo;
            $this->paese=$paese;
            $this->telefono=$telefono;
            
    }
    
    
    /**
     * @return mixed
     */
    public function getCod_cliente()
    {
        return $this->cod_cliente;
    }

    /**
     * @return mixed
     */
    public function getNome_cliente()
    {
        return $this->nome_cliente;
    }

    /**
     * @return mixed
     */
    public function getCognome_cliente()
    {
        return $this->cognome_cliente;
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
    public function getAmministratore()
    {
        return $this->amministratore;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getIndirizzo()
    {
        return $this->indirizzo;
    }

    /**
     * @return mixed
     */
    public function getPaese()
    {
        return $this->paese;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $cod_cliente
     */
    public function setCod_cliente($cod_cliente)
    {
        $this->cod_cliente = $cod_cliente;
    }

    /**
     * @param mixed $nome_cliente
     */
    public function setNome_cliente($nome_cliente)
    {
        $this->nome_cliente = $nome_cliente;
    }

    /**
     * @param mixed $cognome_cliente
     */
    public function setCognome_cliente($cognome_cliente)
    {
        $this->cognome_cliente = $cognome_cliente;
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
     * @param mixed $amministratore
     */
    public function setAmministratore($amministratore)
    {
        $this->amministratore = $amministratore;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $indirizzo
     */
    public function setIndirizzo($indirizzo)
    {
        $this->indirizzo = $indirizzo;
    }

    /**
     * @param mixed $paese
     */
    public function setPaese($paese)
    {
        $this->paese = $paese;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    
        
}

