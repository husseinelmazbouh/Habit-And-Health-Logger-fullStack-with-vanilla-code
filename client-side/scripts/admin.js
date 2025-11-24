const BASE_URL = "http://localhost/Habit-And-Health-Logger-fullStack-with-vanilla-code/server-side/index.php";
const token = localStorage.getItem("token");

if (!token) window.location.href = "../pages/login.html";

axios.defaults.headers.common["Authorization"] = token;

async function loadUsers() {
    try {
        const res = await axios.get(`${BASE_URL}/users`);
        const users = res.data.data; 
        
        const tableBody = document.getElementById("users-table-body");
        tableBody.innerHTML = ""; 

        if (Array.isArray(users)) {
            users.forEach(u => {
                const tr = document.createElement("tr");
                
                tr.innerHTML = `
                    <td>${u.id}</td>
                    <td>${u.email}</td>
                    <td>
                        <span style="padding:4px 8px; background:${u.role === 'admin' ? '#d1ecf1' : '#eee'}; border-radius:4px;">
                            ${u.role}
                        </span>
                    </td>
                    <td>${u.created_at}</td>
                    <td>
                        <button onclick="deleteUser(${u.id})" style="background:#dc3545; width:auto; padding:5px 10px; font-size:12px;">
                            Delete
                        </button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });
        } else {
            tableBody.innerHTML = "<tr><td colspan='5'>No users found.</td></tr>";
        }

    } catch (err) {
        console.error(err);
        alert("Error loading users. Are you an admin?");
    }
}

async function deleteUser(id) {
    if (!confirm("Are you sure you want to delete this user?")) return;

    try {
        await axios.get(`${BASE_URL}/users/delete?id=${id}`);
        loadUsers(); 
    } catch (err) {
        alert("Failed deleting user.");
    }
}

function logout() {
    localStorage.clear();
    window.location.href = "../pages/login.html";
}
loadUsers();