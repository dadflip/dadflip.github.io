<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Erreur - Service Temporairement Indisponible</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      background-color: #f0f0f0;
    }

    .error-container {
      text-align: center;
      margin-top: 100px;
    }

    .error-message {
      font-size: 24px;
      color: #e74c3c;
      margin-bottom: 20px;
    }

    .back-button {
      background-color: #3498db;
      color: #fff;
      padding: 10px 20px;
      font-size: 18px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .back-button:hover {
      background-color: #2980b9;
    }
  </style>
</head>

<body>
  <div class="error-container">
    <p class="error-message">Le service est temporairement indisponible. Veuillez réessayer plus tard.</p>
    <button class="back-button" onclick="retourPagePrecedente()">Revenir à la page précédente</button>
  </div>

  <script>
    function retourPagePrecedente() {
      window.history.back();
    }
  </script>
</body>

</html>
