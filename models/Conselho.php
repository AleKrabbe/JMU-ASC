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

    protected $id_nome_sigla;
    protected $nome;
    protected $sigla;
    protected $militares;

    public function __construct($nome, $sigla, $militares)
    {
        $this->nome = $nome;
        $this->sigla = $sigla;
        $this->militares = $militares;
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

    public function setIdNomeSigla($id_nome_sigla): void
    {
        $this->id_nome_sigla = $id_nome_sigla;
    }

}