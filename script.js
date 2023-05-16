function validateForm() {
    let name = document.getElementById('name').value;
    let surname = document.getElementById('surname').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let role = document.getElementById('role').value;

    if (name === '') {
        alert('Meno je povinné.');
        return false;
    }
    if (surname === '') {
        alert('Priezvisko je povinné.');
        return false;
    }
    if (email === '' || !email.includes('@')) {
        alert('Prosím, zadajte platnú emailovú adresu.');
        return false;
    }
    if (password === '' || password.length < 6) {
        alert('Heslo musí obsahovať aspoň 6 znakov.');
        return false;
    }
    if (role !== 'Študent' && role !== 'Učiteľ') {
        alert('Prosím, vyberte platnú rolu.');
        return false;
    }

    return true;
}