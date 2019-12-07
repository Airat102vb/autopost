<?php
include __DIR__ . '/../communitiesList.php';
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
$randomGroupsArray = [];

for ($i=0; count($randomGroupsArray)<10; $i++){
    $randomGroupsArray[]=rand(0, count($communitiesList));
}
$uniqueArray = array_values(array_unique($randomGroupsArray));

while(count($uniqueArray)<10){
    $groupPosition = rand(0, count($communitiesList));
    if(!array_key_exists($groupPosition, $uniqueArray)){
        $uniqueArray[] = $groupPosition;;
    }
}

//Цикл для репоста группам рандомно
foreach($uniqueArray as $key){

    //Получаю id группы из общего списка
    $destinationGroup = "&group_id=".trim($communitiesList[$key][0], '-');

    $apiRepost="https://api.vk.com/method/wall.repost?";
    $recUrl = $apiRepost.$objectPost.$destinationGroup.$version;

    repostRequest($recUrl);

    sleep($sleepTime);
    $sleepTime = $sleepTime+2;
}

function repostRequest($recUrl){
    $ch = curl_init($recUrl);
    curl_exec($ch);
    curl_close($ch);
}

?>
