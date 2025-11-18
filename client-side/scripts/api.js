const API_BASE_URL = 'http://localhost/Habit-And-Health-Logger-fullStack-with-vanilla-code/routes/apis'; 

axios.defaults.baseURL = API_BASE_URL;

function getAuthHeaders() {
    const token = localStorage.getItem('token');
    return token ? { Authorization: token } : {};
}

async function apiPost(path, data = {}, auth = false) {
    const config = auth ? { headers: getAuthHeaders() } : {};
    return axios.post(path, data, config);
}

async function apiGet(path, auth = false, params = {}) {
    const config = auth ? { headers: getAuthHeaders(), params } : { params };
    return axios.get(path, config);
}

function saveUserSession(user, token) {
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify(user));
}

function getCurrentUser() {
    const raw = localStorage.getItem('user');
    return raw ? JSON.parse(raw) : null;
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '../pages/login.html';
}

