<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = 'kittyrun29@gmail.com'; // ใส่อีเมล IT
    $subject = 'แจ้งปัญหาไอทีจาก ' . $_POST['name'];
    $message = "ชื่อผู้แจ้ง: " . $_POST['name'] . "\n";
    $message .= "อุปกรณ์ที่มีปัญหา: " . $_POST['device'] . "\n";
    $message .= "รายละเอียด: " . $_POST['detail'] . "\n";

    $headers = "From: noreply@yourcompany.com";

    // ตรวจสอบว่ามีไฟล์แนบ
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0){
        $file_tmp = $_FILES['attachment']['tmp_name'];
        $file_name = $_FILES['attachment']['name'];
        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));
        $boundary = md5(time());

        // Headers สำหรับแนบไฟล์
        $headers .= "\r\nMIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
        $body .= $message . "\r\n";

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
        $body .= $file_content . "\r\n";
        $body .= "--$boundary--";

        mail($to, $subject, $body, $headers);
    } else {
        mail($to, $subject, $message, $headers);
    }

    echo "ส่งข้อมูลเรียบร้อยแล้ว!";
} else {
    echo "ไม่สามารถใช้งานไฟล์นี้โดยตรงได้";
}
?>
