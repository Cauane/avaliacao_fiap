<?php

namespace sistema\Turma;

use sistema\Aluno\AController;
use sistema\GlobalView;
use sistema\Matricula\MController;

class MModel {
    public static function nameStudentById($idStudent) {
        if (!is_numeric($idStudent) || empty($idStudent)) {
            return GlobalView::createErrorAlert('Aluno inválido.');
        }

        return AController::nameStudentById($idStudent);
    }

    public static function associateClassToStudent($idStudent, $idClass) {
        if (!is_numeric($idStudent) || empty($idStudent)) {
            return GlobalView::createErrorAlert('Aluno inválido.');
        }

        if (!is_numeric($idClass) || empty($idClass)) {
            return GlobalView::createErrorAlert('Turma inválida.');
        }

        $associateClassToStudent = MController::associateClassToStudent($idStudent, $idClass);

        if ($associateClassToStudent > 0) {
            unset($_POST);
            return GlobalView::createSuccessAlert('Aluno <b>matriculado</b> com sucesso.');
        }

        unset($_GET);
        unset($_POST);
        return GlobalView::createErrorAlert('Ocorreu um erro ao tentar efetuar a matrícula. Por favor, verifique as informações e tente novamente.');
    }
}