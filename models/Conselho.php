<?php
/**
 * Created by PhpStorm.
 * User: alekrabbe
 * Date: 20/09/18
 * Time: 09:52
 */

namespace asc;


class Conselho
{

    protected $id_conselho;
    private $ciphered_id_conselho;
    protected $id_nome_sigla;
    protected $nome;
    protected $sigla;
    protected $militares;
    protected $tipo;
    protected $fa;
    protected $key;

    public function __construct($nome, $sigla)
    {
        $this->nome = $nome;
        $this->sigla = $sigla;
        $this->key = $_SESSION['key'];
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getSigla()
    {
        return $this->sigla;
    }

    public function getMilitares()
    {
        return $this->militares;
    }

    public function getIdNomeSigla()
    {
        return $this->id_nome_sigla;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setFa($fa): void
    {
        $this->fa = $fa;
    }

    public function getFa()
    {
        return $this->fa;
    }

    public function setIdNomeSigla($id_nome_sigla): void
    {
        $this->id_nome_sigla = $id_nome_sigla;
    }

    public function setMilitares($militares): void
    {
        $this->militares = $militares;
    }

    public function setIdConselho($id_conselho): void
    {
        $this->id_conselho = $id_conselho;
        $this->ciphered_id_conselho = safeEncrypt($this->id_conselho, $this->key);
    }

    public function getIdConselho()
    {
        return $this->id_conselho;
    }

    public function getCipheredIdConselho()
    {
        return $this->ciphered_id_conselho;
    }

}