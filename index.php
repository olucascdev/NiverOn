<?php

include_once '../config/database.php';


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NiverOn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-500 text-white p-2 text-center rounded mb-4">
                Cadastro realizado com sucesso!
            </div>
            <?php endif; ?>
        <h2 class="text-2xl font-bold text-center mb-6">Aniversáriante da Next🎉</h2>
        <form action="controllers/aniversariantes.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="nome" class="block text-gray-700 font-medium">Nome Completo</label>
                <input type="text" id="nome" name="nome" required
                    class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>
            <div>
                <label for="nome" class="block text-gray-700 font-medium">Instagram</label>
                <input type="text" id="instagram" name="instagram" 
                    class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>
            
            <div>
                <label for="data_nascimento" class="block text-gray-700 font-medium">Data de Aniversário (MM/DD/YY)</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required
                    class="w-full mt-1 p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>
            
            <div>
                <label class="block text-gray-700 font-medium">Tipo</label>
                <div class="flex space-x-4 mt-1">
                    <label class="flex items-center">
                        <input type="radio" name="tipo" value="professor" required class="mr-2"> Professor
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="tipo" value="aluno" required class="mr-2"> Aluno
                    </label>
                </div>
            </div>
            
            <div>
                <label for="foto" class="block text-gray-700 font-medium">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*" required
                    class="w-full mt-1 p-2 border rounded-lg">
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                Enviar
            </button>
        </form>
    </div>
</body>
</html>
