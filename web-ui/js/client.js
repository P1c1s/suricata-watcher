document.addEventListener("DOMContentLoaded", function () {
  if (document.body.id === "page-index") {
    let allData = [];
    let currentPage = 1;
    const rowsPerPage = 17;

    // Formatta timestamp
    function formatDate(timestamp) {
      const date = new Date(timestamp);
      if (isNaN(date)) return 'N/A';
      const day = ('0' + date.getDate()).slice(-2);
      const month = ('0' + (date.getMonth() + 1)).slice(-2);
      const year = date.getFullYear();
      const hours = ('0' + date.getHours()).slice(-2);
      const minutes = ('0' + date.getMinutes()).slice(-2);
      const seconds = ('0' + date.getSeconds()).slice(-2);
      return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
    }

    // Render tabella
// Helper to compact IPv6 addresses
function compactIPv6(ip) {
  if (!ip.includes(':')) return ip; // IPv4 or invalid, return as is
  return ip.replace(/\b0{1,3}/g, '') // Remove leading zeros in each block
           .replace(/(:0){2,}/, '::') // Collapse consecutive zeros (simplistic)
           .replace(/::+/, '::'); // Ensure only one double colon
}
function renderTable(data) {
  const tableBody = document.querySelector('#data-table tbody');
  tableBody.innerHTML = '';

  data.forEach((record, idx) => {
    let severityClass = '';
    const sev = record.alert?.severity;
    if (sev !== undefined) {
      if (sev == 1) severityClass = 'severity-low';
      else if (sev == 2) severityClass = 'severity-medium';
      else if (sev >= 3) severityClass = 'severity-high';
    }

    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${record.timestamp ? formatDate(record.timestamp) : 'N/A'}</td>
      <td>${record.src_ip ? compactIPv6(record.src_ip) : 'N/A'}</td>
      <td>${record.dest_ip ? compactIPv6(record.dest_ip) : 'N/A'}</td>
      <td>${record.proto || 'N/A'}</td>
      <td>${record.icmp_type !== undefined ? record.icmp_type : 'N/A'}</td>
      <td>${record.alert?.action || 'N/A'}</td>
      <td class="${severityClass}">${sev !== undefined ? sev : 'N/A'}</td>
      <td>${record.alert?.signature || 'N/A'}</td>
      <td>${record.alert?.category || 'N/A'}</td>
      <td>${record.in_iface || 'N/A'}</td>
      <td><button class="raw-btn" data-idx="${idx}">Raw</button></td>
    `;
    tableBody.appendChild(row);
  });

  // Aggiungi evento click per mostrare raw JSON
  document.querySelectorAll('.raw-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const idx = btn.getAttribute('data-idx');
      showRawModal(data[idx]);
    });
  });
}

// Funzione per mostrare il raw JSON in un popup/modal
function showRawModal(record) {
  let modal = document.getElementById('rawModal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'rawModal';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.background = 'rgba(0,0,0,0.7)';
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '9999';
    modal.innerHTML = `
      <div style="background:white; padding:20px; border-radius:10px; max-width:90%; max-height:80%; overflow:auto; position:relative;">
        <button id="closeModal" style="position:absolute; top:10px; right:10px; background:#ef4444; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer;">Chiudi</button>
        <pre id="rawContent" style="white-space:pre-wrap;"></pre>
      </div>
    `;
    document.body.appendChild(modal);
    document.getElementById('closeModal').addEventListener('click', () => modal.style.display = 'none');
  }
  document.getElementById('rawContent').textContent = JSON.stringify(record, null, 2);
  modal.style.display = 'flex';
}



    // Paginazione
    function paginateData(data) {
      const start = (currentPage - 1) * rowsPerPage;
      const end = start + rowsPerPage;
      return data.slice(start, end);
    }

    function renderPagination(totalRows) {
      const totalPages = Math.ceil(totalRows / rowsPerPage);
      const container = document.getElementById('pagination-controls');
      container.innerHTML = '';
      if (totalPages <= 1) return;

      const prevBtn = document.createElement('button');
      prevBtn.textContent = '« Precedente';
      prevBtn.disabled = currentPage === 1;
      prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; applyFilters(); scrollToTop(); } };
      container.appendChild(prevBtn);

      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        if (i === currentPage) btn.classList.add('active');
        btn.onclick = () => { currentPage = i; applyFilters(); scrollToTop(); };
        container.appendChild(btn);
      }

      const nextBtn = document.createElement('button');
      nextBtn.textContent = 'Successivo »';
      nextBtn.disabled = currentPage === totalPages;
      nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; applyFilters(); scrollToTop(); } };
      container.appendChild(nextBtn);
    }

    // Filtri
    function applyFilters() {
      const filters = {
        timestamp: document.getElementById('filter-timestamp')?.value.trim().toLowerCase() || '',
        src_ip: document.getElementById('filter-src-ip')?.value.trim().toLowerCase() || '',
        dest_ip: document.getElementById('filter-dest-ip')?.value.trim().toLowerCase() || '',
        proto: document.getElementById('filter-proto')?.value.trim().toLowerCase() || '',
        icmp_type: document.getElementById('filter-icmp-type')?.value.trim().toLowerCase() || '',
        action: document.getElementById('filter-action')?.value.trim().toLowerCase() || '',
        severity: document.getElementById('filter-severity')?.value.trim() || '',
        signature: document.getElementById('filter-signature')?.value.trim().toLowerCase() || '',
        category: document.getElementById('filter-category')?.value.trim().toLowerCase() || '',
        in_iface: document.getElementById('filter-in-iface')?.value.trim().toLowerCase() || ''

      };

      const filtered = allData.filter(record => {
        const recTimestamp = record.timestamp ? formatDate(record.timestamp).toLowerCase() : '';
        const recSrc = record.src_ip?.toLowerCase() || '';
        const recDest = record.dest_ip?.toLowerCase() || '';
        const recProto = record.proto?.toLowerCase() || '';
        const recIcmp = record.icmp_type !== undefined ? String(record.icmp_type).toLowerCase() : '';
        const recAction = record.alert?.action?.toLowerCase() || '';
        const recSeverity = record.alert?.severity !== undefined ? String(record.alert.severity) : '';
        const recSignature = record.alert?.signature?.toLowerCase() || '';
        const recCategory = record.alert?.category?.toLowerCase() || '';
        const recIn = record.alert?.in_iface?.toLowerCase() || '';

        return recTimestamp.includes(filters.timestamp)
          && recSrc.includes(filters.src_ip)
          && recDest.includes(filters.dest_ip)
          && recProto.includes(filters.proto)
          && recIcmp.includes(filters.icmp_type)
          && recAction.includes(filters.action)
          && (!filters.severity || recSeverity === filters.severity)
          && recSignature.includes(filters.signature)
          && recCategory.includes(filters.category)
          && recIn.includes(filters.in_iface);

      });

      const paginated = paginateData(filtered);
      renderTable(paginated);
      renderPagination(filtered.length);
    }

    // Scroll top
    function scrollToTop() { window.scrollTo({ top: 0, behavior: 'smooth' }); }

    // Auto-refresh
    function autoRefresh() {
      fetch('server.php', {
        headers: { 'Authorization': 'Bearer edjedeij83neuenf38unenfw9enpodd' }
      })
        .then(res => res.json())
        .then(data => {
          allData = data;
          applyFilters();
        })
        .catch(err => console.error('Errore fetch:', err));
    }

    // Event listeners filtri
    document.querySelectorAll('thead input').forEach(input => {
      input.addEventListener('input', () => {
        currentPage = 1;
        applyFilters();
      });
    });

    // Sniffer toggle
    const button = document.getElementById('sniffer-button');

    button.addEventListener('click', () => {
      // Cambia stato
      const isActive = button.value === "true";
      button.value = isActive ? "false" : "true";

      // Cambia testo
      button.innerText = isActive ? "Updates Paused" : "Live update ON";

      // Cambia classe per il colore
      if (isActive) {
        button.classList.remove('btn-green');
        button.classList.add('btn-red');
      } else {
        button.classList.remove('btn-red');
        button.classList.add('btn-green');
      }
    });


    // Intervallo auto-refresh
    setInterval(() => { if (button.value === "true") { autoRefresh(); } }, 1000);

    // Caricamento iniziale
    autoRefresh();


  }
})