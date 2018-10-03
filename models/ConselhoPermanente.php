<?php
/**
 * Created by PhpStorm.
 * User: alekrabbe
 * Date: 20/09/18
 * Time: 09:53
 */

namespace asc;


class ConselhoPermanente extends Conselho
{

    private $trimestre;
    private $ano;

    public function __construct($nome, $sigla, $trimestre, $ano)
    {
        parent::__construct($nome, $sigla);
        $this->trimestre = $trimestre;
        $this->tipo = 3;
        $this->ano = $ano;
    }

    public function getTrimestre()
    {
        return $this->trimestre;
    }

    public function getAno()
    {
        return $this->ano;
    }

}