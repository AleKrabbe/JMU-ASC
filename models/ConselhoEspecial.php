<?php
/**
 * Created by PhpStorm.
 * User: alekrabbe
 * Date: 20/09/18
 * Time: 09:53
 */

namespace asc;


class ConselhoEspecial extends Conselho
{

    private $processo;

    public function __construct($nome, $sigla, $processo)
    {
        parent::__construct($nome, $sigla);
        $this->processo = $processo;
        $this->tipo = 4;
    }

    public function getProcesso()
    {
        return $this->processo;
    }

}