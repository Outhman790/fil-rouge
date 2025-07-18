<?php
class Login extends DB
{
    public function getUser($email, $password)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM residents WHERE email = ? ;');
        if (!$stmt->execute([$email])) {
            header("location: ../login.php?error=stmtfailed");
            exit();
        }

        if ($stmt->rowCount() == 0) {
            header("location: ../login.php?error=usernotfound");
            exit();
        }

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        $checkedPassword = password_verify($password, $userData['password']);

        if ($checkedPassword == false) {
            header("location: ../login.php?error=wrongpassword");
            exit();
        } elseif ($checkedPassword == true) {
            session_start();
            $_SESSION['fName'] = $userData['fName'];
            $_SESSION['lName'] = $userData['lName'];
            $_SESSION['email'] = $userData['email'];
            $_SESSION['resident_id'] = $userData['resident_id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['status'] = $userData['status'];
            
            // Redirect based on user status
            if ($userData['status'] === 'Admin') {
                header('location: ../index.php');
            } else {
                header('location: ../homepage.php');
            }
            exit();
        }
    }
}
