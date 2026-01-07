<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/model/auth.php";

$db = Database::connectDefault();

$id1 = "a3f5c8d1-4b2e-4a1c-9f3d-7e8b2c4a6d1f";
$id2 = "0f1a2b3c-4d5e-6f7a-8b9c-0d1e2f3a4b5c";

$statement = $db->createStatement(<<<sql
SELECT * 
FROM `Users` 
WHERE 
    `ID` = ?
    OR `ID` = ?
sql);

$statement->bind([
    SqlValueType::String->createParam($id1),
    SqlValueType::String->createParam($id2),
]);

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
?>