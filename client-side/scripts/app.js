const BASE_URL = "http://localhost/Habit-And-Health-Logger-fullStack-with-vanilla-code/server-side/index.php";
const token = localStorage.getItem("token");

if (!token) window.location.href = "login.html";
axios.defaults.headers.common["Authorization"] = token;

async function loadHabits() {
    try {
        const res = await axios.get(`${BASE_URL}/habits`);
        const list = document.getElementById("habitList");
        list.innerHTML = "";
        
        const habits = res.data.data; 
        if(Array.isArray(habits)) {
            habits.forEach(h => {
                const li = document.createElement("li");
                li.textContent = `${h.name} (Target: ${h.target_value})`;
                list.appendChild(li);
            });
        }
    } catch (err) { console.log("Error loading habits"); }
}

async function addHabit() {
    const name = document.getElementById("habitName").value;
    if (!name) return alert("Enter name");

    try {
        await axios.post(`${BASE_URL}/habits/create`, { 
            name: name,
            type: "tick", 
            target_value: "1"
        });
        loadHabits();
        document.getElementById("habitName").value = "";
    } catch (err) { alert("Error creating habit"); }
}

async function addEntry() {
    const text = document.getElementById("entryText").value;
    if (!text) return alert("Enter text");
    
    const today = new Date().toISOString().split('T')[0];
    try {
        await axios.post(`${BASE_URL}/entries/create`, {
            free_text: text,
            entry_date: today,
            structured_data: {},
            ai_analysis: ""
        });
        alert("Entry added!");
    } catch(err) {
        alert("Failed. You might already have an entry for today.");
    }
}

async function askAI() {
    const text = document.getElementById("aiInput").value;
    if (!text.trim()) return alert("Enter text");

    try {
        const res = await axios.post(`${BASE_URL}/ai/parse`, { free_text: text });
        document.getElementById("aiResponse").innerText = JSON.stringify(res.data.data, null, 2);
    } catch (err) {
        document.getElementById("aiResponse").innerText = "AI Error.";
    }
}
let myChart = null; 

async function loadChart() {
    try {
        const res = await axios.get(`${BASE_URL}/entries`);
        const entries = res.data.data;
        if (!Array.isArray(entries) || entries.length === 0) {
            console.log("No data for chart yet.");
            return;
        }
        const labels = entries.map(e => e.entry_date);
        const data = entries.map(e => e.free_text ? e.free_text.length : 0);
        const ctx = document.getElementById('chart');
        if (myChart) {
            myChart.destroy();
        }
        myChart = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: labels,
                datasets: [{
                    label: 'Activity Level (Text Length)',
                    data: data,
                    backgroundColor: '#3a7bd5',
                    borderColor: '#247aca',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    } catch (err) {
        console.error("Chart Error:", err);
    }
}

function logout() {
    localStorage.clear();
    window.location.href = "../pages/login.html";
}
loadHabits();
loadChart();