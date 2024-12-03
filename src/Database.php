<?php

class Database
{
    private static $connection;

    public static function getConnection()
    {
        if (!self::$connection) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "precificador";

            // Criando a conexão com o banco de dados
            self::$connection = new mysqli($servername, $username, $password, $dbname);

            // Verificando se houve erro na conexão
            if (self::$connection->connect_error) {
                die("Erro ao conectar com o banco de dados: " . self::$connection->connect_error);
            }
        }

        return self::$connection;
    }
}
