<?php

function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

function redirect($url)
{
    header("Location: " . $url);
    exit;
}

function show_message()
{
    if (isset($_SESSION['success'])) {

        echo '
        <div class="alert alert-success alert-dismissible fade show">
            ' . $_SESSION['success'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        ';

        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {

        echo '
        <div class="alert alert-danger alert-dismissible fade show">
            ' . $_SESSION['error'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        ';

        unset($_SESSION['error']);
    }
}

?>