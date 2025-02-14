<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la page</title>

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
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        .container {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
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

        /* Back Button */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #0056b3;
        }

        /* Error Message */
        .error {
            color: red;
            font-weight: bold;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Modifier la page CMS</h1>

        <form method="POST">
            <label>Titre</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($page['title']) ?>">

            <label>Slug</label>
            <input type="text" name="slug" pattern="[a-z0-9-]+" title="Seuls les lettres minuscules, chiffres et tirets sont autorisés." value="<?= htmlspecialchars($page['slug']) ?>">

            <label>Contenu</label>
            <textarea id="content" name="content"><?= htmlspecialchars($page['content']) ?></textarea>

            <input type="hidden" name="id" value="<?= htmlspecialchars($page['id']) ?>">
            <button type="submit" name="submit_edit_page">Enregistrer</button>
        </form>

        <?php if (isset($result) && !$result): ?>
            <p class="error">Erreur lors de la modification</p>
        <?php endif; ?>

        <a href="index.php?page=home" class="back-btn">Retour</a>
    </div>

</body>
</html>
