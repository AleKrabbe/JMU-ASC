<?php
namespace asc{

    include __DIR__ . "/FA.php";

    class OM
    {
        private $id;
        private $nome;
        private $sigla;
        private $forca_armada;
        private $key;
        private $cipherId;

        private $nome_comandante;
        private $vinculo;
        private $telefone;
        private $fax;
        private $email;
        private $cidade;

        public function __construct($nome, $sigla, $forca_armada)
        {
            $this->nome = $nome;
            $this->sigla = $sigla;
            $this->forca_armada = $forca_armada;
            $this->key = $_SESSION['key'];
        }

        public function setId($id): void
        {
            $this->id = $id;
            $this->cipherId = safeEncrypt($this->id, $this->key);
        }

        public function setNomeComandante($nome_comandante): void
        {
            $this->nome_comandante = $nome_comandante;
        }

        public function setVinculo($vinculo): void
        {
            $this->vinculo = $vinculo;
        }

        public function setTelefone($telefone): void
        {
            $this->telefone = $telefone;
        }

        public function setFax($fax): void
        {
            $this->fax = $fax;
        }

        public function setEmail($email): void
        {
            $this->email = $email;
        }

        public function setCidade($cidade): void
        {
            $this->cidade = $cidade;
        }

        public function getIdEncrypted()
        {
            return $this->cipherId;
        }

        public function getNome()
        {
            return $this->nome;
        }

        public function getSigla()
        {
            return $this->sigla;
        }

        public function getForcaArmada()
        {
            return $this->forca_armada;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getTelefone()
        {
            return $this->telefone;
        }

        public function getFax()
        {
            return $this->fax;
        }

        public function getCidade()
        {
            return $this->cidade;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function getNomeComandante()
        {
            return $this->nome_comandante;
        }

        public function getVinculo()
        {
            return $this->vinculo;
        }

    }
}
