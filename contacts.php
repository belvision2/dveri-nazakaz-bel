<?php
if (isset ($_POST['phone'])) {
  $to = "375296126565@sms.velcom.by,vitaminiby@ya.ru,vlad_abramov@tut.by"; // поменять на свой электронный адрес
  $from = "vitaminiby@ya.ru";
  $subject = "готовый проект двери.назаказ.бел ";
  $message = "Имя: ".$_POST['name']."\nТелефон: ".$_POST['phone']."\nIP: ".$_SERVER['REMOTE_ADDR']."\n ".$_POST['messageFF']."\n ".$_SERVER['HTTP_REFERER'];
  $boundary = md5(date('r', time()));
  $filesize = '';
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "From: " . $from . "\r\n";
  $headers .= "Reply-To: " . $from . "\r\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
  $message="
Content-Type: multipart/mixed; boundary=\"$boundary\"

--$boundary
Content-Type: text/plain; charset=\"utf-8\"
Content-Transfer-Encoding: 7bit

$message";
  for($i=0;$i<count($_FILES['form_file_4']['name']);$i++) {
     if(is_uploaded_file($_FILES['form_file_4']['tmp_name'][$i])) {
         $attachment = chunk_split(base64_encode(file_get_contents($_FILES['form_file_4']['tmp_name'][$i])));
         $filename = $_FILES['form_file_4']['name'][$i];
         $filetype = $_FILES['form_file_4']['type'][$i];
         $filesize += $_FILES['form_file_4']['size'][$i];
         $message.="

--$boundary
Content-Type: \"$filetype\"; name=\"$filename\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=\"$filename\"

$attachment";
     }
   }
   $message.="
--$boundary--";

  if ($filesize < 10000000) { // проверка на общий размер всех файлов. Многие почтовые сервисы не принимают вложения больше 10 МБ
    mail($to, $subject, $message, $headers);
	$URL="index_otpr.html";
header ("Location: $URL");
  } else {
    echo 'Извините, письмо не отправлено. Размер всех файлов превышает 10 МБ.';
  }
}
?>