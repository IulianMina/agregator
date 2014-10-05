<?php

require_once 'bootstrap.php';

if (isset($_POST['id']) && $_POST['id']) {
    $stmt = 'DELETE FROM aw_blog_temp
             WHERE  id = :id';

    $query = $db->prepare($stmt);
    $query->bindParam(':id', $_POST['id']);
    $query->execute();
}

if (isset($_POST['ids']) && $_POST['ids']) {
    $ids = $_POST['ids'];

    $ids = str_replace('ids=', '', $ids);
    $ids = str_replace('&', ', ', $ids);

    $stmt = 'DELETE FROM aw_blog_temp
             WHERE  id in (' . $ids . ')';

    $query = $db->prepare($stmt);
    $query->execute();
}


?>