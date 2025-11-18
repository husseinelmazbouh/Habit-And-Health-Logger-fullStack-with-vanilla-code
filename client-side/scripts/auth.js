const BASE_URL = "http://localhost/Habit-And-Health-Logger-fullStack-with-vanilla-code/routes/apis";

async function register() {
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    try {
        await axios.post(`${BASE_URL}/register`, {
            name,
            email,
            password
        });

        alert("Registered successfully! :) :)");
        window.location.href = "../pages/login.html";

    } catch (err) {
        alert("Registration failed :(");
    }
}
async function login() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    try {
        const res = await axios.post(`${BASE_URL}/login`, {
            email,
            password
        });
        localStorage.setItem("token", res.data.token);
        localStorage.setItem("user", JSON.stringify(res.data.user));

        window.location.href = "../pages/dashboard.html";
    } 
    catch (err) {
        alert("Login failed :(");
    }
}
