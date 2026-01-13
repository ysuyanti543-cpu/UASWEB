
<?php include 'header.php'; ?>

<div class="container mt-4">
    <h2>Edit Tas</h2>

    <div id="alertBox"></div>

    <div class="card">
        <div class="card-body">
            <form id="editForm">
                <input type="hidden" id="tas_id">
                <div class="mb-3">
                    <label for="nama_tas" class="form-label">Nama Tas</label>
                    <input type="text" class="form-control" id="nama_tas" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="harga" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stok" required>
                </div>
                <div class="mb-3">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select class="form-control" id="kategori_id">
                        <option value="">Pilih Kategori</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar (URL)</label>
                    <input type="url" class="form-control" id="gambar">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
    const API_TAS = 'tas.php';
    const API_KATEGORI = 'kategori.php';

    let currentTasId = localStorage.getItem('editTasId');

    // Load categories
    async function loadKategori() {
        try {
            const response = await fetch(API_KATEGORI);
            const result = await response.json();
            if (result.success) {
                const select = document.getElementById('kategori_id');
                result.data.forEach(kat => {
                    select.innerHTML += `<option value="${kat.id}">${kat.nama_kategori}</option>`;
                });
            }
        } catch (error) {
            console.error('Error loading kategori:', error);
        }
    }

    // Load tas data
    async function loadTasData() {
        if (!currentTasId) {
            alert('ID tas tidak ditemukan');
            window.location.href = 'dashboard.php';
            return;
        }

        try {
            const response = await fetch(`${API_TAS}?id=${currentTasId}`);
            const result = await response.json();
            if (result.success && result.data.length > 0) {
                const tas = result.data[0];
                document.getElementById('tas_id').value = tas.id;
                document.getElementById('nama_tas').value = tas.nama_tas;
                document.getElementById('deskripsi').value = tas.deskripsi || '';
                document.getElementById('harga').value = tas.harga;
                document.getElementById('stok').value = tas.stok;
                document.getElementById('kategori_id').value = tas.kategori_id || '';
                document.getElementById('gambar').value = tas.gambar || '';
            } else {
                alert('Data tas tidak ditemukan');
                window.location.href = 'dashboard.php';
            }
        } catch (error) {
            console.error('Error loading tas data:', error);
            alert('Error loading data');
        }
    }

    // Handle form submit
    document.getElementById('editForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = {
            id: document.getElementById('tas_id').value,
            nama_tas: document.getElementById('nama_tas').value,
            deskripsi: document.getElementById('deskripsi').value,
            harga: parseFloat(document.getElementById('harga').value),
            stok: parseInt(document.getElementById('stok').value),
            kategori_id: document.getElementById('kategori_id').value || null,
            gambar: document.getElementById('gambar').value
        };

        try {
            const response = await fetch(API_TAS, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            showAlert(result.message, result.success ? 'success' : 'danger');
            if (result.success) {
                setTimeout(() => window.location.href = 'dashboard.php', 2000);
            }
        } catch (error) {
            showAlert('Error updating tas', 'danger');
        }
    });

    // Alert function
    function showAlert(msg, type) {
        document.getElementById("alertBox").innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        setTimeout(() => {
            document.getElementById("alertBox").innerHTML = '';
        }, 3000);
    }

    // Load on page load
    loadKategori();
    loadTasData();
</script>

<?php include 'footer.php'; ?>
