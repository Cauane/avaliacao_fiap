let turmas = {
    formValidation: function () {
        let nome = document.getElementById('txt_nome').value;
        let desc = document.getElementById('txt_desc').value;
        let tipo = document.getElementById('txt_tipo').value;

        if (!nome || nome.length < 3) {
            alert('Ops! O campo Nome é obrigatório!');
            return false;
        }

        if (!desc) {
            alert('Ops! O campo Descrição é obrigatório!');
            return false;
        }

        if (dataNasc.length < 10) {
            alert('Ops! A descrição está muito curta. Por favor, insira uma descrição com mais de 10 caracteres.');
            return false;
        }

        if (!tipo || tipo == 0) {
            alert('Ops! O campo Tipo é obrigatório!');
            return false;
        }

        return true;
    },

    deleteClass: function(idClass) {
        if (!idClass) {
            alert('Ops! Essa Turma não existe.');
            return false;
        }

        if (!confirm("Tem certeza que deseja deletar essa turma? Todas as matrículas associadas a essa turma será deletada.")) {
            return false;
        }

        parent.location = "./turmas&del=" + idClass;
    },

    changeClass: function (idClass) {
        if (!idClass) {
            alert('Ops! Essa Turma não existe.');
            return false;
        }

        parent.location = "./turmas&change=" + idClass;
    },

    registrationStudent: function (idClass) {
        if (!idClass) {
            alert('Ops! Essa Turma não existe.');
            return false;
        }

        parent.location = "./turmas&students=" + idClass;

    },

    deleteStudent: function (idStudent, idClass) {
        if (!confirm("Tem certeza que deseja deletar remover esse aluno?")) {
            return false;
        }

        parent.location = "./turmas&delStudent=" + idStudent + "&class=" + idClass;
    }
}