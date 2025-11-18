const BASE_URL = "http://localhost/Habit-And-Health-Logger-fullStack-with-vanilla-code/routes/apis";
const token = localStorage.getItem("token");

if (!token) window.location.href = "login.html";

axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

async function loadUsers() {
    try {
        const res = await axios.get(`${BASE_URL}/users`);
        const users = res.data;

        const list = document.getElementById("userList");
        list.innerHTML = "";

        users.forEach(u => {
            const li = document.createElement("li");
            li.innerHTML = `
                <strong>${u.name}</strong> (${u.email})
                <button onclick="viewUser(${u.id})">View</button>
                <button onclick="disableUser(${u.id})">Disable</button>
                <button onclick="deleteUser(${u.id})" style="background:red">Delete</button>
            `;
            list.appendChild(li);
        });

    } catch (err) {
        alert("Error loading users.");
    }
}

async function viewUser(id) {
    document.getElementById("selectedUserId").innerText = id;

    await loadUserHabits(id);
    await loadUserEntries(id);
}

async function loadUserHabits(userId) {
    try {
        const res = await axios.get(`${BASE_URL}/habits`);
        const list = document.getElementById("habitList");

        list.innerHTML = "";

        res.data.forEach(h => {
            const li = document.createElement("li");
            li.textContent = h.name;
            list.appendChild(li);
        });

    } catch (err) {
        alert("Cannot load habits.");
    }
}

async function loadUserEntries(userId) {
    try {
        const res = await axios.get(`${BASE_URL}/entries`);

        const list = document.getElementById("entryList");
        list.innerHTML = "";

        const labels = [];
        const values = [];

        res.data.forEach(e => {
            const li = document.createElement("li");
            li.textContent = `${e.date}: ${e.text}`;
            list.appendChild(li);

            labels.push(e.date);
            values.push(e.score);
        });

        buildChart(labels, values);

    } catch (err) {
        alert("Cannot load entries.");
    }
}

async function deleteUser(id) {
    if (!confirm("Delete user?")) return;

    try {
        await axios.post(`${BASE_URL}/users/delete`, { id });
        alert("User deleted.");
        loadUsers();

    } catch (err) {
        alert("Failed deleting user.");
    }
}

let chart;

function buildChart(labels, values) {
    const ctx = document.getElementById("adminChart");

    if (chart) chart.destroy();

    chart = new Chart(ctx, {
        type: "bar",
        data: {
            labels,
            datasets: [{
                label: "User Activity",
                data: values
            }]
        }
    });
}
function logout() {
    localStorage.clear();
    window.location.href = "../pages/login.html";
}

loadUsers();
