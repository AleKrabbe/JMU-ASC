<?php

namespace asc;


class Cidade
{

    private $id;
    private $cipherId;
    private $nome;
    private $estado;

    public function __construct($id, $nome, $estado)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->estado = $estado;
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

    public function getEstado()
    {
        return $this->estado;
    }

}