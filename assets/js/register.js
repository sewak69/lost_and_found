document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Password match validation
    if (password !== confirmPassword) {
        alert('Passwords do not match. Please try again.');
        e.preventDefault(); // stop submission
        return;
    }

    // Password strength validation
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!passwordRegex.test(password)) {
        alert('Password must be at least 8 characters with uppercase, lowercase, and number.');
        e.preventDefault(); // stop submission
        return;
    }
});
