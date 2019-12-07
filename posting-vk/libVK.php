<?php

//Постит на стену, принимает полный запрос
function simplePost($inquiry){

    $ch=curl_init($inquiry);
    curl_exec($ch);
    curl_close($ch);
}

//Загрузка фото на сервер
function uploadPhotoToServer($groupAndAlbum, $version, $pathToJPG) {

    // Загрузка изображения на сервер
    $apiPost="https://api.vk.com/method/photos.getUploadServer?";
    $response2=file_get_contents($apiPost.$groupAndAlbum.$version);//запрос, получаю адрес сервера для загрузки
    $rs=json_decode($response2);
    $uurl=$rs->response->upload_url; //получаю адрес сервера для загрузки изображения из ответа
    $postparam['file1']= new CURLFile(realpath($pathToJPG),'image/jpg','pct-mixr.jpg');
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
    $saveApiPhoto="https://api.vk.com/method/photos.save?". $groupAndAlbum ."&server=".$fserver."&photos_list=".$fphoto."&aid=".$faid."&hash=".$fhash.$version;
    $ch=curl_init($saveApiPhoto);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $resSave=json_decode(curl_exec($ch));
    curl_close($ch);

    return $resSave;
}

function adaptationPhraseForURL($anyPhrase) {

    $patter=['/ /', '/\\\\n/', '/\\\\n\\\\n/', '/\\n\\n/', '/ \\\\n\\\\n/', '/ \\n\\n/'];
    $repl=['%20', '%0A', '%0A%0A', '%0A%0A', '%0A%0A', '%0A%0A'];
    @$pmassege=preg_replace($patter, $repl, $anyPhrase);

    return $pmassege;
}

?>
