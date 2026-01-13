<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori - Penjualan Tas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .category-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .category-header {
            padding: 20px;
            font-weight: 600;
        }
        .category-body {
            padding: 20px;
        }
        .category-footer {
            background: #f8f9fa;
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
        }
        .btn-add-category {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-add-category:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background: #f8f9fa;
        }
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .modal-header {
            border-bottom: 1px solid #dee2e6;
            border-radius: 15px 15px 0 0;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        .navbar-brand {
            font-weight: 700;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-shopping-bag me-2"></i>Penjualan Tas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Halaman.php">Halaman Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kategori.php">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2">
                        <i class="fas fa-tags text-primary me-2"></i>Manajemen Kategori Tas
                    </h2>
                    <p class="text-muted mb-0">Kelola kategori produk tas Anda dengan mudah</p>
                </div>
                <button class="btn btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-2"></i>Tambah Kategori
                </button>
            </div>
        </div>

        <!-- Alert Box -->
        <div id="alertBox"></div>

        <!-- Categories Grid -->
        <div class="row" id="categoriesGrid">
            <!-- Categories will be loaded here -->
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-success me-2"></i>Tambah Kategori Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_kategori" placeholder="Masukkan nama kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" rows="3" placeholder="Deskripsi kategori (opsional)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success" onclick="addCategory()">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit text-primary me-2"></i>Edit Kategori
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm">
                        <input type="hidden" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="edit_nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" onclick="updateCategory()">
                        <i class="fas fa-save me-1"></i>Update
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
const API_KATEGORI = '../kategori.php';

// Color palette for category cards
const colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
let colorIndex = 0;

// ================= LOAD CATEGORIES =================
async function loadCategories() {
    try {
        const response = await fetch(API_KATEGORI);
        const result = await response.json();
        if (result.success) {
            displayCategories(result.data);
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        showAlert('Error loading categories', 'danger');
    }
}

function displayCategories(categories) {
    const grid = document.getElementById('categoriesGrid');
    grid.innerHTML = '';

    if (categories.length === 0) {
        grid.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <h5>Belum ada kategori</h5>
                    <p>Klik tombol "Tambah Kategori" untuk membuat kategori pertama.</p>
                </div>
            </div>
        `;
        return;
    }

    categories.forEach(category => {
        const color = colors[colorIndex % colors.length];
        colorIndex++;

        grid.innerHTML += `
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-${color} shadow-sm category-card">
                    <div class="card-header bg-${color} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-tag me-2"></i>${category.nama_kategori}
                            </h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="editCategory(${category.id}, '${category.nama_kategori}', '${category.deskripsi || ''}')">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory(${category.id}, '${category.nama_kategori}')">
                                        <i class="fas fa-trash me-2"></i>Hapus
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            ${category.deskripsi || '<em class="text-muted">Tidak ada deskripsi</em>'}
                        </p>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Dibuat: ${new Date(category.created_at).toLocaleDateString('id-ID')}
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">ID: ${category.id}</small>
                            <span class="badge bg-${color}">${getCategoryStats(category.id)} produk</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
}

function getCategoryStats(categoryId) {
    // This would normally fetch from API, but for demo we'll return a placeholder
    return Math.floor(Math.random() * 10);
}

// ================= ADD CATEGORY =================
async function addCategory() {
    const nama = document.getElementById('nama_kategori').value.trim();
    const deskripsi = document.getElementById('deskripsi').value.trim();

    if (!nama) {
        showAlert('Nama kategori harus diisi', 'warning');
        return;
    }

    try {
        const response = await fetch(API_KATEGORI, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nama_kategori: nama,
                deskripsi: deskripsi
            })
        });

        const result = await response.json();

        if (result.success) {
            showAlert(result.message, 'success');
            document.getElementById('addCategoryForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
            loadCategories();
        } else {
            showAlert(result.message, 'danger');
        }
    } catch (error) {
        showAlert('Error adding category', 'danger');
    }
}

// ================= EDIT CATEGORY =================
function editCategory(id, nama, deskripsi) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama_kategori').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;

    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}

async function updateCategory() {
    const id = document.getElementById('edit_id').value;
    const nama = document.getElementById('edit_nama_kategori').value.trim();
    const deskripsi = document.getElementById('edit_deskripsi').value.trim();

    if (!nama) {
        showAlert('Nama kategori harus diisi', 'warning');
        return;
    }

    try {
        const response = await fetch(API_KATEGORI, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                nama_kategori: nama,
                deskripsi: deskripsi
            })
        });

        const result = await response.json();

        if (result.success) {
            showAlert(result.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('editCategoryModal')).hide();
            loadCategories();
        } else {
            showAlert(result.message, 'danger');
        }
    } catch (error) {
        showAlert('Error updating category', 'danger');
    }
}

// ================= DELETE CATEGORY =================
async function deleteCategory(id, nama) {
    if (!confirm(`Apakah Anda yakin ingin menghapus kategori "${nama}"?\n\nSemua tas dalam kategori ini akan kehilangan referensi kategori.`)) {
        return;
    }

    try {
        const response = await fetch(`${API_KATEGORI}?id=${id}`, {
            method: 'DELETE'
        });

        const result = await response.json();

        if (result.success) {
            showAlert(result.message, 'success');
            loadCategories();
        } else {
            showAlert(result.message, 'danger');
        }
    } catch (error) {
        showAlert('Error deleting category', 'danger');
    }
}

// ================= ALERT BOOTSTRAP =================
function showAlert(msg, type) {
    document.getElementById("alertBox").innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    setTimeout(() => {
        document.getElementById("alertBox").innerHTML = '';
    }, 5000);
}

// ================= FIRST LOAD =================
loadCategories();
</script>

<style>
.category-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.dropdown-menu {
    min-width: 120px;
}

.badge {
    font-size: 0.75em;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
