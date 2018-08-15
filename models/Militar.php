<?php

namespace asc;

include __DIR__ . "/OM.php";
include __DIR__ . "/Posto.php";

class Militar
{

    private $cpf;
    private $fname;
    private $lname;
    private $telefone;
    private $email;
    private $OM;
    private $posto;

    public function __construct($cpf, $fname, $lname, $OM, $posto)
    {
        $this->cpf = $cpf;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->OM = $OM;
        $this->posto = $posto;
        $this->email = null;
        $this->telefone = null;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setTelefone($telefone): void
    {
        $this->telefone = $telefone;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function getFname()
    {
        return $this->fname;
    }

    public function getLname()
    {
        return $this->lname;
    }

    public function getOM()
    {
        return $this->OM;
    }

    public function getPosto()
    {
        return $this->posto;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function getNome()
    {
        return $this->fname . ' ' . $this->lname;
    }

}