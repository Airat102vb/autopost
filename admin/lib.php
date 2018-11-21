<?php

function addGroupVKInDB($connect, $idGroup, $groupName, $groupType, $groupSubscribersCount)
{

    $sql = "SELECT * FROM `groups_vk` WHERE group_id = '" . $idGroup . "'";
    $result = mysqli_query($connect, $sql);
    $resArray = mysqli_num_rows($result);
    mysqli_free_result($result);
    if ($resArray > 1) {
        echo "Есть несколько совпадений! Лишние нужно удалить.";
    } elseif ($resArray == 0) {
        $sqlAdd = "INSERT INTO `groups_vk` (group_id, sity_name, theme, subscribers_count) VALUES (".$idGroup.", '".$groupName."', '".$groupType."', ".$groupSubscribersCount.")";
        mysqli_query($connect, $sqlAdd);
        $last_id = mysqli_insert_id($connect);
        echo "Группа - \"" . $idGroup . "\" - добавлена. ID = " . $last_id;
    } else {
        echo "Группа уже существует";
    }
    mysqli_close($connect);

}

function addInvitationPhrase($connect, $invitationPhrase)
{
    $sql = "SELECT * FROM `mutual_subscription` WHERE phrase = '" . $invitationPhrase . "'";
    $result = mysqli_query($connect, $sql);
    $resArray = mysqli_num_rows($result);
    mysqli_free_result($result);
    if ($resArray > 1) {
        echo "Есть несколько совпадений! Лишние нужно удалить.";
    } elseif ($resArray == 0) {
        $sqlAdd = "INSERT INTO `mutual_subscription` (phrase) VALUES ('" . $invitationPhrase . "')";
        mysqli_query($connect, $sqlAdd);
        $last_id = mysqli_insert_id($connect);
        echo "Фраза - \"" . $invitationPhrase . "\" - добавлена. ID = " . $last_id;
    } else {
        echo "Фраза существует";
    }
    mysqli_close($connect);
}

function addPostGroupForMutualSubscription($connect, $idAlienGroup)
{
    $sql = "SELECT * FROM `mutual_group_subscription` WHERE group_id = '" . $idAlienGroup . "'";
    $result = mysqli_query($connect, $sql);
    $resArray = mysqli_num_rows($result);
    mysqli_free_result($result);
    if ($resArray > 1) {
        echo "Есть несколько совпадений! Лишние нужно удалить.";
    } elseif ($resArray == 0) {
        $sqlAdd = "INSERT INTO `mutual_group_subscription` (group_id) VALUES ('" . $idAlienGroup . "')";
        mysqli_query($connect, $sqlAdd);
        $last_id = mysqli_insert_id($connect);
        echo "Группа - \"" . $idAlienGroup . "\" - добавлена. ID = " . $last_id;
    } else {
        echo "Группа существует";
    }
    mysqli_close($connect);
}

?>
