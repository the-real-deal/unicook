<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/model/auth.php";

$db = Database::connectDefault();

$statement = $db->createStatement(<<<sql
SELECT * 
FROM `Users` 
WHERE 
    `ID` = ?
sql);

function myApi(string $id1) {
    global $statement; // or store it as a class member / static var

    $statement->bind(
        // one for each '?' in the query
        SqlValueType::String->createParam($id1),
    );

    if ($statement->execute()) {
        $result = $statement->getResult();
        foreach ($result->iterate() as $row) {
            $user = User::fromTableRow($row);
            echo '<pre>';
            var_dump($user);
            echo '</pre>';
        }
    } else {
        echo "ERROR IN EXECUTION";
    }
}

myApi("a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f");

?>