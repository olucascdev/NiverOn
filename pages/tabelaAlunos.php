<?php
include_once '../config/database.php';

// Obtém o mês e o tipo do filtro, se existirem
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'aluno';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registros_por_pagina = 10;
$offset = ($pagina - 1) * $registros_por_pagina;

try {
    // Contar o total de registros para paginação
    $sql_count = "SELECT COUNT(*) FROM aniversariantes WHERE tipo = :tipo AND MONTH(data_nascimento) = :mes";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute([':tipo' => $tipo, ':mes' => $mes]);
    $total_registros = $stmt_count->fetchColumn();
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    // Consulta para obter registros filtrando pelo mês, tipo e aplicando a paginação
    $sql = "SELECT * FROM aniversariantes WHERE tipo = :tipo AND MONTH(data_nascimento) = :mes ORDER BY data_nascimento ASC LIMIT :offset, :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $registros_por_pagina, PDO::PARAM_INT);
    $stmt->execute();
    $aniversariantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar aniversariantes: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Aniversariantes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <h2 class="text-2xl font-bold text-center mb-6">🎉 Lista de Aniversariantes 🎉</h2>
        
        <!-- Filtros por mês e tipo -->
        <form method="GET" class="mb-4 flex justify-center space-x-4">
            <div>
                <label for="mes" class="font-medium">Filtrar por mês:</label>
                <select name="mes" id="mes" class="p-2 border rounded-lg" onchange="this.form.submit()">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo ($mes == $i) ? 'selected' : ''; ?>>
                            <?php echo DateTime::createFromFormat('!m', $i)->format('F'); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="tipo" class="font-medium">Filtrar por tipo:</label>
                <select name="tipo" id="tipo" class="p-2 border rounded-lg" onchange="this.form.submit()">
                    <option value="aluno" <?php echo ($tipo == 'aluno') ? 'selected' : ''; ?>>Aluno</option>
                    <option value="professor" <?php echo ($tipo == 'professor') ? 'selected' : ''; ?>>Professor</option>
                </select>
            </div>
        </form>
        
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 p-2">Nome</th>
                    <th class="border border-gray-300 p-2">Instagram</th>
                    <th class="border border-gray-300 p-2">Data de Aniversário</th>
                    <th class="border border-gray-300 p-2">Download</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aniversariantes as $aniversariante): ?>
                    <tr class="text-center">
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($aniversariante['nome']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($aniversariante['instagram']); ?></td>

                        <td class="border border-gray-300 p-2"><?php echo date("d/m/Y", strtotime($aniversariante['data_nascimento'])); ?></td>
                        <td class="border border-gray-300 p-2">
                            <a href="../<?php echo htmlspecialchars($aniversariante['foto']); ?>" download="<?php echo basename($aniversariante['foto']); ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Download</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="mt-4 flex justify-center space-x-2">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?mes=<?php echo $mes; ?>&tipo=<?php echo $tipo; ?>&pagina=<?php echo $i; ?>"
                   class="px-4 py-2 border rounded-lg <?php echo ($pagina == $i) ? 'bg-blue-500 text-white' : 'bg-gray-200'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
