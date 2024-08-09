<?php

namespace sistema\Aluno;

use Sistema\Database\Database;

class AController {

    public static function insertNewStudent($name, $dtNasc, $user) {
        $dbase = Database::getInstance();

        $query = self::queryinsertStudent($name, $dtNasc, $user);
        $dbase->query($query);

        return $dbase->affectedRows();
    }

    private static function queryinsertStudent($name, $dtNasc, $user) {
        return '
            INSERT INTO aluno(
                sNome,
                dtNasc,
                sUser
            ) VALUES (
                "'.addslashes(stripslashes($name)).'",
                "'.addslashes(stripslashes($dtNasc)).'",
                "'.addslashes(stripslashes($user)).'"
            )
        ';
    }

    /**
     * Retorna um array com todos alunos
     *
     * @return array
     */
    public static function vetListStudents($idClass='', $filter='') {
        $dbase = Database::getInstance();

        $query = self::queryListStudents($idClass, $filter);
        $queryResult = $dbase->query($query);

        $vetStudents = [];
        while ($row = $dbase->fetchAssocResult($queryResult)) {
            $vetStudents[] = $row;
        }

        return $vetStudents;
    }

    private static function queryListStudents($idClass='', $filter='') {
        $join_aux = '';
        $field_aux = '';
        if ($idClass) {
            $join_aux = '
                INNER JOIN
                    matricula m
                        ON m.IDE_Aluno = a.ID_Aluno
                        AND m.IDE_Turma = "'.$idClass.'"
                INNER JOIN
                    turma t
                        ON t.ID_Turma = m.IDE_Turma
            ';

            $field_aux = ',t.sNome AS className';
        }


        $where_aux = '';
        if (!empty($filter)) {
            $where_aux = '
                WHERE
                    sNome
                LIKE "%'.addslashes(stripslashes($filter)).'%"
            ';
        }

        return '
            SELECT
                a.*,
                DATE_FORMAT(a.dtNasc, "%d/%m/%Y") AS dtNascimento
                ' . $field_aux . '
            FROM
                aluno a
            ' . $join_aux .'
            ' . $where_aux .'
            ORDER BY
                sNome asc
        ';
    }

    public static function studentById($idStudent) {
        $dbase = Database::getInstance();

        $query = self::queryStudentById($idStudent);
        $queryResult = $dbase->query($query);

        return $dbase->fetchAssocResult($queryResult);
    }

    private static function queryStudentById($idStudent) {
        return '
            SELECT
                *,
                DATE_FORMAT(dtNasc, "%d/%m/%Y") AS dtNascimento
            FROM
                aluno
            WHERE
                ID_Aluno = "' . addslashes(stripslashes($idStudent)) . '"
            ORDER BY
                sNome asc
            LIMIT 1

        ';
    }

    public static function deleteStudentById($idStudent) {
        $dbase = Database::getInstance();

        $query = self::queryDeleteStudentById($idStudent);
        $dbase->query($query);

        return $dbase->affectedRows();
    }

    private static function queryDeleteStudentById($idStudent) {
        return '
            DELETE FROM
                aluno
            WHERE
                ID_Aluno = "' . addslashes(stripslashes($idStudent)) . '"
        ';
    }

    public static function updateStudentById($idStudent, $name, $dtNasc, $user) {
        $dbase = Database::getInstance();

        $query = self::queryUpdateStudentById($idStudent, $name, $dtNasc, $user);
        $dbase->query($query);

        return $dbase->affectedRows();
    }

    private static function queryUpdateStudentById($idStudent, $name, $dtNasc, $user) {
        return '
            UPDATE
                aluno
            SET
                sNome = "' . addslashes(stripslashes($name)) . '",
                dtNasc = "' . addslashes(stripslashes($dtNasc)) . '",
                sUser = "' . addslashes(stripslashes($user)) . '"
            WHERE
                ID_Aluno = "' . addslashes(stripslashes($idStudent)) . '"
        ';
    }

    public static function nameStudentById($idStudent) {
        $dbase = Database::getInstance();

        $query = self::queryStudentById($idStudent);
        $queryResult = $dbase->query($query);

        $row = $dbase->fetchAssocResult($queryResult);

        return $row['sNome'];
    }

}