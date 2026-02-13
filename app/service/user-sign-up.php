<?php

namespace Service;

require_once __DIR__ . '/../db/users.php';
require_once __DIR__ . '/../models/createUserDto.php';

use Models\CreateUserDto;
use Database\UsersDatabase;

session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // store user inputs as variables
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $verifyPassword = $_POST['verify-password'] ?? null;

    // Validate inputs are there.
    if (
        empty($email) ||
        empty($password) ||
        empty($verifyPassword) 
    ) {
        $_SESSION['error'] = 'Empty Input(s)';
        header('Location: ../../public/sign-up.php');
        exit();
    }

    // ensure a valid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid Email';
        header('Location: ../../public/sign-up.php');
        exit();
    }

    // ensure password is 8+ characters
    if (strlen($password) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters.';
        header('Location: ../../public/sign-up.php');
        exit();
    }

    // ensure user with that email doesnt already exist
    $db = new UsersDatabase();
    $result = $db->getUserByEmail($email);
    if ($result) {
        $_SESSION['error'] = 'Account already exists!';
        header('Location: ../../public/sign-up.php');
        exit();
    }

    // ensure passwords match
    if ($password !== $verifyPassword) {
        $_SESSION['error'] = 'Passwords don\'t match.';
        header('Location: ../../public/sign-up.php');
        exit();
    }

    // hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create DTO
    $user = new CreateUserDto(
        $email,
        $hashedPassword
    );

    // create the user and insert them into the database.
    $result = $db->createUser($user);
    if ($result === false ) {
        $_SESSION['error'] = 'Error occured. Please try again.';
        header('Location: ../../public/sign-up.php');
        exit();
    }

    // now user has been created, fetch their id from database
    $result = $db->getUserByEmail($email);
    $userId = $result->id;
    // is user an admin?
    $isAdmin = $result->isAdmin;

    // store session tokens for the user account.
    $_SESSION['success'] = 'Account created successfully';
    $_SESSION['user-logged-in'] = true;
    $_SESSION['user-id'] = $userId;
    $_SESSION['user-email'] = $email;
    $_SESSION['user-admin'] = $isAdmin;
    header('Location: ../../public/index.php');
    exit();
}

header('Location: ../../public/sign-up.php');