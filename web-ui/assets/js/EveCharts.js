// Assicurati di aver incluso Chart.js nel tuo progetto (es. via CDN o npm)
// CDN: <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

class EveCharts {
  /**
   * @param {string} containerId - ID del container HTML (es. "#statsContainer")
   */
  constructor(containerId) {
    this.container = document.querySelector(containerId);
    if (!this.container) throw new Error(`Container ${containerId} non trovato`);

    this.charts = {}; // memorizza istanze dei grafici Chart.js
    this.container.classList.add("suricata-stats");
  }

  /**
   * 🔄 Aggiorna i grafici con nuovi dati delle statistiche
   * @param {Object} statsData - Dati restituiti da SuricataClient.getStats()
   */
  render(statsData) {
    if (!statsData || typeof statsData !== "object") return;

    // Pulizia iniziale del container se vuoto
    if (Object.keys(this.charts).length === 0) {
      this.container.innerHTML = "";
    }

    // Suricata invia stats come oggetto con categorie (decoder, capture, etc.)
    for (const [category, metrics] of Object.entries(statsData)) {
      if (typeof metrics !== "object") continue;

      // Se il grafico non esiste ancora → crealo
      if (!this.charts[category]) {
        const card = document.createElement("div");
        card.className = "stat-card";
        card.style.border = "1px solid #333";
        card.style.borderRadius = "10px";
        card.style.padding = "10px";
        card.style.margin = "10px 0";
        card.style.background = "#121212";

        const title = document.createElement("h3");
        title.textContent = category;
        title.style.color = "#00ff88";
        title.style.fontSize = "1.2rem";
        card.appendChild(title);

        const canvas = document.createElement("canvas");
        card.appendChild(canvas);
        this.container.appendChild(card);

        const ctx = canvas.getContext("2d");

        const chart = new Chart(ctx, {
          type: "bar",
          data: {
            labels: Object.keys(metrics),
            datasets: [{
              label: "Valore",
              data: Object.values(metrics),
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                ticks: { color: "#ccc" },
                grid: { color: "#333" }
              },
              x: {
                ticks: { color: "#ccc" },
                grid: { color: "#333" }
              }
            },
            plugins: {
              legend: { display: false },
              title: {
                display: false
              }
            }
          }
        });

        this.charts[category] = chart;
      } else {
        // Aggiorna i dati del grafico esistente
        const chart = this.charts[category];
        chart.data.labels = Object.keys(metrics);
        chart.data.datasets[0].data = Object.values(metrics);
        chart.update();
      }
    }
  }
}

export default EveCharts;
