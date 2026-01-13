<?php
require_once '../koneksi/connection.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getPenjualan();
        break;
    case 'POST':
        createPenjualan();
        break;
    case 'PUT':
        updatePenjualan();
        break;
    case 'DELETE':
        deletePenjualan();
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

function getPenjualan() {
    global $database_connection;
    try {
        $stmt = $database_connection->query("
            SELECT p.*, t.nama_tas, u.nama_depan, u.nama_belakang, u.username
            FROM penjualan p
            JOIN tas t ON p.tas_id = t.id
            JOIN data_pendaftar u ON p.user_id = u.id
            ORDER BY p.tanggal_penjualan DESC
        ");
        $penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $penjualan]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error fetching data']);
    }
}

function createPenjualan() {
    global $database_connection;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['user_id']) || !isset($data['tas_id']) || !isset($data['jumlah'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Required fields missing']);
        return;
    }
    
    try {
        // Get tas price
        $stmt = $database_connection->prepare("SELECT harga, stok FROM tas WHERE id = ?");
        $stmt->execute([$data['tas_id']]);
        $tas = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$tas) {
            echo json_encode(['success' => false, 'message' => 'Tas not found']);
            return;
        }
        
        if ($tas['stok'] < $data['jumlah']) {
            echo json_encode(['success' => false, 'message' => 'Stok tidak cukup']);
            return;
        }
        
        $total_harga = $tas['harga'] * $data['jumlah'];
        
        // Insert penjualan
        $stmt = $database_connection->prepare("
            INSERT INTO penjualan (user_id, tas_id, jumlah, total_harga) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$data['user_id'], $data['tas_id'], $data['jumlah'], $total_harga]);
        
        // Update stok
        $stmt = $database_connection->prepare("UPDATE tas SET stok = stok - ? WHERE id = ?");
        $stmt->execute([$data['jumlah'], $data['tas_id']]);
        
        echo json_encode(['success' => true, 'message' => 'Penjualan created successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error creating penjualan']);
    }
}

function updatePenjualan() {
    global $database_connection;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID required']);
        return;
    }
    
    try {
        $stmt = $database_connection->prepare("
            UPDATE penjualan SET user_id = ?, tas_id = ?, jumlah = ?, total_harga = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $data['user_id'], 
            $data['tas_id'], 
            $data['jumlah'], 
            $data['total_harga'], 
            $data['id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Penjualan updated successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error updating penjualan']);
    }
}

function deletePenjualan() {
    global $database_connection;
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID required']);
        return;
    }
    
    try {
        $stmt = $database_connection->prepare("DELETE FROM penjualan WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Penjualan deleted successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error deleting penjualan']);
    }
}
?>
