const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the icon
            this.classList.toggle('bx-show');
            this.classList.toggle('bx-hide');})


function loginPageRedirect() {
    window.location.hred = 'loginpage.php';

}