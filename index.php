<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/* lees de url uit en ga naar home/ of view/index */
$links = [];
$fourofour = true;
if (!empty($_GET['url'])) {
    $url = $_GET['url'];
    $links = explode('/', $url);
    $links[0] = ucfirst($links[0]);
    if ($links[0] == 'Home'  && $links[1] == '') {
        $links[1] = 'index';
        $fourofour = false;
    } else if ($links[0] == 'Index.php' || $links[0] == 'Index.html') {
        $links[0] = 'Home';
        $links[1] = 'index';
        $fourofour = false;
    } else if ($links[0] == 'View' && $links[1] == '') {
        $links[1] = 'list';
        $fourofour = false;
    }
} else {
    $links[0] = 'View';
    $links[1] = 'list';
    $fourofour = false;
}
$linknul = $links[0];
$linkeen = $links[1];
$pagecontroller = $linknul . 'Controller';
/* controle of controllers folder/bestand  bestaat */
if (file_exists('controllers/' . $linknul . 'Controller.class.php')) {
    require_once('controllers/' . $linknul . 'Controller.class.php');
    $loader = new FilesystemLoader(__DIR__ . '/views');
    $twig = new Environment($loader);
    $page = new $pagecontroller($twig);
    /* geen 404 melding */
    if (method_exists($page, $linkeen)) {
        echo $page->$linkeen();
        $fourofour = false;
        return;
    }
/* geen controllers folder & controller class geeft 404 melding */
} else {
    http_response_code(404);
    echo '404 Not found. <br><br>';
    return;
}
if ($fourofour == true) {
    http_response_code(404);
    echo '<br> 404 De pagina waar je naar zoekt is er helaas niet (meer).<br><br>';
}
