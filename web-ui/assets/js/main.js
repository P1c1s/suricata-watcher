// main.js
import SuricataClient from './SuricataClient.js';
import EveTable from './EveTable.js';
import EveCharts from "./EveCharts.js";
const TOKEN = 'edjedeij83neuenf38unenfw9enpodd'
const client = new SuricataClient(TOKEN); // metti qui il token valido

document.addEventListener("DOMContentLoaded", () => {

    const page = window.location.pathname.split("/").pop(); 

    if (page === "eve.php") {
        const columns = [
            { key: 'timestamp', label: 'Timestamp' },
            { key: 'src_ip', label: 'Source IP' },
            { key: 'dest_ip', label: 'Destination IP' },
            { key: 'src_port', label: 'S Port' },
            { key: 'dest_port', label: 'D Port' },
            { key: 'proto', label: 'Protocol' },
            { key: 'alert.signature', label: 'Signature' },
            { key: 'event_type', label: 'Event Type' },
            { key: 'msg', label: 'Message' },
            { key: 'alert.severity', label: 'Severity' },
            { key: 'in_iface', label: 'Interface' },
            { key: 'state', label: 'State' },

        ];

        // --- 2. Crea la tabella ---
        const table = new EveTable('#table-container', columns);

        // --- 3. Crea il client Suricata ---


        table.setAutoRefreshCallbacks(
            () => client.startAutoRefresh('logs', data => table.render(data), 1000),
            () => client.stopAutoRefresh()
        );
    }

    if (page === "traffic.php") {

        const viewer = new EveCharts("#charts-container");

        // Auto-refresh dei grafici ogni 2 secondi
        client.startAutoRefresh("stats", (data) => {
            if (!data.error) viewer.render(data);
        }, 4000);
    }

});









// =====================
// 🔒 LOCK SCREEN INDEX
// =====================
document.addEventListener("DOMContentLoaded", () => {
    const page = window.location.pathname.split("/").pop(); 

    if (page !== "lock_screen.php") {
        let timeout;

        function resetTimer() {
            clearTimeout(timeout);
            timeout = setTimeout(logoutUser, 120 * 1000); // 120 seconds
        }
        function logoutUser() {

            const form = document.createElement("form");
            form.method = "POST";
            form.action = window.location.href + "/index.php";
            form.id = "lock_screen";

            form.innerHTML = `
                <input type="hidden" name="lock_screen" value="true">
                <input type="hidden" name="csrf_token" value="${document.getElementById("csrf_token").value}">
            `;

            document.body.appendChild(form);
            form.submit();
        }

        // Eventi che indicano attività dell'utente
        ["load", "mousemove", "keypress", "scroll", "click", "touchstart"].forEach(event => {
            window.addEventListener(event, resetTimer);
        });

        resetTimer(); // Avvia subito
    }
});




//////////SISTEMARE PARTE DELLA PAGINA MODEL NAVBAR E SIDEBAR


const toggleBtn = document.getElementById('toggle-btn');
const sidebar = document.getElementById('sidebar');
const toggleIcon = document.getElementById('toggle-icon');


toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');

    // Cambia direzione della freccia
    if (sidebar.classList.contains('active')) {
        toggleIcon.classList.remove('fa-chevron-right');
        toggleIcon.classList.add('fa-chevron-left');
    } else {
        toggleIcon.classList.remove('fa-chevron-left');
        toggleIcon.classList.add('fa-chevron-right');
    }
});


function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
}

updateClock();
setInterval(updateClock, 1000);

