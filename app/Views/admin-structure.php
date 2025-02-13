<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la structure globale</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        textarea {
            width: 100%;
            height: 150px;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            display: block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

    <script src="https://cdn.tiny.cloud/1/no4el2dls3ms4xzvu5gtl4ehoedvog1w20cpsblibmyif6gh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.wysiwyg',
            height: 200,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            branding: false
        });
    </script>
</head>
<body>

    <div class="container">
        <h1>Modifier la structure globale du CMS</h1>

        <form method="POST" action="index.php?page=admin-structure">
            <input type="hidden" name="update_structure" value="1">

            <label>Head</label>
            <textarea class="wysiwyg" name="head"><?= htmlspecialchars($structure['head'] ?? '') ?></textarea>

            <label>Header</label>
            <textarea class="wysiwyg" name="header"><?= htmlspecialchars($structure['header'] ?? '') ?></textarea>

            <label>Footer</label>
            <textarea class="wysiwyg" name="footer"><?= htmlspecialchars($structure['footer'] ?? '') ?></textarea>

            <button type="submit">Enregistrer</button>
        </form>

        <a href="index.php?page=admin" class="back-link">Retour à l'admin</a>
    </div>

</body>
</html>
