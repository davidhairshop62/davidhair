<?php
// config.php - PHP와 MySQL 연결 설정

$servername = "localhost";  // MySQL 서버 주소
$username = "root";         // MySQL 사용자명
$password = "Gudangrambut@2933"; // MySQL 비밀번호
$dbname = "davidhair";      // 연결할 데이터베이스 이름

// PDO를 사용한 연결 (권장)
try {
    // 문자 인코딩(charset=utf8)을 명시하여 한글 깨짐 방지
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // 에러 발생 시 예외를 던지도록 설정
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // 연결 실패 시 에러 메시지를 출력하고 스크립트 실행 중단
    die("Connection failed: " . $e->getMessage());
}
?>
