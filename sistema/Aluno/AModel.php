<?php

namespace sistema\Aluno;

use sistema\GlobalView;

class AModel {

    public static function formValidation ($name, $dtNasc, $user, $idStudent = '') {
        $name = addslashes($name);
        if (empty($name) || strlen($name) < 3) {
            return GlobalView::createErrorAlert('O campo Nome é obrigatório.');
        }

        $dtNasc = addslashes($dtNasc);
        if (empty($dtNasc)) {
            return GlobalView::createErrorAlert('O campo Data de Nascimento é obrigatório.');
        }

        if (!self::validateDateBirth($dtNasc)) {
            return GlobalView::createErrorAlert('Data de Nascimento inválida.');
        }

        $user = addslashes($user);
        if (empty($user)) {
            return GlobalView::createErrorAlert('O campo Usuário é obrigatório.');
        }

        if ($idStudent > 0) {
            return self::changeStudent($idStudent, $name, $dtNasc, $user);
        } else {
            $insertDB = AController::insertNewStudent($name, $dtNasc, $user);
        }


        if ($insertDB > 0) {
            unset($_POST);
            return GlobalView::createSuccessAlert('Aluno <b>cadastrado</b> com sucesso.');
        }

        unset($_POST);
        return GlobalView::createErrorAlert('Ocorreu um erro ao tentar cadastrar seus dados. Por favor, verifique as informações inseridas e tente novamente.');
    }

    private static function validateDateBirth($dtNasc) {
        $regex = '/^\d{4}-\d{2}-\d{2}$/'; //YYYY-MM-DD
        if (!preg_match($regex, $dtNasc)) {
            return false;
        }

        list($year, $month, $day) = explode('-', $dtNasc);
        if (!checkdate($month, $day, $year)) {
            return false;
        }

        $anoAtual = (int)date('Y');
        if ($year <= 1900 || $year >= $anoAtual) {
            return false;
        }

        return true;
    }

    public static function listStudents($filter) {
        return AController::vetListStudents('', $filter);
    }

    public static function listStudentsByClass($idClass) {
        if (!is_numeric($idClass) || empty($idClass)) {
            return GlobalView::createErrorAlert('Turma inválida.');
        }

        return AController::vetListStudents($idClass);
    }

    public static function studentById($idStudent) {
        if (!is_numeric($idStudent) || empty($idStudent)) {
            return GlobalView::createErrorAlert('Aluno inválido.');
        }

        return AController::studentById($idStudent);
    }

    public static function deleteStudent($idStudent) {
        if (!is_numeric($idStudent) || empty($idStudent)) {
            return GlobalView::createErrorAlert('Aluno inválido.');
        }

        $deleteStudent = AController::deleteStudentById($idStudent);

        if ($deleteStudent > 0) {
            return GlobalView::createSuccessAlert('Aluno <b>deletado</b> com sucesso.');
        }

        unset($_GET);
        return GlobalView::createErrorAlert('Ocorreu um erro ao tentar deletar seus dados. Por favor, verifique as informações e tente novamente.');
    }

    public static function changeStudent($idStudent, $name, $dtNasc, $user) {
        if (!is_numeric($idStudent) || empty($idStudent)) {
            return GlobalView::createErrorAlert('Aluno inválido.');
        }

        $changeStudent = AController::updateStudentById($idStudent, $name, $dtNasc, $user);

        if ($changeStudent > 0) {
            return GlobalView::createSuccessAlert('Aluno <b>alterado</b> com sucesso.');
        }

        unset($_GET);
        return GlobalView::createErrorAlert('Ocorreu um erro ao tentar deletar seus dados. Por favor, verifique as informações e tente novamente.');
    }

}