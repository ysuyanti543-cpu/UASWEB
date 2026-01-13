case 'PUT':
    $input = json_decode(file_get_contents("php://input"), true);

    $sql = "UPDATE users SET 
            nama = ?, 
            email = ?, 
            no_hp = ?";

    $params = [
        $input['nama'],
        $input['email'],
        $input['no_hp']
    ];

    if (!empty($input['password'])) {
        $sql .= ", password = ?";
        $params[] = password_hash($input['password'], PASSWORD_DEFAULT);
    }

    $sql .= " WHERE id = ?";
    $params[] = $input['id'];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode([
        'status' => true,
        'message' => 'Profil berhasil diperbarui'
    ]);
break;
