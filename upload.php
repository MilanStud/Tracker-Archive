<?php

$tempGPX = explode(".", $_FILES["fileGPX"]["name"]);
$tempNTM = explode(".", $_FILES["fileNTM"]["name"]);

$timeStamp = time();

$newfilenameGPX = $timeStamp . '.' . end($tempGPX);
fceUpload($newfilenameGPX,"fileGPX","gpx");

$newfilenameNTM = $timeStamp . '.' . end($tempNTM);
fceUpload($newfilenameNTM,"fileNTM","ntm");

function fceUpload($newfilename,$formElement,$fType) {

    $target_dir = "uploads/";

    $target_file = $target_dir . $newfilename;
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // KOntrola existence souboru
    if (file_exists($target_file)) {
      echo "Soubor se stejným názvem již v adresáři existuje.";
      $uploadOk = 0;
    }

    // KOntrola velikosti
    if ($_FILES["fileGPX"]["size"] > 500000) {
      echo "Soubor je příliš velký.";
      $uploadOk = 0;
    }

    // kontrola typu souboru
    if($fileType != $fType) {
      echo "Soubor není typu ". $fType;
      $uploadOk = 0;
    }

    // Je-li $uploadOk roven 0 pak se výše vyskytla chyba a k uložení souboru nedojde
    if ($uploadOk == 0) {
      echo "SOubor nebyl uložen.";
    // je-li vše OK, pak k nahrání souboru dojde
    } else 
    {
      if (move_uploaded_file($_FILES[$formElement]["tmp_name"], $target_file)) 
      {
        echo "Soubor ". htmlspecialchars( basename( $_FILES[$formElement]["name"])). " byl uložen.";
      } else 
      {
        echo "Neznáma chyba při ukládání.";
      }
    }
}
?>
<script>
  location.href = 'index.php';  
</script>