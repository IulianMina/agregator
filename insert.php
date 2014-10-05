<?php

require_once 'bootstrap.php';

if (isset($_POST['id']) && $_POST['id']) {
    $stmt = 'SELECT *
             FROM   aw_blog_temp
             WHERE  id = :id';

    $query = $db->prepare($stmt);
    $query->bindParam(':id', $_POST['id']);
    $query->execute();

    $result = $query->fetchAll();
    if ($result && $result[0]) {
        $result = $result[0];
    }


    $stmt = 'INSERT INTO aw_blog(title, post_content, status, created_time, update_time, identifier, comments)
             VALUES(:title, :content, 1, CURRENT_TIMESTAMP(), null, :identifier, 0)';

    $query = $db->prepare($stmt);
    $query->bindParam(':title', $result['title']);
    $query->bindParam(':content', $result['content']);
    $query->bindParam(':identifier', toLink($result['title']));
    $query->execute();
}

function toLink($link){
    return strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $link), '-'));
}


?>