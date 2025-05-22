<?php
$conn = new mysqli("localhost", "root", "", "gestao_alunos"); // Conexão com o banco de dados 'gestao_alunos' [cite: 1]

$search = $_GET['search'] ?? ''; // Obtém o termo de busca da URL [cite: 1]
// Ajusta a query para buscar por nome, RA, email ou curso
$query = "SELECT * FROM alunos WHERE nome LIKE '%$search%' OR ra LIKE '%$search%' OR email LIKE '%$search%' OR curso LIKE '%$search%'"; // Consulta para buscar alunos [cite: 2]
$result = $conn->query($query); // Executa a consulta [cite: 2]
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Alunos</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function editarCampo(id, coluna, valorAtual) {
            // Fechar edições anteriores antes de abrir uma nova
            let camposEditaveis = document.querySelectorAll(".editable-field"); // Seleciona campos editáveis [cite: 4]
            camposEditaveis.forEach(campo => { // Itera sobre campos editáveis [cite: 4]
                let input = campo.querySelector("input");
                if (input) {
                    // Chama salvarEdicao para o campo anterior
                    salvarEdicao(input.dataset.id, input.dataset.coluna);
                }
            });

            let campo = document.getElementById(`campo-${id}-${coluna}`); // Obtém o elemento do campo [cite: 5]

            let divContainer = document.createElement("div");
            divContainer.classList.add("editable-field");

            let input = document.createElement("input");
            input.type = "text";
            input.value = valorAtual; // Define o valor inicial do input [cite: 6]
            input.dataset.id = id; // Armazena o ID do aluno [cite: 6]
            input.dataset.coluna = coluna; // Armazena o nome da coluna [cite: 6]

            let botaoSalvar = document.createElement("button");
            botaoSalvar.innerText = "OK";
            botaoSalvar.classList.add("save-btn"); // Adiciona classe CSS para o botão salvar [cite: 7]
            botaoSalvar.onclick = function() { // Define a função de clique para salvar [cite: 7]
                salvarEdicao(id, coluna);
            };

            // Salvar ao pressionar "Enter"
            input.addEventListener("keypress", function(event) { // Adiciona evento de tecla [cite: 8]
                if (event.key === "Enter") { // Verifica se a tecla é "Enter" [cite: 8]
                    salvarEdicao(id, coluna);
                }
            });

            divContainer.appendChild(input); // Adiciona input ao container [cite: 9]
            divContainer.appendChild(botaoSalvar); // Adiciona botão ao container [cite: 9]

            campo.innerHTML = ""; // Limpa o conteúdo original do campo
            campo.appendChild(divContainer); // Adiciona o container editável ao campo
            input.focus(); // Foca no input
        }

        function salvarEdicao(id, coluna) {
            let inputCampo = document.querySelector(`input[data-id='${id}'][data-coluna='${coluna}']`); // Seleciona o input do campo [cite: 10]
            let novoValor = inputCampo.value; // Obtém o novo valor do input [cite: 10]

            // Validação simples para e-mail e RA (se for o caso)
            if (coluna === 'email' && !isValidEmail(novoValor)) {
                exibirNotificacao("error", "E-mail inválido!");
                // Reverter o campo ou não salvar a edição se a validação falhar
                document.getElementById(`campo-${id}-${coluna}`).innerHTML = inputCampo.dataset.originalValue; // Assumindo que você armazenaria o valor original em dataset
                return;
            }
            // Adicionar validação para RA se necessário (ex: números e tamanho específico)

            fetch(`edit.php?id=${id}&coluna=${coluna}&valor=${encodeURIComponent(novoValor)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById(`campo-${id}-${coluna}`).innerHTML = novoValor;
                    exibirNotificacao("success", "Edição salva com sucesso!");
                })
                .catch(error => {
                    console.error("Erro ao salvar:", error);
                    exibirNotificacao("error", "Erro ao salvar edição."); // Exibe erro [cite: 11]
                });
        }

        function isValidEmail(email) {
            // Regex simples para validação de e-mail
            return /\S+@\S+\.\S+/.test(email);
        }

        // Função para exibir notificações
        function exibirNotificacao(tipo, mensagem) {
            let notificacao = document.createElement("div");
            notificacao.classList.add("notification", tipo); // Adiciona classes CSS [cite: 12]
            notificacao.innerText = mensagem; // Define o texto da notificação [cite: 12]
            document.body.appendChild(notificacao); // Adiciona a notificação ao corpo do documento [cite: 13]

            setTimeout(() => { // Define um temporizador [cite: 14]
                notificacao.remove(); // Remove a notificação após 3 segundos [cite: 14]
            }, 3000);
        }
    </script>
</head>
<body>
    <h1>Consulta de Alunos</h1>

    <button class="back-button" onclick="window.location.href='index.php'">⬅ Voltar ao Cadastro</button>

    <form method="GET">
        <input type="text" name="search" placeholder="Buscar aluno (Nome, RA, Email, Curso)">
        <button type="submit">Pesquisar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>R.A.</th> <th>E-mail</th> <th>Curso</th>  <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?> <tr>
                <td id="campo-<?= $row['id'] ?>-nome" onclick="editarCampo(<?= $row['id'] ?>, 'nome', '<?= htmlspecialchars($row['nome']) ?>')"><?= htmlspecialchars($row["nome"]) ?></td>
                <td id="campo-<?= $row['id'] ?>-ra" onclick="editarCampo(<?= $row['id'] ?>, 'ra', '<?= htmlspecialchars($row['ra']) ?>')"><?= htmlspecialchars($row["ra"]) ?></td>
                <td id="campo-<?= $row['id'] ?>-email" onclick="editarCampo(<?= $row['id'] ?>, 'email', '<?= htmlspecialchars($row['email']) ?>')"><?= htmlspecialchars($row["email"]) ?></td>
                <td id="campo-<?= $row['id'] ?>-curso" onclick="editarCampo(<?= $row['id'] ?>, 'curso', '<?= htmlspecialchars($row['curso']) ?>')"><?= htmlspecialchars($row["curso"]) ?></td>
                <td class="action-buttons">
                    <a href="delete.php?id=<?= $row["id"] ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja apagar este aluno?');">🗑 Apagar</a> </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="export-buttons">
        <a href="export_xls.php" class="download-btn"> Baixar XLS</a>
        <a href="export_json.php" class="download-btn"> Baixar JSON</a>
    </div>
</body>
<footer>
    <p>&copy; <?= date("Y") ?> - Olha pra cá não</p> </footer>
</html>