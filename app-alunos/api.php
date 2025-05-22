<?php
// Início do script PHP.

// Variáveis para configurar a conexão com o banco de dados.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "alunos_db";

// Configurações de CORS (Cross-Origin Resource Sharing) para permitir que o frontend acesse esta API.
header("Access-Control-Allow-Origin: *"); // Permite requisições de qualquer origem.
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos HTTP permitidos.
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cabeçalhos permitidos na requisição.
header("Content-Type: application/json; charset=UTF-8"); // Define que a resposta será JSON.

// Lida com requisições OPTIONS (preflight) enviadas por navegadores.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Tenta estabelecer uma conexão com o banco de dados usando PDO.
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura PDO para lançar exceções em erros.
} catch(PDOException $e) {
    // Em caso de falha na conexão, retorna um erro 500 e encerra.
    http_response_code(500);
    echo json_encode(["message" => "Erro de conexão com o banco de dados: " . $e->getMessage()]);
    exit();
}

// Lógica para lidar com diferentes métodos de requisição HTTP (GET, POST).

// Se a requisição for GET, busca a lista de alunos.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepara e executa a consulta para selecionar todos os alunos, usando aliases para os nomes das colunas.
        $stmt = $conn->prepare("SELECT id, nome AS name, ra AS student_id, email, curso AS course FROM alunos ORDER BY nome ASC");
        $stmt->execute();
        
        // Retorna todos os resultados como um array associativo em formato JSON.
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($students);
    } catch (PDOException $e) {
        // Em caso de erro na consulta, retorna um erro 500.
        http_response_code(500);
        echo json_encode(["message" => "Erro ao buscar alunos: " . $e->getMessage()]);
    }
}

// Se a requisição for POST, adiciona um novo aluno.
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decodifica o corpo da requisição JSON.
    $data = json_decode(file_get_contents("php://input"));

    // Validação de campos obrigatórios.
    if (empty($data->name) || empty($data->student_id) || empty($data->email) || empty($data->course)) {
        http_response_code(400); // Bad Request.
        echo json_encode(["message" => "Todos os campos são obrigatórios."]);
        exit();
    }

    // Atribui os dados a variáveis.
    $name = $data->name;
    $student_id = $data->student_id;
    $email = $data->email;
    $course = $data->course;

    try {
        // Prepara e executa a consulta SQL para inserir um novo aluno, usando placeholders para segurança.
        $stmt = $conn->prepare("INSERT INTO alunos (nome, ra, email, curso) VALUES (:name, :student_id, :email, :course)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':course', $course);
        $stmt->execute();

        // Retorna o ID do aluno inserido e uma mensagem de sucesso com status 201 (Created).
        $lastId = $conn->lastInsertId();
        http_response_code(201);
        echo json_encode([
            "message" => "Aluno adicionado com sucesso!",
            "id" => $lastId,
            "name" => $name,
            "student_id" => $student_id,
            "email" => $email,
            "course" => $course
        ]);

    } catch (PDOException $e) {
        // Em caso de erro na inserção, retorna um erro 500.
        http_response_code(500);
        echo json_encode(["message" => "Erro ao adicionar aluno: " . $e->getMessage()]);
    }
}

// Para qualquer outro método HTTP não suportado.
else {
    http_response_code(405); // Method Not Allowed.
    echo json_encode(["message" => "Método não permitido."]);
}

// Fecha a conexão com o banco de dados.
$conn = null;
?>