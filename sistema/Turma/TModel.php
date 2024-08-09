<?php

namespace sistema\Turma;

use sistema\GlobalView;

class TModel {

    public static function formValidation ($name, $desc, $tipo, $idClass = '') {
        $name = addslashes($name);
        if (empty($name) || strlen($name) < 3) {
            return GlobalView::createErrorAlert('O campo Nome é obrigatório.');
        }

        $desc = addslashes($desc);
        if (empty($desc)) {
            return GlobalView::createErrorAlert('O campo Descrição é obrigatório.');
        }

        if (strlen($desc) < 10) {
            return GlobalView::createErrorAlert('A descrição está muito curta. Por favor, insira uma descrição com mais de 10 caracteres.');
        }

        $tipo = addslashes($tipo);
        if (empty($tipo)) {
            return GlobalView::createErrorAlert('O campo Tipo é obrigatório.');
        }

        if (!is_numeric($tipo)) {
            return GlobalView::createErrorAlert('Tipo inválido.');
        }

        if ($idClass > 0) {
            return self::changeClass($idClass, $name, $desc, $tipo);
        } else {
            $insertDB = TController::insertNewClass($name, $desc, $tipo);
        }


        if ($insertDB > 0) {
            unset($_POST);
            return GlobalView::createSuccessAlert('Turma <b>cadastrada</b> com sucesso.');
        }

        unset($_POST);
        return GlobalView::createErrorAlert('Ocorreu um erro ao tentar cadastrar seus dados. Por favor, verifique as informações inseridas e tente novamente.');
    }

    public static function classById($idClass) {
        if (!is_numeric($idClass) || empty($idClass)) {
            return GlobalView::createErrorAlert('Turma inválida.');
        }

        return TController::classById($idClass);
    }

    public static function changeClass($idClass, $name, $desc, $tipo) {
        if (!is_numeric($idClass) || empty($idClass)) {
            return GlobalView::createErrorAlert('Turma inválida.');
        }

        $changeClass = TController::updateClassById($idClass, $name, $desc, $tipo);

        if ($changeClass > 0) {
            return GlobalView::createSuccessAlert('Turma <b>alterada</b> com sucesso.');
        }

        unset($_GET);
        return GlobalView::createErrorAlert('Ocorreu um erro ao tentar deletar seus dados. Por favor, verifique as informações e tente novamente.');
    }

    public static function createVetTypes() {
        return TController::vetTypesClass();
    }

    public static function listClass() {
        return TController::vetListClass();
    }

    public static function deleteClass($idClass) {
        if (!is_numeric($idClass) || empty($idClass)) {
            return GlobalView::createErrorAlert('Turma inválida.');
        }

        $deleteClass = TController::deleteClassById($idClass);

        if ($deleteClass > 0) {
            return GlobalView::createSuccessAlert('Turma <b>deletada</b> com sucesso.');
        }

        unset($_GET);
        return GlobalView::createErrorAlert('Ocorreu um erro ao tentar deletar seus dados. Por favor, verifique as informações e tente novamente.');
    }

    public static function deleteStudentByClass($idStudent, $idClass) {
        if (!is_numeric($idStudent) || empty($idStudent)) {
            return GlobalView::createErrorAlert('Aluno inválido.');
        }

        if (!is_numeric($idClass) || empty($idClass)) {
            return GlobalView::createErrorAlert('Turma inválida.');
        }

        $deleteClass = TController::deleteStudentByClass($idStudent, $idClass);

        if ($deleteClass > 0) {
            return GlobalView::createSuccessAlert('Aluno <b>removido</b> da turma com sucesso.');
        }

        unset($_GET);
        return GlobalView::createErrorAlert('Ocorreu um erro ao tentar deletar seus dados. Por favor, verifique as informações e tente novamente.');

    }

}