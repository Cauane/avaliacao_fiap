<?php

namespace sistema\Aluno;

use sistema\GlobalView;


class AView {
    public static function createHtmlStudent() {
        $alertMsg = '';

        if (!empty($_GET['new']) && $_GET['new'] == 1) {
            return self::createStudentRegistrationForm();
        }

        if (!empty($_GET['change'])) {
            $idStudent = $_GET['change'];

            if (!empty($_POST['txt_id'])) {
                $nome = $_POST['txt_nome'];
                $dataNasc = $_POST['dt_nasc'];
                $user = $_POST['txt_user'];

                $alertMsg = AModel::formValidation($nome, $dataNasc, $user, $idStudent);
                return self::createStudentListing($alertMsg);
                exit;
            }

            return self::createStudentRegistrationForm($idStudent);
        }

        if (!empty($_GET['del'])) {
            $idStudent = $_GET['del'];
            $alertMsg = AModel::deleteStudent($idStudent);
        }

        return self::createStudentListing($alertMsg);
    }

    private static function createStudentListing($alertMsg) {
        $filter = '';
        if (!empty($_POST['txt_search'])) {
            $filter = $_POST['txt_search'];
        }

        $vetStudents = AModel::listStudents($filter);

        if (empty($vetStudents)) {
            return
                GlobalView::createErrorAlert('Nenhum Aluno cadastrado.') . '<br><br>
                <a href="./alunos&new=1">
                    '. GlobalView::createButton(['btn', 'btn-white'], 'Cadastrar Novo', 'button') .
                '</a>'
            ;
        }

        $htmlListStudents = '';
        foreach ($vetStudents as $student) {
            $htmlListStudents .= '
                <tr>
                    <td> ' .  $student['sNome'] . ' </td>
                    <td> ' .  $student['dtNascimento'] . ' </td>
                    <td> ' .  $student['sUser'] . ' </td>
                    <td class="actions">
                        <div class="actions-icons">
                            <img src="././images/icon-update.svg"
                                width="18" height="18" class="icon-update"
                                title="Alterar Aluno" alt="Icone de Alteração"
                                onclick="alunos.changeStudent(' .  $student['ID_Aluno'] . ');"
                            >
                            <img src="././images/icon-trash.svg"
                                width="18" height="18"
                                title="Excluir Aluno" alt="Icone de Lixeira"
                                onclick="alunos.deleteStudent(' .  $student['ID_Aluno'] . ');"
                            >
                        </div>
                    </td>
                </tr>
            ';
        }

        $htmlSearch = GlobalView::createContainerSearch('./alunos', 'Buscar alunos por nome', 'txt_search');

        return '
            ' . $alertMsg . '
            <div class="container-student">
                ' . $htmlSearch . '
                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>Usuário</th>
                        <th class="right">Ações</th>
                    </tr>
                </thead>
                    <tbody>
                        ' . $htmlListStudents . '
                    </tbody>
                </table>
            </div>
            <a href="./alunos&new=1">
                '. GlobalView::createButton(['btn', 'btn-white'], 'Cadastrar Novo', 'button') .
            '</a>
        ';
    }

    private static function createStudentRegistrationForm($idStudent = '') {

        $alertMsg = '';
        $nome = '';
        $dataNasc = '';
        $user = '';
        $actionForm = './alunos&new=1';
        $labelForm = 'CADASTRO DE ALUNOS';
        if (!empty($_POST['submitAlunos'])) {
            $nome = $_POST['txt_nome'];
            $dataNasc = $_POST['dt_nasc'];
            $user = $_POST['txt_user'];

            $alertMsg = AModel::formValidation($nome, $dataNasc, $user);
        }

        if (!empty($idStudent)) {
            $vetStudent = AModel::studentById($idStudent);
            $nome = $vetStudent['sNome'];
            $dataNasc = $vetStudent['dtNasc'];
            $user = $vetStudent['sUser'];
            $actionForm = './alunos&change='.$idStudent;
            $labelForm = 'ALTERA ALUNO';
        }

        return '
            ' .  $alertMsg . '
            <div class="container-student">
                <div class="title">' . $labelForm . '</div>
                <form action="'.$actionForm.'" method="post" onsubmit="return alunos.formValidation();">
                    ' . (!empty($idStudent) ? '<input type="hidden" name="txt_id" value="'.$idStudent.'">' : '') . '
                    <div class="container-form">
                        ' . GlobalView::createInput('text', 'Nome', 'txt_nome', true, $nome) . '
                        ' . GlobalView::createInput('date', 'Data de Nascimento', 'dt_nasc', true, $dataNasc) . '
                        ' . GlobalView::createInput('text', 'Usuário', 'txt_user', true, $user) . '

                        <div class="container-buttons">
                            <a href="./alunos">' . GlobalView::createButton(['btn', 'btn-dark'], 'Voltar', 'button') .'</a>
                            <input type="hidden" name="submitAlunos" value="1">
                            ' . GlobalView::createButton(['btn', 'btn-primary'], 'Salvar', 'submit') .'
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
}

?>