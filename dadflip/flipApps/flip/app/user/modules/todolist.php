<?php
// todolist.php

// Inclure le fichier de connexion à la base de données
include 'db.php';

// Vérifier la session utilisateur
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Utilisateur non connecté'));
    exit();
}

$userId = $_SESSION['user_id'];

// Récupérer les tâches de la to-do list associées à l'utilisateur depuis la base de données
try {
    $stmt = $conn->prepare("SELECT id, task_name, completed, created_at FROM todo_tasks WHERE user_id = ?");
    $stmt->bind_param('i', $userId); // 'i' indique un paramètre de type entier
    $stmt->execute();
    $result = $stmt->get_result();
    $tasks = $result->fetch_all(MYSQLI_ASSOC);
    json_encode($tasks);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Erreur de base de données: ' . $e->getMessage()));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .todolist-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        .task-form {
            margin-bottom: 20px;
        }

        .task-form input[type="text"] {
            width: 70%;
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .task-form button {
            padding: 8px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .task-list {
            list-style-type: none;
            padding: 0;
        }

        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
            padding: 10px;
            background-color: #fafafa;
        }

        .task-item .task-name {
            flex-grow: 1;
        }

        .task-item button {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="todolist-container">
        <h2>ToDo List</h2>

        <div class="task-form">
            <input type="text" id="taskInput" placeholder="Ajouter une nouvelle tâche">
            <button onclick="addTask()">Ajouter</button>            
        </div>

        <ul class="task-list" id="taskList">
            <!-- Les tâches seront ajoutées ici dynamiquement -->
            <?php foreach ($tasks as $task) : ?>
                <li class="task-item" id="<?= $task['id'] ?>">
                    <div class="task-details">
                        <span class="task-name"><?= $task['task_name'] ?></span><br>
                        <span class="task-date"><?= $task['created_at'] ?></span><br>
                        <input type="checkbox" <?= $task['completed'] ? 'checked' : '' ?> onchange="completeTask(<?= $task['id'] ?>)">
                    </div>
                    <button onclick="removeTask(<?= $task['id'] ?>)">Supprimer</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>

        function updateTaskList() {
            // Effectuer une requête Ajax pour récupérer les nouvelles tâches depuis le serveur
            $.ajax({
                url: 'getTasks.php', // Créez un fichier getTasks.php pour récupérer les tâches
                type: 'GET',
                success: function (response) {
                    // Mettre à jour dynamiquement la liste des tâches
                    $('#taskList').html(response);
                },
                error: function (error) {
                    console.error('Erreur lors de la récupération des tâches: ' + JSON.stringify(error));
                }
            });
        }

        function addTask() {
            var taskInput = document.getElementById('taskInput');
            var taskList = document.getElementById('taskList');

            // Stocker la valeur du champ de saisie
            var taskNameValue = taskInput.value.trim();

            if (taskNameValue !== '') {
                // Créer un nouvel élément li (tâche)
                var taskItem = document.createElement('li');
                taskItem.className = 'task-item';

                // Créer un span pour afficher le nom de la tâche
                var taskName = document.createElement('span');
                taskName.className = 'task-name';
                taskName.innerText = taskNameValue;

                // Créer un bouton pour supprimer la tâche
                var deleteButton = document.createElement('button');
                deleteButton.innerText = 'Supprimer';
                deleteButton.addEventListener('click', function () {
                    removeTask(taskItem.id);
                });

                // Ajouter le span et le bouton à la tâche
                taskItem.appendChild(taskName);
                taskItem.appendChild(deleteButton);

                // Ajouter la tâche à la liste
                taskList.appendChild(taskItem);

                // Effacer le champ de saisie
                taskInput.value = '';

                // Ajoutez ici le code pour ajouter la tâche à la base de données (Ajax, etc.)
                // Utilisez taskNameValue pour obtenir le nom de la tâche

                $.ajax({
                    url: 'addTask.php',
                    type: 'POST',
                    data: { taskName: taskNameValue },
                    success: function (response) {
                        updateTaskList();
                        console.log('Tâche ajoutée avec succès à la base de données');
                    },
                    error: function (error) {
                        console.error('Erreur lors de l\'ajout de la tâche à la base de données: ' + JSON.stringify(error));
                    }
                });
            }

        }


        function removeTask(taskId) {
            var taskItem = document.getElementById(taskId);

            // Supprimer la tâche de l'interface
            if (taskItem) {
                taskItem.remove();

                // Ajoutez ici le code pour supprimer la tâche de la base de données (Ajax, etc.)
                // Utilisez la variable taskId pour envoyer l'ID de la tâche à supprimer

                $.ajax({
                    url: 'removeTask.php',
                    type: 'POST',
                    data: { taskId: taskId },
                    success: function (response) {
                        updateTaskList();
                        console.log('Tâche supprimée avec succès de la base de données');
                    },
                    error: function (error) {
                        console.error('Erreur lors de la suppression de la tâche de la base de données: ' + JSON.stringify(error));
                    }
                });
            }

        }


        function completeTask(taskId) {
            var checkbox = document.getElementById('taskCheckbox-' + taskId);
            
            // Envoyer la mise à jour à la base de données
            $.ajax({
                url: 'completeTask.php',
                type: 'POST',
                data: { taskId: taskId, completed: checkbox.checked },
                success: function (response) {
                    console.log('Statut de la tâche mis à jour avec succès');
                },
                error: function (error) {
                    console.error('Erreur lors de la mise à jour du statut de la tâche: ' + JSON.stringify(error));
                }
            });
        }

    </script>

</body>
</html>
