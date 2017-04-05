<?php
namespace CS4450\Controllers;

use PDO;
use CS4450\Http\StatusCodes;
use CS4450\Utilities\DatabaseConnection;


class InstructorsController
{
	public static function getAllInstructorsBasedOnDepartmentList(){
		$db = DatabaseConnection::getInstance();
		$data = (object) json_decode(file_get_contents('php://input'));
		
		$departmentsSelected = $data->departmentsSelected;
		var_dump($departmentsSelected);
		
		
		$stmtGetAllDepts = $db->prepare($queryGetAllDepts);
		$stmtGetAllDepts->execute();
		
		return $stmtGetAllDepts->fetchAll(PDO::FETCH_ASSOC);
}