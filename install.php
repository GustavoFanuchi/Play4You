<?php
// CLI installer for Play4You
// Usage: php scripts/install.php

declare(strict_types=1);

function println(string $message = ''): void {
    fwrite(STDOUT, $message . PHP_EOL);
}

function readInput(string $prompt, ?string $default = null, bool $silent = false): string {
    $suffix = $default !== null && $default !== '' ? " [{$default}]" : '';
    if ($silent && stripos(PHP_OS, 'WIN') === 0) {
        // Fallback: no silent on Windows easily without stty; just normal input
        $silent = false;
    }
    fwrite(STDOUT, $prompt . $suffix . ': ');
    if ($silent) {
        // Disable echo on *nix for password input
        system('stty -echo');
    }
    $line = trim((string)fgets(STDIN));
    if ($silent) {
        system('stty echo');
        fwrite(STDOUT, PHP_EOL);
    }
    return $line !== '' ? $line : (string)($default ?? '');
}

function requireExtension(string $ext): void {
    if (!extension_loaded($ext)) {
        println("[ERRO] Extensão PHP requerida não carregada: {$ext}");
        exit(1);
    }
}

function path(string $relative): string {
    return realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR . ltrim($relative, DIRECTORY_SEPARATOR);
}

function ensureDir(string $dir): void {
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0775, true) && !is_dir($dir)) {
            println("[ERRO] Não foi possível criar diretório: {$dir}");
            exit(1);
        }
    }
}

function runSql(PDO $pdo, string $sqlFile): void {
    if (!is_file($sqlFile)) {
        println("[AVISO] Arquivo SQL não encontrado: {$sqlFile}");
        return;
    }
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        println("[AVISO] Não foi possível ler: {$sqlFile}");
        return;
    }
    // Split on semicolons that end statements (basic split; scripts aqui são simples)
    $statements = array_filter(array_map('trim', preg_split('/;\s*\n|;\s*$/m', $sql)));
    $pdo->beginTransaction();
    try {
        foreach ($statements as $statement) {
            if ($statement === '' || stripos($statement, '--') === 0) {
                continue;
            }
            $pdo->exec($statement);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        println('[ERRO] Falha ao executar SQL: ' . $e->getMessage());
        exit(1);
    }
}

function updateDatabaseConfig(string $host, string $db, string $user, string $pass): void {
    $configPath = path('config/database.php');
    $contents = file_get_contents($configPath);
    if ($contents === false) {
        println('[ERRO] Não foi possível ler config/database.php');
        exit(1);
    }

    $replacements = [
        "private $host = '" => $host,
        "private $username = '" => $user,
        "private $password = '" => $pass,
        "private $database = '" => $db,
    ];

    foreach ($replacements as $needle => $value) {
        $contents = preg_replace(
            "/(" . preg_quote($needle, '/') . ")[^']*(\');/",
            '$1' . addslashes($value) . '$2;',
            $contents,
            1
        );
    }

    if (file_put_contents($configPath, $contents) === false) {
        println('[ERRO] Não foi possível escrever config/database.php');
        exit(1);
    }
}

println('=== Play4You - Instalador CLI ===');

// Requisitos
if (PHP_SAPI !== 'cli') {
    println('[ERRO] Este instalador deve ser executado via CLI: php scripts/install.php');
    exit(1);
}

if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    println('[ERRO] PHP 8.0+ é requerido. Versão atual: ' . PHP_VERSION);
    exit(1);
}

requireExtension('pdo');
requireExtension('pdo_mysql');

// Inputs
$defaultHost = 'localhost';
$defaultDb = 'play4you';
$defaultUser = 'root';

$host = readInput('Host do MySQL', $defaultHost);
$database = readInput('Nome do banco de dados', $defaultDb);
$username = readInput('Usuário do MySQL', $defaultUser);
$password = readInput('Senha do MySQL (não será exibida)', '', true);

// Conexão sem DB para criar se necessário
try {
    $pdoRoot = new PDO(
        "mysql:host={$host};charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );
} catch (Throwable $e) {
    println('[ERRO] Não foi possível conectar ao MySQL: ' . $e->getMessage());
    exit(1);
}

// Criar DB se não existir
try {
    $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
} catch (Throwable $e) {
    println('[ERRO] Falha ao criar/verificar o banco: ' . $e->getMessage());
    exit(1);
}

// Conectar no DB alvo
try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Throwable $e) {
    println('[ERRO] Conexão ao banco selecionado falhou: ' . $e->getMessage());
    exit(1);
}

println('[OK] Conectado ao banco de dados.');

// Executar SQLs principais
$baseSql = path('scripts/database_setup.sql');
println('Executando scripts de criação de banco/tabelas...');
runSql($pdo, $baseSql);

// Executar scripts de chat/migrações se existirem
$extras = [
    path('scripts/create_chat_tables.sql'),
    path('scripts/migrate_chat_schema.sql'),
    path('scripts/create_contacts_table.sql'),
];
foreach ($extras as $extra) {
    if (is_file($extra)) {
        println('Aplicando: ' . basename($extra));
        runSql($pdo, $extra);
    }
}

// Seed opcional
$seed = strtolower(readInput('Deseja inserir dados de exemplo? (s/n)', 's'));
if ($seed === 's' || $seed === 'sim') {
    $seedSql = path('scripts/insert_sample_data.sql');
    println('Inserindo dados de exemplo...');
    runSql($pdo, $seedSql);
}

// Atualizar config/database.php
println('Atualizando credenciais em config/database.php...');
updateDatabaseConfig($host, $database, $username, $password);

// Diretórios de upload
$uploadsDir = path('public/uploads/products');
println('Garantindo diretórios de upload...');
ensureDir($uploadsDir);

println('');
println('Instalação concluída!');
println('- Acesse: http://localhost:8000');
println('- Para rodar o servidor: php -S localhost:8000 -t .');
println('');
exit(0);


