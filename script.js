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

function validateName(input) {
    const errorElement = document.getElementById('nameError');

    if (input.value.trim() === '') {
        input.classList.add('invalid-input');
        errorElement.textContent = 'Meno je povinné.';
    } else {
        input.classList.remove('invalid-input');
        errorElement.textContent = '';
    }
}

function validateSurname(input) {
    const errorElement = document.getElementById('surnameError');

    if (input.value.trim() === '') {
        input.classList.add('invalid-input');
        errorElement.textContent = 'Priezvisko je povinné.';
    } else {
        input.classList.remove('invalid-input');
        errorElement.textContent = '';
    }
}

function validateEmail(input) {
    const errorElement = document.getElementById('emailError');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (input.value.trim() === '' || !emailPattern.test(input.value)) {
        input.classList.add('invalid-input');
        errorElement.textContent = 'Prosím, zadajte platnú emailovú adresu.';
    } else {
        input.classList.remove('invalid-input');
        errorElement.textContent = '';
    }
}

function validatePassword(input) {
    const errorElement = document.getElementById('passwordError');

    if (input.value.trim() === '' || input.value.length < 6) {
        input.classList.add('invalid-input');
        errorElement.textContent = 'Heslo musí obsahovať aspoň 6 znakov.';
    } else {
        input.classList.remove('invalid-input');
        errorElement.textContent = '';
    }
}

function checkFormValidity() {
    const name = document.getElementById('name').value;
    const surname = document.getElementById('surname').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const registerButton = document.getElementById('registerButton');

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordMinLength = 6;

    const isNameValid = name.trim() !== '';
    const isSurnameValid = surname.trim() !== '';
    const isEmailValid = email.trim() !== '' && emailPattern.test(email);
    const isPasswordValid = password.trim() !== '' && password.length >= passwordMinLength;

    registerButton.disabled = !(isNameValid && isSurnameValid && isEmailValid && isPasswordValid);
}

