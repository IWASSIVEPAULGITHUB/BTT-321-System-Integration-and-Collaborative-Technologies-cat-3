<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Kanban Board + Cloud Upload</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .column { float: left; width: 30%; margin: 1%; padding: 10px; background: #ddd; min-height: 400px; }
        .task { background: #fff; margin: 5px 0; padding: 10px; border: 1px solid #aaa; }
        .form-area { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Kanban Task Board + Cloud File Upload</h2>

    <div class="form-area">
        <form action="addtask.php" method="POST">
            <input type="text" name="title" required placeholder="Task Title" />
            <button type="submit">Add Task</button>
        </form>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required />
            <button type="submit">Upload File to Google Drive</button>
        </form>
    </div>

    <?php
    $statuses = ['To Do', 'In Progress', 'Done'];
    foreach ($statuses as $status):
        echo "<div class='column'><h3>$status</h3>";

        $stmt = $conn->prepare("SELECT * FROM tasks WHERE status=?");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($task = $result->fetch_assoc()):
            echo "<div class='task'>";
            echo htmlspecialchars($task['title']);
            echo "<form method='POST' action='movetask.php'>";
            echo "<input type='hidden' name='id' value='{$task['id']}'>";
            foreach ($statuses as $s) {
                if ($s !== $status)
                    echo "<button name='status' value='$s'>$s</button> ";
            }
            echo "<button name='delete' value='1'>Delete</button>";
            echo "</form></div>";
        endwhile;

        echo "</div>";
    endforeach;
    ?>
</body>
</html>
