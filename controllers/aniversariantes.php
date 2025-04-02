<?php
include_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $instagram = trim($_POST['instagram']);
    $data_nascimento = $_POST['data_nascimento'];
    $tipo = $_POST['tipo'];

    // Define o diretório com base no tipo
    $upload_base = '../upload/';
    $upload_dir = ($tipo === 'professor') ? $upload_base . 'professor/' : $upload_base . 'alunos/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Processamento do upload de foto
    $foto_nome_original = $_FILES['foto']['name'];
    $foto_extensao = pathinfo($foto_nome_original, PATHINFO_EXTENSION);
    $foto_nome_formatado = preg_replace("/[^a-zA-Z0-9]/", "_", $nome) . '_' . str_replace("-", "", $data_nascimento) . '.' . $foto_extensao;
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_caminho = $upload_dir . $foto_nome_formatado;
    
    if (move_uploaded_file($foto_tmp, $foto_caminho)) {
        $foto_caminho = str_replace('../', '', $foto_caminho); // Ajuste para salvar apenas o caminho relativo
    } else {
        die("Erro ao enviar a foto.");
    }

    try {
        $sql = "INSERT INTO aniversariantes (nome, instagram, data_nascimento, tipo, foto) VALUES (:nome,:instagram , :data_nascimento, :tipo, :foto)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':instagram' => $instagram,
            ':data_nascimento' => $data_nascimento,
            ':tipo' => $tipo,
            ':foto' => $foto_caminho
        ]);
        
        header("Location: ../index.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Erro ao cadastrar: " . $e->getMessage());
    }
} else {
    die("Método inválido.");
}

