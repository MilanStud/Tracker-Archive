<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracker Archive</title>
    <style>
        #map { 
            height: 500px;
            width: 100%;
        }
      </style>  

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>

     <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
        crossorigin=""></script>  
          
    <link rel="stylesheet" href="https://kit.fontawesome.com/a7c7eee35a.css" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
	<link
		href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
		rel="stylesheet"
		integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
		crossorigin="anonymous"
	/>
</head>

<body class="d-flex flex-column h-100 m-2">
    <nav class="navbar fixed-top navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="fa-solid fa-boxes-packing"></i> Tracker Archive</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                            <a href="#" class="nav-link"  data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                
                            <i class="fa-solid fa-circle-question"></i> About</a>
                    </li>	                 					
                        
                </ul>
            </div>
        </div>
    </nav>  
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="fa-solid fa-circle-question"></i> About</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <a href="https://github.com/MilanStud/Tracker-Archive" class="nav-link" target="_blank">                                               
                <i class="fa-brands fa-github"></i> GIT - code in repositories</a>
                <hr>
                <i class="fa-solid fa-boxes-packing"></i> Tracker Archive 1.1
                <br>
                &copy; 2023 Milan Študent
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>              
    <br>
    <main class="flex-shrink-0 mt-4">
    <div class="row">
        <div class="col-lg-3 mb-2 text-start">  
            <div class="card " >
                <div class="card-header">
                    <i class="fa-solid fa-file-import"></i>&nbsp;&nbsp;&nbsp; Nahrej soubory 
                </div>
                <div class="card-body overflow-auto ">
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        vyber soubor GPX:<br>
                        <input type="file"  accept=".gpx" name="fileGPX" id="fileGPX">
                        <br><br>
                        vyber soubor NTM:<br>
                        <input type="file"  accept=".ntm" name="fileNTM" id="fileNTM">
                        <br><br>
                        <input type="submit" value="Uložit" name="submit">
                    </form>
                    <hr>
                    1. Byl-li při záznamu trasy trackerem pořízen také záznam BTS bodů aplikací NetMonstr, pak tento soubor nahrejte příslušným formulářovým polem spolu se souborem GPX.
                    <br>
                    Zaznamenané BTS body budou zobrazeny na mapě se zachycenou trasou.
                    <br>
                    2. Pokud k trase nebyl pořízen záznam BTS bodů, není nutné soubor NTM nahrávat. Aplikace vykreslí jen trasu zaznamenanou v souboru GPX.
                    <br>
                    3. K již nahraným souborům GPX nelze soubor NTM dodatečně nahrát.                     
                </div>
            </div>                   
        </div>         
        <div class="col-lg-7 mb-2 text-start">  
        <div class="card " >
                <div class="card-header">
                    <i class="fa-solid fa-map-location-dot"></i>&nbsp;&nbsp;&nbsp; Mapa 
                </div>
                <div class="card-body overflow-auto ">
                    <div id="map"></div>
                        <script>
                            <?php
                                $dir = 'uploads';
                                $filesGPX = scandir($dir, SCANDIR_SORT_DESCENDING);

                                if (isset($_GET["f"])){
                                    $lastFileGPX = htmlspecialchars($_GET["f"]);
                                    $urlValue = $lastFileGPX;
                                }
                                else{
                                    $lastFileGPX = $filesGPX[0];
                                    $urlValue = "";
                                }
                                

                                $fileType = strtolower(pathinfo($lastFileGPX,PATHINFO_EXTENSION));

                                // přístup k souboru s vyznačenou trasou - GPX
                                $xml=simplexml_load_file("uploads/". str_replace($fileType,"gpx",$lastFileGPX)) or die("Chyba, objekt nemohl být vytvořen");

                                $arrTrkpt = $xml->trk->trkseg->children();
                                $lat = $arrTrkpt[0]['lat'];
                                $lon = $arrTrkpt[0]['lon'];
                            ?>
                            var map = L.map('map').setView([<?php echo $lat ?>, <?php echo $lon ?>], 14); 
                            const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', 
                            {
                                maxZoom: 19,
                                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);

                            var polylinePoints = [
                                <?php
                                    foreach($xml->trk->trkseg->children() as $trkpt) 
                                    {
                                        echo "[". $trkpt['lat'] .", ".  $trkpt['lon'] ."],";
                                    }
                                ?>
                            ];            

                            var polyline = L.polyline(polylinePoints).addTo(map); 

                            <?php
                                if (file_exists("uploads/".str_replace($fileType,"ntm",$lastFileGPX))) {
                                    if (($open = fopen("uploads/".str_replace($fileType,"ntm",$lastFileGPX), "r")) !== FALSE) {
                                        while (($data = fgetcsv($open, 1000, ";")) !== FALSE) 
                                        {        
                                            $array[] = $data; 
                                        }
                                        fclose($open);
                                    }
                                    $i = 0;
                                    foreach($array as $a) {
                                        if($a[7] !== ""){
                                            echo "var marker". $i ." = L.marker([". $a[7] .", ".  $a[8] ."]).addTo(map).bindPopup('".  $a[9] ."<hr><b>".  $a[0] ."</b> &nbsp;&nbsp;&nbsp;<b>eNB:CID</b> ".  $a[5] .":".  $a[3] ." &nbsp;&nbsp;&nbsp;<b>TAC</b> ".  $a[4] ." &nbsp;&nbsp;&nbsp;<b>PCI</b> ".  $a[6] ."').openPopup();";
                                        $i++;
                                        }
                                    }
                                }
                            ?>
                        </script>                    
                </div>
            </div>                 
        </div>  
        <div class="col-lg-2 mb-2 text-start">  
        <div class="card " >
            <div class="card-header">
                <i class="fa-solid fa-folder"></i>&nbsp;&nbsp;&nbsp; Uložené mapy 
            </div>
            <div class="card-body overflow-auto ">
                <?php
                $i = 0;
                foreach($filesGPX as $s) {
                    $fileTypeForLink = strtolower(pathinfo($filesGPX[$i],PATHINFO_EXTENSION));
                    if($filesGPX[$i]!=="." && $filesGPX[$i]!==".."){
                        if($fileTypeForLink=="gpx"){
                            if($urlValue == $filesGPX[$i])
                            {
                                echo "<i class='fa-solid fa-caret-right'></i> ";
                            }
                            echo "<a href=index.php?f=". $filesGPX[$i] .">". date("d.m.Y  H:m:s",str_replace(".".$fileTypeForLink,"",$filesGPX[$i]))."</a><br>";
                        }
                        $i++;
                    }
                }
                ?>
            </div>
            </div>               
        </div>                 
    </div>  
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
    crossorigin="anonymous"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.2/iconify-icon.min.js"></script>
    <script src="https://kit.fontawesome.com/a7c7eee35a.js" crossorigin="anonymous"></script>
</body>
</html>
