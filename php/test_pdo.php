<?php
/*****************************************************************
 * 文件名称：test_pdo.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-13 09:50
 * 描    述：
 *****************************************************************/

function getDbInstance() {
    static $pdo = null;
    if (empty($pdo)) {
        $dsn = 'mysql:host=localhost;dbname=test';
        $user = 'blacknc';
        $pwd = '123456';
        $pdo = new PDO($dsn, $user, $pwd);
    }
    return $pdo;
}

$pdo = getDbInstance();
$sql = 'insert into user set name=:name,age=:age,sex=:sex,mobile=:mobile,email=:email,password=:password';
$result = $pdo->prepare($sql);

$name = '小东';
$age = 23;
$sex = 1;
$moile = '18210672147';
$email = 'blacknc@163.com';
$password = '123456';
$result->bindParam(':name', $name);
$result->bindParam(':age', $age);
$result->bindParam(':sex', $sex);
$result->bindParam(':mobile', $mobile);
$result->bindParam(':email', $email);
$result->bindParam(':password', $password);
$res = $result->execute();
var_dump($res);

$res = $result->execute(array(
':name' => $name,
':age' => $age,
':sex' =>  $sex,
':mobile' => $mobile,
':email' => $email,
':password' => $password
));
var_dump($res);
