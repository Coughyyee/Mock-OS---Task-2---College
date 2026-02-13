<?php
namespace Service;

require_once __DIR__ . '/../db/users.php';

use Database\UsersDatabase;

session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // store user inputs as variables
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Validate inputs are there.
    if (
        empty($email) ||
        empty($password) 
    ) {
        $_SESSION['error'] = 'Empty Input(s)';
        header('Location: ../../public/login.php');
        exit();
    }

    // ensure a valid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid Email';
        header('Location: ../../public/login.php');
        exit();
    }

    // ensure password is 8+ characters
    if (strlen($password) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters.';
        header('Location: ../../public/login.php');
        exit();
    }

    // ensure user with that email doesnt already exist
    $db = new UsersDatabase();
    $result = $db->getUserByEmail($email);
    if ($result === null) {
        $_SESSION['error'] = 'Account doesnt exists!';
        header('Location: ../../public/login.php');
        exit();
    }

    // Validate Password
    if (!password_verify($password, $result->password)) {
        $_SESSION['error'] = 'Incorrect password!';
        header('Location: ../../public/login.php');
        exit();
    }

    // retrieve user id
    $userId = $result->id;
    // is user an admin?
    $isAdmin = $result->isAdmin; 

    // store session tokens for the user account.
    $_SESSION['success'] = 'Account logged in successfully';
    $_SESSION['user-logged-in'] = true;
    $_SESSION['user-id'] = $userId;
    $_SESSION['user-email'] = $email;
    $_SESSION['user-admin'] = $isAdmin;
    header('Location: ../../public/index.php');
    exit();
}

header('Location: ../../public/login.php');