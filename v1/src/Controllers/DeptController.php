<?php
namespace CS4450\Controllers;

use PDO;
use CS4450\Http\StatusCodes;
use CS4450\Utilities\DatabaseConnection;

class DeptController
{

	public static function getAllDepts(){
		$db = DatabaseConnection::getInstance();

    //This query should get all departments that have an instructor,
    //leaving out the other "test" department names
		$queryGetAllDepts = '
    SELECT
      d.code            as DepartmentCode,
      d.name            as DepartmentName
    FROM InstructorDepartments id
    JOIN Departments d
    ON id.departmentCode = d.code
    JOIN Users u
    ON u.Id = id.InstructorID;
		';

		$stmtGetAllDepts = $db->prepare($queryGetAllDepts);
		$stmtGetAllDepts->execute();

		return $stmtGetAllDepts->fetchAll(PDO::FETCH_ASSOC);
	}
}
