<?php
//Репост постов группы "?"

include('communitiesList.php');
$apiUrl="https://api.vk.com/method/wall.get?owner_id=-1&offset=1&count=1";
$version="&v=5.101";

$jsonResponse = json_decode(file_get_contents($apiUrl.$version));
//Получаю id группы и id поста
$idGroup = $jsonResponse->response->items[0]->from_id;
$idPost = $jsonResponse->response->items[0]->id;

//Объект для репоста
$objectPost = "object=wall".$idGroup."_".$idPost;
$sleepTime=1;
//Цикл для репоста группам рандомно
for( $i=0;  $i<9; $i++){

    $randGroup=rand(0, count($communitiesList));
    //Получаю id группы из общего списка
    $destinationGroup = "&group_id=".trim($communitiesList[mt_rand(0, $randGroup)][0], '-');

    $apiRepost="https://api.vk.com/method/wall.repost?";
    $recUrl = $apiRepost.$objectPost.$destinationGroup.$version;

    repostRequest($recUrl);

    sleep($sleepTime);
    $sleepTime = $sleepTime+3;
}

function repostRequest($recUrl){
    $ch = curl_init($recUrl);
    curl_exec($ch);
    curl_close($ch);
}

?>
