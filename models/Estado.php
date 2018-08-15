<?php

namespace asc;


class Estado
{

    private $id;
    private $cipherId;
    private $nome;
    private $uf;

    public function __construct($id, $nome, $uf)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->uf = $uf;
        $this->key = $_SESSION['key'];
        $this->cipherId = safeEncrypt($this->id, $this->key);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCipherId(): string
    {
        return $this->cipherId;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getUf()
    {
        return $this->uf;
    }

}