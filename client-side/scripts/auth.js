const BASE_URL = "http://localhost/Habit-And-Health-Logger-fullStack-with-vanilla-code/server-side/index.php";

async function register() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if(!email || !password) return alert("Please fill all fields :)");

    try {
        await axios.post(`${BASE_URL}/register`, {
            email,
            password
        });
        alert("Registered successfully! :)");
        window.location.href = "login.html";
    } catch (err) {
        console.error(err);
        alert("Registration failed (Email might exist).");
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

        const responseData = res.data.data; 

        if (responseData.token) {
            localStorage.setItem("token", responseData.token);
            localStorage.setItem("user", JSON.stringify(responseData.user));
            
            if(responseData.user.role === 'admin'){
                window.location.href = "admin.html";
            } else {
                window.location.href = "dashboard.html";
            }
        } else {
            alert("Login failed :(");
        }
    } catch (err) {
        console.error(err);
        alert("Login failed. Check credentials. ;)");
    }
}