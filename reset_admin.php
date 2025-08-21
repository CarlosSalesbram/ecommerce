<?php

require_once("vendor/autoload.php"); // Autoload do Composer
use Hcode\DB\Sql;

// Inicia a conexão com o banco
$sql = new Sql();

// Cria o hash da nova senha
$hash = password_hash("admin", PASSWORD_DEFAULT);

// Atualiza o usuário admin no banco
$sql->query("UPDATE tb_users SET despassword = :HASH WHERE deslogin = :LOGIN", [
    ":HASH" => $hash,
    ":LOGIN" => "admin"
]);

echo "Senha do admin redefinida com sucesso!";
