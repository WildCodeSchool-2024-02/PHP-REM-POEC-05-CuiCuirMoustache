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
