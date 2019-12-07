<?php
/**
 * Получаю по 200 фото,
 * смещение рандомное (от 1 до максимального количества),
 * выборка из полученных фото рандомное (от 1 до 200)
 */

$socratifyText = file_get_contents( 'https://I am sorry for that =)/quotes/random?src=menu_secondary' );
preg_match( '/<h1 class="b-quote__text">(.*?)<\\/h1>/is' , $socratifyText , $socratifyQuotes );

$patter=['/ /', '/\\\\n/', '/\\\\n\\\\n/', '/\\n\\n/', '/ \\\\n\\\\n/', '/ \\n\\n/'];
$repl=['%20', '%0A', '%0A%0A', '%0A%0A', '%0A%0A', '%0A%0A'];
$pmassege=preg_replace($patter, $repl, $socratifyQuotes[1]);
$version = "&v=5.131";
$countFotoOfAlbum = "https://api.vk.com/method/photos.getAll?&owner_id=-1&from_group=1&no_service_albums=1&count=1&".$version;
$getResponse = file_get_contents($countFotoOfAlbum);
$countFoto = json_decode($getResponse) -> response -> count;

//Получаю ранодомный оффсет (смещение)
$randomOffset = "&offset=" . random_int(1, $countFoto-1) . "&";

//Запрос на получение списка
$getApiPhoto= "https://api.vk.com/method/photos.getAll?&owner_id=-1&from_group=1&no_service_albums=1&count=1".$randomOffset.$version;
$response = file_get_contents($getApiPhoto);
$jsonDecoded = json_decode($response);

$photoId = $jsonDecoded->response->items[0]->id;
$saveApiPhoto = "https://api.vk.com/method/wall.post?&owner_id=-1&from_group=1&attachments=photo-1_".$photoId.'&message='.$pmassege.$version;
$ch=curl_init($saveApiPhoto);
curl_exec($ch);
curl_close($ch);
sleep(2);

$groups=[-1];
$apiUrlCheat="https://api.vk.com/method/wall.post?message=";
$groupLink='Good%20day'.'%20https://vk.com/.'.$pmassege;
for($i=0; $i<count($groups); $i++){
    $groupQueue = $groups[$i];
    $ch = curl_init($apiUrlCheat.$groupLink.'&owner_id='.$groupQueue.$version);
    curl_exec($ch);
    curl_close($ch);
    sleep(1);
}

?>
