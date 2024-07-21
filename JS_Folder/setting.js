function validatePassword() {
    const password = document.getElementById('new_password').value;
    const charRule = document.getElementById('char_rule');
    const upperRule = document.getElementById('upper_rule');
    const specialRule = document.getElementById('special_rule');
    const updatePasswordBtn = document.getElementById('updatePasswordBtn');

    const isValidLength = password.length >= 6;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    charRule.className = isValidLength ? 'valid' : 'invalid';
    upperRule.className = hasUpperCase ? 'valid' : 'invalid';
    specialRule.className = hasSpecialChar ? 'valid' : 'invalid';

    updatePasswordBtn.disabled = !(isValidLength && hasUpperCase && hasSpecialChar);
}