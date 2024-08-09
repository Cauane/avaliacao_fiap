<?php

namespace sistema\Turma;

use sistema\Aluno\AModel;
use sistema\GlobalView;

class TView {
    public static function createHtmlClass() {
        $alertMsg = '';

        if (!empty($_GET['new']) && $_GET['new'] == 1) {
            return self::createClassRegistrationForm();
        }

        if (!empty($_GET['students']) && is_numeric($_GET['students'])) {
            return self::registrationStudent($_GET['students']);
        }

        if (!empty($_GET['change'])) {
            $idClass = $_GET['change'];

            if (!empty($_POST['txt_id'])) {
                $nome = $_POST['txt_nome'];
                $desc = $_POST['txt_desc'];
                $tipo = $_POST['txt_tipo'];

                $alertMsg = TModel::formValidation($nome, $desc, $tipo, $idClass);
                return self::createClassListing($alertMsg);
                exit;
            }

            return self::createClassRegistrationForm($idClass);
        }

        if (!empty($_GET['del'])) {
            $idClass = $_GET['del'];
            $alertMsg = TModel::deleteClass($idClass);
        }

        if (!empty($_GET['delStudent']) && !empty($_GET['class'])) {
            $idClass = $_GET['class'];
            $idStudent = $_GET['delStudent'];
            $alertMsg = TModel::deleteStudentByClass($idStudent, $idClass);
        }

        return self::createClassListing($alertMsg);
    }

    private static function createClassListing($alertMsg) {
        $vetClass = TModel::listClass();

        if (empty($vetClass)) {
            return
                GlobalView::createErrorAlert('Nenhuma Turma cadastrada.') . '<br><br>
                <a href="./turmas&new=1">
                    '. GlobalView::createButton(['btn', 'btn-white'], 'Cadastrar Nova', 'button') .
                '</a>'
            ;
        }

        $htmlListClass = '';
        foreach ($vetClass as $class) {
            $htmlListClass .= '
                <tr>
                    <td> ' .  $class['sNome'] . ' </td>
                    <td> <div class="desc">' .  $class['sDesc'] . ' </div></td>
                    <td> ' .  $class['sTipo'] . ' </td>
                    <td class="actions">
                        <div class="actions-icons">
                            <img src="././images/icon-update.svg"
                                width="18" height="18" class="icon-update"
                                title="Alterar Turma" alt="Icone de Alteração"
                                onclick="turmas.changeClass(' .  $class['ID_Turma'] . ');"
                            >
                            <img src="././images/icon-hat.svg"
                                width="18" height="18" class="cursor-pointer"
                                title="Ver Alunos Matriculados" alt="Icone de Matrícula"
                                onclick="turmas.registrationStudent(' .  $class['ID_Turma'] . ');"
                            >
                            <img src="././images/icon-trash.svg"
                                width="18" height="18"
                                title="Excluir Turma" alt="Icone de Lixeira"
                                onclick="turmas.deleteClass(' .  $class['ID_Turma'] . ');"
                            >

                        </div>
                    </td>
                </tr>
            ';
        }

        return '
            ' . $alertMsg . '
            <div class="container-class">
                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Tipo</th>
                        <th class="right">Ações</th>
                    </tr>
                </thead>
                    <tbody>
                        ' . $htmlListClass . '
                    </tbody>
                </table>
            </div>
            <a href="./turmas&new=1">
                '. GlobalView::createButton(['btn', 'btn-white'], 'Cadastrar Novo', 'button') .
            '</a>
        ';
    }

    private static function createClassRegistrationForm($idClass = '') {

        $alertMsg = '';
        $nome = '';
        $desc = '';
        $tipo = '';
        $actionForm = './turmas&new=1';
        $labelForm = 'CADASTRO DE TURMAS';
        $vetTypes = TModel::createVetTypes();
        if (!empty($_POST['submitTurma'])) {
            $nome = $_POST['txt_nome'];
            $desc = $_POST['txt_desc'];
            $tipo = $_POST['txt_tipo'];

            $alertMsg = TModel::formValidation($nome, $desc, $tipo);
        }

        if (!empty($idClass)) {
            $vetClass = TModel::classById($idClass);
            $nome = $vetClass['sNome'];
            $desc = $vetClass['sDesc'];
            $tipo = $vetClass['iTipo'];
            $actionForm = './turmas&change='.$idClass;
            $labelForm = 'ALTERA TURMA';
        }

        return '
            ' .  $alertMsg . '
            <div class="container-class">
                <div class="title">' . $labelForm . '</div>
                <form action="'.$actionForm.'" method="post" onsubmit="return turmas.formValidation();">
                    ' . (!empty($idClass) ? '<input type="hidden" name="txt_id" value="'.$idClass.'">' : '') . '
                    <div class="container-form">
                        ' . GlobalView::createInput('text', 'Nome', 'txt_nome', true, $nome) . '
                        ' . GlobalView::createTextArea('Descrição', 'txt_desc', true, $desc) . '
                        ' . GlobalView::createSelect('Tipo', 'txt_tipo', $vetTypes, true, $tipo, 'ID_Tipo', true) . '

                        <div class="container-buttons">
                            <a href="./turmas">' . GlobalView::createButton(['btn', 'btn-dark'], 'Voltar', 'button') .'</a>
                            <input type="hidden" name="submitTurma" value="1">
                            ' . GlobalView::createButton(['btn', 'btn-primary'], 'Salvar', 'submit') .'
                        </div>
                    </div>
                </div>
            </div>
        ';
    }

    public static function registrationStudent($idClass) {
        $vetStudents = AModel::listStudentsByClass($idClass);

        if (empty($vetStudents)) {
            return
                GlobalView::createErrorAlert('Nenhum Aluno matriculado.') . '<br><br>
                <a href="./turmas">' . GlobalView::createButton(['btn', 'btn-dark'], 'Voltar', 'button') .'</a>'
            ;
        }

        $htmlListStudents = '';
        $className = '';
        foreach ($vetStudents as $student) {
            $className = $student['className'];
            $htmlListStudents .= '
                <tr>
                    <td> ' .  $student['sNome'] . ' </td>
                    <td class="actions">
                        <div class="actions-icons">
                            <img src="././images/icon-trash.svg"
                                width="18" height="18"
                                title="Remover Aluno dessa turma" alt="Icone de Lixeira"
                                onclick="turmas.deleteStudent(' .  $student['ID_Aluno'] . ', ' . $idClass .');"
                            >
                        </div>
                    </td>
                </tr>
            ';
        }

        return '
             <div class="container-class">
                <h1>Turma: ' . $className . '</h1> <br>
                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome Aluno</th>
                        <th class="right">Ações</th>
                    </tr>
                </thead>
                    <tbody>
                        ' . $htmlListStudents . '
                    </tbody>
                </table>
            </div>
            <a href="./turmas">' . GlobalView::createButton(['btn', 'btn-dark'], 'Voltar', 'button') .'</a>
        ';

    }
}