<?php

$url = "https://I'm sorry for that =)/search?t=?";
$dom = new DOMDocument();
@$dom->loadHTMLFile($url);
$domXpath = new DOMXPath($dom);

$article = $domXpath->query("//article[1]");

//Скипаем длиннопост
if(!is_null($article)) {
    $checkForLongPost = $article[0]->getAttribute("data-story-long");
    if($checkForLongPost=="true"){
        exit("Скипнут длиннопост, приложение остановлено.");
    }
}

//Тайтл, картинка  краткий текст
$getTitle = $domXpath->query("//article[1]//header//a");
$getImg = $domXpath->query("//article[1]//div[@class='story-image__content image-lazy']//img");
if(!is_null($getImg) && !is_null($getTitle)) {
    $postTitle = $getTitle[0]->textContent;
    $postIMG = $getImg[0]->getAttribute('data-src');
}


$patter=['/ /', '/\\\\n/', '/\\\\n\\\\n/', '/\\n\\n/', '/ \\\\n\\\\n/', '/ \\n\\n/'];
$repl=['%20', '%0A', '%0A%0A', '%0A%0A', '%0A%0A', '%0A%0A'];
@$pmassege=preg_replace($patter, $repl, $postTitle);

//Адаптирую фразу для GET запроса, заменяются пробелы и перенос строки
$adaptedPhraseMIXR = adaptationPhraseForURL($postTitle);

$version="&v=5.101";
$groupAndAlbum = "&group_id=?&album_id=?";
$pathToJPG = __DIR__ . '/../picture/pct-mixr.jpg';

// сохранение картинки по полученному адресу
copy($postIMG, __DIR__ . '/../picture/pct-mixr.jpg');

//Загрузка фото на севрер, получения адреса загруженного фото
$resSave = uploadPhotoToServer($groupAndAlbum, $version, $pathToJPG);

// Загрузка изображения на сервер
$apiPost="https://api.vk.com/method/photos.getUploadServer?&group_id=?&album_id=?".$version;
$response2=file_get_contents($apiPost.$version);//запрос, получаю адрес сервера для загрузки
$rs=json_decode($response2);
$uurl=$rs->response->upload_url; //получаю адрес сервера для загрузки изображения из ответа

$cfile = 'picture/pct-mixr.jpg';
$postparam['file1']= new CURLFile('picture/pct-mixr.jpg','image/jpg','pct-mixr.jpg');

$ch = curl_init($uurl); //адрес сервера для загрузки
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$postparam);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
$json =json_decode(curl_exec($ch));
curl_close($ch);

//get photo, server, hash variables
@$fserver=$json->server;
@$fphoto=$json->photos_list;
@$faid=$json->aid;
@$fhash=$json->hash;

//save photo photos.save
$saveApiPhoto="https://api.vk.com/method/photos.save?&group_id=?&album_id=?&server=".$fserver."&photos_list=".$fphoto."&aid=".$faid."&hash=".$fhash.$version;
$ch=curl_init($saveApiPhoto);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$resSave=json_decode(curl_exec($ch));
curl_close($ch);

@$photoId=$resSave->response[0]->id;

//post photo
$saveApiPhoto="https://api.vk.com/method/wall.post?&owner_id=-?&from_group=1&message=".$pmassege."&attachments=photo-?_".$photoId.$version;
$ch=curl_init($saveApiPhoto);
curl_exec($ch);
curl_close($ch);
$postingMIXR="https://api.vk.com/method/wall.post?&owner_id=-1&from_group=1&message=".$adaptedPhraseMIXR."&attachments=photo-1_".$photoId.$version;
simplePost($postingMIXR);
?>

