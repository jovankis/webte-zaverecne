function validateEmail(input) {
    const emailError = document.getElementById('emailError');

    if (input.value.trim() === '' || !validateEmailFormat(input.value)) {
        input.classList.add('invalid-input');
        emailError.textContent = 'Prosím, zadajte platnú emailovú adresu.';
    } else {
        input.classList.remove('invalid-input');
        emailError.textContent = '';
    }
    updateSubmitButtonState();
}

function validateEmailFormat(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
}

function validatePassword(input) {
    const passwordError = document.getElementById('passwordError');

    if (input.value.trim() === '') {
        input.classList.add('invalid-input');
        passwordError.textContent = 'Prosím, zadajte heslo.';
    } else {
        input.classList.remove('invalid-input');
        passwordError.textContent = '';
    }
    updateSubmitButtonState();
}

function validateForm() {
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    validateEmail(email);
    validatePassword(password);

    return !email.classList.contains('invalid-input') && !password.classList.contains('invalid-input');
}

function updateSubmitButtonState() {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const submitButton = document.querySelector('button[type="submit"]');

    if (email.classList.contains('invalid-input') || password.classList.contains('invalid-input') ||
        email.value.trim() === '' || password.value.trim() === '') {
        submitButton.disabled = true;
    } else {
        submitButton.disabled = false;
    }
}