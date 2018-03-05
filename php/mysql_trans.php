<?php
require_once 'dao/datasourceFactroy.php';

echo date('H:i:s').' begin'.PHP_EOL;
$conn = DatasourceFactroy::getDatasource();
echo date('H:i:s').' ok 0'.PHP_EOL;
$conn->startTrans();

/* $res1 = $conn->execute("insert into test(name,age,city,work) values('jia', 25, 'beijing', 'IT')"); */
/* $res2 = $conn->execute("update test set name='dong',age=26,city='beijing',work=BT where id=16"); */
/* echo date('H:i:s').' ok 1 '.$res1.' - '.$res2.PHP_EOL; */
/* echo 'sleep'.PHP_EOL; */
/* sleep(5); */
/* echo 'trans sleep'.PHP_EOL; */


$conn1 = DatasourceFactroy::getDatasource();
//$conn1->startTrans();
//sleep(5);
$res3 = $conn1->execute("insert into test(name,age,city,work) values('kk', 20, 'sh', 'by')");
echo date('H:i:s').' ok 2 -'.$res3.PHP_EOL;
/* if ($res3) { */
/*     echo "commit 1\n"; */
/*     $conn1->commit(); */
/* } else { */
/*     echo "rollBack 1\n"; */
/*     $conn1->rollBack(); */
/* } */
echo 'sleep'.PHP_EOL;
sleep(10);

if ($res3) {
    echo "commit\n";
    $conn->commit();
} else {
    echo "rollBack\n";
    $conn->rollBack();
}
?>