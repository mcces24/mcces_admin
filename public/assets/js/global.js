function getPasswordStrength(password) {
    var score = 0;

    if (password.length >= 8) score++; // Minimum length
    if (/[A-Z]/.test(password)) score++; // Uppercase letter
    if (/[a-z]/.test(password)) score++; // Lowercase letter
    if (/[0-9]/.test(password)) score++; // Number
    if (/[\W]/.test(password)) score++; // Special character

    return { score: Math.min(score, 4) }; // Max score is 4
}

function setCookie(name, value, hours) {
    const date = new Date();
    date.setTime(date.getTime() + (hours * 60 * 60 * 1000)); // Set expiration time (in milliseconds)
    const expires = "expires=" + date.toUTCString(); // Format the date to UTC string
    document.cookie = `${name}=${value};${expires};path=/`; // Set the cookie with the expiration date
}

function getCookie(name) {
    let cookieArray = document.cookie.split(';'); // Split all cookies by ";"
    for (let i = 0; i < cookieArray.length; i++) {
        let cookie = cookieArray[i].trim(); // Remove leading/trailing spaces
        if (cookie.indexOf(name + "=") === 0) { // Check if the cookie starts with the name
            return cookie.substring(name.length + 1); // Return the value of the cookie
        }
    }
    return null; // Return null if the cookie is not found
}