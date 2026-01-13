<?php
/* ===== NO DATABASE LOGIN CHECK ===== */

include 'header.php'; ?>

<div class="container mt-4">
    <h4 class="mb-3">Tambah Stok Tas</h4>

    <div id="alertBox"></div>

    <div class="card">
        <div class="card-body">
            <form id="createForm">

                <!-- PILIH TAS -->
                <div class="mb-3">
                    <label class="form-label">Pilih Tas</label>
                    <select class="form-control" id="tas_id" required>
                        <option value="">-- Pilih Tas --</option>
                    </select>
                </div>

                <!-- NAMA TAS OTOMATIS -->
                <div class="mb-3">
                    <label class="form-label">Nama Tas</label>
                    <input type="text" class="form-control" id="nama_tas" readonly>
                </div>

                <!-- HARGA OTOMATIS -->
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-control" id="harga" readonly>
                </div>

                <!-- TAMBAH STOK -->
                <div class="mb-3">
                    <label class="form-label">Tambah Stok</label>
                    <input type="number" class="form-control" id="stok" min="1" required>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
    // STATIC DATA TAS (NO DATABASE)
    const tasList = [
        { id: 1, nama_tas: 'Tas Kulit Hitam', harga: 150000 },
        { id: 2, nama_tas: 'Tas Travel Biru', harga: 200000 },
        { id: 3, nama_tas: 'Tas Nike Merah', harga: 100000 },
        { id: 4, nama_tas: 'Tas Kulit Coklat', harga: 180000 },
        { id: 5, nama_tas: 'Tas Travel Hijau', harga: 220000 }
    ];

    // LOAD DATA TAS
    function loadTas() {
        const select = document.getElementById('tas_id');

        tasList.forEach(t => {
            select.innerHTML += `
                <option value="${t.id}" data-harga="${t.harga}" data-nama="${t.nama_tas}">
                    ${t.nama_tas}
                </option>
            `;
        });
    }

    // SET HARGA DAN NAMA TAS OTOMATIS
    document.getElementById('tas_id').addEventListener('change', function () {
        const harga = this.options[this.selectedIndex].dataset.harga || 0;
        const nama = this.options[this.selectedIndex].dataset.nama || '';
        document.getElementById('harga').value = harga;
        document.getElementById('nama_tas').value = nama;
    });

    // SUBMIT FORM (NO DATABASE)
    document.getElementById('createForm').addEventListener('submit', (e) => {
        e.preventDefault();

        // Simulate success
        showAlert('Stok tas berhasil ditambahkan (simulasi tanpa database)', 'success');
        setTimeout(() => window.location.href = 'dashboard.php', 1500);
    });

    function showAlert(msg, type) {
        document.getElementById("alertBox").innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        setTimeout(() => document.getElementById("alertBox").innerHTML = '', 3000);
    }

    loadTas();
</script>

<?php include 'footer.php'; ?>
