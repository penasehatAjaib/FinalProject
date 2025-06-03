document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', function (event) {
        const username = form.username.value.trim();
        const password = form.password.value.trim();

        if (username === '' || password === '') {
            errorMessage.textContent = 'Username dan password tidak boleh kosong.';
            errorMessage.style.display = 'block';
            event.preventDefault();
        } else {
            errorMessage.style.display = 'none';
        }
    });
});
