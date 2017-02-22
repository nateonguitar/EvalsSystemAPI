<?php

namespace CS4450\Controllers;

use PDO;
use CS4450\Http\StatusCodes;
use CS4450\Utilities\DatabaseConnection;

class UserRolesController
{
	public static function getUserRoles(){
		$db = DatabaseConnection::getInstance();

/*
 * Join Users, UserRoles, and Roles
 * SELECT FirstName, LastName, Role
 */
		$queryGetAllUserRoles = '
      SELECT u.Id, u.LastName, u.FirstName, r.name
      FROM Users u
      JOIN UserRoles ur
      ON u.Id = ur.userID
      JOIN Roles r
      ON ur.roleID = r.id;
		';

		$stmtGetAllUserRoles = $db->prepare($queryGetAllUserRoles);
		$stmtGetAllUserRoles->execute();

		return $stmtGetAllUserRoles->fetchAll(PDO::FETCH_ASSOC);
	}
}
