<?php
class session
{
    public function killSession()
    {
        $_SESSION = []; // overwrite current session with empty array

        // if session uses cookies -- remove the session cookie also
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        //finally calling session_destoy() to end session
        session_destroy();
    }

    public function forgetSession()
    {
        //remove all session data and cookies
        $this->killSession();

        //redirect user to login page
        header("location:login.php");
        exit;
    }
}