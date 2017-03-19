<?php
namespace CS4450\Controllers;

use PDO;
use CS4450\Http\StatusCodes;
use CS4450\Utilities\DatabaseConnection;

class OldAPIController
{
	
	public static function getPingraphData(){
		$db = DatabaseConnection::getInstance();
		$data = json_decode($_POST['data']);
		
		
		$queryGetAllRoles = '
			SELECT *
			FROM Roles;
		';
		
		$stmtGetAllRoles = $db->prepare($queryGetAllRoles);
		$stmtGetAllRoles->execute();
		
		return $stmtGetAllRoles->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function getSemesters(){
		$db = databaseConnection::getInstance();
		$data = (object)json_decode(file_get_contents('php://input'));
		$yearList = $data->yearList;
		
		$query = '
			DECLARE @yearList varchar(max);
			SET @yearList = :yearList;
			
			EXEC sp_GetSemesters @yearList;
		';
		
		$stmt = $db->prepare($query);
		$stmt->bindValue(':yearList', $yearList);
		
		if(!$stmt->execute()){
			echo 'internal server error';
			http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
			die();
		}
		
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function getDepartments(){
		$db = databaseConnection::getInstance();
		$data = (object)json_decode(file_get_contents('php://input'));
		
		$yearList = $data->yearList;
		$semesterList = $data->semesterList;
		
		$query = '
			DECLARE @yearList varchar(max);
			SET @yearList = :yearList;
			
			DECLARE @semesterList varchar(max);
			set @semesterList = :semesterList;
			
			EXEC sp_GetSemesters @yearList, @semesterList;
		';
		
		$stmt = $db->prepare($query);
		$stmt->bindValue(':yearList', $yearList);
		$stmt->bindValue(':semesterList', $semesterList);
		
		if(!$stmt->execute()){
			echo 'internal server error';
			http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
			die();
		}
		
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
}