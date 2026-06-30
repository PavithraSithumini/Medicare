// ===== Shared data layer =====
async function getMedicines() {
    const response = await fetch('api/medicines.php');
    const result = await response.json();
    return result.success ? result.data : [];
}

async function getDashboardStats() {
    const response = await fetch('api/dashboard.php');
    const result = await response.json();
    return result.success ? result.data : { total: 0, safe: 0, expired: 0, expiring_soon: 0 };
}

async function saveMedicine(data) {
    const response = await fetch('api/medicines.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data).toString()
    });
    return response.json();
}

async function updateMedicine(data) {
    const response = await fetch('api/medicines.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data).toString()
    });
    return response.json();
}

async function deleteMedicineById(id) {
    const response = await fetch('api/medicines.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ med_id: id }).toString()
    });
    return response.json();
}

function generateId() {
    return Date.now().toString(36) + Math.random().toString(36).slice(2, 7);
}

function getDaysLeft(expiryDate) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const exp = new Date(expiryDate);
    exp.setHours(0, 0, 0, 0);
    return Math.ceil((exp - today) / (1000 * 60 * 60 * 24));
}

function getStatus(daysLeft) {
    if (daysLeft < 0) return 'expired';
    if (daysLeft <= 30) return 'expiring';
    return 'safe';
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function getStatusLabel(status) {
    const map = { safe: 'Safe', expiring: 'Expiring Soon', expired: 'Expired' };
    return map[status] || status;
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-triangle-exclamation'
    };
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <span class="toast-icon ${type}"><i class="fas ${icons[type] || icons.success}"></i></span>
        <span class="toast-msg">${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        if (toast.parentElement) toast.remove();
    }, 4500);
}
