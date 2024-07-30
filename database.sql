<<<<<<< HEAD
-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 26 Octobre 2017 à 13:53
-- Version du serveur :  5.7.19-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `simple-mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

CREATE TABLE `item` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `item`
--

INSERT INTO `item` (`id`, `title`) VALUES
(1, 'Stuff'),
(2, 'Doodads');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/* CREATION TABLE  */

CREATE TABLE IF NOT EXISTS User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role VARCHAR(50),
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    phone VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    city VARCHAR(255),
    state VARCHAR(255),
    postal_code VARCHAR(50),
    country VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    parent_id INT NULL,
    FOREIGN KEY (parent_id) REFERENCES Category(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_name VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    address VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES Category(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    supplier_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES Product(id)
        ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES Supplier(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Ordered (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS OrderItem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ordered_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ordered_id) REFERENCES Ordered(id),
    FOREIGN KEY (product_id) REFERENCES Product(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES Product(id),
    FOREIGN KEY (user_id) REFERENCES User(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Payment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Ordered(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Discount (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    description TEXT,
    percentage DECIMAL(5, 2) NOT NULL,
    valid_from DATETIME NOT NULL,
    valid_to DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE PasswordResetTokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO
    User (
        username,
        password,
        email,
        role,
        first_name,
        last_name,
        phone
    )
VALUES (
        'john_doe',
        'password123',
        'john.doe@example.com',
        'user',
        'John',
        'Doe',
        '+1234567890'
    ),
    (
        'jane_smith',
        'pass456',
        'jane.smith@example.com',
        'user',
        'Jane',
        'Smith',
        '+9876543210'
    ),
    (
        'admin',
        'admin123',
        'admin@example.com',
        'admin',
        'Admin',
        'User',
        '+1112223333'
    );

INSERT INTO
    Address (
        user_id,
        address_line1,
        city,
        state,
        postal_code,
        country
    )
VALUES (
        1,
        '123 Main St',
        'New York',
        'NY',
        '10001',
        'USA'
    ),
    (
        2,
        '456 Oak Ave',
        'Los Angeles',
        'CA',
        '90001',
        'USA'
    ),
    (
        3,
        '789 Elm Rd',
        'Chicago',
        'IL',
        '60001',
        'USA'
    );

INSERT INTO
    Category (name, description, parent_id)
VALUES (
        'Electronics',
        'Electronics products',
        NULL
    ),
    (
        'Clothing',
        'Apparel and fashion',
        NULL
    ),
    (
        'Phones',
        'Mobile phones and accessories',
        1
    ),
    (
        'Laptops',
        'Laptop computers',
        1
    );

INSERT INTO
    Supplier (
        name,
        contact_name,
        contact_email,
        contact_phone,
        address
    )
VALUES (
        'Tech Supplier Inc.',
        'John Tech',
        'info@techsupplier.com',
        '+1234567890',
        '789 Tech Rd'
    ),
    (
        'Fashion World',
        'Jane Fashion',
        'info@fashionworld.com',
        '+9876543210',
        '456 Fashion Ave'
    );

INSERT INTO
    Product (
        name,
        description,
        price,
        category_id
    )
VALUES (
        'Smartphone X',
        'High-end smartphone',
        999.99,
        3
    ),
    (
        'Laptop Pro',
        'Powerful laptop',
        1499.99,
        4
    ),
    (
        'T-shirt',
        'Cotton T-shirt',
        29.99,
        2
    );

INSERT INTO
    Stock (
        product_id,
        quantity,
        supplier_id
    )
VALUES (1, 100, 1),
    (2, 50, 1),
    (3, 200, 2);

INSERT INTO
    Ordered (user_id, total_amount, status)
VALUES (1, 999.99, 'Pending'),
    (2, 1499.99, 'Completed'),
    (3, 29.99, 'Pending');

INSERT INTO
    OrderItem (
        ordered_id,
        product_id,
        quantity,
        price
    )
VALUES (1, 1, 1, 999.99),
    (2, 2, 1, 1499.99),
    (3, 3, 1, 29.99);

INSERT INTO
    Review (
        product_id,
        user_id,
        rating,
        comment
    )
VALUES (1, 1, 5, 'Great phone!'),
    (2, 2, 4, 'Excellent laptop'),
    (3, 3, 3, 'Nice T-shirt');

INSERT INTO
    Payment (
        order_id,
        payment_method,
        amount,
        status
    )
VALUES (
        1,
        'Credit Card',
        999.99,
        'Paid'
    ),
    (2, 'PayPal', 1499.99, 'Paid'),
    (
        3,
        'Debit Card',
        29.99,
        'Paid'
    );

INSERT INTO
    Discount (
        code,
        description,
        percentage,
        valid_from,
        valid_to
    )
VALUES (
        'SUMMER20',
        'Summer discount',
        20.00,
        '2024-06-01 00:00:00',
        '2024-08-31 23:59:59'
    ),
    (
        'FALLSALE',
        'Fall sale discount',
        15.00,
        '2024-09-01 00:00:00',
        '2024-11-30 23:59:59'
    );
=======
-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 26 Octobre 2017 à 13:53
-- Version du serveur :  5.7.19-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `simple-mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

