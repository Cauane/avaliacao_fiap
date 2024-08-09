let alunos = {
    formValidation: function () {
        let nome = document.getElementById('txt_nome').value;
        let dataNasc = document.getElementById('dt_nasc').value;
        let user = document.getElementById('txt_user').value;

        if (!nome || nome.length < 3) {
            alert('Ops! O campo Nome é obrigatório!');
            return false;
        }

        if (!dataNasc) {
            alert('Ops! O campo Data é obrigatório!');
            return false;
        }

        if (dataNasc.length < 8) {
            alert('Ops! Campo Data inválido.');
            return false;
        }

        if (!user) {
            alert('Ops! O campo Usuário é obrigatório!');
            return false;
        }

        return true;
    },

    deleteStudent: function(idStudent) {
        if (!idStudent) {
            alert('Ops! Este Aluno não existe.');
            return false;
        }

        if (!confirm("Tem certeza que deseja deletar esse aluno?")) {
            return false;
        }

        parent.location = "./alunos&del=" + idStudent;
    },

    changeStudent: function (idStudent) {
        if (!idStudent) {
            alert('Ops! Este Aluno não existe.');
            return false;
        }

        parent.location = "./alunos&change=" + idStudent;
    },

    registrationStudent: function (idStudent) {
        if (!idStudent) {
            alert('Ops! Este Aluno não existe.');
            return false;
        }

        parent.location = "./matriculas&id=" + idStudent;
    }
}