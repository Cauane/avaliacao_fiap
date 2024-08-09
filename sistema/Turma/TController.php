<?php

namespace sistema\Turma;

use Sistema\Database\Database;

class TController {
    public static function insertNewClass($name, $desc, $tipo) {
        $dbase = Database::getInstance();

        $query = self::queryinsertClass($name, $desc, $tipo);
        $dbase->query($query);

        return $dbase->affectedRows();
    }

    private static function queryinsertClass($name, $desc, $tipo) {
        return '
            INSERT INTO turma(
                sNome,
                sDesc,
                iTipo
            ) VALUES (
                "'.addslashes(stripslashes($name)).'",
                "'.addslashes(stripslashes($desc)).'",
                "'.addslashes(stripslashes($tipo)).'"
            )
        ';
    }

    public static function vetTypesClass() {
        $dbase = Database::getInstance();

        $query = self::queryTypesClass();
        $queryResult = $dbase->query($query);

        $vetTypes = [];
        while ($row = $dbase->fetchAssocResult($queryResult)) {
            $vetTypes[] = $row;
        }

        return $vetTypes;
    }

    private static function queryTypesClass() {
        return '
            SELECT
                *
            FROM
                tipo_turma
            ORDER BY
                ID_Tipo asc
        ';
    }

    public static function classById($idClass) {
        $dbase = Database::getInstance();

        $query = self::queryClassById($idClass);
        $queryResult = $dbase->query($query);

        return $dbase->fetchAssocResult($queryResult);
    }

    private static function queryClassById($idClass) {
        return '
            SELECT
                *
            FROM
                turma
            WHERE
                ID_Turma = "' . addslashes(stripslashes($idClass)) . '"
            ORDER BY
                sNome asc
            LIMIT 1
        ';
    }

    public static function updateClassById($idClass, $name, $dtNasc, $user) {
        $dbase = Database::getInstance();

        $query = self::queryUpdateClassById($idClass, $name, $dtNasc, $user);
        $dbase->query($query);

        return $dbase->affectedRows();
    }

    private static function queryUpdateClassById($idClass, $name, $dtNasc, $user) {
        return '
            UPDATE
                turma
            SET
                sNome = "' . addslashes(stripslashes($name)) . '",
                sDesc = "' . addslashes(stripslashes($dtNasc)) . '",
                iTipo = "' . addslashes(stripslashes($user)) . '"
            WHERE
                ID_Turma = "' . addslashes(stripslashes($idClass)) . '"
        ';
    }

    public static function vetListClass() {
        $dbase = Database::getInstance();

        $query = self::queryListClass();
        $queryResult = $dbase->query($query);

        $vetClass = [];
        while ($row = $dbase->fetchAssocResult($queryResult)) {
            $vetClass[] = $row;
        }

        return $vetClass;
    }

    private static function queryListClass() {
        return '
            SELECT
                t.*,
                tt.sNome as sTipo
            FROM
                turma t
            INNER JOIN
                tipo_turma tt
                    ON tt.ID_Tipo = t.iTipo
            ORDER BY
                sNome asc
        ';
    }

    public static function deleteClassById($idClass) {
        $dbase = Database::getInstance();

        $queryClass = self::queryDeleteClassById($idClass);
        $queryRegistrations = self::queryDeleteRegistrationsByClass($idClass);
        $dbase->query($queryClass);
        $dbase->query($queryRegistrations);

        return $dbase->affectedRows();
    }

    private static function queryDeleteClassById($idClass) {
        return '
            DELETE FROM
                turma
            WHERE
                ID_Turma = "' . addslashes(stripslashes($idClass)) . '";
        ';
    }

    private static function queryDeleteRegistrationsByClass($idClass) {
        return '
            DELETE FROM
                matricula
            WHERE
                IDE_Turma = "' . addslashes(stripslashes($idClass)) . '";
        ';
    }

    public static function deleteStudentByClass($idStudent, $idClass) {
        $dbase = Database::getInstance();

        $queryClass = self::queryDeleteStudentByClass($idStudent, $idClass);
        $dbase->query($queryClass);

        return $dbase->affectedRows();
    }

    private static function queryDeleteStudentByClass($idStudent, $idClass) {
        return '
            DELETE FROM
                matricula
            WHERE
                IDE_Aluno = "' . addslashes(stripslashes($idStudent)) . '"
                AND IDE_Turma = "' . addslashes(stripslashes($idClass)) . '";
        ';
    }
}