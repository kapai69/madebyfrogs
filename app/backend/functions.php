<?php

function user_id()
{
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

function user_name()
{
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
}

function user_email()
{
    return array_var($_SESSION, 'user_email');
}

function user_is_admin()
{
    return (boolean) array_var($_SESSION, 'user_is_admin', false);
}

function include_javascript($file=null)
{
    static $javascripts = array();

    if (is_null($file)) {
        $out = "\n";
        foreach ($javascripts as $js) {
            $out .= '    <script src="javascripts/'.$js.'.js" type="text/javascript"></script>'."\n";
        }
        return $out;
    }
    $javascripts[] = trim($file);
}
