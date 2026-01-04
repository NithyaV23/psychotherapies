<?php
$conn = new mysqli("localhost", "db_user", "db_pass", "db_name");
if ($conn->connect_error) {
    die("DB Error");
}
