document.querySelector('.img-btn').addEventListener('click', function() {
    document.querySelector('.cont').classList.toggle('s-signup');

    // Reset the password validation messages when toggling forms
    document.getElementById('password-error').style.display = 'none';
    document.getElementById('success-message').style.display = 'none';
});

// Ensure the password validation only checks when the sign-up form is submitted
function validatePassword() {
    const signupForm = document.querySelector('.form.sign-up');
    if (signupForm.style.display !== 'none') { // Check if the sign-up form is visible
        const password = document.querySelector('input[name="password"]').value.trim();
        const confirmPassword = document.querySelector('input[name="confirm_password"]').value.trim();
        const errorMessage = document.getElementById('password-error');
        const successMessage = document.getElementById('success-message');

        if (password !== confirmPassword) {
            errorMessage.style.display = 'block';
            errorMessage.classList.add('shake');

            setTimeout(() => {
                errorMessage.classList.remove('shake');
            }, 500);

            successMessage.style.display = 'none'; // Hide success message
            return false; // Prevent form submission
        } else {
            errorMessage.style.display = 'none'; // Hide error if passwords match
            successMessage.style.display = 'block'; // Show success message
            return true; // Allow form submission
        }
    }
    return true; // Allow submission if not in sign-up form
}
