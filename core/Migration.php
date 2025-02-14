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
            <ul>
                <li>Bulbizarre</li><li>Herbizarre</li><li>Florizarre</li><li>Salamèche</li><li>Reptincel</li><li>Dracaufeu</li><li>Carapuce</li><li>Carabaffe</li><li>Tortank</li><li>Chenipan</li><li>Chrysacier</li><li>Papilusion</li><li>Aspicot</li><li>Coconfort</li><li>Dardargnan</li><li>Roucool</li><li>Roucoups</li><li>Roucarnage</li><li>Rattata</li><li>Rattatac</li><li>Pikachu</li><li>Raichu</li>
            </ul>
            
            <h2>Deuxième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul>
                <li>Germignon</li><li>Macronium</li><li>Méganium</li><li>Héricendre</li><li>Feurisson</li><li>Typhlosion</li><li>Kaiminus</li><li>Crocrodil</li><li>Aligatueur</li><li>Fouinette</li><li>Fouinar</li><li>Hoothoot</li><li>Noarfang</li><li>Coxy</li><li>Coxyclaque</li><li>Mimigal</li><li>Migalos</li><li>Nostenfer</li><li>Loupio</li><li>Lanturn</li><li>Pichu</li><li>Mélo</li><li>Toudoudou</li><li>Togepi</li><li>Togetic</li><li>Natu</li><li>Xatu</li><li>Wattouat</li><li>Lainergie</li><li>Pharamp</li><li>Joliflor</li><li>Marill</li><li>Azumarill</li><li>Simularbre</li><li>Tarpaud</li><li>Granivol</li><li>Floravol</li><li>Cotovol</li><li>Capumain</li><li>Tournegrin</li><li>Héliatronc</li><li>Yanma</li><li>Axolot</li><li>Maraiste</li><li>Mentali</li><li>Noctali</li><li>Cornèbre</li><li>Roigad</li><li>Feuforêve</li><li>Zarbi</li><li>Qulbutoké</li><li>Girafarig</li><li>Pomdepik</li><li>Foretress</li><li>Insolourdo</li><li>Scorplane</li><li>Steelix</li><li>Snubbull</li><li>Granbull</li><li>Qwilfish</li><li>Cizayox</li><li>Caratroc</li><li>Scarhin</li><li>Farfuret</li><li>Teddiursa</li><li>Ursaring</li><li>Limagma</li><li>Volcaropod</li><li>Marcacrin</li><li>Cochignon</li><li>Corayon</li><li>Rémoraid</li><li>Octillery</li><li>Cadoizo</li><li>Démanta</li><li>Airmure</li><li>Malosse</li><li>Démoloss</li><li>Hyporoi</li><li>Phanpy</li><li>Donphan</li><li>Porygon2</li><li>Cerfrousse</li><li>Queulorior</li><li>Debugant</li><li>Kapoera</li><li>Lippouti</li><li>Élekid</li><li>Magby</li><li>Écrémeuh</li><li>Leuphorie</li><li>Raikou</li><li>Entei</li><li>Suicune</li><li>Embrylex</li><li>Ymphect</li><li>Tyranocif</li><li>Lugia</li><li>Ho-Oh</li><li>Celebi</li>
            </ul>

                
            <h2>Troisième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul>
                <li>Arcko</li><li>Massko</li><li>Jungko</li><li>Poussifeu</li><li>Galifeu</li><li>Braségali</li><li>Gobou</li><li>Flobio</li><li>Laggron</li><li>Medhyèna</li><li>Grahyèna</li><li>Zigzaton</li><li>Linéon</li><li>Chenipotte</li><li>Armulys</li><li>Charmillon</li><li>Blindalys</li><li>Papinox</li><li>Nénupiot</li><li>Lombre</li><li>Ludicolo</li><li>Grainipiot</li><li>Pifeuil</li><li>Tengalice</li><li>Nirondelle</li><li>Hélédelle</li><li>Goélise</li><li>Bekipan</li><li>Tarsal</li><li>Kirlia</li><li>Gardevoir</li><li>Arakdo</li><li>Maskadra</li><li>Balignon</li><li>Chapignon</li><li>Parécool</li><li>Vigoroth</li><li>Monaflèmit</li><li>Ningale</li><li>Ninjask</li><li>Munja</li><li>Chuchmur</li><li>Ramboum</li><li>Brouhabam</li><li>Makuhita</li><li>Hariyama</li><li>Azurill</li><li>Tarinor</li><li>Skitty</li><li>Delcatty</li><li>Ténéfix</li><li>Mysdibule</li><li>Galekid</li><li>Galegon</li><li>Galeking</li><li>Méditikka</li><li>Charmina</li><li>Dynavolt</li><li>Élecsprint</li><li>Posipi</li><li>Négapi</li><li>Muciole</li><li>Lumivole</li><li>Rosélia</li><li>Gloupti</li><li>Avaltout</li><li>Carvanha</li><li>Sharpedo</li><li>Wailmer</li><li>Wailord</li><li>Chamallot</li><li>Camérupt</li><li>Chartor</li><li>Spoink</li><li>Groret</li><li>Spinda</li><li>Kraknoix</li><li>Vibraninf</li><li>Libégon</li><li>Cacnea</li><li>Cacturne</li><li>Tylton</li><li>Altaria</li><li>Mangriff</li><li>Séviper</li><li>Séléroc</li><li>Solaroc</li><li>Barloche</li><li>Barbicha</li><li>Écrapince</li><li>Colhomard</li><li>Balbuto</li><li>Kaorine</li><li>Lilia</li><li>Vacilys</li><li>Anorith</li><li>Armaldo</li><li>Barpau</li><li>Milobellus</li><li>Morphéo</li><li>Kecleon</li><li>Polichombr</li><li>Branette</li><li>Skelénox</li><li>Téraclope</li><li>Tropius</li><li>Éoko</li><li>Absol</li><li>Okéoké</li><li>Stalgamin</li><li>Oniglalin</li><li>Obalie</li><li>Phogleur</li><li>Kaimorse</li><li>Coquiperl</li><li>Serpang</li><li>Rosabyss</li><li>Relicanth</li><li>Lovdisc</li><li>Draby</li><li>Drackhaus</li><li>Drattak</li><li>Terhal</li><li>Métang</li><li>Métalosse</li><li>Regirock</li><li>Regice</li><li>Registeel</li><li>Latias</li><li>Latios</li><li>Kyogre</li><li>Groudon</li><li>Rayquaza</li><li>Jirachi</li><li>Deoxys</li>
            </ul>

            <h2>Quatrième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul>
                <li>Tortipouss</li><li>Boskara</li><li>Torterra</li><li>Ouisticram</li><li>Chimpenfeu</li><li>Simiabraz</li><li>Tiplouf</li><li>Prinplouf</li><li>Pingoléon</li><li>Étourmi</li><li>Étourvol</li><li>Étouraptor</li><li>Keunotor</li><li>Castorno</li><li>Crikzik</li><li>Mélokrik</li><li>Lixy</li><li>Luxio</li><li>Luxray</li><li>Rozbouton</li><li>Roserade</li><li>Kranidos</li><li>Charkos</li><li>Dinoclier</li><li>Bastiodon</li><li>Cheniti</li><li>Cheniselle</li><li>Papilord</li><li>Apitrini</li><li>Apireine</li><li>Pachirisu</li><li>Mustébouée</li><li>Mustéflott</li><li>Ceribou</li><li>Ceriflor</li><li>Sancoki</li><li>Tritosor</li><li>Capidextre</li><li>Baudrive</li><li>Grodrive</li><li>Laporeille</li><li>Lockpin</li><li>Magirêve</li><li>Corboss</li><li>Chaglam</li><li>Chaffreux</li><li>Korillon</li><li>Moufouette</li><li>Moufflair</li><li>Archéomire</li><li>Archéodong</li><li>Manzaï</li><li>Mime Jr.</li><li>Ptiravi</li><li>Pijako</li><li>Spiritomb</li><li>Griknot</li><li>Carmache</li><li>Carchacrokn</li><li>Goinfrex</li><li>Riolu</li><li>Lucario</li><li>Hippopotas</li><li>Hippodocus</li><li>Rapion</li><li>Drascore</li><li>Cradopaud</li><li>Coatox</li><li>Vortente</li><li>Écayon</li><li>Luminéon</li><li>Babimanta</li><li>Blizzi</li><li>Blizzaro</li><li>Dimoret</li><li>Magnézone</li><li>Coudlangue</li><li>Rhinastoc</li><li>Bouldeneu</li><li>Élekable</li><li>Maganon</li><li>Togekiss</li><li>Yanmega</li><li>Phyllali</li><li>Givrali</li><li>Scorvol</li><li>Mammochon</li><li>Porygon-Z</li><li>Gallamenote</li><li>Tarinorme</li><li>Noctunoir</li><li>Momartik</li><li>Motisma</li><li>Créhelf</li><li>Créfollet</li><li>Créfadet</li><li>Dialga</li><li>Palkia</li><li>Heatran</li><li>Regigigas</li><li>Giratina</li><li>Cresselia</li><li>Phione</li><li>Manaphy</li><li>Darkrai</li><li>Shaymin</li><li>Arceus</li>
            </ul>

            <h2>Cinquième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul><li>Victini</li><li>Vipélierre</li><li>Lianaja</li><li>Majaspic</li><li>Gruikui</li><li>Grotichon</li><li>Roitiflam</li><li>Moustillon</li><li>Mateloutre</li><li>Clamiral</li><li>Ratentif</li><li>Miradar</li><li>Ponchiot</li><li>Ponchien</li><li>Mastouffe</li><li>Chacripan</li><li>Léopardus</li><li>Feuillajou</li><li>Feuiloutan</li><li>Flamajou</li><li>Flamoutan</li><li>Flotajou</li><li>Flotoutan</li><li>Munna</li><li>Mushana</li><li>Poichigeon</li><li>Colombeau</li><li>Déflaisan</li><li>Zébibron</li><li>Zéblitz</li><li>Nodulithe</li><li>Géolithe</li><li>Gigalithe</li><li>Chovsourir</li><li>Rhinolove</li><li>Rototaupe</li><li>Minotaupe</li><li>Nanméouïen</li><li>Charpenti</li><li>Ouvrifier</li><li>Bétochef</li><li>Tritonde</li><li>Batracné</li><li>Crapustule</li><li>Judokrak</li><li>Karaclée</li><li>Larveyette</li><li>Couverdure</li><li>Manternel</li><li>Venipatte</li><li>Scobolide</li><li>Brutapode</li><li>Doudouvet</li><li>Farfaduvet</li><li>Chlorobule</li><li>Fragilady</li><li>Bargantua</li><li>Mascaïman</li><li>Escroco</li><li>Crocorible</li><li>Darumarond</li><li>Darumachon</li><li>Maracachi</li><li>Crabicoque</li><li>Crabaraque</li><li>Baggiguane</li><li>Baggaïd</li><li>Cryptéro</li><li>Tutafeh</li><li>Tutankafer</li><li>Carapagos</li><li>Mégapagos</li><li>Arkéapti</li><li>Aéroptéryx</li><li>Miamiasme</li><li>Miasmax</li><li>Zorua</li><li>Zoroark</li><li>Chinchidou</li><li>Pashmilla</li><li>Scrutella</li><li>Mesmérella</li><li>Sidérella</li><li>Nucléos</li><li>Méios</li><li>Symbios</li><li>Couaneton</li><li>Lakmécygne</li><li>Sorbébé</li><li>Sorboul</li><li>Sorbouboul</li><li>Vivaldaim</li><li>Haydaim</li><li>Emolga</li><li>Carabing</li><li>Lançargot</li><li>Trompignon</li><li>Gaulet</li><li>Viskuse</li><li>Moyade</li><li>Mamanbo</li><li>Statitik</li><li>Mygavolt</li><li>Grindur</li><li>Noacier</li><li>Tic</li><li>Clic</li><li>Cliticlic</li><li>Anchwatt</li><li>Lampéroie</li><li>Ohmassacre</li><li>Lewsor</li><li>Neitram</li><li>Funécire</li><li>Mélancolux</li><li>Lugulabre</li><li>Coupenotte</li><li>Incisache</li><li>Tranchodon</li><li>Polarhume</li><li>Polagriffe</li><li>Hexagel</li><li>Escargaume</li><li>Limaspeed</li><li>Limonden</li><li>Kungfouine</li><li>Shaofouine</li><li>Drakkarmin</li><li>Gringolem</li><li>Golemastoc</li><li>Scalpion</li><li>Scalproie</li><li>Frison</li><li>Furaiglon</li><li>Gueriaigle</li><li>Vostourno</li><li>Vaututrice</li><li>Aflamanoir</li><li>Fermite</li><li>Solochi</li><li>Diamat</li><li>Trioxhydre</li><li>Pyronille</li><li>Pyrax</li><li>Cobaltium</li><li>Terrakium</li><li>Viridium</li><li>Boréas</li><li>Fulguris</li><li>Reshiram</li><li>Zekrom</li><li>Démétéros</li><li>Kyurem</li><li>Keldeo</li><li>Meloetta</li><li>Genesect</li></ul>

            <h2>Sixième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul><li>Marisson</li><li>Boguérisse</li><li>Blindépique</li><li>Feunnec</li><li>Roussil</li><li>Goupelin</li><li>Grenousse</li><li>Croâporal</li><li>Amphinobi</li><li>Sapereau</li><li>Excavarenne</li><li>Passerouge</li><li>Braisillon</li><li>Flambusard</li><li>Lépidonille</li><li>Pérégrain</li><li>Prismillon</li><li>Hélionceau</li><li>Némélios</li><li>Flabébé</li><li>Floette</li><li>Florges</li><li>Cabriolaine</li><li>Chevroum</li><li>Pandespiègle</li><li>Pandarbare</li><li>Couafarel</li><li>Psystigri</li><li>Mistigrix</li><li>Monorpale</li><li>Dimoclès</li><li>Exagide</li><li>Fluvetin</li><li>Cocotine</li><li>Sucroquin</li><li>Cupcanaille</li><li>Sepiatop</li><li>Sepiatroce</li><li>Opermine</li><li>Golgopathe</li><li>Venalgue</li><li>Kravarech</li><li>Flingouste</li><li>Gamblast</li><li>Galvaran</li><li>Iguolta</li><li>Ptyranidur</li><li>Rexillius</li><li>Amagara</li><li>Dragmara</li><li>Nymphali</li><li>Brutalibré</li><li>Dedenne</li><li>Strassie</li><li>Mucuscule</li><li>Colimucus</li><li>Muplodocus</li><li>Trousselin</li><li>Brocélôme</li><li>Desséliande</li><li>Pitrouille</li><li>Banshitrouye</li><li>Grelaçon</li><li>Séracrawl</li><li>Sonistrelle</li><li>Bruyverne</li><li>Xerneas</li><li>Yveltal</li><li>Zygarde</li><li>Diancie</li><li>Hoopa</li><li>Volcanion</li></ul>

            <h2>Septième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul><li>Brindibou</li><li>Efflèche</li><li>Archéduc</li><li>Flamiaou</li><li>Matoufeu</li><li>Félinferno</li><li>Otaquin</li><li>Otarlette</li><li>Oratoria</li><li>Picassaut</li><li>Piclairon</li><li>Bazoucan</li><li>Manglouton</li><li>Argouste</li><li>Larvibule</li><li>Chrysapile</li><li>Lucanon</li><li>Crabagarre</li><li>Crabominable</li><li>Plumeline</li><li>Bombydou</li><li>Rubombelle</li><li>Rocabot</li><li>Lougaroc</li><li>Froussardine</li><li>Vorastérie</li><li>Prédastérie</li><li>Tiboudet</li><li>Bourrinos</li><li>Araqua</li><li>Tarenbulle</li><li>Mimantis</li><li>Floramantis</li><li>Spododo</li><li>Lampignon</li><li>Tritox</li><li>Malamandre</li><li>Nounourson</li><li>Chelours</li><li>Croquine</li><li>Candine</li><li>Sucreine</li><li>Guérilande</li><li>Gouroutan</li><li>Quartermac</li><li>Sovkipou</li><li>Sarmuraï</li><li>Bacabouh</li><li>Trépassable</li><li>Concombaffe</li><li>Type:0</li><li>Silvallié</li><li>Météno</li><li>Dodoala</li><li>Boumata</li><li>Togedemaru</li><li>Mimiqui</li><li>Denticrisse</li><li>Draïeul</li><li>Sinistrail</li><li>Bébécaille</li><li>Écaïd</li><li>Ékaïser</li><li>Tokorico</li><li>Tokopiyon</li><li>Tokotoro</li><li>Tokopisco</li><li>Cosmog</li><li>Cosmovum</li><li>Solgaleo</li><li>Lunala</li><li>Zéroïd</li><li>Mouscoto</li><li>Cancrelove</li><li>Câblifère</li><li>Bamboiselle</li><li>Katagami</li><li>Engloutyran</li><li>Necrozma</li><li>Magearna</li><li>Marshadow</li><li>Vémini</li><li>Mandrillon</li><li>Ama-Ama</li><li>Pierroteknik</li><li>Zeraora</li><li>Meltan</li><li>Melmetal</li></ul>

            <h2>Huitième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul><li>Ouistempo</li><li>Badabouin</li><li>Gorythmic</li><li>Flambino</li><li>Lapyro</li><li>Pyrobut</li><li>Larméléon</li><li>Arrozard</li><li>Lézargus</li><li>Rongourmand</li><li>Rongrigou</li><li>Minisange</li><li>Bleuseille</li><li>Corvaillus</li><li>Larvadar</li><li>Coléodôme</li><li>Astronelle</li><li>Goupilou</li><li>Roublenard</li><li>Tournicoton</li><li>Blancoton</li><li>Moumouton</li><li>Moumouflon</li><li>Khélocrok</li><li>Torgamord</li><li>Voltoutou</li><li>Fulgudog</li><li>Charbi</li><li>Wagomine</li><li>Monthracite</li><li>Verpom</li><li>Pomdrapi</li><li>Dratatin</li><li>Dunaja</li><li>Dunaconda</li><li>Nigosier</li><li>Embrochet</li><li>Hastacuda</li><li>Toxizap</li><li>Salarsen</li><li>Grillepattes</li><li>Scolocendre</li><li>Poulpaf</li><li>Krakos</li><li>Théffroi</li><li>Polthégeist</li><li>Bibichut</li><li>Chapotus</li><li>Sorcilence</li><li>Grimalin</li><li>Fourbelin</li><li>Angoliath</li><li>Ixon</li><li>Berserkatt</li><li>Corayôme</li><li>Palarticho</li><li>M. Glaquette</li><li>Tutétékri</li><li>Crèmy</li><li>Charmilly</li><li>Hexadron</li><li>Wattapik</li><li>Frissonille</li><li>Beldeneige</li><li>Dolman</li><li>Bekaglaçon</li><li>Wimessir</li><li>Morpeko</li><li>Charibari</li><li>Pachyradjah</li><li>Galvagon</li><li>Galvagla</li><li>Hydragon</li><li>Hydragla</li><li>Duralugon</li><li>Fantyrm</li><li>Dispareptil</li><li>Lanssorien</li><li>Zacian</li><li>Zamazenta</li><li>Éthernatos</li><li>Wushours</li><li>Shifours</li><li>Zarude</li><li>Regieleki</li><li>Regidrago</li><li>Blizzeval</li><li>Spectreval</li><li>Sylveroy</li><li>Cerbyllin</li><li>Hachécateur</li><li>Ursaking</li><li>Paragruel</li><li>Farfurex</li><li>Qwilpik</li><li>Amovénus</li></ul>

            <h2>Neuvième génération</h2>
            <h3>Liste des Pokémon</h3>
            <br/><br/>
            <ul><li>Poussacha</li><li>Matourgeon</li><li>Miascarade</li><li>Chochodile</li><li>Crocogril</li><li>Flâmigator</li><li>Coiffeton</li><li>Canarbello</li><li>Palmaval</li><li>Gourmelet</li><li>Fragroin</li><li>Tissenboule</li><li>Filentrappe</li><li>Lilliterelle</li><li>Gambex</li><li>Pohm</li><li>Pohmotte</li><li>Pohmarmotte</li><li>Compagnol</li><li>Famignol</li><li>Pâtachiot</li><li>Briochien</li><li>Olivini</li><li>Olivado</li><li>Arboliva</li><li>Tapatoès</li><li>Selutin</li><li>Amassel</li><li>Gigansel</li><li>Charbambin</li><li>Carmadura</li><li>Malvalame</li><li>Têtampoule</li><li>Ampibidou</li><li>Zapétrel</li><li>Fulgulairo</li><li>Grondogue</li><li>Dogrino</li><li>Gribouraigne</li><li>Tag-Tag</li><li>Virovent</li><li>Virevorreur</li><li>Terracool</li><li>Terracruel</li><li>Craparoi</li><li>Pimito</li><li>Scovilain</li><li>Léboulérou</li><li>Bérasca</li><li>Flotillon</li><li>Cléopsytra</li><li>Forgerette</li><li>Forgella</li><li>Forgelina</li><li>Taupikeau</li><li>Triopikeau</li><li>Lestombaile</li><li>Dofin</li><li>Superdofin</li><li>Vrombi</li><li>Vrombotor</li><li>Motorizard</li><li>Ferdeter</li><li>Germéclat</li><li>Floréclat</li><li>Toutombe</li><li>Tomberro</li><li>Flamenroule</li><li>Piétacé</li><li>Balbalèze</li><li>Délestin</li><li>Oyacata</li><li>Nigirigon</li><li>Courrousinge</li><li>Terraiste</li><li>Farigiraf</li><li>Deusolourdo</li><li>Scalpereur</li><li>Fort-Ivoire</li><li>Hurle-Queue</li><li>Fongus-Furie</li><li>Flotte-Mèche</li><li>Rampe-Ailes</li><li>Pelage-Sablé</li><li>Roue-de-Fer</li><li>Hotte-de-Fer</li><li>Paume-de-Fer</li><li>Têtes-de-Fer</li><li>Mite-de-Fer</li><li>Épine-de-Fer</li><li>Frigodo</li><li>Cryodo</li><li>Glaivodo</li><li>Mordudor</li><li>Gromago</li><li>Chongjian</li><li>Baojian</li><li>Dinglu</li><li>Yuyu</li><li>Rugit-Lune</li><li>Garde-de-Fer</li><li>Koraidon</li><li>Miraidon</li><li>Serpente-Eau</li><li>Vert-de-Fer</li><li>Pomdramour</li><li>Poltchageist</li><li>Théffroyable</li><li>Félicanis</li><li>Fortusimia</li><li>Favianos</li><li>Ogerpon</li><li>Pondralugon</li><li>Pomdorochi</li><li>Feu-Perçant</li><li>Ire-Foudre</li><li>Roc-de-Fer</li><li>Chef-de-Fer</li><li>Terapagos</li><li>Pêchaminus</li></ul>
            
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
