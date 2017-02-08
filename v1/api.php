<?php
/**
 * Created by PhpStorm.
 * User: nathan brooks
 * Date: 11/30/2016
 */
error_reporting(0);

use CS4450\Http;
use CS4450\Controllers;

require_once 'config.php';
require_once 'vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($baseURI) {

	/*
		// this is an example of how to get data after a ?
		// this was stolen from another student, he only allowed a single attribute after the ?
		// we will need to make it a little more advanced if we need more data attributes
		
		$uri = $_SERVER['REQUEST_URI'];
        $pos = strpos($uri, '?');
        if ($pos !== false) {
            $uri = substr($uri, $pos+1);
            $args['BY'] = $uri;
        }
        return (new Eportfolio\Controllers\ClassController)->getClass($args);
	*/
	
	/*
	// we built this as a test of the database
	// it gets all of Brad Peterson's roles
	
		SELECT * 
		FROM Users u
		JOIN UserRoles ur
		ON u.Id = ur.userID
		join Roles r
		ON ur.roleID = r.id
		WHERE u.ID = 887969243;
	
	*/
	
	
	
	$handleGetAllColleges = function(){
        return (new CS4450\Controllers\CollegesController)->getAllColleges();
    };
	
	$handleGetCollegeByCode = function($args){
		return (new CS4450\Controllers\CollegesController)->getCollegeByCode($args);
	};
	
	//*********************************************************************************
	
	$handleGetAllRoles = function(){
		return (new CS4450\Controllers\RolesController)->getAllRoles();
	};
	
	$handleGetRoleByID = function($args){
		return (new CS4450\Controllers\RolesController)->getRoleByID($args);
	};
	
	//*********************************************************************************
	
	$handleGetAllCategories = function(){
		return (new CS4450\Controllers\CategoriesController)->getAllCategories();
	};
	
	$handleGetCategoryByID = function($args){
		return (new CS4450\Controllers\CategoriesController)->getCategoryByID($args);
	};
	
	//*********************************************************************************
	
	$r->addRoute('GET',     $baseURI . '/colleges/',           	$handleGetAllColleges);
    $r->addRoute('GET',     $baseURI . '/colleges',            	$handleGetAllColleges);
	$r->addRoute('GET',     $baseURI . '/colleges/{code:\d+}',   $handleGetCollegeByCode);
	
	$r->addRoute('GET',     $baseURI . '/roles/{id:\d+}',   $handleGetRoleByID);
	$r->addRoute('GET',     $baseURI . '/roles/',   $handleGetAllRoles);
	$r->addRoute('GET',     $baseURI . '/roles',   $handleGetAllRoles);
	
	$r->addRoute('GET',     $baseURI . '/categories',   $handleGetAllCategories);
	$r->addRoute('GET',     $baseURI . '/categories/',   $handleGetAllCategories);
	$r->addRoute('GET',     $baseURI . '/categories/{id:\d+}',   $handleGetCategoryByID);
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$pos = strpos($uri, '?');
if ($pos !== false) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($method, $uri);

switch($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(CS4450\Http\StatusCodes::NOT_FOUND);
        //Handle 404
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:

        http_response_code(CS4450\Http\StatusCodes::METHOD_NOT_ALLOWED);
        //Handle 403
        break;
    case FastRoute\Dispatcher::FOUND:

        $handler  = $routeInfo[1];
        $vars = $routeInfo[2];

        $response = $handler($vars);
		header('Access-Control-Allow-Origin:*');
        echo json_encode($response);
        break;
    default:
        break;
}