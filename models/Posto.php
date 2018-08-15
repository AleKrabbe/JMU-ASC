<?php

namespace asc;

class Posto
{

    private $id;
    private $nome;
    private $sigla;
    private $rank;
    private $forca_armada;
    private $key;
    private $cipherId;

    public function __construct($id, $nome, $sigla, $rank, $forca_armada)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->sigla = $sigla;
        $this->rank = $rank;
        $this->forca_armada = $forca_armada;
        $this->key = $_SESSION['key'];
        $this->cipherId = safeEncrypt($this->id, $this->key);

    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function getForcaArmada()
    {
        return $this->forca_armada;
    }

    public function getIdEncrypted()
    {
        return $this->cipherId;
    }

    public function getSigla()
    {
        return $this->sigla;
    }

}