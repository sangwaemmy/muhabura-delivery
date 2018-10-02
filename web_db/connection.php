<?php

    class dbconnection {

        function openconnection() {
            $db = new PDO('mysql:host=localhost;dbname=accounting_db;charset=utf8mb4', 'sangwa', 'A.manigu125');
            return $db;
        }

    }
    