<?php
namespace asc {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'PDO_Conn.php';
    include __DIR__ . "/../../models/Militar.php";
    include __DIR__ . "/../../models/Estado.php";
    include __DIR__ . "/../../models/Cidade.php";
    include __DIR__ . "/../../models/Conselho.php";
    include __DIR__ . "/../../models/ConselhoPermanente.php";
    include __DIR__ . "/../../models/ConselhoEspecial.php";
    include __DIR__ . "/../safeCrypto.php";

    class MySQL_DataMapper
    {

        public static $instance = null;

        private $pdo;
        private $OMs;
        private $FAs;
        private $postos;
        private $estados;
        private $cidades;
        private $militares;
        private $conselhoNomes;

        public function __construct()
        {
            $this->pdo = PDO_Conn::getInstance();

            if(!isset($_SESSION['key'])){
                try {
                    $_SESSION['key'] = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }

            $this->fetchAllFAs();
            $this->fetchAllPostos();
            $this->fetchAllOMs();
            $this->fetchAllEstados();
            $this->fetchAllCidades();
            $this->fetchAllMilitares();
            $this->completeOMInfo();
        }

        public static function getInstance() {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function fetchUserByUsername($username)
        {
            $query = "SELECT * FROM USUARIOS WHERE username = :username";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(":username", $username);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result;
        }

        public function fetchAllEstados()
        {
            $this->estados = array();
            $query = "SELECT * FROM stm_asc.ESTADO;";
            $result = $this->pdo->query($query);
            while ($row = $result->fetch()) {
                $estado = new Estado($row['id'], $row['nome'], $row['uf']);
                array_push($this->estados, $estado);
            }
        }

        public function fetchNomesConselho($tipo)
        {
            // tipo = 3 -> Conselho Permanente
            // tipo = 4 -> Conselho Especial
            $this->conselhoNomes = array();
            $query = "SELECT * FROM stm_asc.NOME_SIGLA WHERE tipo = :tipo;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(":tipo", $tipo);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                array_push($this->conselhoNomes, [safeEncrypt($row['id_nome_sigla'], $_SESSION['key']) , $row['nome'], $row['sigla']]);
            }
            return $this->conselhoNomes;
        }

        public function fetchNomeSiglaByIdEncrypted($idEncrypted)
        {
            $id = safeDecrypt($idEncrypted, $_SESSION['key']);
            $query = "SELECT * FROM stm_asc.NOME_SIGLA WHERE id_nome_sigla = :id;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $row = $stmt->fetch();
            $nome_sigla = ["nome" => $row['nome'], "sigla" => $row['sigla']];
            return $nome_sigla;
        }

        public function fetchAllMilitares()
        {
            $this->militares = array();
            $query = "SELECT * FROM stm_asc.MILITAR;";
            $result = $this->pdo->query($query);
            while ($row = $result->fetch()) {
                $OM = $this->getOMbyId($row['id_organizacao_militar']);
                $posto = $this->getPostoById($row['id_posto']);
                $militar = new Militar($row['cpf'], $row['fname'], $row['lname'], $OM, $posto);
                $militar->setEmail($row['email']);
                $militar->setTelefone($row['telefone']);
                array_push($this->militares, $militar);
            }
        }

        public function fetchAllOMs()
        {
            $this->OMs = array();
            $query = "SELECT SUBQUERY.id_organizacao_militar, SUBQUERY.sigla, SUBQUERY.nome, SUBQUERY.id_fa FROM (SELECT ORGANIZACAO_MILITAR.id_organizacao_militar, NOME_SIGLA.sigla, NOME_SIGLA.nome, FORCA_ARMADA.id_nome_sigla, FORCA_ARMADA.id_fa FROM ORGANIZACAO_MILITAR JOIN NOME_SIGLA ON ORGANIZACAO_MILITAR.id_nome_sigla=NOME_SIGLA.id_nome_sigla JOIN FORCA_ARMADA ON ORGANIZACAO_MILITAR.id_forca_armada=FORCA_ARMADA.id_fa) AS SUBQUERY JOIN NOME_SIGLA ON SUBQUERY.id_nome_sigla=NOME_SIGLA.id_nome_sigla;";
            $result = $this->pdo->query($query);
            while ($row = $result->fetch()){
                $FA = $this->getFAbyId($row['id_fa']);
                $OM = new OM($row['nome'], $row['sigla'], $FA);
                $OM->setId($row['id_organizacao_militar']);
                array_push($this->OMs, $OM);
            }
        }

        public function completeOMInfo() {
            $query = "SELECT * FROM stm_asc.ORGANIZACAO_MILITAR;";
            $result = $this->pdo->query($query);
            while ($row = $result->fetch()){
                $vinculo = $this->getOMbyId($row['vinculo']);
                $cidade = $this->getCidadeById($row['cidade']);
                $OM = $this->getOMbyId($row['id_organizacao_militar']);
                $OM->setVinculo($vinculo);
                $OM->setCidade($cidade);
                $OM->setTelefone($row['telefone']);
                $OM->setFax($row['fax']);
                $OM->setEmail($row['email']);
                $OM->setNomeComandante($row['nome_comandante']);
            }
        }

        public function fetchAllFAs()
        {
            $this->FAs = array();
            $query = "SELECT stm_asc.FORCA_ARMADA.id_fa, stm_asc.NOME_SIGLA.nome, stm_asc.NOME_SIGLA.sigla FROM stm_asc.FORCA_ARMADA JOIN stm_asc.NOME_SIGLA ON FORCA_ARMADA.id_nome_sigla = NOME_SIGLA.id_nome_sigla;";
            $result = $this->pdo->query($query);
            while ($row = $result->fetch()){
                $FA = new FA($row['id_fa'], $row['nome'], $row['sigla']);
                array_push($this->FAs, $FA);
            }
        }

        public function fetchAllPostos()
        {
            $this->postos = array();
            $query = "SELECT id_posto, NOME_SIGLA.nome, NOME_SIGLA.sigla, rank, id_forca_armada FROM stm_asc.POSTO JOIN NOME_SIGLA ON POSTO.id_nome_sigla = NOME_SIGLA.id_nome_sigla;";
            $result = $this->pdo->query($query);
            while ($row = $result->fetch()){
                $FA = $this->getFAbyId($row['id_forca_armada']);
                $posto = new Posto($row['id_posto'], $row['nome'], $row['sigla'], $row['rank'], $FA);
                array_push($this->postos, $posto);
            }
        }

        public function fetchAllCidades()
        {
            $this->cidades = array();
            $query = "SELECT * FROM stm_asc.CIDADE;";
            $result = $this->pdo->query($query);
            while ($row = $result->fetch()){
                $cidade = new Cidade($row['id'], $row['nome'], $this->getEstadoById($row['estado']));
                array_push($this->cidades, $cidade);
            }
        }

        public function loadCitiesInState($id){
            $decriptedId = safeDecrypt($id, $_SESSION['key']);
            $sub_array_cidades = array();

            foreach ($this->cidades as $cidade){
                if ($cidade->getEstado()->getId() == $decriptedId){
                    array_push($sub_array_cidades, $cidade);
                }
            }
            return $sub_array_cidades;

        }

        public function getOMbyIdEncrypted($id) {
            $decriptedId = safeDecrypt($id, $_SESSION['key']);
            foreach ($this->OMs as $OM) {
                if ($OM->getId() == $decriptedId){
                    return $OM;
                }
            }
            return null;
        }

        public function getOMbyId($id) {
            foreach ($this->OMs as $OM) {
                if ($OM->getId() == $id){
                    return $OM;
                }
            }
            return null;
        }

        public function getFAbyCypheredId($id) {
            $decriptedId = safeDecrypt((string)$id, $_SESSION['key']);
            foreach ($this->FAs as $FA) {
                if ($FA->getId() == $decriptedId){
                    return $FA;
                }
            }
            return null;
        }

        public function getFAbyId($id) {
            foreach ($this->FAs as $FA) {
                if ($FA->getId() == $id){
                    return $FA;
                }
            }
            return null;
        }

        public function getFAbyName($nome) {
            foreach ($this->FAs as $FA) {
                if ($FA->getNome() === $nome){
                    return $FA;
                }
            }
            return null;
        }

        public function getPostoByIdEncrypted($id) {
            $decriptedId = safeDecrypt($id, $_SESSION['key']);
            foreach ($this->postos as $posto) {
                if ($posto->getId() == $decriptedId){
                    return $posto;
                }
            }
            return null;
        }

        public function getPostoById($id) {
            foreach ($this->postos as $posto) {
                if ($posto->getId() == $id){
                    return $posto;
                }
            }
            return null;
        }

        public function getPostosByIdFa($id_fa){
            $sub_postos = array();
            foreach ($this->postos as $posto) {
                if ($posto->getForcaArmada()->getId() == $id_fa){
                    array_push($sub_postos, $posto);
                }
            }
            return $sub_postos;
        }

        public function cadastrarMilitar($militar) {
            try {
                $id_OM = $militar->getOM()->getId();
                $id_posto = $militar->getPosto()->getId();
                $stmt = $this->pdo->prepare("INSERT INTO MILITAR (cpf, fname, lname, email, telefone, id_organizacao_militar, id_posto) VALUES (?,?,?,?,?,?,?);");
                if ($stmt->execute([$militar->getCpf(), $militar->getFname(), $militar->getLname(), $militar->getEmail(), $militar->getTelefone(), $id_OM, $id_posto])) {
                    return array(0,"Success");
                }
            } catch (\Exception $e) {
                return array($stmt->errorInfo()[1], $stmt->errorInfo()[2]);
            }
        }

        public function cadastrarOM($OM) {
            try {
                $nome = $OM->getNome();
                $sigla = $OM->getSigla();
                $stmt = $this->pdo->prepare("INSERT INTO NOME_SIGLA (nome, sigla, tipo) VALUES (?,?,?);");
                if ($stmt->execute([$nome, $sigla, 2])) {
//                    echo $OM->getTelefone() . '<br>' . $OM->getFax() . '<br>' . $OM->getEmail() . '<br>' . $OM->getNomeComandante() . '<br>' . $OM->getVinculo()->getId() . '<br>' . $this->getLastId() . '<br>' . $OM->getCidade()->getId() . '<br>' . $OM->getForcaArmada()->getId();
                    $stmt = $this->pdo->prepare("INSERT INTO ORGANIZACAO_MILITAR (telefone, fax, email, nome_comandante, vinculo, id_nome_sigla, cidade, id_forca_armada) VALUES (?,?,?,?,?,?,?,?);");
                    if ($stmt->execute([$OM->getTelefone(), $OM->getFax(), $OM->getEmail(), $OM->getNomeComandante(), $OM->getVinculo()->getId(), $this->getLastId(), $OM->getCidade()->getId(), $OM->getForcaArmada()->getId()])) {
                        return array(0,"Success");
                    }
                }
            } catch (\Exception $e) {
                return array($stmt->errorInfo()[1], $stmt->errorInfo()[2]);
            }
        }

        public function cadastrarConselho($conselho) {
            $count_success = 0;
            try {
                $id_nome_sigla = $conselho->getIdNomeSigla();
                $tipo = $conselho->getTipo();

                if ($tipo == 3) {
                    $specific = $conselho->getTrimestre();
                    $stmt = $this->pdo->prepare("INSERT INTO CONSELHO_PERMANENTE (id_nome_sigla, trimestre) VALUES (?,?);");
                } else {
                    $specific = $conselho->getProcesso();
                    $stmt = $this->pdo->prepare("INSERT INTO CONSELHO_ESPECIAL (id_nome_sigla, processo) VALUES (?,?);");
                }

                $militares = $conselho->getMilitares();

                if ($stmt->execute([$id_nome_sigla, $specific])) {

                    if ($tipo == 3) {
                        $stmt = $this->pdo->prepare("INSERT INTO MILITAR_CONSELHO_PERMANENTE (cpf_militar,id_conselho_permanente,posicao,posto_na_epoca,data_sorteio,data_compromisso) VALUES (?,?,?,?,?,?);");
                    } else {
                        $stmt = $this->pdo->prepare("INSERT INTO MILITAR_CONSELHO_ESPECIAL (cpf_militar,id_conselho_especial,posicao,posto_na_epoca,data_sorteio,data_compromisso) VALUES (?,?,?,?,?,?);");
                    }

                    foreach ($militares as $militar) {

                        $data_sorteio = date("Y-m-d", strtotime(str_replace('/','-', $militar[1])));
                        $data_compromisso = date("Y-m-d", strtotime(str_replace('/','-', $militar[2])));

                        if ($stmt->execute([$militar[0]->getCpf(), $this->getLastId(), $militar[3], $militar[0]->getPosto()->getNome(), $data_sorteio, $data_compromisso])) {
                            $count_success += 1;
                        }
                    }

                    if ($count_success == count($militares)){

                        return array(0,"Success");
                    }

                }
            } catch (\Exception $e) {
                return array($stmt->errorInfo()[1], $stmt->errorInfo()[2]);
            }
        }

        public function checkMilitar($cpf) {
            $stmt = $this->pdo->prepare("SELECT * FROM stm_asc.MILITAR WHERE cpf = :cpf;");
            $stmt->execute(array(':cpf' => $cpf));
            return $stmt->rowCount();
        }

        public function getLastId()
        {
            $query = 'SELECT LAST_INSERT_ID() AS id;';
            foreach ($this->pdo->query($query) as $row) {
                return $row['id'];
            }
        }

        public function getOMs()
        {
            return $this->OMs;
        }

        public function getEstados()
        {
            return $this->estados;
        }

        public function getMilitares(){
            return $this->militares;
        }

        public function getMilitarFromEncryptedCPF($cpf){
            $decryptedCPF = safeDecrypt($cpf, $_SESSION['key']);
            foreach ($this->militares as $militar) {
                if ($militar->getCpf() == $decryptedCPF){
                    return $militar;
                }
            }
            return null;
        }

        public function getAllMilitaresFromFA ($idFA) {
            $subArray = array();
            foreach ($this->militares as $militar) {
                if ($militar->getOM()->getForcaArmada()->getId() === $idFA){
                    array_push($subArray, $militar);
                }
            }
            return $subArray;
        }

        public function getEstadoByIdEncrypted($id){
            $decriptedId = safeDecrypt($id, $_SESSION['key']);
            foreach ($this->estados as $estado){
                if ($estado->getId() == $decriptedId){
                    return $estado;
                }
            }
            return null;
        }

        public function getEstadoById($id){
            foreach ($this->estados as $estado){
                if ($estado->getId() == $id){
                    return $estado;
                }
            }
            return null;
        }

        public function getCidadeByIdEncrypted($id){
            $decriptedId = safeDecrypt($id, $_SESSION['key']);
            foreach ($this->cidades as $cidade){
                if ($cidade->getId() == $decriptedId){
                    return $cidade;
                }
            }
            return null;
        }

        public function getCidadeById($id){
            foreach ($this->cidades as $cidade){
                if ($cidade->getId() == $id){
                    return $cidade;
                }
            }
            return null;
        }

        public function getInfodoConselhoByEncryptedNomeID($id){
            $id = safeDecrypt($id, $_SESSION['key']);
            $query = "SELECT nome, sigla, tipo FROM stm_asc.NOME_SIGLA WHERE id_nome_sigla = :id;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $row = $stmt->fetch();

            if ($row["tipo"] == 3) {
                $tipo = "permanente";
            } else if ($row["tipo"] == 4) {
                $tipo = "especial";
            } else {
                $tipo = null;
            }

            if ($this->match_my_string("Exército", $row['nome'])) {
                $idFA = 1;
            } elseif ($this->match_my_string("Marinha", $row['nome'])) {
                $idFA = 2;
            } elseif ($this->match_my_string("Aeronáutica", $row['nome'])) {
                $idFA = 3;
            } else {
                $idFA = 0;
            }

            $return_array = ["tipo" => $tipo, "FA" => $idFA, "nome" => $row["nome"], "sigla" => $row["sigla"]];

            return $return_array;
        }

        public function getVinculos()
        {
            $vinculos = array();
            foreach ($this->OMs as $OM){
                if (in_array($OM->getId(), [1,12,19,30,38,44,48,52,77])){
                    array_push($vinculos, $OM);
                }
            }
            return $vinculos;
        }

        public function getFAs()
        {
            return $this->FAs;
        }

        public function getPostos()
        {
            return $this->postos;
        }

        public function decrypt($msg) {
            return safeDecrypt($msg, $_SESSION['key']);
        }

        public function match_my_string($needle, $haystack) {
            if (strpos($haystack, $needle) !== false) return true;
            else return false;
        }

    }
}