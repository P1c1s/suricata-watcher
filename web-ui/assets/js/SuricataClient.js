class SuricataClient {
  constructor(token, endpoint = `${window.location.origin}/server.php`) {
    if (!token) throw new Error("❌ Token mancante: è obbligatorio fornire un Bearer token.");
    this.token = token;
    this.endpoint = endpoint;
    this.refreshInterval = null;
  }

  async _fetch(action = "logs") {
    try {
      const response = await fetch(`${this.endpoint}?action=${action}`, {
        headers: {
          "Authorization": `Bearer ${this.token}`,
          "Cache-Control": "no-cache"
        }
      });
      if (!response.ok) throw new Error(`Errore server (${response.status}): ${response.statusText}`);
      return await response.json();
    } catch (err) {
      console.error("❌ Errore nella richiesta:", err);
      return { error: err.message };
    }
  }

  /** 📄 Ottiene i log */
  getLogs() { return this._fetch("logs"); }

  /** 📊 Ottiene le statistiche */
  getStats() { return this._fetch("stats"); }

  /**
   * 🔄 Avvia auto-refresh
   * @param {"logs"|"stats"} type - Tipo di dati da aggiornare
   * @param {function} callback - Funzione chiamata ad ogni refresh con i dati
   * @param {number} intervalMs - Intervallo in millisecondi
   */
  startAutoRefresh(type, callback, intervalMs = 1000) {
    if (this.refreshInterval) clearInterval(this.refreshInterval);
    if (typeof callback !== "function") throw new Error("callback deve essere una funzione");

    const fetchFn = type === "stats" ? this.getStats.bind(this) : this.getLogs.bind(this);

    // Primo fetch immediato
    fetchFn().then(callback);

    // Refresh periodico
    this.refreshInterval = setInterval(async () => {
      const data = await fetchFn();
      callback(data);
    }, intervalMs);

    console.log(`🔁 Auto-refresh attivo per ${type} ogni ${intervalMs} ms`);
  }

  stopAutoRefresh() {
    if (this.refreshInterval) {
      clearInterval(this.refreshInterval);
      this.refreshInterval = null;
      console.log("⏸️ Auto-refresh fermato");
    }
  }
}

export default SuricataClient;
