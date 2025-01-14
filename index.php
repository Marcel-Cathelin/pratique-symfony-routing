<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

require __DIR__ . '/vendor/autoload.php';

$listRoute = new Route('/');
$createRoute = new Route('/create');
$showRoute = new Route('/show/{id}');

$collection = new RouteCollection();
$collection->add('list', $listRoute);
$collection->add('show', $showRoute);
$collection->add('create', $createRoute);

$matcher = new UrlMatcher($collection, new RequestContext());
$generator = new UrlGenerator($collection, new RequestContext());

// dd($generator->generate('show', ['id' => 100]));

$pathInfo = $_SERVER['PATH_INFO'] ?? '/';

try {
    $currentRoute = $matcher->match($pathInfo);

    // echo "<pre>";
    // dump($currentRoute);
    // echo "<pre>"; 

    $page = $currentRoute['_route'];

    require_once "pages/$page.php";
} catch (ResourceNotFoundException $e) {
    require 'pages/404.php';
    return;
}

/**
 * LES PAGES DISPONIBLES
 * ---------
 * Afin de pouvoir être sur que le visiteur souhaite voir une page existante, on maintient ici une liste des pages existantes
 */
// $availablePages =  [
//     'list', 'show', 'create'
// ];

// Par défaut, la page qu'on voudra voir si on ne précise pas (par exemple sur /index.php) sera "list"
// $page = 'list';

// Si on nous envoi une page en GET, on la prend en compte (exemple : /index.php?page=create)
// if (isset($_GET['page'])) {
//     $page = $_GET['page'];
// }

// Si la page demandée n'existe pas (n'est pas dans le tableau $availablePages)
// On affiche la page 404
// if (!in_array($page, $availablePages)) {
//     require 'pages/404.php';
//     return;
// }

/**
 * ❌ ATTENTION DEMANDEE !
 * -----------
 * Ici, un moyen simple d'obeir au visiteur et de lui présenter ce qu'il demande c'est d'inclure le fichier qui porte le même nom que la 
 * variable $page. 
 * 
 * => EXTREMENT DANGEREUX ! Ca veut dire que le visiteur pilote l'inclusion de scripts PHP, quelqu'un de malin pourrait s'en servir pour inclure 
 * un script non prévu ou voulu. On est un peu protégé par la condition juste au dessus, mais c'est quand même HYPER LIMITE.
 * 
 * Comment allons nous réparer ça dans les prochaines sections ?
 * 
 * ❌ AUTRE PROBLEME DE TAILLE ICI : LE COUPLAGE DE L'URL ET DES NOMS DE FICHIERS
 * ------------
 * Le fichier que l'on va inclure porte le même nom que le paramètre $_GET['page']. C'est à dire que si on appelle /index.php?page=create
 * c'est le fichier pages/create.php qui va être inclus.
 * 
 * La conséquence, c'est que si demain je décide que le formulaire de création devrait se trouver sur /index.php?page=new il faudra que je
 * renomme forcément le fichier pages/create.php en pages/new.php et inversement (l'enfer)
 */
// require_once "pages/$page.php";
