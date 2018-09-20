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

    public function __construct($nome, $sigla, $militares, $trimestre)
    {
        parent::__construct($nome, $sigla, $militares);
        $this->trimestre = $trimestre;
        $this->tipo = 3;
    }

    public function getTrimestre()
    {
        return $this->trimestre;
    }

}