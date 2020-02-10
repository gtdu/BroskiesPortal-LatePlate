<?php

include_once("init.php");

if ($_SESSION['level'] == 0 || $_SESSION['level'] > 2) {
    die();
}

if ($_SESSION['level'] > 1) {
    $handle = $config['dbo']->prepare('SELECT * FROM requests WHERE completed = 0 AND date = DATE(NOW())');
    $handle->execute();
    $lore = $handle->fetchAll(\PDO::FETCH_ASSOC);
} else {
    $handle = $config['dbo']->prepare('SELECT * FROM requests WHERE completed = 0 AND who = ?');
    $handle->bindValue(1, $_SESSION['name']);
    $handle->execute();
    $lore = $handle->fetchAll(\PDO::FETCH_ASSOC);
}

if ($_POST['action'] == 'newRequest') {
    $handle = $config['dbo']->prepare('INSERT INTO requests (date, who) VALUES (?, ?)');
    $handle->bindValue(1, $_POST['date']);
    $handle->bindValue(2, $_SESSION['name']);
    $handle->execute();
    header("Location: ?");
    die();
}

if ($_SESSION['level'] > 1) {
    if ($_REQUEST['action'] == 'completeRequest') {
        $handle = $config['dbo']->prepare('UPDATE requests SET completed = 1 WHERE id = ?');
        $handle->bindValue(1, $_REQUEST['resource_id']);
        $handle->execute();
        header("Location: ?");
        die();
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<h1 class="mt-2">Late Plate System</h1>
<div class="d-flex mt-3 mb-3">
    <div class="btn-group flex-fill" role="group" aria-label="Basic example">
        <a href="?action=newRequest" class="btn btn-warning">Request Late Plate</a>
    </div>
</div>
<?php

if ($_GET['action'] == 'newRequest') {
    ?>
    <div class="pl-4 pr-4 mb-4">
        <form method="post">
            <div class="form-group">
                <label for="newLoreTitle">Date</label>
                <input name="date" type="date" class="form-control" id="newLoreName" aria-describedby="aria" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <input type="hidden" name="action" value="newRequest">
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    <?php
}

echo "</br>";

if (count($lore) == 0) {
    echo "<h3>No Active Requests Found</h3>";
} else {
    ?>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th style="width: 15%;">Date</th>
                <th style="width: 45%;">Who</th>
                <?php
                if ($_SESSION['level'] > 1) {
                    echo '<th style="width: 10%;">&nbsp;</th>';
                } ?>
            </tr>
        </thead>
    <?php
    foreach ($lore as $story) {
        echo "<tr>";
        echo "<td>" . $story['date'] . "</td>";
        echo "<td>" . $story['who'] . "</td>";
        if ($_SESSION['level'] > 1) {
            echo "<td><a href='?action=completeRequest&resource_id=" . $story['id'] . "'>Mark as Completed</a></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script></body>
</html>
