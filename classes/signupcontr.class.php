<?php
class SignupController extends Signup
{
    public function signup($username, $password, $email)
    {
        // Validate input data
        if (empty($username) || empty($password) || empty($email)) {
            echo "Username, password, and email are required.";
        }

        // Check if the username is already taken
        if ($this->isUsernameTaken($username)) {
            echo "Username is already taken.";
        }

        // Check if the email is already taken
        if ($this->emailTaken($email)) {
            echo "Email is already taken.";
        }

        // Create a new user
        if ($this->createUser($username, $password, $email)) {
            // User created successfully
            echo "Signup successful. Welcome, $username!";
        } else {
            // Failed to create user
            echo "Signup failed. Please try again later.";
        }
    }
}