CREATE TABLE `item` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `item`
--

INSERT INTO `item` (`id`, `title`) VALUES
(1, 'Stuff'),
(2, 'Doodads');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/* CREATION TABLE  */

CREATE TABLE IF NOT EXISTS User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role VARCHAR(50),
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    phone VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    city VARCHAR(255),
    state VARCHAR(255),
    postal_code VARCHAR(50),
    country VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    parent_id INT NULL,
    FOREIGN KEY (parent_id) REFERENCES Category(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_name VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    address VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(80),
    price DECIMAL(10, 2) NOT NULL,
    category_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES Category(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    supplier_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES Product(id)
        ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES Supplier(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Ordered (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS OrderItem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ordered_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ordered_id) REFERENCES Ordered(id),
    FOREIGN KEY (product_id) REFERENCES Product(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES Product(id),
    FOREIGN KEY (user_id) REFERENCES User(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Payment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Ordered(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Discount (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    description TEXT,
    percentage DECIMAL(5, 2) NOT NULL,
    valid_from DATETIME NOT NULL,
    valid_to DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

ALTER TABLE Product ADD image VARCHAR(255) DEFAULT NULL;
ALTER TABLE Product ADD descriptionDetail LONGTEXT DEFAULT NULL;
ALTER TABLE Category ADD image VARCHAR(255) DEFAULT NULL;

INSERT INTO
    User (
        username,
        password,
        email,
        role,
        first_name,
        last_name,
        phone
    )
VALUES (
        'john_doe',
        'password123',
        'john.doe@example.com',
        'user',
        'John',
        'Doe',
        '+1234567890'
    ),
    (
        'jane_smith',
        'pass456',
        'jane.smith@example.com',
        'user',
        'Jane',
        'Smith',
        '+9876543210'
    ),
    (
        'admin',
        'admin123',
        'admin@example.com',
        'admin',
        'Admin',
        'User',
        '+1112223333'
    );

INSERT INTO
    Address (
        user_id,
        address_line1,
        city,
        state,
        postal_code,
        country
    )
VALUES (
        1,
        '123 Main St',
        'New York',
        'NY',
        '10001',
        'USA'
    ),
    (
        2,
        '456 Oak Ave',
        'Los Angeles',
        'CA',
        '90001',
        'USA'
    ),
    (
        3,
        '789 Elm Rd',
        'Chicago',
        'IL',
        '60001',
        'USA'
    );

INSERT INTO Category (name, description, parent_id, image) VALUES
('Outils de Travail du Cuir', 'Outils nécessaires pour couper, poinçonner, coudre et travailler le cuir', NULL, 'test.png'),
('Cuirs et Peaux', 'Différents types de cuir et peaux utilisés pour la fabrication', NULL, 'test.png'),
('Fournitures de Couture', 'Fournitures nécessaires pour la couture du cuir, telles que des fils, aiguilles, etc.', NULL, 'test.png'),
('Accessoires et Finitions', 'Accessoires et matériaux pour la finition des projets en cuir', NULL, 'test.png'),
('Produits Finis', 'Articles en cuir prêts à l\'emploi, comme des ceintures, sacs, portefeuilles, etc.', NULL, 'test.png');

INSERT INTO
    Supplier (
        name,
        contact_name,
        contact_email,
        contact_phone,
        address
    )
VALUES (
        'Tech Supplier Inc.',
        'John Tech',
        'info@techsupplier.com',
        '+1234567890',
        '789 Tech Rd'
    ),
    (
        'Fashion World',
        'Jane Fashion',
        'info@fashionworld.com',
        '+9876543210',
        '456 Fashion Ave'
    );

-- Outils de Travail du Cuir
--
INSERT INTO Product (name, description, descriptionDetail, price, image, category_id) VALUES
('Couteau Rotatif', 'Outil pour couper le cuir avec précision', 'Le couteau rotatif est un outil indispensable pour tout artisan du cuir. Conçu pour des coupes précises et nettes, ce couteau est doté d\'une lame circulaire qui permet de trancher facilement à travers le cuir, même les matériaux les plus épais. Sa poignée ergonomique offre une prise en main confortable, réduisant la fatigue lors des travaux prolongés. Que vous travailliez sur de grands projets comme des sacs ou des vestes, ou sur des articles plus petits comme des portefeuilles et des ceintures, ce couteau rotatif vous aidera à réaliser des coupes parfaites à chaque fois. En acier inoxydable de haute qualité, la lame est durable et reste affûtée longtemps. Facile à utiliser et à entretenir, c\'est l\'outil idéal pour les amateurs comme pour les professionnels du cuir.', 20.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Outils de Travail du Cuir')),

('Poinçon à Couture', 'Outil pour créer des trous pour la couture', 'Le poinçon à couture est un outil essentiel pour tout projet de couture du cuir. Conçu pour percer des trous propres et uniformes, il est parfait pour préparer le cuir avant de coudre. Avec une poignée confortable en bois et une pointe en acier trempé, cet outil est à la fois durable et facile à utiliser. Idéal pour les ceintures, sacs, portefeuilles, et autres articles en cuir, le poinçon à couture permet de créer des trous réguliers qui facilitent le passage des aiguilles et des fils. Cet outil est particulièrement utile pour les projets nécessitant une précision accrue, garantissant que chaque point de couture est parfaitement aligné. Que vous soyez débutant ou professionnel, le poinçon à couture est un ajout précieux à votre collection d\'outils de travail du cuir.', 15.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Outils de Travail du Cuir')),

('Marteau à Rivets', 'Marteau spécialement conçu pour fixer les rivets', 'Le marteau à rivets est un outil spécialisé conçu pour fixer solidement les rivets sur les articles en cuir. Doté d\'une tête en acier robuste et d\'une poignée ergonomique en bois, ce marteau offre une combinaison parfaite de durabilité et de confort. Utilisé pour renforcer les coutures et ajouter des éléments décoratifs, il est indispensable pour la fabrication de ceintures, sacs, portefeuilles, et autres accessoires en cuir. La tête du marteau est conçue pour répartir uniformément la force, assurant que les rivets sont fixés de manière sécurisée sans endommager le cuir. Que vous soyez un artisan amateur ou un professionnel expérimenté, le marteau à rivets vous permettra de réaliser des finitions impeccables sur tous vos projets en cuir. Avec une construction solide et une conception ergonomique, cet outil vous accompagnera dans tous vos travaux de maroquinerie.', 25.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Outils de Travail du Cuir'));



-- cuirs et peaux
--
INSERT INTO Product (name, description, descriptionDetail, price, image, category_id) VALUES
('Cuir de Vachette Pleine Fleur', 'Cuir de haute qualité', 'Le cuir de vachette pleine fleur est l\'un des matériaux les plus prisés dans le domaine de la maroquinerie. Reconnu pour sa durabilité, sa résistance et son aspect luxueux, ce type de cuir est idéal pour la fabrication de nombreux articles, tels que des sacs, ceintures, portefeuilles et chaussures. Ce cuir est fabriqué à partir de la couche supérieure de la peau de vache, ce qui lui confère une texture douce et naturelle. Contrairement à d\'autres types de cuir, le cuir pleine fleur conserve les imperfections naturelles de la peau, ajoutant un caractère unique à chaque pièce. Avec le temps, il développe une patine riche qui en rehausse encore la beauté. Le cuir de vachette pleine fleur est également apprécié pour sa capacité à respirer, ce qui le rend confortable à porter. Utilisé par les artisans du monde entier, il est le choix idéal pour ceux qui recherchent la qualité et l\'élégance.', 50.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Cuirs et Peaux')),

('Peau de Chèvre', 'Cuir souple et résistant', 'La peau de chèvre est un matériau extrêmement polyvalent et apprécié dans la fabrication d\'articles en cuir. Connue pour sa souplesse, sa légèreté et sa durabilité, elle est idéale pour des applications variées telles que les gants, vêtements, accessoires et articles de maroquinerie. Le tannage végétal de cette peau de chèvre lui confère une finition naturelle et respectueuse de l\'environnement, tout en renforçant ses qualités intrinsèques. La texture fine et le grain distinctif de la peau de chèvre ajoutent une touche d\'élégance et de sophistication à chaque pièce fabriquée. En outre, ce type de cuir est résistant à l\'usure et vieillit magnifiquement, développant une patine unique au fil du temps. Que vous soyez un artisan professionnel ou un amateur passionné, la peau de chèvre vous permettra de créer des produits en cuir à la fois esthétiques et durables.', 30.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Cuirs et Peaux'));



-- Fournitures de Couture
--
INSERT INTO Product (name, description, descriptionDetail, price, image, category_id) VALUES
('Fil à Coudre pour Cuir', 'Fil spécial pour couture du cuir', 'Le fil à coudre pour cuir est spécialement conçu pour répondre aux exigences de la couture de matériaux épais et robustes. Fabriqué en polyester de haute qualité, ce fil offre une résistance exceptionnelle à la tension et à l\'abrasion, garantissant des coutures durables et sécurisées. Disponible dans une variété de couleurs, il permet de réaliser des finitions esthétiques sur tous vos projets en cuir. Que vous travailliez à la main ou à la machine, ce fil glisse facilement à travers le cuir sans se rompre ni s\'effilocher. Sa texture légèrement cirée facilite le passage à travers les trous de couture, tout en offrant une meilleure adhérence. Utilisé par les maroquiniers professionnels et les amateurs, le fil à coudre pour cuir est un élément essentiel pour créer des articles en cuir de haute qualité. Avec ce fil, vos créations bénéficieront non seulement d\'une solidité accrue, mais aussi d\'une apparence soignée et professionnelle.', 10.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Fournitures de Couture')),

('Aiguilles à Cuir', 'Aiguilles robustes pour couture', 'Les aiguilles à cuir sont spécialement conçues pour pénétrer facilement les matériaux épais et denses, tels que le cuir. Fabriquées en acier trempé, ces aiguilles robustes offrent une résistance supérieure et une longue durée de vie. Leur pointe effilée permet de percer le cuir sans effort, tandis que l\'œillet élargi facilite l\'enfilage du fil, même pour les fils plus épais utilisés dans la couture du cuir. Disponibles en différentes tailles, les aiguilles à cuir s\'adaptent à une variété de projets, qu\'il s\'agisse de couture manuelle ou à la machine. Que vous fabriquiez des sacs, ceintures, chaussures ou autres articles en cuir, ces aiguilles vous aideront à réaliser des coutures précises et durables. Leur conception ergonomique réduit la fatigue lors des travaux prolongés, rendant la couture plus agréable. Les aiguilles à cuir sont un outil indispensable pour tout artisan désireux de créer des pièces de qualité professionnelle.', 5.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Fournitures de Couture'));


-- Accessoires et Finitions
--
INSERT INTO Product (name, description, descriptionDetail, price, image, category_id) VALUES
('Boucles de Ceinture', 'Boucles métalliques pour ceintures', 'Les boucles de ceinture sont des éléments essentiels pour la fabrication de ceintures en cuir. Fabriquées en métal de haute qualité, ces boucles offrent une durabilité et une résistance exceptionnelles. Disponibles dans une variété de styles et de finitions, elles permettent de personnaliser chaque ceinture selon les préférences individuelles. Que vous recherchiez un look classique, moderne ou vintage, il existe une boucle de ceinture qui correspond à votre vision. Leur conception robuste assure un maintien sûr et sécurisé, tandis que leur esthétique soignée ajoute une touche de sophistication à vos créations. Faciles à attacher, ces boucles sont compatibles avec une large gamme de largeurs de ceintures. Que vous soyez un artisan professionnel ou un amateur passionné, les boucles de ceinture en métal vous aideront à réaliser des produits de qualité supérieure qui raviront vos clients ou feront de parfaits cadeaux.', 7.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Accessoires et Finitions')),

('Rivets en Laiton', 'Rivets durables pour le cuir', 'Les rivets en laiton sont des accessoires indispensables pour tout artisan du cuir cherchant à ajouter des touches décoratives ou renforcer les coutures de ses créations. Fabriqués en laiton de haute qualité, ces rivets offrent une résistance à la corrosion et une durabilité exceptionnelles, garantissant la longévité de vos projets en cuir. Disponibles en différentes tailles et finitions, les rivets en laiton peuvent être utilisés pour une multitude d\'applications, allant de la fabrication de sacs et portefeuilles à la décoration de vestes et ceintures. Leur installation est simple grâce à des outils spécifiques, et ils assurent une fixation solide qui ne s\'affaiblit pas avec le temps. Les rivets ajoutent non seulement de la robustesse à vos articles en cuir, mais également une touche esthétique unique qui peut varier du look industriel au style élégant et raffiné. Avec leur finition brillante, ils rehaussent l\'apparence générale de vos créations.', 8.00, 
    'test.png', (SELECT id FROM Category WHERE name = 'Accessoires et Finitions'));


INSERT INTO
    Stock (product_id, quantity, supplier_id) VALUES (1, 100, 1),
    (2, 50, 1),
    (3, 75, 1),
    (4, 23, 2),
    (5, 49, 1),
    (6, 33, 2),
    (7, 82, 1),
    (8, 452, 1),
    (9, 389, 2);

INSERT INTO
    Ordered (user_id, total_amount, status)
VALUES (1, 999.99, 'Pending'),
    (2, 1499.99, 'Completed'),
    (3, 29.99, 'Pending');

INSERT INTO
    OrderItem (
        ordered_id,
        product_id,
        quantity,
        price
    )
VALUES (1, 1, 1, 999.99),
    (2, 2, 1, 1499.99),
    (3, 3, 1, 29.99);

INSERT INTO
    Review (
        product_id,
        user_id,
        rating,
        comment
    )
VALUES (1, 1, 5, 'Great phone!'),
    (2, 2, 4, 'Excellent laptop'),
    (3, 3, 3, 'Nice T-shirt');

INSERT INTO
    Payment (
        order_id,
        payment_method,
        amount,
        status
    )
VALUES (
        1,
        'Credit Card',
        999.99,
        'Paid'
    ),
    (2, 'PayPal', 1499.99, 'Paid'),
    (
        3,
        'Debit Card',
        29.99,
        'Paid'
    );

INSERT INTO
    Discount (
        code,
        description,
        percentage,
        valid_from,
        valid_to
    )
VALUES (
        'SUMMER20',
        'Summer discount',
        20.00,
        '2024-06-01 00:00:00',
        '2024-08-31 23:59:59'
    ),
    (
        'FALLSALE',
        'Fall sale discount',
        15.00,
        '2024-09-01 00:00:00',
        '2024-11-30 23:59:59'
    );
>>>>>>> 3f507d697d1f70036ac9294321e2d2865d6d89db
