<?php

namespace Sistema\Database;

use InvalidArgumentException;
use mysqli;
use mysqli_result;

/**
 * Classe responsável por fazer a interação com o banco de dados.
 */
class Database {
    /**
     * @var Database
     */
    private static $instance;
    /**
     * @var mysqli MySQL link identifier
     */
    private $connection;

    /**
     * Private constructor
     */
    private function __construct() {
    }

    public function __destruct() {
        $this->closeConnection();
    }

    /**
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect($host, $user, $passdb, $db) {
        $this->connection = new mysqli($host, $user, $passdb, $db);
        if ($this->connection->connect_errno) {
            die('Site em manutenção...');
        }
    }

    /**
     * Fecha a conexão com o banco
     */
    public function closeConnection() {
        if ($this->connection) {
            @mysqli_close($this->connection);
        }
    }

    /**
     * Checa se existe conexao
     */
    public function hasConnection() {
        if ($this->connection) {
            return true;
        }

        return false;
    }

    /**
     * Executa uma query de INSERT comum
     *
     * Exemplo de uso:
     * ```
     *  $database->insert('tabela', ['field1' => 'v1', 'field2' => 'v2']);
     * ```
     * Query executada:
     * `
     *  INSERT INTO tabela (`field1`, `field2`) VALUES ('v1', 'v2')
     * `
     *
     * @param string $table Nome da tabela
     * @param array $data Array com campos e valores no formato ['nomeDoCampo' => valorDoCampo]
     * @return bool sucesso/falha
     */
    public function insert($table, array $data) {
        $query = 'INSERT INTO ' . $table . ' ' . $this->arrayToSqlInsert($data);
        return $this->query($query);
    }

    private function arrayToSqlInsert(array $data) {
        $labels = [];
        $fields = [];

        foreach ($data as $key => $value) {
            $valorTratado = self::escapeString($value);
            $labels[] = '`' . $key . '`';
            $fields[] = "'" . $valorTratado . "'";
        }

        return '
                (' . implode(', ', $labels) . ')
            VALUES
                (' . implode(', ', $fields) . ')
        ';
    }

    /**
     * Executa uma query de UPDATE
     *
     * Exemplo de uso:
     * ```
     *  $database->update('tabela', ['id' => '1'], ['field' => 'v']);
     * ```
     * Query executada:
     * `
     *  UPDATE tabela SET field1 = 'v' WHERE id = 1
     * `
     *
     * @param string $table Nome da tabela
     * @param array $whereFields Campo e valor de cada campo "chave" dessa edição para ser usado no WHERE
     * @param array $data Array com campos e valores no formato ['nomeDoCampo' => valorDoCampo]
     * @param int $limit Limite de registros a serem afetados, se houver.
     * @return bool true/false para indicar successo/falha.
     */
    public function update($table, array $whereFields, array $data, $limit = null) {
        $whereClause = [];
        foreach ($whereFields as $field => $value) {
            $valueSanitized = self::escapeString($value);
            $whereClause[] = $field . "='" . $valueSanitized . "'";
        }
        $query = 'UPDATE ' . $table . ' SET ' . $this->arrayToSqlUpdate($data) .
                ' WHERE ' . implode(' AND ', $whereClause);

        if (isset($limit) && is_int($limit)) {
            $query .= ' LIMIT ' . intval($limit);
        }

        return $this->query($query);
    }

