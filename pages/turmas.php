<?php

    include_once 'sistema/Turma/TView.php';
    include_once 'sistema/Turma/TController.php';
    include_once 'sistema/Turma/TModel.php';
    include_once 'sistema/Aluno/AModel.php';
    include_once 'sistema/Aluno/AController.php';

    use sistema\GlobalView;
    use sistema\Login\LModel;
    use sistema\Turma\TView;

    LModel::validateLogin();

    echo '
        <div class="util">
            ' . GlobalView::createPageTitle('Turmas') . '
            ' . TView::createHtmlClass() . '
        </div>
    ';

?>