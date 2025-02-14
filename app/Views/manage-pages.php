<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Pages</title>

    <script src="https://cdn.tiny.cloud/1/no4el2dls3ms4xzvu5gtl4ehoedvog1w20cpsblibmyif6gh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            height: 300,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            branding: false
        });
    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .admin-btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            margin-top: 15px;
            transition: 0.3s;
        }

        .admin-btn:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>

    <h1>Gérer les pages</h1>

    <?php if (!empty($errorMessage)): ?>
        <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>

    <h2>Créer une nouvelle page</h2>
    
    <form method="POST">
        <input type="text" name="title" placeholder="Titre de la page" required>
        <input type="text" name="slug" placeholder="Slug de la page (optionnel)" pattern="[a-z0-9-]+" title="Seuls les lettres minuscules, chiffres et tirets sont autorisés.">
        <textarea id="content" name="content"></textarea>
        <button type="submit">Créer la page</button>
    </form>

    <a href="index.php?page=home" class="admin-btn">Retour</a>

</body>
</html>
