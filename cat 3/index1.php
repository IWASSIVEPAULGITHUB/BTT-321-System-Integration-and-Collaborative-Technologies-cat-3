<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kanban Board + Cloud Upload</title>
    <style>
        <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        h2 {
            background: #007bff;
            color: white;
            padding: 15px;
            margin: 0;
        }
        p {
            padding: 10px;
        }
        .form-area {
            padding: 15px;
            background: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            margin: 10px;
        }
        .form-area form {
            display: inline-block;
            margin-right: 20px;
        }
        input[type="text"], input[type="file"] {
            padding: 6px;
            width: 200px;
            margin-right: 5px;
        }
        button {
            padding: 6px 10px;
            border: none;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #218838;
        }
        .column {
            float: left;
            width: 30%;
            margin: 1.5%;
            background: #e9ecef;
            padding: 10px;
            min-height: 400px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
            border-radius: 8px;
        }
        .task {
            background: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #007bff;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .task form {
            margin-top: 8px;
        }
        .task button {
            background-color: #007bff;
            margin-right: 5px;
        }
        .task button[name="delete"] {
            background-color: #dc3545;
        }
        .task button:hover {
            opacity: 0.9;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
    </style>
</head>
<body>
    <h2>Kanban Task Board + Cloud File Upload</h2>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> | <a href="logout2.php">Logout</a></p>

    <div class="form-area">
        <form id="taskForm">
            <input type="text" name="title" id="taskTitle" required placeholder="Task Title" />
            <button type="submit">Add Task</button>
        </form>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required />
            <button type="submit">Upload File to Google Drive</button>
        </form>
    </div>

    <div id="board">
    <?php
    $statuses = ['To Do', 'In Progress', 'Done'];
    foreach ($statuses as $status):
        echo "<div class='column' id='" . strtolower(str_replace(' ', '_', $status)) . "'><h3>$status</h3>";

        $stmt = $conn->prepare("SELECT * FROM tasks WHERE status=? AND user_id=?");
        $stmt->bind_param("si", $status, $_SESSION['user_id']);
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
    </div>

<script>
document.getElementById('taskForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const title = document.getElementById('taskTitle').value;
    if (!title.trim()) return;

    fetch('addtask.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'title=' + encodeURIComponent(title)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskDiv = document.createElement('div');
            taskDiv.classList.add('task');
            taskDiv.innerHTML = `
                ${data.title}
                <form method="POST" action="movetask.php">
                    <input type="hidden" name="id" value="${data.id}">
                    <button name="status" value="In Progress">In Progress</button>
                    <button name="status" value="Done">Done</button>
                    <button name="delete" value="1">Delete</button>
                </form>
            `;
            document.getElementById('to_do').appendChild(taskDiv);
            document.getElementById('taskTitle').value = '';
        } else {
            alert('Failed to add task.');
        }
    });
});
</script>

</body>
</html>
