<!-- Struktur Modal Custom -->
<!-- Tombol untuk membuka modal menggunakan data-toggle
<button type="button" class="btn btn-primary" data-toggle="custom-modal" data-target="customModal">
  Buka Modal Custom
</button> -->

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <!-- Header dengan flexbox -->
        <div class="custom-modal-header">
            <div class="custom-modal-title"></div>
            <span class="custom-modal-close" data-dismiss="custom-modal">&times;</span>
        </div>
        <div id="data-modal"></div>
        <div class="text-right">
            <button class="btn btn-secondary" data-dismiss="custom-modal">Tutup</button>
        </div>
    </div>
</div>

<script>
// ============================================
// 1. MEMBUKA MODAL dengan data-toggle
// ============================================
document.addEventListener('click', function(e) {
    // Cari tombol yang memiliki data-toggle="custom-modal"
    var toggleBtn = e.target.closest('[data-toggle="custom-modal"]');
    
    if (toggleBtn) {
        e.preventDefault();
        var targetId = toggleBtn.getAttribute('data-target');
        var modal = document.getElementById(targetId);
        
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Matikan scroll
        }
    }
});

// ============================================
// 2. MENUTUP MODAL dengan data-dismiss
// ============================================
document.addEventListener('click', function(e) {
    // Cari elemen yang memiliki data-dismiss="custom-modal"
    var dismissBtn = e.target.closest('[data-dismiss="custom-modal"]');
    
    if (dismissBtn) {
        e.preventDefault();
        // Cari modal terdekat dari tombol
        var modal = dismissBtn.closest('.custom-modal');
        
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Aktifkan scroll
        }
    }
});

// ============================================
// 3. TUTUP MODAL dengan klik di luar area
// ============================================
window.onclick = function(event) {
    if (event.target.classList.contains('custom-modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// ============================================
// 4. TUTUP MODAL dengan tombol ESC
// ============================================
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        var modals = document.querySelectorAll('.custom-modal');
        modals.forEach(function(modal) {
            if (modal.style.display === 'block') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }
});
</script>

<style>
/* Modal Background */
.custom-modal {
    display: none; 
    position: fixed; 
    z-index: 1050; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

/* Header Modal - Menggunakan Flexbox */
.custom-modal-header {
    display: flex;
    justify-content: space-between; /* Pisahkan judul dan tombol close */
    align-items: center; /* Sejajarkan secara vertikal di tengah
    margin-bottom: 10px; */
}

/* Judul Modal */
.modal-title {
    margin: 0; /* Hilangkan margin default h2
    font-size: 24px; */
    flex: 1; /* Opsional: biarkan judul mengambil ruang yang tersisa */
}

/* Modal Content */
.custom-modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 90%;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Close Button */
.custom-modal-close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.custom-modal-close:hover { color: black; }

/* Animasi */
@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsif */
@media (max-width: 600px) {
    .custom-modal-content {
        width: 95%;
        margin: 20% auto;
    }
}
</style>