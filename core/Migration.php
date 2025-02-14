<?php
namespace Core;

use PDO;
use Core\Database;
use PDOException;

class Migration {
    private static array $migrations = [
        "001_create_users_table" => "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('user', 'admin') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;",
        
        "002_create_pages_table" => "CREATE TABLE IF NOT EXISTS pages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            content TEXT NOT NULL,
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;",

        "003_create_structure_table" => "CREATE TABLE IF NOT EXISTS structure (
            id INT AUTO_INCREMENT PRIMARY KEY,
            head TEXT NOT NULL,
            header TEXT NOT NULL,
            footer TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;",
    ];

    private static string $migrationTable = "migrations";

    public static function setupMigrationTable() {
        $db = Database::getInstance();
        $db->exec("CREATE TABLE IF NOT EXISTS " . self::$migrationTable . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;");
    }

    public static function getExecutedMigrations() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT migration FROM " . self::$migrationTable);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function insertDefaultData() {
        $db = Database::getInstance();

        $stmt = $db->query("SELECT COUNT(*) FROM users WHERE email = 'admin@example.com'");
        $adminExists = $stmt->fetchColumn();

        $stmt = $db->query("SELECT COUNT(*) FROM users WHERE email = 'user@example.com'");
        $userExists = $stmt->fetchColumn();

        if ($adminExists == 0) {
            $hashedPassword = password_hash("admin123", PASSWORD_BCRYPT);
            $db->exec("INSERT INTO users (email, password, role) VALUES 
                ('admin@example.com', '$hashedPassword', 'admin')");
            echo "Admin user inserted.\n";
        }

        if ($userExists == 0) {
            $hashedPassword = password_hash("user123", PASSWORD_BCRYPT);
            $db->exec("INSERT INTO users (email, password, role) VALUES 
                ('user@example.com', '$hashedPassword', 'user')");
            echo "Normal user inserted.\n";
        }

        $stmt = $db->query("SELECT COUNT(*) FROM pages");
        $count = $stmt->fetchColumn();

        if ($count == 0) {

            $contenue = "
            <h2 style=\"color: #0073e6; font-size: 24px; text-align: center;\">Une solution simple et efficace</h2>
            <br/>
            <p style=\"font-size: 16px; color: #333; text-align: center;\">
                Notre CMS est conçu pour offrir une expérience utilisateur fluide et intuitive. Grâce à son interface ergonomique, vous pouvez facilement gérer votre contenu en quelques clics. Que vous soyez un débutant ou un expert, notre plateforme s\'adapte à vos besoins en vous proposant des fonctionnalités avancées tout en restant simple d\'utilisation. Vous pourrez créer des pages dynamiques, structurer votre contenu avec des catégories et balises, et programmer la publication de vos articles selon votre calendrier éditorial.
            </p>
            <br/><br/>
            <p style=\"font-size: 16px; color: #333; text-align: center;\">
                Avec notre CMS, vous bénéficiez d\'un large éventail d\'outils pour organiser et structurer vos articles, pages et médias. La personnalisation est au cœur de notre solution : ajoutez vos propres styles, intégrez des images et des vidéos, et optimisez votre référencement sans effort. Grâce à un tableau de bord clair et des fonctionnalités de glisser-déposer, vous prenez le contrôle total de votre site web en toute autonomie. De plus, notre éditeur WYSIWYG vous permet de visualiser vos modifications en temps réel, sans avoir à écrire une seule ligne de code. Vous pouvez également intégrer des widgets, ajouter des formulaires de contact interactifs et même connecter des plugins tiers pour enrichir encore plus votre site.
            </p>
            <br/><br/>
            <p style=\"font-size: 16px; color: #333; text-align: center;\">
                De plus, la sécurité et la performance sont au centre de nos préoccupations. Nous garantissons des mises à jour régulières et une protection renforcée contre les menaces potentielles. Nos serveurs utilisent les dernières technologies pour assurer une vitesse de chargement optimale et une expérience utilisateur sans latence. Notre CMS inclut également un système de sauvegarde automatique, garantissant que votre contenu reste toujours sécurisé en cas d\'imprévu. Vous pourrez gérer les autorisations et rôles des utilisateurs pour une administration simplifiée et une meilleure organisation au sein de votre équipe.
            </p>
            <br/><br/>
            <p style=\"font-size: 16px; color: #333; text-align: center;\">
                En plus de toutes ces fonctionnalités, notre CMS est optimisé pour le référencement (SEO). Grâce à des outils intégrés, vous pourrez améliorer le classement de votre site sur les moteurs de recherche en optimisant vos balises méta, vos URL et votre structure de contenu. La compatibilité mobile est également assurée, garantissant une expérience fluide sur tous les types d\'appareils, qu\'il s\'agisse d\'un ordinateur, d\'une tablette ou d\'un smartphone. Notre solution est idéale pour les blogueurs, les entreprises, les agences web et tous ceux qui souhaitent développer une présence en ligne performante.
            </p>
            <br/><br/>
            <p style=\"font-size: 16px; color: #333; text-align: center;\">
                Enfin, notre équipe d\'assistance est disponible pour répondre à toutes vos questions et vous accompagner dans la prise en main du CMS. Que vous ayez besoin d\'aide pour la configuration, l\'installation d\'un plugin ou la personnalisation de votre site, nous sommes là pour vous guider pas à pas. Notre communauté active d\'utilisateurs partage régulièrement des conseils et des astuces, vous permettant de tirer le meilleur parti de notre solution.
            </p>
            <br/><br/>
            <p style=\"font-size: 10px; color:rgb(114, 114, 114); text-align: center;\">
                Tout ce texte n\'est que du pipo, il fallait juste faire une page de présentation pour que ça fasse moins vide. voila voila.    
            </p>";

            $db->exec("INSERT INTO pages (title, slug, content, created_by) VALUES 
                ('Bienvenue', 'bienvenue', '" . $contenue . "', 1)");
            echo "Default page inserted.\n";


            $contenue = "
            <p>La liste des Pokémon recense l\'ensemble des 1025 espèces de Pokémon, réparties en neuf générations. Les différentes espèces sont numérotées dans l\'ordre de l\'encyclopédie animalière fictive de la franchise dit le « Pokédex national » ; les versions de l\'encyclopédie spécifiques à chaque paire de jeux de la série principale et ne recensant que les espèces locales de chaque région sont appelées « Pokédex régionaux ».</p>
        
            <br/><br/>
            <h2>Générations</h2>
            <br/><br/>
            <p>Terme d\'abord utilisé par les fans de la franchise avant d\'être parfois repris par Nintendo, les « générations » désignent un ensemble de créatures apparues pour la première fois dans une nouvelle paire de jeux vidéo...</p>
            
            <br/><br/>
            <ul>
                <li>Elle commence par les trois Pokémon de départ et leurs évolutions respectives : « Plante », « Feu » et « Eau ».</li>
                <li>Les Pokémon légendaires se trouvent à la fin de la liste, suivi des Pokémon fabuleux et des Ultra-Chimères.</li>
                <li>Un petit rongeur électrique avec ou sans évolution (appelé « Pikachu-like » par les fans).</li>
                <li>Près du début de la liste, un ou deux groupes de Pokémon insectes, un groupe de rongeurs et un groupe d\'oiseaux.</li>
                <li>Vers la fin de la liste, un groupe de « semi-légendaires » : une famille de 2 évolutions.</li>
            </ul>

            <br/><br/>
            <br/><br/>
            <h2>Liste des Générations</h2>
            
            <br/><br/>
            <ul>
                <li><strong>Première génération</strong> : Introduit les 151 premiers Pokémon.</li>
                <li><strong>Deuxième génération</strong> : Introduit 100 espèces.</li>
                <li><strong>Troisième génération</strong> : Introduit 135 espèces.</li>
                <li><strong>Quatrième génération</strong> : Introduit 107 espèces.</li>
                <li><strong>Cinquième génération</strong> : Introduit 156 espèces.</li>
                <li><strong>Sixième génération</strong> : Introduit 72 espèces.</li>
                <li><strong>Septième génération</strong> : Introduit 88 espèces.</li>
                <li><strong>Huitième génération</strong> : Introduit 89 espèces.</li>
                <li><strong>Neuvième génération</strong> : Introduit 120 nouvelles espèces.</li>
            </ul>
            
            <br/><br/>
            <br/><br/>
            <h2>Première génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\">
                <li style=\"margin-right: 20px;\">Bulbizarre</li><li style=\"margin-right: 20px;\">Herbizarre</li><li style=\"margin-right: 20px;\">Florizarre</li><li style=\"margin-right: 20px;\">Salamèche</li><li style=\"margin-right: 20px;\">Reptincel</li><li style=\"margin-right: 20px;\">Dracaufeu</li><li style=\"margin-right: 20px;\">Carapuce</li><li style=\"margin-right: 20px;\">Carabaffe</li><li style=\"margin-right: 20px;\">Tortank</li><li style=\"margin-right: 20px;\">Chenipan</li><li style=\"margin-right: 20px;\">Chrysacier</li><li style=\"margin-right: 20px;\">Papilusion</li><li style=\"margin-right: 20px;\">Aspicot</li><li style=\"margin-right: 20px;\">Coconfort</li><li style=\"margin-right: 20px;\">Dardargnan</li><li style=\"margin-right: 20px;\">Roucool</li><li style=\"margin-right: 20px;\">Roucoups</li><li style=\"margin-right: 20px;\">Roucarnage</li><li style=\"margin-right: 20px;\">Rattata</li><li style=\"margin-right: 20px;\">Rattatac</li><li style=\"margin-right: 20px;\">Pikachu</li><li style=\"margin-right: 20px;\">Raichu</li>
            </ul>

            <br/><br/>
            <h2>Deuxième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\">
                <li style=\"margin-right: 20px;\">Germignon</li><li style=\"margin-right: 20px;\">Macronium</li><li style=\"margin-right: 20px;\">Méganium</li><li style=\"margin-right: 20px;\">Héricendre</li><li style=\"margin-right: 20px;\">Feurisson</li><li style=\"margin-right: 20px;\">Typhlosion</li><li style=\"margin-right: 20px;\">Kaiminus</li><li style=\"margin-right: 20px;\">Crocrodil</li><li style=\"margin-right: 20px;\">Aligatueur</li><li style=\"margin-right: 20px;\">Fouinette</li><li style=\"margin-right: 20px;\">Fouinar</li><li style=\"margin-right: 20px;\">Hoothoot</li><li style=\"margin-right: 20px;\">Noarfang</li><li style=\"margin-right: 20px;\">Coxy</li><li style=\"margin-right: 20px;\">Coxyclaque</li><li style=\"margin-right: 20px;\">Mimigal</li><li style=\"margin-right: 20px;\">Migalos</li><li style=\"margin-right: 20px;\">Nostenfer</li><li style=\"margin-right: 20px;\">Loupio</li><li style=\"margin-right: 20px;\">Lanturn</li><li style=\"margin-right: 20px;\">Pichu</li><li style=\"margin-right: 20px;\">Mélo</li><li style=\"margin-right: 20px;\">Toudoudou</li><li style=\"margin-right: 20px;\">Togepi</li><li style=\"margin-right: 20px;\">Togetic</li><li style=\"margin-right: 20px;\">Natu</li><li style=\"margin-right: 20px;\">Xatu</li><li style=\"margin-right: 20px;\">Wattouat</li><li style=\"margin-right: 20px;\">Lainergie</li><li style=\"margin-right: 20px;\">Pharamp</li><li style=\"margin-right: 20px;\">Joliflor</li><li style=\"margin-right: 20px;\">Marill</li><li style=\"margin-right: 20px;\">Azumarill</li><li style=\"margin-right: 20px;\">Simularbre</li><li style=\"margin-right: 20px;\">Tarpaud</li><li style=\"margin-right: 20px;\">Granivol</li><li style=\"margin-right: 20px;\">Floravol</li><li style=\"margin-right: 20px;\">Cotovol</li><li style=\"margin-right: 20px;\">Capumain</li><li style=\"margin-right: 20px;\">Tournegrin</li><li style=\"margin-right: 20px;\">Héliatronc</li><li style=\"margin-right: 20px;\">Yanma</li><li style=\"margin-right: 20px;\">Axolot</li><li style=\"margin-right: 20px;\">Maraiste</li><li style=\"margin-right: 20px;\">Mentali</li><li style=\"margin-right: 20px;\">Noctali</li><li style=\"margin-right: 20px;\">Cornèbre</li><li style=\"margin-right: 20px;\">Roigad</li><li style=\"margin-right: 20px;\">Feuforêve</li><li style=\"margin-right: 20px;\">Zarbi</li><li style=\"margin-right: 20px;\">Qulbutoké</li><li style=\"margin-right: 20px;\">Girafarig</li><li style=\"margin-right: 20px;\">Pomdepik</li><li style=\"margin-right: 20px;\">Foretress</li><li style=\"margin-right: 20px;\">Insolourdo</li><li style=\"margin-right: 20px;\">Scorplane</li><li style=\"margin-right: 20px;\">Steelix</li><li style=\"margin-right: 20px;\">Snubbull</li><li style=\"margin-right: 20px;\">Granbull</li><li style=\"margin-right: 20px;\">Qwilfish</li><li style=\"margin-right: 20px;\">Cizayox</li><li style=\"margin-right: 20px;\">Caratroc</li><li style=\"margin-right: 20px;\">Scarhin</li><li style=\"margin-right: 20px;\">Farfuret</li><li style=\"margin-right: 20px;\">Teddiursa</li><li style=\"margin-right: 20px;\">Ursaring</li><li style=\"margin-right: 20px;\">Limagma</li><li style=\"margin-right: 20px;\">Volcaropod</li><li style=\"margin-right: 20px;\">Marcacrin</li><li style=\"margin-right: 20px;\">Cochignon</li><li style=\"margin-right: 20px;\">Corayon</li><li style=\"margin-right: 20px;\">Rémoraid</li><li style=\"margin-right: 20px;\">Octillery</li><li style=\"margin-right: 20px;\">Cadoizo</li><li style=\"margin-right: 20px;\">Démanta</li><li style=\"margin-right: 20px;\">Airmure</li><li style=\"margin-right: 20px;\">Malosse</li><li style=\"margin-right: 20px;\">Démoloss</li><li style=\"margin-right: 20px;\">Hyporoi</li><li style=\"margin-right: 20px;\">Phanpy</li><li style=\"margin-right: 20px;\">Donphan</li><li style=\"margin-right: 20px;\">Porygon2</li><li style=\"margin-right: 20px;\">Cerfrousse</li><li style=\"margin-right: 20px;\">Queulorior</li><li style=\"margin-right: 20px;\">Debugant</li><li style=\"margin-right: 20px;\">Kapoera</li><li style=\"margin-right: 20px;\">Lippouti</li><li style=\"margin-right: 20px;\">Élekid</li><li style=\"margin-right: 20px;\">Magby</li><li style=\"margin-right: 20px;\">Écrémeuh</li><li style=\"margin-right: 20px;\">Leuphorie</li><li style=\"margin-right: 20px;\">Raikou</li><li style=\"margin-right: 20px;\">Entei</li><li style=\"margin-right: 20px;\">Suicune</li><li style=\"margin-right: 20px;\">Embrylex</li><li style=\"margin-right: 20px;\">Ymphect</li><li style=\"margin-right: 20px;\">Tyranocif</li><li style=\"margin-right: 20px;\">Lugia</li><li style=\"margin-right: 20px;\">Ho-Oh</li><li style=\"margin-right: 20px;\">Celebi</li>
            </ul>

            <br/><br/>
            <h2>Troisième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\">
                <li style=\"margin-right: 20px;\">Arcko</li><li style=\"margin-right: 20px;\">Massko</li><li style=\"margin-right: 20px;\">Jungko</li><li style=\"margin-right: 20px;\">Poussifeu</li><li style=\"margin-right: 20px;\">Galifeu</li><li style=\"margin-right: 20px;\">Braségali</li><li style=\"margin-right: 20px;\">Gobou</li><li style=\"margin-right: 20px;\">Flobio</li><li style=\"margin-right: 20px;\">Laggron</li><li style=\"margin-right: 20px;\">Medhyèna</li><li style=\"margin-right: 20px;\">Grahyèna</li><li style=\"margin-right: 20px;\">Zigzaton</li><li style=\"margin-right: 20px;\">Linéon</li><li style=\"margin-right: 20px;\">Chenipotte</li><li style=\"margin-right: 20px;\">Armulys</li><li style=\"margin-right: 20px;\">Charmillon</li><li style=\"margin-right: 20px;\">Blindalys</li><li style=\"margin-right: 20px;\">Papinox</li><li style=\"margin-right: 20px;\">Nénupiot</li><li style=\"margin-right: 20px;\">Lombre</li><li style=\"margin-right: 20px;\">Ludicolo</li><li style=\"margin-right: 20px;\">Grainipiot</li><li style=\"margin-right: 20px;\">Pifeuil</li><li style=\"margin-right: 20px;\">Tengalice</li><li style=\"margin-right: 20px;\">Nirondelle</li><li style=\"margin-right: 20px;\">Hélédelle</li><li style=\"margin-right: 20px;\">Goélise</li><li style=\"margin-right: 20px;\">Bekipan</li><li style=\"margin-right: 20px;\">Tarsal</li><li style=\"margin-right: 20px;\">Kirlia</li><li style=\"margin-right: 20px;\">Gardevoir</li><li style=\"margin-right: 20px;\">Arakdo</li><li style=\"margin-right: 20px;\">Maskadra</li><li style=\"margin-right: 20px;\">Balignon</li><li style=\"margin-right: 20px;\">Chapignon</li><li style=\"margin-right: 20px;\">Parécool</li><li style=\"margin-right: 20px;\">Vigoroth</li><li style=\"margin-right: 20px;\">Monaflèmit</li><li style=\"margin-right: 20px;\">Ningale</li><li style=\"margin-right: 20px;\">Ninjask</li><li style=\"margin-right: 20px;\">Munja</li><li style=\"margin-right: 20px;\">Chuchmur</li><li style=\"margin-right: 20px;\">Ramboum</li><li style=\"margin-right: 20px;\">Brouhabam</li><li style=\"margin-right: 20px;\">Makuhita</li><li style=\"margin-right: 20px;\">Hariyama</li><li style=\"margin-right: 20px;\">Azurill</li><li style=\"margin-right: 20px;\">Tarinor</li><li style=\"margin-right: 20px;\">Skitty</li><li style=\"margin-right: 20px;\">Delcatty</li><li style=\"margin-right: 20px;\">Ténéfix</li><li style=\"margin-right: 20px;\">Mysdibule</li><li style=\"margin-right: 20px;\">Galekid</li><li style=\"margin-right: 20px;\">Galegon</li><li style=\"margin-right: 20px;\">Galeking</li><li style=\"margin-right: 20px;\">Méditikka</li><li style=\"margin-right: 20px;\">Charmina</li><li style=\"margin-right: 20px;\">Dynavolt</li><li style=\"margin-right: 20px;\">Élecsprint</li><li style=\"margin-right: 20px;\">Posipi</li><li style=\"margin-right: 20px;\">Négapi</li><li style=\"margin-right: 20px;\">Muciole</li><li style=\"margin-right: 20px;\">Lumivole</li><li style=\"margin-right: 20px;\">Rosélia</li><li style=\"margin-right: 20px;\">Gloupti</li><li style=\"margin-right: 20px;\">Avaltout</li><li style=\"margin-right: 20px;\">Carvanha</li><li style=\"margin-right: 20px;\">Sharpedo</li><li style=\"margin-right: 20px;\">Wailmer</li><li style=\"margin-right: 20px;\">Wailord</li><li style=\"margin-right: 20px;\">Chamallot</li><li style=\"margin-right: 20px;\">Camérupt</li><li style=\"margin-right: 20px;\">Chartor</li><li style=\"margin-right: 20px;\">Spoink</li><li style=\"margin-right: 20px;\">Groret</li><li style=\"margin-right: 20px;\">Spinda</li><li style=\"margin-right: 20px;\">Kraknoix</li><li style=\"margin-right: 20px;\">Vibraninf</li><li style=\"margin-right: 20px;\">Libégon</li><li style=\"margin-right: 20px;\">Cacnea</li><li style=\"margin-right: 20px;\">Cacturne</li><li style=\"margin-right: 20px;\">Tylton</li><li style=\"margin-right: 20px;\">Altaria</li><li style=\"margin-right: 20px;\">Mangriff</li><li style=\"margin-right: 20px;\">Séviper</li><li style=\"margin-right: 20px;\">Séléroc</li><li style=\"margin-right: 20px;\">Solaroc</li><li style=\"margin-right: 20px;\">Barloche</li><li style=\"margin-right: 20px;\">Barbicha</li><li style=\"margin-right: 20px;\">Écrapince</li><li style=\"margin-right: 20px;\">Colhomard</li><li style=\"margin-right: 20px;\">Balbuto</li><li style=\"margin-right: 20px;\">Kaorine</li><li style=\"margin-right: 20px;\">Lilia</li><li style=\"margin-right: 20px;\">Vacilys</li><li style=\"margin-right: 20px;\">Anorith</li><li style=\"margin-right: 20px;\">Armaldo</li><li style=\"margin-right: 20px;\">Barpau</li><li style=\"margin-right: 20px;\">Milobellus</li><li style=\"margin-right: 20px;\">Morphéo</li><li style=\"margin-right: 20px;\">Kecleon</li><li style=\"margin-right: 20px;\">Polichombr</li><li style=\"margin-right: 20px;\">Branette</li><li style=\"margin-right: 20px;\">Skelénox</li><li style=\"margin-right: 20px;\">Téraclope</li><li style=\"margin-right: 20px;\">Tropius</li><li style=\"margin-right: 20px;\">Éoko</li><li style=\"margin-right: 20px;\">Absol</li><li style=\"margin-right: 20px;\">Okéoké</li><li style=\"margin-right: 20px;\">Stalgamin</li><li style=\"margin-right: 20px;\">Oniglalin</li><li style=\"margin-right: 20px;\">Obalie</li><li style=\"margin-right: 20px;\">Phogleur</li><li style=\"margin-right: 20px;\">Kaimorse</li><li style=\"margin-right: 20px;\">Coquiperl</li><li style=\"margin-right: 20px;\">Serpang</li><li style=\"margin-right: 20px;\">Rosabyss</li><li style=\"margin-right: 20px;\">Relicanth</li><li style=\"margin-right: 20px;\">Lovdisc</li><li style=\"margin-right: 20px;\">Draby</li><li style=\"margin-right: 20px;\">Drackhaus</li><li style=\"margin-right: 20px;\">Drattak</li><li style=\"margin-right: 20px;\">Terhal</li><li style=\"margin-right: 20px;\">Métang</li><li style=\"margin-right: 20px;\">Métalosse</li><li style=\"margin-right: 20px;\">Regirock</li><li style=\"margin-right: 20px;\">Regice</li><li style=\"margin-right: 20px;\">Registeel</li><li style=\"margin-right: 20px;\">Latias</li><li style=\"margin-right: 20px;\">Latios</li><li style=\"margin-right: 20px;\">Kyogre</li><li style=\"margin-right: 20px;\">Groudon</li><li style=\"margin-right: 20px;\">Rayquaza</li><li style=\"margin-right: 20px;\">Jirachi</li><li style=\"margin-right: 20px;\">Deoxys</li>
            </ul>

            <br/><br/>
            <h2>Quatrième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\">
                <li style=\"margin-right: 20px;\">Tortipouss</li><li style=\"margin-right: 20px;\">Boskara</li><li style=\"margin-right: 20px;\">Torterra</li><li style=\"margin-right: 20px;\">Ouisticram</li><li style=\"margin-right: 20px;\">Chimpenfeu</li><li style=\"margin-right: 20px;\">Simiabraz</li><li style=\"margin-right: 20px;\">Tiplouf</li><li style=\"margin-right: 20px;\">Prinplouf</li><li style=\"margin-right: 20px;\">Pingoléon</li><li style=\"margin-right: 20px;\">Étourmi</li><li style=\"margin-right: 20px;\">Étourvol</li><li style=\"margin-right: 20px;\">Étouraptor</li><li style=\"margin-right: 20px;\">Keunotor</li><li style=\"margin-right: 20px;\">Castorno</li><li style=\"margin-right: 20px;\">Crikzik</li><li style=\"margin-right: 20px;\">Mélokrik</li><li style=\"margin-right: 20px;\">Lixy</li><li style=\"margin-right: 20px;\">Luxio</li><li style=\"margin-right: 20px;\">Luxray</li><li style=\"margin-right: 20px;\">Rozbouton</li><li style=\"margin-right: 20px;\">Roserade</li><li style=\"margin-right: 20px;\">Kranidos</li><li style=\"margin-right: 20px;\">Charkos</li><li style=\"margin-right: 20px;\">Dinoclier</li><li style=\"margin-right: 20px;\">Bastiodon</li><li style=\"margin-right: 20px;\">Cheniti</li><li style=\"margin-right: 20px;\">Cheniselle</li><li style=\"margin-right: 20px;\">Papilord</li><li style=\"margin-right: 20px;\">Apitrini</li><li style=\"margin-right: 20px;\">Apireine</li><li style=\"margin-right: 20px;\">Pachirisu</li><li style=\"margin-right: 20px;\">Mustébouée</li><li style=\"margin-right: 20px;\">Mustéflott</li><li style=\"margin-right: 20px;\">Ceribou</li><li style=\"margin-right: 20px;\">Ceriflor</li><li style=\"margin-right: 20px;\">Sancoki</li><li style=\"margin-right: 20px;\">Tritosor</li><li style=\"margin-right: 20px;\">Capidextre</li><li style=\"margin-right: 20px;\">Baudrive</li><li style=\"margin-right: 20px;\">Grodrive</li><li style=\"margin-right: 20px;\">Laporeille</li><li style=\"margin-right: 20px;\">Lockpin</li><li style=\"margin-right: 20px;\">Magirêve</li><li style=\"margin-right: 20px;\">Corboss</li><li style=\"margin-right: 20px;\">Chaglam</li><li style=\"margin-right: 20px;\">Chaffreux</li><li style=\"margin-right: 20px;\">Korillon</li><li style=\"margin-right: 20px;\">Moufouette</li><li style=\"margin-right: 20px;\">Moufflair</li><li style=\"margin-right: 20px;\">Archéomire</li><li style=\"margin-right: 20px;\">Archéodong</li><li style=\"margin-right: 20px;\">Manzaï</li><li style=\"margin-right: 20px;\">Mime Jr.</li><li style=\"margin-right: 20px;\">Ptiravi</li><li style=\"margin-right: 20px;\">Pijako</li><li style=\"margin-right: 20px;\">Spiritomb</li><li style=\"margin-right: 20px;\">Griknot</li><li style=\"margin-right: 20px;\">Carmache</li><li style=\"margin-right: 20px;\">Carchacrokn</li><li style=\"margin-right: 20px;\">Goinfrex</li><li style=\"margin-right: 20px;\">Riolu</li><li style=\"margin-right: 20px;\">Lucario</li><li style=\"margin-right: 20px;\">Hippopotas</li><li style=\"margin-right: 20px;\">Hippodocus</li><li style=\"margin-right: 20px;\">Rapion</li><li style=\"margin-right: 20px;\">Drascore</li><li style=\"margin-right: 20px;\">Cradopaud</li><li style=\"margin-right: 20px;\">Coatox</li><li style=\"margin-right: 20px;\">Vortente</li><li style=\"margin-right: 20px;\">Écayon</li><li style=\"margin-right: 20px;\">Luminéon</li><li style=\"margin-right: 20px;\">Babimanta</li><li style=\"margin-right: 20px;\">Blizzi</li><li style=\"margin-right: 20px;\">Blizzaro</li><li style=\"margin-right: 20px;\">Dimoret</li><li style=\"margin-right: 20px;\">Magnézone</li><li style=\"margin-right: 20px;\">Coudlangue</li><li style=\"margin-right: 20px;\">Rhinastoc</li><li style=\"margin-right: 20px;\">Bouldeneu</li><li style=\"margin-right: 20px;\">Élekable</li><li style=\"margin-right: 20px;\">Maganon</li><li style=\"margin-right: 20px;\">Togekiss</li><li style=\"margin-right: 20px;\">Yanmega</li><li style=\"margin-right: 20px;\">Phyllali</li><li style=\"margin-right: 20px;\">Givrali</li><li style=\"margin-right: 20px;\">Scorvol</li><li style=\"margin-right: 20px;\">Mammochon</li><li style=\"margin-right: 20px;\">Porygon-Z</li><li style=\"margin-right: 20px;\">Gallamenote</li><li style=\"margin-right: 20px;\">Tarinorme</li><li style=\"margin-right: 20px;\">Noctunoir</li><li style=\"margin-right: 20px;\">Momartik</li><li style=\"margin-right: 20px;\">Motisma</li><li style=\"margin-right: 20px;\">Créhelf</li><li style=\"margin-right: 20px;\">Créfollet</li><li style=\"margin-right: 20px;\">Créfadet</li><li style=\"margin-right: 20px;\">Dialga</li><li style=\"margin-right: 20px;\">Palkia</li><li style=\"margin-right: 20px;\">Heatran</li><li style=\"margin-right: 20px;\">Regigigas</li><li style=\"margin-right: 20px;\">Giratina</li><li style=\"margin-right: 20px;\">Cresselia</li><li style=\"margin-right: 20px;\">Phione</li><li style=\"margin-right: 20px;\">Manaphy</li><li style=\"margin-right: 20px;\">Darkrai</li><li style=\"margin-right: 20px;\">Shaymin</li><li style=\"margin-right: 20px;\">Arceus</li>
            </ul>

            <br/><br/>
            <h2>Cinquième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\"><li style=\"margin-right: 20px;\">Victini</li><li style=\"margin-right: 20px;\">Vipélierre</li><li style=\"margin-right: 20px;\">Lianaja</li><li style=\"margin-right: 20px;\">Majaspic</li><li style=\"margin-right: 20px;\">Gruikui</li><li style=\"margin-right: 20px;\">Grotichon</li><li style=\"margin-right: 20px;\">Roitiflam</li><li style=\"margin-right: 20px;\">Moustillon</li><li style=\"margin-right: 20px;\">Mateloutre</li><li style=\"margin-right: 20px;\">Clamiral</li><li style=\"margin-right: 20px;\">Ratentif</li><li style=\"margin-right: 20px;\">Miradar</li><li style=\"margin-right: 20px;\">Ponchiot</li><li style=\"margin-right: 20px;\">Ponchien</li><li style=\"margin-right: 20px;\">Mastouffe</li><li style=\"margin-right: 20px;\">Chacripan</li><li style=\"margin-right: 20px;\">Léopardus</li><li style=\"margin-right: 20px;\">Feuillajou</li><li style=\"margin-right: 20px;\">Feuiloutan</li><li style=\"margin-right: 20px;\">Flamajou</li><li style=\"margin-right: 20px;\">Flamoutan</li><li style=\"margin-right: 20px;\">Flotajou</li><li style=\"margin-right: 20px;\">Flotoutan</li><li style=\"margin-right: 20px;\">Munna</li><li style=\"margin-right: 20px;\">Mushana</li><li style=\"margin-right: 20px;\">Poichigeon</li><li style=\"margin-right: 20px;\">Colombeau</li><li style=\"margin-right: 20px;\">Déflaisan</li><li style=\"margin-right: 20px;\">Zébibron</li><li style=\"margin-right: 20px;\">Zéblitz</li><li style=\"margin-right: 20px;\">Nodulithe</li><li style=\"margin-right: 20px;\">Géolithe</li><li style=\"margin-right: 20px;\">Gigalithe</li><li style=\"margin-right: 20px;\">Chovsourir</li><li style=\"margin-right: 20px;\">Rhinolove</li><li style=\"margin-right: 20px;\">Rototaupe</li><li style=\"margin-right: 20px;\">Minotaupe</li><li style=\"margin-right: 20px;\">Nanméouïen</li><li style=\"margin-right: 20px;\">Charpenti</li><li style=\"margin-right: 20px;\">Ouvrifier</li><li style=\"margin-right: 20px;\">Bétochef</li><li style=\"margin-right: 20px;\">Tritonde</li><li style=\"margin-right: 20px;\">Batracné</li><li style=\"margin-right: 20px;\">Crapustule</li><li style=\"margin-right: 20px;\">Judokrak</li><li style=\"margin-right: 20px;\">Karaclée</li><li style=\"margin-right: 20px;\">Larveyette</li><li style=\"margin-right: 20px;\">Couverdure</li><li style=\"margin-right: 20px;\">Manternel</li><li style=\"margin-right: 20px;\">Venipatte</li><li style=\"margin-right: 20px;\">Scobolide</li><li style=\"margin-right: 20px;\">Brutapode</li><li style=\"margin-right: 20px;\">Doudouvet</li><li style=\"margin-right: 20px;\">Farfaduvet</li><li style=\"margin-right: 20px;\">Chlorobule</li><li style=\"margin-right: 20px;\">Fragilady</li><li style=\"margin-right: 20px;\">Bargantua</li><li style=\"margin-right: 20px;\">Mascaïman</li><li style=\"margin-right: 20px;\">Escroco</li><li style=\"margin-right: 20px;\">Crocorible</li><li style=\"margin-right: 20px;\">Darumarond</li><li style=\"margin-right: 20px;\">Darumachon</li><li style=\"margin-right: 20px;\">Maracachi</li><li style=\"margin-right: 20px;\">Crabicoque</li><li style=\"margin-right: 20px;\">Crabaraque</li><li style=\"margin-right: 20px;\">Baggiguane</li><li style=\"margin-right: 20px;\">Baggaïd</li><li style=\"margin-right: 20px;\">Cryptéro</li><li style=\"margin-right: 20px;\">Tutafeh</li><li style=\"margin-right: 20px;\">Tutankafer</li><li style=\"margin-right: 20px;\">Carapagos</li><li style=\"margin-right: 20px;\">Mégapagos</li><li style=\"margin-right: 20px;\">Arkéapti</li><li style=\"margin-right: 20px;\">Aéroptéryx</li><li style=\"margin-right: 20px;\">Miamiasme</li><li style=\"margin-right: 20px;\">Miasmax</li><li style=\"margin-right: 20px;\">Zorua</li><li style=\"margin-right: 20px;\">Zoroark</li><li style=\"margin-right: 20px;\">Chinchidou</li><li style=\"margin-right: 20px;\">Pashmilla</li><li style=\"margin-right: 20px;\">Scrutella</li><li style=\"margin-right: 20px;\">Mesmérella</li><li style=\"margin-right: 20px;\">Sidérella</li><li style=\"margin-right: 20px;\">Nucléos</li><li style=\"margin-right: 20px;\">Méios</li><li style=\"margin-right: 20px;\">Symbios</li><li style=\"margin-right: 20px;\">Couaneton</li><li style=\"margin-right: 20px;\">Lakmécygne</li><li style=\"margin-right: 20px;\">Sorbébé</li><li style=\"margin-right: 20px;\">Sorboul</li><li style=\"margin-right: 20px;\">Sorbouboul</li><li style=\"margin-right: 20px;\">Vivaldaim</li><li style=\"margin-right: 20px;\">Haydaim</li><li style=\"margin-right: 20px;\">Emolga</li><li style=\"margin-right: 20px;\">Carabing</li><li style=\"margin-right: 20px;\">Lançargot</li><li style=\"margin-right: 20px;\">Trompignon</li><li style=\"margin-right: 20px;\">Gaulet</li><li style=\"margin-right: 20px;\">Viskuse</li><li style=\"margin-right: 20px;\">Moyade</li><li style=\"margin-right: 20px;\">Mamanbo</li><li style=\"margin-right: 20px;\">Statitik</li><li style=\"margin-right: 20px;\">Mygavolt</li><li style=\"margin-right: 20px;\">Grindur</li><li style=\"margin-right: 20px;\">Noacier</li><li style=\"margin-right: 20px;\">Tic</li><li style=\"margin-right: 20px;\">Clic</li><li style=\"margin-right: 20px;\">Cliticlic</li><li style=\"margin-right: 20px;\">Anchwatt</li><li style=\"margin-right: 20px;\">Lampéroie</li><li style=\"margin-right: 20px;\">Ohmassacre</li><li style=\"margin-right: 20px;\">Lewsor</li><li style=\"margin-right: 20px;\">Neitram</li><li style=\"margin-right: 20px;\">Funécire</li><li style=\"margin-right: 20px;\">Mélancolux</li><li style=\"margin-right: 20px;\">Lugulabre</li><li style=\"margin-right: 20px;\">Coupenotte</li><li style=\"margin-right: 20px;\">Incisache</li><li style=\"margin-right: 20px;\">Tranchodon</li><li style=\"margin-right: 20px;\">Polarhume</li><li style=\"margin-right: 20px;\">Polagriffe</li><li style=\"margin-right: 20px;\">Hexagel</li><li style=\"margin-right: 20px;\">Escargaume</li><li style=\"margin-right: 20px;\">Limaspeed</li><li style=\"margin-right: 20px;\">Limonden</li><li style=\"margin-right: 20px;\">Kungfouine</li><li style=\"margin-right: 20px;\">Shaofouine</li><li style=\"margin-right: 20px;\">Drakkarmin</li><li style=\"margin-right: 20px;\">Gringolem</li><li style=\"margin-right: 20px;\">Golemastoc</li><li style=\"margin-right: 20px;\">Scalpion</li><li style=\"margin-right: 20px;\">Scalproie</li><li style=\"margin-right: 20px;\">Frison</li><li style=\"margin-right: 20px;\">Furaiglon</li><li style=\"margin-right: 20px;\">Gueriaigle</li><li style=\"margin-right: 20px;\">Vostourno</li><li style=\"margin-right: 20px;\">Vaututrice</li><li style=\"margin-right: 20px;\">Aflamanoir</li><li style=\"margin-right: 20px;\">Fermite</li><li style=\"margin-right: 20px;\">Solochi</li><li style=\"margin-right: 20px;\">Diamat</li><li style=\"margin-right: 20px;\">Trioxhydre</li><li style=\"margin-right: 20px;\">Pyronille</li><li style=\"margin-right: 20px;\">Pyrax</li><li style=\"margin-right: 20px;\">Cobaltium</li><li style=\"margin-right: 20px;\">Terrakium</li><li style=\"margin-right: 20px;\">Viridium</li><li style=\"margin-right: 20px;\">Boréas</li><li style=\"margin-right: 20px;\">Fulguris</li><li style=\"margin-right: 20px;\">Reshiram</li><li style=\"margin-right: 20px;\">Zekrom</li><li style=\"margin-right: 20px;\">Démétéros</li><li style=\"margin-right: 20px;\">Kyurem</li><li style=\"margin-right: 20px;\">Keldeo</li><li style=\"margin-right: 20px;\">Meloetta</li><li style=\"margin-right: 20px;\">Genesect</li></ul>

            <br/><br/>
            <h2>Sixième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\"><li style=\"margin-right: 20px;\">Marisson</li><li style=\"margin-right: 20px;\">Boguérisse</li><li style=\"margin-right: 20px;\">Blindépique</li><li style=\"margin-right: 20px;\">Feunnec</li><li style=\"margin-right: 20px;\">Roussil</li><li style=\"margin-right: 20px;\">Goupelin</li><li style=\"margin-right: 20px;\">Grenousse</li><li style=\"margin-right: 20px;\">Croâporal</li><li style=\"margin-right: 20px;\">Amphinobi</li><li style=\"margin-right: 20px;\">Sapereau</li><li style=\"margin-right: 20px;\">Excavarenne</li><li style=\"margin-right: 20px;\">Passerouge</li><li style=\"margin-right: 20px;\">Braisillon</li><li style=\"margin-right: 20px;\">Flambusard</li><li style=\"margin-right: 20px;\">Lépidonille</li><li style=\"margin-right: 20px;\">Pérégrain</li><li style=\"margin-right: 20px;\">Prismillon</li><li style=\"margin-right: 20px;\">Hélionceau</li><li style=\"margin-right: 20px;\">Némélios</li><li style=\"margin-right: 20px;\">Flabébé</li><li style=\"margin-right: 20px;\">Floette</li><li style=\"margin-right: 20px;\">Florges</li><li style=\"margin-right: 20px;\">Cabriolaine</li><li style=\"margin-right: 20px;\">Chevroum</li><li style=\"margin-right: 20px;\">Pandespiègle</li><li style=\"margin-right: 20px;\">Pandarbare</li><li style=\"margin-right: 20px;\">Couafarel</li><li style=\"margin-right: 20px;\">Psystigri</li><li style=\"margin-right: 20px;\">Mistigrix</li><li style=\"margin-right: 20px;\">Monorpale</li><li style=\"margin-right: 20px;\">Dimoclès</li><li style=\"margin-right: 20px;\">Exagide</li><li style=\"margin-right: 20px;\">Fluvetin</li><li style=\"margin-right: 20px;\">Cocotine</li><li style=\"margin-right: 20px;\">Sucroquin</li><li style=\"margin-right: 20px;\">Cupcanaille</li><li style=\"margin-right: 20px;\">Sepiatop</li><li style=\"margin-right: 20px;\">Sepiatroce</li><li style=\"margin-right: 20px;\">Opermine</li><li style=\"margin-right: 20px;\">Golgopathe</li><li style=\"margin-right: 20px;\">Venalgue</li><li style=\"margin-right: 20px;\">Kravarech</li><li style=\"margin-right: 20px;\">Flingouste</li><li style=\"margin-right: 20px;\">Gamblast</li><li style=\"margin-right: 20px;\">Galvaran</li><li style=\"margin-right: 20px;\">Iguolta</li><li style=\"margin-right: 20px;\">Ptyranidur</li><li style=\"margin-right: 20px;\">Rexillius</li><li style=\"margin-right: 20px;\">Amagara</li><li style=\"margin-right: 20px;\">Dragmara</li><li style=\"margin-right: 20px;\">Nymphali</li><li style=\"margin-right: 20px;\">Brutalibré</li><li style=\"margin-right: 20px;\">Dedenne</li><li style=\"margin-right: 20px;\">Strassie</li><li style=\"margin-right: 20px;\">Mucuscule</li><li style=\"margin-right: 20px;\">Colimucus</li><li style=\"margin-right: 20px;\">Muplodocus</li><li style=\"margin-right: 20px;\">Trousselin</li><li style=\"margin-right: 20px;\">Brocélôme</li><li style=\"margin-right: 20px;\">Desséliande</li><li style=\"margin-right: 20px;\">Pitrouille</li><li style=\"margin-right: 20px;\">Banshitrouye</li><li style=\"margin-right: 20px;\">Grelaçon</li><li style=\"margin-right: 20px;\">Séracrawl</li><li style=\"margin-right: 20px;\">Sonistrelle</li><li style=\"margin-right: 20px;\">Bruyverne</li><li style=\"margin-right: 20px;\">Xerneas</li><li style=\"margin-right: 20px;\">Yveltal</li><li style=\"margin-right: 20px;\">Zygarde</li><li style=\"margin-right: 20px;\">Diancie</li><li style=\"margin-right: 20px;\">Hoopa</li><li style=\"margin-right: 20px;\">Volcanion</li></ul>

            <br/><br/>
            <h2>Septième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\"><li style=\"margin-right: 20px;\">Brindibou</li><li style=\"margin-right: 20px;\">Efflèche</li><li style=\"margin-right: 20px;\">Archéduc</li><li style=\"margin-right: 20px;\">Flamiaou</li><li style=\"margin-right: 20px;\">Matoufeu</li><li style=\"margin-right: 20px;\">Félinferno</li><li style=\"margin-right: 20px;\">Otaquin</li><li style=\"margin-right: 20px;\">Otarlette</li><li style=\"margin-right: 20px;\">Oratoria</li><li style=\"margin-right: 20px;\">Picassaut</li><li style=\"margin-right: 20px;\">Piclairon</li><li style=\"margin-right: 20px;\">Bazoucan</li><li style=\"margin-right: 20px;\">Manglouton</li><li style=\"margin-right: 20px;\">Argouste</li><li style=\"margin-right: 20px;\">Larvibule</li><li style=\"margin-right: 20px;\">Chrysapile</li><li style=\"margin-right: 20px;\">Lucanon</li><li style=\"margin-right: 20px;\">Crabagarre</li><li style=\"margin-right: 20px;\">Crabominable</li><li style=\"margin-right: 20px;\">Plumeline</li><li style=\"margin-right: 20px;\">Bombydou</li><li style=\"margin-right: 20px;\">Rubombelle</li><li style=\"margin-right: 20px;\">Rocabot</li><li style=\"margin-right: 20px;\">Lougaroc</li><li style=\"margin-right: 20px;\">Froussardine</li><li style=\"margin-right: 20px;\">Vorastérie</li><li style=\"margin-right: 20px;\">Prédastérie</li><li style=\"margin-right: 20px;\">Tiboudet</li><li style=\"margin-right: 20px;\">Bourrinos</li><li style=\"margin-right: 20px;\">Araqua</li><li style=\"margin-right: 20px;\">Tarenbulle</li><li style=\"margin-right: 20px;\">Mimantis</li><li style=\"margin-right: 20px;\">Floramantis</li><li style=\"margin-right: 20px;\">Spododo</li><li style=\"margin-right: 20px;\">Lampignon</li><li style=\"margin-right: 20px;\">Tritox</li><li style=\"margin-right: 20px;\">Malamandre</li><li style=\"margin-right: 20px;\">Nounourson</li><li style=\"margin-right: 20px;\">Chelours</li><li style=\"margin-right: 20px;\">Croquine</li><li style=\"margin-right: 20px;\">Candine</li><li style=\"margin-right: 20px;\">Sucreine</li><li style=\"margin-right: 20px;\">Guérilande</li><li style=\"margin-right: 20px;\">Gouroutan</li><li style=\"margin-right: 20px;\">Quartermac</li><li style=\"margin-right: 20px;\">Sovkipou</li><li style=\"margin-right: 20px;\">Sarmuraï</li><li style=\"margin-right: 20px;\">Bacabouh</li><li style=\"margin-right: 20px;\">Trépassable</li><li style=\"margin-right: 20px;\">Concombaffe</li><li style=\"margin-right: 20px;\">Type:0</li><li style=\"margin-right: 20px;\">Silvallié</li><li style=\"margin-right: 20px;\">Météno</li><li style=\"margin-right: 20px;\">Dodoala</li><li style=\"margin-right: 20px;\">Boumata</li><li style=\"margin-right: 20px;\">Togedemaru</li><li style=\"margin-right: 20px;\">Mimiqui</li><li style=\"margin-right: 20px;\">Denticrisse</li><li style=\"margin-right: 20px;\">Draïeul</li><li style=\"margin-right: 20px;\">Sinistrail</li><li style=\"margin-right: 20px;\">Bébécaille</li><li style=\"margin-right: 20px;\">Écaïd</li><li style=\"margin-right: 20px;\">Ékaïser</li><li style=\"margin-right: 20px;\">Tokorico</li><li style=\"margin-right: 20px;\">Tokopiyon</li><li style=\"margin-right: 20px;\">Tokotoro</li><li style=\"margin-right: 20px;\">Tokopisco</li><li style=\"margin-right: 20px;\">Cosmog</li><li style=\"margin-right: 20px;\">Cosmovum</li><li style=\"margin-right: 20px;\">Solgaleo</li><li style=\"margin-right: 20px;\">Lunala</li><li style=\"margin-right: 20px;\">Zéroïd</li><li style=\"margin-right: 20px;\">Mouscoto</li><li style=\"margin-right: 20px;\">Cancrelove</li><li style=\"margin-right: 20px;\">Câblifère</li><li style=\"margin-right: 20px;\">Bamboiselle</li><li style=\"margin-right: 20px;\">Katagami</li><li style=\"margin-right: 20px;\">Engloutyran</li><li style=\"margin-right: 20px;\">Necrozma</li><li style=\"margin-right: 20px;\">Magearna</li><li style=\"margin-right: 20px;\">Marshadow</li><li style=\"margin-right: 20px;\">Vémini</li><li style=\"margin-right: 20px;\">Mandrillon</li><li style=\"margin-right: 20px;\">Ama-Ama</li><li style=\"margin-right: 20px;\">Pierroteknik</li><li style=\"margin-right: 20px;\">Zeraora</li><li style=\"margin-right: 20px;\">Meltan</li><li style=\"margin-right: 20px;\">Melmetal</li></ul>

            <br/><br/>
            <h2>Huitième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\"><li style=\"margin-right: 20px;\">Ouistempo</li><li style=\"margin-right: 20px;\">Badabouin</li><li style=\"margin-right: 20px;\">Gorythmic</li><li style=\"margin-right: 20px;\">Flambino</li><li style=\"margin-right: 20px;\">Lapyro</li><li style=\"margin-right: 20px;\">Pyrobut</li><li style=\"margin-right: 20px;\">Larméléon</li><li style=\"margin-right: 20px;\">Arrozard</li><li style=\"margin-right: 20px;\">Lézargus</li><li style=\"margin-right: 20px;\">Rongourmand</li><li style=\"margin-right: 20px;\">Rongrigou</li><li style=\"margin-right: 20px;\">Minisange</li><li style=\"margin-right: 20px;\">Bleuseille</li><li style=\"margin-right: 20px;\">Corvaillus</li><li style=\"margin-right: 20px;\">Larvadar</li><li style=\"margin-right: 20px;\">Coléodôme</li><li style=\"margin-right: 20px;\">Astronelle</li><li style=\"margin-right: 20px;\">Goupilou</li><li style=\"margin-right: 20px;\">Roublenard</li><li style=\"margin-right: 20px;\">Tournicoton</li><li style=\"margin-right: 20px;\">Blancoton</li><li style=\"margin-right: 20px;\">Moumouton</li><li style=\"margin-right: 20px;\">Moumouflon</li><li style=\"margin-right: 20px;\">Khélocrok</li><li style=\"margin-right: 20px;\">Torgamord</li><li style=\"margin-right: 20px;\">Voltoutou</li><li style=\"margin-right: 20px;\">Fulgudog</li><li style=\"margin-right: 20px;\">Charbi</li><li style=\"margin-right: 20px;\">Wagomine</li><li style=\"margin-right: 20px;\">Monthracite</li><li style=\"margin-right: 20px;\">Verpom</li><li style=\"margin-right: 20px;\">Pomdrapi</li><li style=\"margin-right: 20px;\">Dratatin</li><li style=\"margin-right: 20px;\">Dunaja</li><li style=\"margin-right: 20px;\">Dunaconda</li><li style=\"margin-right: 20px;\">Nigosier</li><li style=\"margin-right: 20px;\">Embrochet</li><li style=\"margin-right: 20px;\">Hastacuda</li><li style=\"margin-right: 20px;\">Toxizap</li><li style=\"margin-right: 20px;\">Salarsen</li><li style=\"margin-right: 20px;\">Grillepattes</li><li style=\"margin-right: 20px;\">Scolocendre</li><li style=\"margin-right: 20px;\">Poulpaf</li><li style=\"margin-right: 20px;\">Krakos</li><li style=\"margin-right: 20px;\">Théffroi</li><li style=\"margin-right: 20px;\">Polthégeist</li><li style=\"margin-right: 20px;\">Bibichut</li><li style=\"margin-right: 20px;\">Chapotus</li><li style=\"margin-right: 20px;\">Sorcilence</li><li style=\"margin-right: 20px;\">Grimalin</li><li style=\"margin-right: 20px;\">Fourbelin</li><li style=\"margin-right: 20px;\">Angoliath</li><li style=\"margin-right: 20px;\">Ixon</li><li style=\"margin-right: 20px;\">Berserkatt</li><li style=\"margin-right: 20px;\">Corayôme</li><li style=\"margin-right: 20px;\">Palarticho</li><li style=\"margin-right: 20px;\">M. Glaquette</li><li style=\"margin-right: 20px;\">Tutétékri</li><li style=\"margin-right: 20px;\">Crèmy</li><li style=\"margin-right: 20px;\">Charmilly</li><li style=\"margin-right: 20px;\">Hexadron</li><li style=\"margin-right: 20px;\">Wattapik</li><li style=\"margin-right: 20px;\">Frissonille</li><li style=\"margin-right: 20px;\">Beldeneige</li><li style=\"margin-right: 20px;\">Dolman</li><li style=\"margin-right: 20px;\">Bekaglaçon</li><li style=\"margin-right: 20px;\">Wimessir</li><li style=\"margin-right: 20px;\">Morpeko</li><li style=\"margin-right: 20px;\">Charibari</li><li style=\"margin-right: 20px;\">Pachyradjah</li><li style=\"margin-right: 20px;\">Galvagon</li><li style=\"margin-right: 20px;\">Galvagla</li><li style=\"margin-right: 20px;\">Hydragon</li><li style=\"margin-right: 20px;\">Hydragla</li><li style=\"margin-right: 20px;\">Duralugon</li><li style=\"margin-right: 20px;\">Fantyrm</li><li style=\"margin-right: 20px;\">Dispareptil</li><li style=\"margin-right: 20px;\">Lanssorien</li><li style=\"margin-right: 20px;\">Zacian</li><li style=\"margin-right: 20px;\">Zamazenta</li><li style=\"margin-right: 20px;\">Éthernatos</li><li style=\"margin-right: 20px;\">Wushours</li><li style=\"margin-right: 20px;\">Shifours</li><li style=\"margin-right: 20px;\">Zarude</li><li style=\"margin-right: 20px;\">Regieleki</li><li style=\"margin-right: 20px;\">Regidrago</li><li style=\"margin-right: 20px;\">Blizzeval</li><li style=\"margin-right: 20px;\">Spectreval</li><li style=\"margin-right: 20px;\">Sylveroy</li><li style=\"margin-right: 20px;\">Cerbyllin</li><li style=\"margin-right: 20px;\">Hachécateur</li><li style=\"margin-right: 20px;\">Ursaking</li><li style=\"margin-right: 20px;\">Paragruel</li><li style=\"margin-right: 20px;\">Farfurex</li><li style=\"margin-right: 20px;\">Qwilpik</li><li style=\"margin-right: 20px;\">Amovénus</li></ul>

            <br/><br/>
            <h2>Neuvième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul style=\"display: flex; flex-wrap: wrap; padding: 0; margin: 0;\"><li style=\"margin-right: 20px;\">Poussacha</li><li style=\"margin-right: 20px;\">Matourgeon</li><li style=\"margin-right: 20px;\">Miascarade</li><li style=\"margin-right: 20px;\">Chochodile</li><li style=\"margin-right: 20px;\">Crocogril</li><li style=\"margin-right: 20px;\">Flâmigator</li><li style=\"margin-right: 20px;\">Coiffeton</li><li style=\"margin-right: 20px;\">Canarbello</li><li style=\"margin-right: 20px;\">Palmaval</li><li style=\"margin-right: 20px;\">Gourmelet</li><li style=\"margin-right: 20px;\">Fragroin</li><li style=\"margin-right: 20px;\">Tissenboule</li><li style=\"margin-right: 20px;\">Filentrappe</li><li style=\"margin-right: 20px;\">Lilliterelle</li><li style=\"margin-right: 20px;\">Gambex</li><li style=\"margin-right: 20px;\">Pohm</li><li style=\"margin-right: 20px;\">Pohmotte</li><li style=\"margin-right: 20px;\">Pohmarmotte</li><li style=\"margin-right: 20px;\">Compagnol</li><li style=\"margin-right: 20px;\">Famignol</li><li style=\"margin-right: 20px;\">Pâtachiot</li><li style=\"margin-right: 20px;\">Briochien</li><li style=\"margin-right: 20px;\">Olivini</li><li style=\"margin-right: 20px;\">Olivado</li><li style=\"margin-right: 20px;\">Arboliva</li><li style=\"margin-right: 20px;\">Tapatoès</li><li style=\"margin-right: 20px;\">Selutin</li><li style=\"margin-right: 20px;\">Amassel</li><li style=\"margin-right: 20px;\">Gigansel</li><li style=\"margin-right: 20px;\">Charbambin</li><li style=\"margin-right: 20px;\">Carmadura</li><li style=\"margin-right: 20px;\">Malvalame</li><li style=\"margin-right: 20px;\">Têtampoule</li><li style=\"margin-right: 20px;\">Ampibidou</li><li style=\"margin-right: 20px;\">Zapétrel</li><li style=\"margin-right: 20px;\">Fulgulairo</li><li style=\"margin-right: 20px;\">Grondogue</li><li style=\"margin-right: 20px;\">Dogrino</li><li style=\"margin-right: 20px;\">Gribouraigne</li><li style=\"margin-right: 20px;\">Tag-Tag</li><li style=\"margin-right: 20px;\">Virovent</li><li style=\"margin-right: 20px;\">Virevorreur</li><li style=\"margin-right: 20px;\">Terracool</li><li style=\"margin-right: 20px;\">Terracruel</li><li style=\"margin-right: 20px;\">Craparoi</li><li style=\"margin-right: 20px;\">Pimito</li><li style=\"margin-right: 20px;\">Scovilain</li><li style=\"margin-right: 20px;\">Léboulérou</li><li style=\"margin-right: 20px;\">Bérasca</li><li style=\"margin-right: 20px;\">Flotillon</li><li style=\"margin-right: 20px;\">Cléopsytra</li><li style=\"margin-right: 20px;\">Forgerette</li><li style=\"margin-right: 20px;\">Forgella</li><li style=\"margin-right: 20px;\">Forgelina</li><li style=\"margin-right: 20px;\">Taupikeau</li><li style=\"margin-right: 20px;\">Triopikeau</li><li style=\"margin-right: 20px;\">Lestombaile</li><li style=\"margin-right: 20px;\">Dofin</li><li style=\"margin-right: 20px;\">Superdofin</li><li style=\"margin-right: 20px;\">Vrombi</li><li style=\"margin-right: 20px;\">Vrombotor</li><li style=\"margin-right: 20px;\">Motorizard</li><li style=\"margin-right: 20px;\">Ferdeter</li><li style=\"margin-right: 20px;\">Germéclat</li><li style=\"margin-right: 20px;\">Floréclat</li><li style=\"margin-right: 20px;\">Toutombe</li><li style=\"margin-right: 20px;\">Tomberro</li><li style=\"margin-right: 20px;\">Flamenroule</li><li style=\"margin-right: 20px;\">Piétacé</li><li style=\"margin-right: 20px;\">Balbalèze</li><li style=\"margin-right: 20px;\">Délestin</li><li style=\"margin-right: 20px;\">Oyacata</li><li style=\"margin-right: 20px;\">Nigirigon</li><li style=\"margin-right: 20px;\">Courrousinge</li><li style=\"margin-right: 20px;\">Terraiste</li><li style=\"margin-right: 20px;\">Farigiraf</li><li style=\"margin-right: 20px;\">Deusolourdo</li><li style=\"margin-right: 20px;\">Scalpereur</li><li style=\"margin-right: 20px;\">Fort-Ivoire</li><li style=\"margin-right: 20px;\">Hurle-Queue</li><li style=\"margin-right: 20px;\">Fongus-Furie</li><li style=\"margin-right: 20px;\">Flotte-Mèche</li><li style=\"margin-right: 20px;\">Rampe-Ailes</li><li style=\"margin-right: 20px;\">Pelage-Sablé</li><li style=\"margin-right: 20px;\">Roue-de-Fer</li><li style=\"margin-right: 20px;\">Hotte-de-Fer</li><li style=\"margin-right: 20px;\">Paume-de-Fer</li><li style=\"margin-right: 20px;\">Têtes-de-Fer</li><li style=\"margin-right: 20px;\">Mite-de-Fer</li><li style=\"margin-right: 20px;\">Épine-de-Fer</li><li style=\"margin-right: 20px;\">Frigodo</li><li style=\"margin-right: 20px;\">Cryodo</li><li style=\"margin-right: 20px;\">Glaivodo</li><li style=\"margin-right: 20px;\">Mordudor</li><li style=\"margin-right: 20px;\">Gromago</li><li style=\"margin-right: 20px;\">Chongjian</li><li style=\"margin-right: 20px;\">Baojian</li><li style=\"margin-right: 20px;\">Dinglu</li><li style=\"margin-right: 20px;\">Yuyu</li><li style=\"margin-right: 20px;\">Rugit-Lune</li><li style=\"margin-right: 20px;\">Garde-de-Fer</li><li style=\"margin-right: 20px;\">Koraidon</li><li style=\"margin-right: 20px;\">Miraidon</li><li style=\"margin-right: 20px;\">Serpente-Eau</li><li style=\"margin-right: 20px;\">Vert-de-Fer</li><li style=\"margin-right: 20px;\">Pomdramour</li><li style=\"margin-right: 20px;\">Poltchageist</li><li style=\"margin-right: 20px;\">Théffroyable</li><li style=\"margin-right: 20px;\">Félicanis</li><li style=\"margin-right: 20px;\">Fortusimia</li><li style=\"margin-right: 20px;\">Favianos</li><li style=\"margin-right: 20px;\">Ogerpon</li><li style=\"margin-right: 20px;\">Pondralugon</li><li style=\"margin-right: 20px;\">Pomdorochi</li><li style=\"margin-right: 20px;\">Feu-Perçant</li><li style=\"margin-right: 20px;\">Ire-Foudre</li><li style=\"margin-right: 20px;\">Roc-de-Fer</li><li style=\"margin-right: 20px;\">Chef-de-Fer</li><li style=\"margin-right: 20px;\">Terapagos</li><li style=\"margin-right: 20px;\">Pêchaminus</li></ul>
            
            <p style=\"font-size: 10px; color:rgb(114, 114, 114); text-align: center;\">
                Oui! Ceci était necessaire!   
            </p>
            ";


            $db->exec("INSERT INTO pages (title, slug, content, created_by) VALUES 
                ('Liste des Pokemons', 'pokemon', '" . $contenue . "', 2)");
            echo "Default page inserted.\n";
        }

        $stmt = $db->query("SELECT COUNT(*) FROM structure");
        $count = $stmt->fetchColumn();
        
        $head = 
        "<style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: Arial, sans-serif;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .main {
                background: white;
                margin: auto;
                width: 80%;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                text-align: left;
            }
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background-color:rgb(98, 160, 223);
                padding: 15px 20px;
                border-bottom: 2px solid #ddd;
            }
            .title {
                flex-grow: 1;
                text-align: center;
                font-size: 24px;
                font-weight: bold;
            }-
            .buttons {
                display: flex;
                gap: 10px;
            }
            .btn {
                padding: 8px 12px;
                border: none;
                cursor: pointer;
                border-radius: 5px;
                font-size: 14px;
            }
            .btn.edit {
                background-color: #007bff;
                color: white;
            }
            .btn.login {
                background-color: #28a745;
                color: white;
            }
            .btn:hover {
                opacity: 0.8;
            }
            .footer {
                background-color: #b0c4de;
                text-align: center;
                padding: 15px;
                margin-top: auto;
            }
            .footer .creators {
                margin: 10px 0;
            }
            .footer .btn {
                background-color: #333;
                color: white;
                padding: 8px 12px;
                text-decoration: none;
                display: inline-block;
                border-radius: 5px;
                margin-top: 10px;
            }
        </style>";

        $header = 
        "<header class=\"header\">
            <div class=\"title\">Wiki Universel</div>
            <div class=\"buttons\">
                <a href=\"index.php?page=update-pages\"><button class=\"btn edit\">Modifier</button></a>
                <a href=\"index.php?page=home\"><button class=\"btn login\">Se Connecter</button></a>
            </div>
        </header>";

        $footer =
        "<footer class=\"footer\">
            <p>&copy; 2025 Wiki Universel. Tous droits réservés.</p>
            <p class=\"creators\">Créateurs : Amin, Alex, Quentin, Thomas</p>
            <a href=\"index.php\" class=\"btn\">Retour</a>
        </footer>";

        if ($count == 0) {
            $db->exec("INSERT INTO structure (head, header, footer) VALUES 
                ('" . $head . "', 
                '" . $header . "', 
                '" . $footer . "')");
            echo "Default structure inserted.\n";
        }
    }

    public static function migrate() {
        self::setupMigrationTable();
        $db = Database::getInstance();
        $executedMigrations = self::getExecutedMigrations();

        try {
            foreach (self::$migrations as $name => $query) {
                if (!in_array($name, $executedMigrations)) {
                    $db->exec($query);
                    $db->exec("INSERT INTO " . self::$migrationTable . " (migration) VALUES ('$name')");
                    echo "Migration $name exécutée avec succès.\n";
                }
            }
        } catch (PDOException $e) {
            die("Erreur lors des migrations : " . $e->getMessage());
        }

        self::insertDefaultData();
    }

    public static function reset() {
        $db = Database::getInstance();
        try {
            $db->exec("DROP TABLE IF EXISTS pages, users, structure, " . self::$migrationTable . ";");
            echo "Toutes les migrations ont été annulées.\n";
        } catch (PDOException $e) {
            die("Erreur lors de la suppression des tables : " . $e->getMessage());
        }
    }

    public static function next() {
        self::setupMigrationTable();
        $db = Database::getInstance();
        $executedMigrations = self::getExecutedMigrations();
        
        foreach (self::$migrations as $name => $query) {
            if (!in_array($name, $executedMigrations)) {
                $db->exec($query);
                $db->exec("INSERT INTO " . self::$migrationTable . " (migration) VALUES ('$name')");
                echo "Migration $name exécutée avec succès.\n";
                return;
            }
        }
        echo "Toutes les migrations ont déjà été exécutées.\n";
    }

    public static function previous() {
        self::setupMigrationTable();
        $db = Database::getInstance();
        $executedMigrations = self::getExecutedMigrations();
        
        if (!empty($executedMigrations)) {
            $lastMigration = end($executedMigrations);
            $db->exec("DELETE FROM " . self::$migrationTable . " WHERE migration = '$lastMigration'");
            echo "Migration $lastMigration annulée avec succès.\n";
        } else {
            echo "Aucune migration à annuler.\n";
        }
    }
}
