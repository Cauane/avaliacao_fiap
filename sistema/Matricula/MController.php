<?php

namespace sistema\Matricula;

use Sistema\Database\Database;

class MController {
    public static function associateClassToStudent($idStudent, $idClass) {
        $dbase = Database::getInstance();

        $query = self::queryAssociateClassToStudent($idStudent, $idClass);
        $dbase->query($query);

        return $dbase->affectedRows();
    }

    public static function queryAssociateClassToStudent($idStudent, $idClass) {
        return '
            INSERT INTO matricula
                (
                    IDE_Aluno,
                    IDE_Turma
                )
            VALUES (
                "'.addslashes(stripslashes($idStudent)).'",
                "'.addslashes(stripslashes($idClass)).'"
            )
        ';
    }


}