<?php include 'header.php'; ?>

<div class="container mt-4">
<h3>Profil Saya</h3>

<div id="alertBox"></div>

<form id="profilForm" class="card p-3 shadow-sm">
    <input type="hidden" id="user_id">

    <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" id="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" id="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password Baru</label>
        <input type="password" id="password" class="form-control">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
    </div>

    <div class="mb-3">
        <label>No. HP</label>
        <input type="text" id="no_hp" class="form-control">
    </div>

    <button class="btn btn-primary">Update Profil</button>
    <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
</form>
</div>

<script>
const API_USER = '../user.php';
const userId = localStorage.getItem('user_id');

// Load profil user
async function loadProfil() {
    if (!userId) {
        alert('Silakan login terlebih dahulu');
        location.href = 'login.php';
        return;
    }

    const res = await fetch(`${API_USER}?id=${userId}`);
    const json = await res.json();
    const u = json.data[0];

    user_id.value = u.id;
    nama.value = u.nama;
    email.value = u.email;
    no_hp.value = u.no_hp ?? '';
}

// Update profil
profilForm.addEventListener('submit', async e => {
    e.preventDefault();

    const data = {
        id: user_id.value,
        nama: nama.value,
        email: email.value,
        no_hp: no_hp.value,
        password: password.value // boleh kosong
    };

    const res = await fetch(API_USER, {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });

    const json = await res.json();
    alert(json.message);
});

loadProfil();
</script>

<?php include 'footer.php'; ?>
