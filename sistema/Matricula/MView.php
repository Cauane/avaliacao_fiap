<?php

namespace sistema\Matricula;

use sistema\Aluno\AModel;
use sistema\GlobalView;
use sistema\Turma\MModel;
use sistema\Turma\TModel;

class MView {

    public static function createHtmlRegistrations() {
        if (!empty($_GET['id'])) {
            $idStudent = $_GET['id'];
            return self::createAssociateClassToStudent($idStudent);
        }

        return self::createStudentListing();
    }

    private static function createAssociateClassToStudent($idStudent) {
        $actionForm = './matriculas&id='.$idStudent;
        $nomeAluno = MModel::nameStudentById($idStudent);
        $turmas = TModel::listClass();

        $alertMsg = '';
        if (!empty($_POST['submitMatricula'])) {
            $idTurma = $_POST['txt_turma'];
            $alertMsg = MModel::associateClassToStudent($idStudent, $idTurma);
        }

        return '
            ' . $alertMsg . '
            <div class="container-student">
                <div class="title">Matricular Aluno - ' . $nomeAluno . ' (#'.$idStudent .')</div>
                <form action="'.$actionForm.'" method="post" onsubmit="return alunos.formValidation();">
                    <div class="container-form">
                        ' . GlobalView::createSelect('Turma', 'txt_turma', $turmas , true, '' ,'ID_Turma', true) . '

                        <div class="container-buttons">
                            <a href="./matriculas">' . GlobalView::createButton(['btn', 'btn-dark'], 'Voltar', 'button') .'</a>
                            <input type="hidden" name="submitMatricula" value="1">
                            ' . GlobalView::createButton(['btn', 'btn-primary'], 'Salvar', 'submit') .'
                        </div>
                    </div>
                </div>
            </div>
        ';
    }

    private static function createStudentListing() {
        $filter = '';
        if (!empty($_POST['txt_search'])) {
            $filter = $_POST['txt_search'];
        }

        $vetStudents = AModel::listStudents($filter);

        $htmlListStudents = '';
        foreach ($vetStudents as $student) {
            $htmlListStudents .= '
                <tr>
                    <td> ' .  $student['sNome'] . ' </td>
                    <td> ' .  $student['dtNascimento'] . ' </td>
                    <td> ' .  $student['sUser'] . ' </td>
                    <td class="actions">
                        <img src="././images/icon-hat.svg"
                            width="18" height="18" class="cursor-pointer"
                            title="Matricular Aluno" alt="Icone de Matrícula"
                            onclick="alunos.registrationStudent(' .  $student['ID_Aluno'] . ');"
                        >
                    </td>
                </tr>
            ';
        }

        $htmlSearch = GlobalView::createContainerSearch('./matriculas', 'Buscar alunos por nome', 'txt_search');

        return '
            <div class="container-student">
                ' . $htmlSearch . '
                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome do Aluno</th>
                        <th>Data de Nascimento</th>
                        <th>Usuário</th>
                        <th class="center">Ações</th>
                    </tr>
                </thead>
                    <tbody>
                        ' . $htmlListStudents . '
                    </tbody>
                </table>
            </div>
        ';

    }

}