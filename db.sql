-- DB for the project "Post Memory"
-- MySQL
-- Created by: Erostate
-- Date: 2024-05-28

-- Database: `post_memory`

-- Table "company"
-- Desc: Companies
CREATE TABLE `company` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `address` text NOT NULL,
    `city` varchar(255) NOT NULL,
    `zip` varchar(255) NOT NULL,
    `phone` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `account_status` enum('available', 'disable', 'pending') NOT NULL,
    CONSTRAINT pk_company PRIMARY KEY(id)
);

-- Table "users"
-- Desc: Users of the system
CREATE TABLE `account` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `firstname` varchar(255) NOT NULL,
    `lastname` varchar(255) NULL,
    `email` varchar(255) NOT NULL,
    `password` text NOT NULL,
    `role` enum('dev', 'admin', 'user') NOT NULL DEFAULT('user'),
    `company_id` int(11) NULL DEFAULT(NULL),
    CONSTRAINT pk_users PRIMARY KEY(id),
    CONSTRAINT fk_users_company FOREIGN KEY(company_id) REFERENCES company(id) ON DELETE
);

-- Table "cards"
-- Desc: All the deceased
CREATE TABLE `cards` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `firstname` varchar(255) NOT NULL,
    `lastname` varchar(255) NOT NULL,
    `birth_date` date NOT NULL,
    `death_date` date NOT NULL,
    `info` text NOT NULL,
    CONSTRAINT pk_cards PRIMARY KEY(id)
);

-- Table "acount_cards"
-- Desc: The relation between the users and the deceased
CREATE TABLE `account_cards` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `account_id` int(11) NOT NULL,
    `card_id` int(11) NOT NULL,
    CONSTRAINT pk_account_cards PRIMARY KEY(id),
    CONSTRAINT fk_account_cards_users FOREIGN KEY(account_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_account_cards_cards FOREIGN KEY(card_id) REFERENCES cards(id) ON DELETE CASCADE
);

-- Table "images"
-- Desc: Images of the deceased
CREATE TABLE `images` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `card_id` int(11) NOT NULL,
    `path` text NOT NULL,
    `location` enum('file', 'url', 'base64') NOT NULL DEFAULT('file'),
    CONSTRAINT pk_images PRIMARY KEY(id),
    CONSTRAINT fk_images_cards FOREIGN KEY(card_id) REFERENCES cards(id) ON DELETE CASCADE
);

-- Table "config_cards"
-- Desc: Configuration for the cards
CREATE TABLE `config_cards` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `card_id` int(11) NOT NULL,
    `display_name` enum('true', 'false') NOT NULL DEFAULT('true'),
    `display_dob` enum('true', 'false') NOT NULL DEFAULT('true'),
    `display_dod` enum('true', 'false') NOT NULL DEFAULT('true'),
    `display_info` enum('true', 'false') NOT NULL DEFAULT('true'),
    CONSTRAINT pk_config_cards PRIMARY KEY(id),
    CONSTRAINT fk_config_cards_cards FOREIGN KEY(card_id) REFERENCES cards(id) ON DELETE CASCADE
);

-- Table "products"
-- Desc: Products
CREATE TABLE `products` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `label` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `price` varchar(11) NOT NULL,
    `tva` varchar(11) NOT NULL,
    `image` text NOT NULL,
    `image_location` enum('file', 'url', 'base64') NOT NULL DEFAULT('file'),
    CONSTRAINT pk_products PRIMARY KEY(id)
);

-- Table "product_customization"
-- Desc: Customization for the products

-- Table "purchase"
-- Desc: Purchases
CREATE TABLE `purchase` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `company_id` int(11) NOT NULL,
    `account_id` int(11) NOT NULL,
    `number` int(11) NOT NULL,
    `price_unit` varchar(11) NOT NULL,
    `price_total` varchar(11) NOT NULL,
    `tva` varchar(11) NOT NULL,
    `customization` text NOT NULL,
    CONSTRAINT pk_purchase PRIMARY KEY(id),
    CONSTRAINT fk_purchase_company FOREIGN KEY(company_id) REFERENCES company(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchase_account FOREIGN KEY(account_id) REFERENCES account(id) ON DELETE CASCADE
);

-- Table "discount_codes"
-- Desc: Discount codes
CREATE TABLE `discount_codes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `code` varchar(255) NOT NULL,
    `desc` text NOT NULL,
    `type` enum('percent', 'amount') NOT NULL COMMENT 'percent: -10%, amount: -10€',
    `value` int(11) NOT NULL,
    `active` enum('true', 'false') NOT NULL DEFAULT('false'),
    `cumulative` enum('true', 'false') NOT NULL DEFAULT('false'),
    `date_start` date NOT NULL,
    `date_end` date NULL,
    CONSTRAINT pk_discount_codes PRIMARY KEY(id)
);