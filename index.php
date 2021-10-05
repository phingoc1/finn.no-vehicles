<?php
require_once './vendor/autoload.php';

use DiDom\Document;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$master = array();

$document = new Document('https://www.finn.no/pw/search/snowmobile?orgId='.$_ENV['ORG_ID'], true);
$ads = $document->find('.unit.flex.align-items-stretch.result-item');

foreach($ads as $ad) {
    $master[] = array(  
        'link' => $post->find('a.clickable::attr(href)')[0], 
        'bilde' => $post->find('img.centered-image::attr(src)')[0], 
        'tekst' => $post->find('.t4.word-break.mhn')[0]->text(), 
        'sted' => $post->find('.blockify.ptt.licorice.fleft.mrm')[0]->text(),
        'aar' => (strlen($post->find('.t5.word-break.mhn')[0]->firstChild()->text() ) == 4) ? $post->find('.t5.word-break.mhn')[0]->firstChild()->text() : "0000",
        'km' => strpos($post->find('.t5.word-break.mhn')[0]->child(1)->text(), "km") ? $post->find('.t5.word-break.mhn')[0]->child(1)->text() : "0",
        'pris' => strpos($post->find('.t5.word-break.mhn')[0]->lastChild()->text(), ",-") ? $post->find('.t5.word-break.mhn')[0]->lastChild()->text() : "0"
    );
}

$json = json_encode($master);
echo $json;