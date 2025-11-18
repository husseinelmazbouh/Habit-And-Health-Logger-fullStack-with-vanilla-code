const BASE_URL = "http://localhost/Habit-And-Health-Logger-fullStack-with-vanilla-code/routes/apis";
const token = localStorage.getItem("token");

if (!token) window.location.href = "../pages/login.html";

axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

async function loadHabits() {
    const res = await axios.get(`${BASE_URL}/habits`);
    const list = document.getElementById("habitList");

    list.innerHTML = "";

    res.data.forEach(h => {
        const li = document.createElement("li");
        li.textContent = h.name;
        list.appendChild(li);
    });
}

async function addHabit() {
    const name = document.getElementById("habitName").value;

    await axios.post(`${BASE_URL}/habits/create`, { name });

    loadHabits();
    document.getElementById("habitName").value = "";
}

async function addEntry() {
    const text = document.getElementById("entryText").value;

    const res = await axios.post(`${BASE_URL}/entries/create`, {
        text
    });

    alert("Entry added!");
    buildChart(res.data.stats);
}

function buildChart(stats) {
    const ctx = document.getElementById('chart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: stats.labels,
            datasets: [{
                label: "Progress",
                data: stats.values
            }]
        }
    });
}

function logout() {
    localStorage.clear();
    window.location.href = "../pages/login.html";
}

loadHabits();

async function askAI() {
    const text = document.getElementById("aiInput").value;

    if (!text.trim()) {
        alert("Please enter text.");
        return;
    }

    try {
        const res = await axios.post(`${BASE_URL}/ai/parse`, {
            text
        });

        document.getElementById("aiResponse").innerText = res.data.response;

    } catch (err) {
        document.getElementById("aiResponse").innerText = "Error processing AI request.";
    }
}
