<?php
class LoginController extends Login
{
    protected $email;
    protected $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    private function emptyInput()
    {
        if (empty($this->email) || empty($this->password)) {
            return false;
        }
        return true;
    }

    public function loginUser()
    {
        if (!$this->emptyInput()) {
            header('location: ../login.php');
            exit();
        }

        $login = new Login();
        $login->getUser($this->email, $this->password);
    }
}
