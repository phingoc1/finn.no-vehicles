<?php
require_once './vendor/autoload.php';

use DiDom\Document as DOM;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


if(!isset($_GET['vehicle'])) {
    die('vehicle type not set');
}

switch($_GET['vehicle']) {
    case "car": 
        $vehicleType = "car-norway";
        break;

    case "snowmobile":
        $vehicleType = "snowmobile";
        break;

    default: 
        die('incorrect vehicle type');
        break;
}

$document = new DOM('https://www.finn.no/pw/search/'.$vehicleType.'?orgId='.$_ENV['ORG_ID'], true);
$ads = $document->find('.unit.flex.align-items-stretch.result-item');

$master = array();

foreach($ads as $ad) {
    $tekst = $ad->find('.t4.word-break.mhn')[0]->text();
    $tittel = explode(" ", $tekst);
    
    $master[] = array(  
        'link' => $ad->find('a.clickable::attr(href)')[0], 
        'tittel' => $tittel[0]. " " . $tittel[1],
        'bilde' => $ad->find('img.centered-image::attr(src)')[0], 
        'tekst' => $tekst, 
        'sted' => $ad->find('.blockify.ptt.licorice.fleft.mrm')[0]->text(),
        'aar' => (strlen($ad->find('.t5.word-break.mhn')[0]->firstChild()->text() ) == 4) ? $ad->find('.t5.word-break.mhn')[0]->firstChild()->text() : "0000",
        'km' => strpos($ad->find('.t5.word-break.mhn')[0]->child(1)->text(), "km") ? $ad->find('.t5.word-break.mhn')[0]->child(1)->text() : "0",
        'pris' => strpos($ad->find('.t5.word-break.mhn')[0]->lastChild()->text(), ",-") ? $ad->find('.t5.word-break.mhn')[0]->lastChild()->text() : "0"
    );
}

$json = json_encode($master);
echo $json;