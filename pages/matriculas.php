<?php

    include_once 'sistema/Matricula/MView.php';
    include_once 'sistema/Matricula/MController.php';
    include_once 'sistema/Matricula/MModel.php';
    include_once 'sistema/Aluno/AModel.php';
    include_once 'sistema/Aluno/AController.php';
    include_once 'sistema/Turma/TModel.php';
    include_once 'sistema/Turma/TController.php';

    use sistema\GlobalView;
    use sistema\Login\LModel;
    use sistema\Matricula\MView;

    LModel::validateLogin();

    echo '
        <div class="util">
            ' . GlobalView::createPageTitle('Matricular Aluno') . '
            ' . MView::createHtmlRegistrations() . '
        </div>
    ';

?>