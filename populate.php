<?php

/*select d.ID as 'Distractor ID', d.questionID, d.text as 'Distractor text', q.text as 'Question text'
from Distractors d
join Questions q
on d.questionID = q.ID
where d.text like '[0-7]%' and
d.text not like '%-%' and
q.text not like '%GPA%'*/

class DatabaseConnection
{
    private static $instance = null;
    private static $host = '137.190.19.16,10433';
    private static $dbname = 'CS4450Spring2017';
    private static $user = 'NathanBrooks';
    private static $pass = '!Bella22';

    public static function getInstance()
    {
        if (!static::$instance === null) {
            return static::$instance;
        } else {
            try {
				//"Server=137.190.19.16:10433;Database=myDataBase;User Id=myUsername;Password=myPassword;"
				//$connectionString = "mysql:host=".static::$host.";dbname=".static::$dbname;
                //static::$instance = new \PDO($connectionString, static::$user, static::$pass);
				
                $connectionString = "sqlsrv:Server=".static::$host.";Database=".static::$dbname;
                static::$instance = new PDO($connectionString, static::$user, static::$pass);
                //static::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return static::$instance;
            } catch (PDOException $e) {
                echo "Unable to connect to the database: " . $e->getMessage();
                die();
            }
        }
    }
}

$db = (new DatabaseConnection())->getInstance();

$query = "
	select d.ID as 'Distractor ID', d.questionID, d.text as 'Distractor text', q.text as 'Question text'
	from Distractors d
	join Questions q
	on d.questionID = q.ID
	where d.text like '[0-7]%' and
	d.text not like '%-%'
";
$stmt = $db->prepare($query);
$stmt->execute();

$Distractors = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($Distractors as $row){
	$distractorText = $row['Distractor text'];
	$score = filter_var($distractorText, FILTER_SANITIZE_NUMBER_INT);
	
	$text = '';
	
	if(strpos($distractorText, '(')){
		$text = 
		substr(
			$distractorText, 
			strpos($distractorText, '(') + 1, 
			strlen(substr($distractorText, strpos($distractorText, '(') + 1)) -1
		);
	}
	
	$queryInsert = '
		INSERT INTO 
		Evals_DistractorTextAndScore (distractorID, score, text)
		Values                       (:distractorID, :score, :text);
	';
	
	
	$stmtInsert = $db->prepare($queryInsert);
	$stmtInsert->bindValue(':distractorID', $row['Distractor ID']);
	$stmtInsert->bindValue(':score', $score);
	$stmtInsert->bindValue(':text', $text);
	$stmtInsert->execute();
}














