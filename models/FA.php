<?php
namespace asc;

class FA
{

    private $id;
    private $cipherId;
    private $nome;
    private $sigla;

    public function __construct($id, $nome, $sigla)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->sigla = $sigla;
        $this->key = $_SESSION['key'];
        $this->cipherId = safeEncrypt($this->id, $this->key);
    }

    public function getIdEncrypted()
    {
        return $this->cipherId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

}