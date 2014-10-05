<?php

require_once 'bootstrap.php';

$stmt = 'SELECT *
         FROM aw_blog_temp
         ORDER by id desc';
$query = $db->prepare($stmt);
$query->execute();

$results = $query->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <form class="form-inline">
            <div class="form-group">
                <label for="action">Action</label>
                <select id="action" class="form-control js-actions">
                    <option>Select...</option>
                    <!--<option value="publish">Publish</option>-->
                    <option value="delete">Delete</option>
                </select>
            </div>
            <div class="form-group">
                <label for="check-all">Check all</label>
                <input id="check-all" type="checkbox" class="js-check-all" />
            </div>
        </form>

        <table class="table table-hover">
            <tr>
                <th>✓</th>
                <th>Title</th>
                <th>Content</th>
                <th>URL</th>
                <th>Source</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($results as $result) { ?>
            <tr>
                <td><input type="checkbox" name="ids" class="js-ids" value="<?= $result['id'] ?>" /></td>
                <td><?= $result['title'] ?></td>
                <td><?= strip_tags($result['content']) ?></td>
                <td><a href="<?= $result['url'] ?>" target="_blank">Link</a></td>
                <td><?= $result['source'] ?></td>
                <td><?= $result['date'] ?></td>
                <td>
                    <a class="js-publish" data-id="<?= $result['id'] ?>" href="#">Publish</a>
                    <br />
                    <a href="#">Edit</a>
                    <br />
                    <a class="js-delete" data-id="<?= $result['id'] ?>" href="#">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
$(function () {
    $('.js-check-all').on('change', function (e) {
        if ($(this).is(':checked'))
            $('.js-ids').prop('checked', true);
        else
            $('.js-ids').prop('checked', false);
    });

    $('.js-delete').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);

        $.post('<?= AGREGATOR_URL ?>/delete.php', { id: $this.data('id')}).done(function (result) {
            $this.closest('tr').remove();
        });
    });

    $('.js-publish').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);

        $.post('<?= AGREGATOR_URL ?>/insert.php', { id: $this.data('id')}).done(function (result) {
            $this.closest('tr').remove();
        });
    });

    // actions
    $('.js-actions').on('change', function (e) {
       ids = $('.js-ids').serialize();

        if ($(this).val() == 'delete') {
            $.post('<?= AGREGATOR_URL ?>/delete.php', {ids: ids}).done(function (result) {
                window.location.reload();
            });
        } else {
            // publish
        }
    });
});
</script>
</body>
</html>