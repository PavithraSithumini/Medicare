// ===== Shared data layer =====
function getMedicines() {
    const stored = localStorage.getItem('meditrack_data');
    if (stored) {
        try {
            return JSON.parse(stored);
        } catch (e) {
            return [];
        }
    }
    // Seed with sample data
    const sample = [
        { id: '1', name: 'Vitamin C 1000mg', category: 'Vitamins', quantity: 60, expiry: '2026-06-13' },
        { id: '2', name: 'Ibuprofen 400mg', category: 'Pain Relief', quantity: 15, expiry: '2026-06-18' },
        { id: '3', name: 'Amoxicillin 250mg', category: 'Antibiotics', quantity: 10, expiry: '2026-07-08' },
        { id: '4', name: 'Metformin 500mg', category: 'Diabetes', quantity: 50, expiry: '2026-07-18' },
        { id: '5', name: 'Paracetamol 500mg', category: 'Pain Relief', quantity: 20, expiry: '2026-10-21' },
        { id: '6', name: 'Cetirizine 10mg', category: 'Antihistamines', quantity: 30, expiry: '2027-01-09' }
    ];
    saveMedicines(sample);
    return sample;
}

function saveMedicines(data) {
    localStorage.setItem('meditrack_data', JSON.stringify(data));
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

// ===== Toast notification =====
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