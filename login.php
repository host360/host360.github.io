<?php
session_start();

$file = 'random.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    if ($action === 'register') {
        if (isset($users[$username])) {
            echo json_encode(["status" => "error", "message" => "Username already exists!"]);
        } else {
            $users[$username] = $password;
            file_put_contents($file, json_encode($users));
            echo json_encode(["status" => "success", "message" => "Registration successful! You can now login."]);
        }
    } elseif ($action === 'login') {
        if (isset($users[$username]) && $users[$username] === $password) {
            $_SESSION['user'] = $username;
            echo json_encode(["status" => "success", "message" => "Login successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid username or password!"]);
        }
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mekaniks Login/Register</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap');

        :root {
            --bg-color: #0a0a0a;
            --text-color: #e0e0e0;
            --accent-color: #ff00ff;
            --secondary-accent: #00ffff;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Roboto Mono', monospace;
            background-color: var(--bg-color);
            color: var(--text-color);
            overflow: hidden;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            background: linear-gradient(45deg, #000, #222);
        }

        .login-box {
            background: rgba(34, 34, 34, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.3);
            text-align: center;
            position: relative;
            overflow: hidden;
            width: 300px;
        }

        .login-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                from 0deg at 50% 50%,
                var(--accent-color) 0deg,
                transparent 60deg,
                transparent 300deg,
                var(