    private function arrayToSqlUpdate(array $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $valueSanitized = self::escapeString($value);
            $fields[] = $key . "='" . $valueSanitized . "'";
        }
        return implode(",\n", $fields);
    }

    /**
     * Executa uma query no banco
     *
     * @param string $query Query a ser executada
     * @return bool|mysqli_result SELECT - Result|false (erro). Outras queries: true/false para indicar successo/falha.
     */
    public function query($query) {
        return mysqli_query($this->connection, $query);
    }

    /**
     * Obtém uma linha de resultado de uma query como um array associativo.
     *
     * @param mysqli_result $queryResult The result resource that is being evaluated
     * @return array|false|null Array associativo de strings da linha (row) buscada. Null se não há rows. False se erro.
     */
    public function fetchAssocResult($queryResult) {
        return mysqli_fetch_assoc($queryResult);
    }

    /**
     * Obtém uma linha de resultado de uma query como um objeto.
     *
     * @param mysqli_result $queryResult The result resource that is being evaluated
     * @return false|object|null Object representing the row, null if there are no more rows or false on failure.
     */
    public function fetchAssocObject($queryResult) {
        return mysqli_fetch_object($queryResult);
    }

    /**
     * Obtém uma linha de resultado de uma query como um array associativo.
     *
     * @param string $query Query a ser executada
     * @return array|false|null Array associativo de strings da linha (row) buscada. Null se não há rows. False se erro.
     */
    public function fetchAssoc($query) {
        return $this->fetchAssocResult(mysqli_query($this->connection, $query));
    }

    /**
     * Get the ID generated in the last query
     *
     * @return int
     */
    public function lastInsertId() {
        return mysqli_insert_id($this->connection);
    }

    /**
     * Obtém os resultados da query de forma limitada
     *
     * @param string $query Query a ser executada
     * @param int $limit Quantidade de dados que serão retornados
     * @return array Array com os resultados da query.
     */
    public function fetch($query, $limit = 30) {
        return $this->fetchAll($query . ' LIMIT ' . $limit);
    }

    /**
     * Obtém os resultados da query
     *
     * @param string $query Query a ser executada
     * @param callable|null $converter Função para converter os valores retornados (como um _map_)
     * @return array Array com os resultados da query.
     */
    public function fetchAll($query, callable $converter = null) {
        $result = $this->query($query);

        $results = [];
        while ($row = $this->fetchAssocResult($result)) {
            if (is_callable($converter)) {
                $results[] = call_user_func($converter, $row);
            } else {
                $results[] = $row;
            }
        }
        return $results;
    }

    /**
     * Obtém o número de resultados de uma query.
     *
     * Cuidado: Conta a quantidade de linhas de resultados de uma query, não é um SELECT count()!
     *
     * @param string $query Query a ser executada
     * @return false|int Número de linhas (row) do resultado. False se não há linhas.
     */
    public function count($query) {
        return $this->countResult($this->query($query));
    }

    /**
     * Obtém o número de resultados de um resource (resultado de query).
     *
     * Cuidado: Conta a quantidade de linhas de resultados de uma query, não é um SELECT count()!
     *
     * @param mysqli_result $queryResult The result that is being evaluated
     * @return false|int Número de linhas (row) do resultado. False se não há linhas.
     */
    public function countResult($queryResult) {
        return mysqli_num_rows($queryResult);
    }

    /**
     * Obtém o número de linhas afetadas pelo último INSERT, UPDATE, REPLACE ou DELETE.
     *
     * @return int Retorna o número de linhas afetadas em caso de sucesso. Se houve falha, -1.
     */
    public function affectedRows() {
        return mysqli_affected_rows($this->connection);
    }

    /**
     * Retorna a mensagem de erro na última operação MySQL
     *
     * @return string Retorna a mensagem de erro.
     */
    public function error() {
        return mysqli_error($this->connection);
    }

    /**
     * Move ponteiro interno
     *
     * @param mixed $result
     * @param mixed $rownumber
     * @return resource Próximo resultado.
     */
    public function seek($result, $rownumber) {
        return mysqli_data_seek($result, $rownumber);
    }

    /**
     * Remove caracteres indesejados dos parâmetros
     *
     * @param string $unescapedString String com caracteres indesejados
     * @return string String formatada
     */
    private static function escapeString($unescapedString) {
        return addslashes(stripslashes($unescapedString));
    }

    /**
     * Formata os filtros gerados pela query
     *
     * @param array $filter Parâmetros do filtro
     * @param string $filterLogicalOperator Operador lógico do filtro (AND, OR)
     * @return string Filtros da query formatados
     */
    private static function convertArrayToWhereParams(array $filter, $filterLogicalOperator) {
        if (!in_array($filterLogicalOperator, ['AND', 'OR'])) {
            throw new InvalidArgumentException('Unexpected filterLogicalOperator value');
        }

        $paramsArray = array_map(
            function ($key, $value) {
                if (is_array($value)) {
                    $escapedList = array_map(
                        function ($element) {
                            return self::escapeString($element);
                        },
                        $value
                    );
                    $values = implode("','", $escapedList);
                    return "{$key} IN ('{$values}')";
                }
                $valueEscaped = self::escapeString($value);
                return "{$key} = '{$valueEscaped}'";
            },
            array_keys($filter),
            array_values($filter)
        );

        return implode(
            ' ' . $filterLogicalOperator . ' ',
            $paramsArray
        );
    }

    /**
     * Formata os campos gerados pela query, se $fields for um array vazio retorna todos os campos
     *
     * @param array $fields Parâmetros da query
     * @return string Campos da query formatados
     */
    private static function convertArrayToFields(array $fields) {
        return empty($fields) ?
            '*' :
            implode(', ', $fields);
    }

    /**
     * Obtém a query a partir dos parâmetros
     *
     * @param array $params Parâmetros da query
     * @return string Query resultante da montagem
     */
    private static function buildQuery(array $params) {
        $query = 'SELECT ' . self::convertArrayToFields($params['fields']) .
            ' FROM ' . $params['table'] .
            ' WHERE ' . self::convertArrayToWhereParams($params['filter'], $params['operator']);

        if (isset($params['limit'])) {
            $query .= ' LIMIT ' . $params['limit'] . ' ';
        }

        return $query;
    }

    /**
     * Obtém um resultado do banco de dados
     *
     * Exemplos de uso:
     * ```
     * $database->searchOne('table', ['id' => 1]); // Busca 1 registro com id=1. Exibe todos campos.
     * $database->searchOne('table', ['id'=>1, 'nome'=>'x']); // Busca 1 registro com id=1 e nome=x. Exibe todos campos.
     * $database->searchOne('table', ['id' => 1], ['c1', 'c2']); // Busca 1 registro com id=1. Exibe os campos c1 e c2.
     * $database->searchOne('table', ['tipo' => [1, 2]]); // Busca 1 registro com tipo IN (1,2). Exibe todos campos.
     * ```
     *
     * @param string $table Tabela a ser executada
     * @param array $filter Parâmetros do filtro
     * @param array $fields Campos de dados que serão retornados
     * @param string $filterLogicalOperator Operador lógico do filtro (AND, OR)
     * @return array Array com o resultado da query.
     */
    public function searchOne($table, array $filter = [], array $fields = [], $filterLogicalOperator = 'AND') {
        return $this->fetchAssoc(self::buildQuery([
            'table' => $table,
            'filter' => $filter,
            'fields' => $fields,
            'limit' => '1',
            'operator' => $filterLogicalOperator,
        ]));
    }

    /**
     * Obtém os vários resultados do banco de dados
     *
     * Exemplos de uso:
     * ```
     * $database->searchMany('t', ['tipo' => 1]); // Busca N registros com tipo=1. Exibe todos campos.
     * $database->searchMany('t', ['tipo'=>1, 'user'=>'2']); // Busca N registros com tipo=1 e user=2. Exibe todos campos.
     * $database->searchMany('t', ['tipo' => 1], ['c1', 'c2']); // Busca N registros com tipo=1. Exibe os campos c1 e c2.
     * $database->searchMany('t', ['tipo' => 1], [], 5); // Busca até 5 registros com tipo=1. Exibe todos campos.
     * $database->searchMany('t', ['tipo' => [1, 2]]); // Busca N registros com tipo IN (1, 2). Exibe todos campos.
     * ```
     *
     * @param string $table Tabela a ser executada
     * @param array $filter Parâmetros do filtro
     * @param array $fields Campos de dados que serão retornados
     * @param int $limit Quantidade de dados que serão retornados
     * @param string $filterLogicalOperator Operador lógico do filtro (AND, OR)
     * @return array Array com os resultados da query.
     */
    public function searchMany($table, array $filter = [], array $fields = [], $limit = null, $filterLogicalOperator = 'AND') {
        return $this->query(self::buildQuery([
            'table' => $table,
            'filter' => $filter,
            'fields' => $fields,
            'limit' => $limit,
            'operator' => $filterLogicalOperator,
        ]));
    }

    /**
     * Retorna o erro. Utilize somente para log interno!
     *
     * @return string Mensagem de erro
     */
    public function getError() {
        return mysqli_errno($this->connection) . ': ' . $this->error();
    }
}
