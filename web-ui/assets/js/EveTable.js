class EveTable {
  /**
   * @param {string} containerSelector - Selettore CSS del container
   * @param {Array} columns - { key: "field", label: "Nome Colonna" }
   * @param {number} rowsPerPage - numero di righe per pagina
   */
  constructor(containerSelector, columns = [], rowsPerPage = 20) {
    this.container = document.querySelector(containerSelector);
    if (!this.container) throw new Error("Container non trovato");

    this.columns = columns;
    this.visibleColumns = new Set(columns.map(c => c.key));
    this.table = document.createElement("table");
    this.table.classList.add("log-table");
    this.container.appendChild(this.table);

    this.filters = {};
    this.currentPage = 1;
    this.rowsPerPage = rowsPerPage;

    this.autoRefreshActive = true; // auto-refresh attivo di default
    this.onStartAutoRefresh = null;
    this.onStopAutoRefresh = null;

    this.paginationContainer = document.createElement("div");
    this.paginationContainer.style.marginTop = "10px";
    this.paginationContainer.style.display = "flex";
    this.paginationContainer.style.justifyContent = "center";
    this.paginationContainer.style.gap = "10px";
    this.container.appendChild(this.paginationContainer);

    this._createColumnSelector();
    this._createFilterRow();
  }

  // --- Pannello selezione colonne + bottone Start/Stop ---
  _createColumnSelector() {
    const selector = document.createElement("div");
    selector.classList.add("column-selector");

    this.columns.forEach(col => {
      const label = document.createElement("label");
      label.style.marginRight = "10px";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.checked = true;
      checkbox.value = col.key;
      checkbox.addEventListener("change", () => {
        if (checkbox.checked) this.visibleColumns.add(col.key);
        else this.visibleColumns.delete(col.key);
        this._createFilterRow();
        this.currentPage = 1;
        this.render(this.currentData || []);
      });

      label.appendChild(checkbox);
      label.appendChild(document.createTextNode(` ${col.label}`));
      selector.appendChild(label);
    });

    // Bottone Live/Down
    const refreshBtn = document.createElement("button");
    refreshBtn.textContent = "Live";
    refreshBtn.style.marginLeft = "auto";
    refreshBtn.className = "btn btn-green"
    refreshBtn.addEventListener("click", () => {
      this.autoRefreshActive = !this.autoRefreshActive;
      refreshBtn.textContent = this.autoRefreshActive ? "Live" : "Down";
      refreshBtn.className = this.autoRefreshActive ? "btn btn-green" : "btn btn-red"
      if (this.autoRefreshActive) {
        this.onStartAutoRefresh?.();
      } else {
        this.onStopAutoRefresh?.();
      }
    });
    selector.appendChild(refreshBtn);

    this.container.insertBefore(selector, this.table);

    // Avvia subito il refresh se la callback è già definita
    if (this.autoRefreshActive) {
      this.onStartAutoRefresh?.();
    }
  }

  // Imposta callback esterne per auto-refresh
  setAutoRefreshCallbacks(onStart, onStop) {
    this.onStartAutoRefresh = onStart;
    this.onStopAutoRefresh = onStop;

    // Se auto-refresh è attivo, chiama subito onStart
    if (this.autoRefreshActive) {
      this.onStartAutoRefresh?.();
    }
  }

  // --- Header e filtri ---
  _createFilterRow() {
    const oldThead = this.table.querySelector("thead");
    if (oldThead) oldThead.remove();

    const thead = document.createElement("thead");

    // Intestazione colonne
    const headerRow = document.createElement("tr");
    this.columns.forEach(col => {
      if (this.visibleColumns.has(col.key)) {
        const th = document.createElement("th");
        th.textContent = col.label;
        headerRow.appendChild(th);
      }
    });
    headerRow.appendChild(document.createElement("th")); // colonna RAW
    thead.appendChild(headerRow);

    // Riga filtri
    this.filterRow = document.createElement("tr");
    this.columns.forEach(col => {
      if (this.visibleColumns.has(col.key)) {
        const th = document.createElement("th");
        const input = document.createElement("input");
        input.type = "text";
        input.placeholder = "Filtro...";
        input.addEventListener("input", e => {
          this.filters[col.key] = e.target.value.toLowerCase();
          this.currentPage = 1;
          this.render(this.currentData || []);
        });
        th.appendChild(input);
        this.filterRow.appendChild(th);
      }
    });
    this.filterRow.appendChild(document.createElement("th")); // colonna RAW
    thead.appendChild(this.filterRow);

    this.table.appendChild(thead);
  }

  // --- Utility ---
  _formatDate(dateStr) {
    const d = new Date(dateStr);
    if (isNaN(d)) return dateStr;
    const pad = n => String(n).padStart(2, '0');
    const day = pad(d.getDate());
    const month = pad(d.getMonth() + 1);
    const year = String(d.getFullYear()).slice(2);
    const hours = pad(d.getHours());
    const minutes = pad(d.getMinutes());
    const seconds = pad(d.getSeconds());
    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
  }



  _compactIPv6(ip) {
    if (!ip.includes(":")) return ip;
    try {
      return ip.replace(/(^|:)0+([0-9a-fA-F]+)/g, "$1$2").replace(/::+/, "::");
    } catch {
      return ip;
    }
  }

  _getNestedValue(obj, path) {
    return path.split('.').reduce((acc, part) => acc && acc[part], obj);
  }

  _filterData(data) {
    return data.filter(row => {
      for (let key in this.filters) {
        const filterVal = this.filters[key];
        if (!filterVal) continue;

        const cellVal = (this._getNestedValue(row, key) ?? "").toString().toLowerCase();
        if (!cellVal.includes(filterVal)) return false;
      }
      return true;
    });
  }

  // --- Pagination ---
  _renderPagination(totalRows) {
    this.paginationContainer.innerHTML = "";
    const totalPages = Math.ceil(totalRows / this.rowsPerPage);
    if (totalPages <= 1) return;

    const prev = document.createElement("button");
    prev.textContent = "Prev";
    prev.disabled = this.currentPage === 1;
    prev.addEventListener("click", () => {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.render(this.currentData);
      }
    });
    this.paginationContainer.appendChild(prev);

    const pageInfo = document.createElement("span");
    pageInfo.textContent = `Pagina ${this.currentPage} di ${totalPages}`;
    pageInfo.style.alignSelf = "center";
    this.paginationContainer.appendChild(pageInfo);

    const next = document.createElement("button");
    next.textContent = "Next";
    next.disabled = this.currentPage === totalPages;
    next.addEventListener("click", () => {
      if (this.currentPage < totalPages) {
        this.currentPage++;
        this.render(this.currentData);
      }
    });
    this.paginationContainer.appendChild(next);
  }

  // --- Render tabella ---
  render(data) {
    this.currentData = data;
    const filtered = this._filterData(data);

    this._renderPagination(filtered.length);

    const start = (this.currentPage - 1) * this.rowsPerPage;
    const end = start + this.rowsPerPage;
    const pageData = filtered.slice(start, end);

    let tbody = this.table.querySelector("tbody");
    if (tbody) tbody.remove();
    tbody = document.createElement("tbody");

    pageData.forEach(row => {
      const tr = document.createElement("tr");

      this.columns.forEach(col => {
        if (this.visibleColumns.has(col.key)) {
          const td = document.createElement("td");
          let val = this._getNestedValue(row, col.key) ?? "";

          if (col.key.toLowerCase().includes("date") || col.key === "timestamp") {
            val = this._formatDate(val);
          }
          if (col.key.toLowerCase().includes("ip")) {
            val = this._compactIPv6(val);
          }

          td.textContent = val;
          tr.appendChild(td);
        }
      });

      const tdBtn = document.createElement("td");
      const btn = document.createElement("button");
      btn.style.fontSize = "1rem";
      btn.style.border = "none";
      btn.style.background = "transparent";
      btn.style.cursor = "pointer";
      btn.innerHTML = '<i class="fas fa-eye"></i>';
      btn.addEventListener("click", () => {
        alert(JSON.stringify(row, null, 2));
      });
      tdBtn.appendChild(btn);
      tr.appendChild(tdBtn);

      tbody.appendChild(tr);
    });

    this.table.appendChild(tbody);
  }
}

export default EveTable;
