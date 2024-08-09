let login = {
    formValidation: function () {
        let inpLogin = document.getElementById('inp-login').value;
        let inpSenha = document.getElementById('inp-pass').value;

        if (!inpLogin || inpLogin.length < 5) {
            alert('Ops! O campo Login é obrigatório!');
            return false;
        }

        if (!inpSenha || inpSenha.length < 5) {
            alert('Ops! O campo Senha é obrigatório!');
            return false;
        }

        return true;
    }
}