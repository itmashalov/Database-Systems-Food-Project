-- ---------------------------------------------------------------|
-- SQL For Setting up database and generating basic test data set.|
--                                                                |
-- 7 task included:                                               |
--   1. DROP the exist Views                                      |
--   2. DROP the exist Tables                                     |
--   3. CREATE the tables                                         |
--   4. CREATE the views                                          |
--   5. DROP the exist Functions                                  |
--   6. CREATE Functions                                          |
--   7. INSERT test data (using Cartesian Product & Funcions)     |
--                                                                |
-- TODO:                                                          |
--   Write commands to control the access permission of different |
--   users.                                                       |
--                                                                |
-- Note for this script:                                          |
--   1. Make sure you have already selected/used a schema         |
--   2. Some Views are referenced to others, check it out before  |
--      you add/delete columns in a view.                         |
--                                                                |
-- Written by Peiran Chen                                         |
--                                                                |
-- ---------------------------------------------------------------|

START TRANSACTION;

-- DROP TABLES

DROP VIEW IF EXISTS SalesRecordDetail;
DROP VIEW IF EXISTS ContractDetail;
DROP VIEW IF EXISTS MarketDetail;
DROP VIEW IF EXISTS ImmigrantsDetail;
DROP VIEW IF EXISTS TransportOfferDetail;
DROP VIEW IF EXISTS ProductDetail;

DROP TABLE IF EXISTS SalesRecord;
DROP TABLE IF EXISTS Contract;
DROP TABLE IF EXISTS Market;
DROP TABLE IF EXISTS Immigrants;
DROP TABLE IF EXISTS TransportOffer;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS TransportCompany;
DROP TABLE IF EXISTS Country;
DROP TABLE IF EXISTS StorageType;
DROP TABLE IF EXISTS Flavour;

-- CREATE TABLES

CREATE TABLE IF NOT EXISTS Flavour (
	id INT AUTO_INCREMENT,
	flavour VARCHAR(45) UNIQUE NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS StorageType (
	id INT AUTO_INCREMENT,
	typename VARCHAR(45) UNIQUE NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Country (
	name VARCHAR(45),
	population DOUBLE NOT NULL DEFAULT 0,
	health_factor DOUBLE NOT NULL DEFAULT 0,
	active BOOLEAN NOT NULL DEFAULT false,
	PRIMARY KEY (name)
);

CREATE TABLE IF NOT EXISTS TransportCompany (
	name VARCHAR(45),
	active BOOLEAN NOT NULL DEFAULT false,
	PRIMARY KEY (name)
);

CREATE TABLE IF NOT EXISTS User (
	id INT AUTO_INCREMENT,
	name VARCHAR(45) UNIQUE NOT NULL,
	password VARCHAR(45) NOT NULL,
	permission_type ENUM('admin','ceo','manager','accountant') NOT NULL,
	active BOOLEAN NOT NULL DEFAULT false,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Product (
	id INT AUTO_INCREMENT,
	flavour_id INT NOT NULL,
	storage_type_id INT NOT NULL,
	name VARCHAR(120) UNIQUE NOT NULL,
	cost DOUBLE NOT NULL DEFAULT 0,
	weight DOUBLE NOT NULL DEFAULT 0,
	instock BOOLEAN NOT NULL DEFAULT false,
	health_factor DOUBLE DEFAULT 0,
	active BOOLEAN NOT NULL DEFAULT false,
	PRIMARY KEY (id),
	CONSTRAINT product_flavour_fk
		FOREIGN KEY (flavour_id)
		REFERENCES Flavour (id)
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT product_type_fk
		FOREIGN KEY (storage_type_id)
		REFERENCES StorageType (id)
		ON UPDATE NO ACTION ON DELETE NO ACTION
);

CREATE TABLE IF NOT EXISTS TransportOffer (
	id INT AUTO_INCREMENT,
	storage_type_id INT NOT NULL,
	country_name VARCHAR(45) NOT NULL,
	transport_company_name VARCHAR(45) NOT NULL,
	price_per_kg DOUBLE NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	CONSTRAINT transportoffer_country_fk
		FOREIGN KEY (country_name)
		REFERENCES Country (name)
		ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT transportoffer_type_fk
		FOREIGN KEY (storage_type_id)
		REFERENCES StorageType (id)
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT transportoffer_company_fk
		FOREIGN KEY (transport_company_name)
		REFERENCES TransportCompany (name)
		ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE IF NOT EXISTS Immigrants (
	id INT AUTO_INCREMENT,
	from_country VARCHAR(45) NOT NULL,
	to_country VARCHAR(45) NOT NULL,
	percentage DOUBLE NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	CONSTRAINT immigrants_from_fk
		FOREIGN KEY (from_country)
		REFERENCES Country (name)
		ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT immigrants_to_fk
		FOREIGN KEY (to_country)
		REFERENCES Country (name)
		ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE IF NOT EXISTS Market (
	id INT AUTO_INCREMENT,
	country_name VARCHAR(45) NOT NULL,
	product_id INT NOT NULL,
	volume DOUBLE NOT NULL DEFAULT 0,
	potential DOUBLE NOT NULL DEFAULT 0,
	minimum_price DOUBLE NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	CONSTRAINT market_country_fk
		FOREIGN KEY (country_name)
		REFERENCES Country (name)
		ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT market_product_fk
		FOREIGN KEY (product_id)
		REFERENCES Product (id)
		ON UPDATE NO ACTION ON DELETE NO ACTION
);

CREATE TABLE IF NOT EXISTS Contract (
	id INT AUTO_INCREMENT,
	country_name VARCHAR(45) NOT NULL,
	transport_company_name VARCHAR(45) NOT NULL,
	product_id INT NOT NULL,
	user_id INT NOT NULL,
	start_date DATE NOT NULL,
	expiry_date DATE NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT contract_country_fk
		FOREIGN KEY (country_name)
		REFERENCES Country (name)
		ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT contract_product_fk
		FOREIGN KEY (product_id)
		REFERENCES Product (id)
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT contract_company_fk
		FOREIGN KEY (transport_company_name)
		REFERENCES TransportCompany (name)
		ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT contract_user_fk
		FOREIGN KEY (user_id)
		REFERENCES User (id)
		ON UPDATE NO ACTION ON DELETE NO ACTION
);

CREATE TABLE IF NOT EXISTS SalesRecord (
	id INT AUTO_INCREMENT,
	contract_id INT NOT NULL,
	cost DOUBLE NOT NULL,
	sale_price DOUBLE NOT NULL,
	transport_price DOUBLE NOT NULL,
	quantity INT NOT NULL,
	date DATE NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT salesrecord_contract_fk
		FOREIGN KEY (contract_id)
		REFERENCES Contract (id)
		ON UPDATE NO ACTION ON DELETE NO ACTION
);

-- CREATE VIEWS

CREATE VIEW ProductDetail AS
SELECT
	Product.name AS name,
	Flavour.flavour AS flavour,
	StorageType.typename AS storage_type,
	Product.cost AS cost,
	Product.weight AS weight,
	Product.instock AS instock,
	Product.health_factor AS health_factor,
	Product.active AS active,
	Product.id AS id,
	Flavour.id AS flavour_id,
	StorageType.id AS storage_type_id
FROM
	Product, Flavour, StorageType
WHERE
	Flavour.id = Product.flavour_id AND
	StorageType.id = Product.storage_type_id;

CREATE VIEW TransportOfferDetail AS
SELECT
	TransportCompany.name AS transport_company,
	Country.name AS country,
	StorageType.typename AS storage_type,
	TransportOffer.price_per_kg AS price_per_kg,
	TransportOffer.id AS id,
	TransportOffer.storage_type_id AS storage_type_id
FROM
	TransportOffer, StorageType, Country, TransportCompany
WHERE
	StorageType.id = TransportOffer.storage_type_id AND
	Country.name = TransportOffer.country_name AND
	TransportCompany.name = TransportOffer.transport_company_name
ORDER BY
	transport_company, country, storage_type;

CREATE VIEW ImmigrantsDetail AS
SELECT
	Country1.name AS host_country,
	Country1.population AS host_country_population,
	Country2.name AS immigrants_from_country,
	Country2.population AS immigrants_from_country_total_population,
	Immigrants.percentage AS immigrants_percentage,
	round(Country2.population * Immigrants.percentage / 100) AS total_immigrants,
	Immigrants.id AS id
FROM
	Country AS Country1, Immigrants, Country AS Country2
WHERE
	Country1.name = Immigrants.to_country AND
	Country2.name = Immigrants.from_country
ORDER BY
	host_country;

CREATE VIEW MarketDetail AS
SELECT
	Country.name AS country,
	ProductDetail.name AS product_name,
	Market.volume AS volume,
	Market.potential AS potential,
	Market.minimum_price AS minimum_price,
	Country.health_factor AS country_health_factor,
	Country.active AS country_active,
	TransportOfferDetail.transport_company,
	TransportOfferDetail.price_per_kg AS price_per_kg,
	ProductDetail.health_factor AS product_health_factor,
	ProductDetail.instock,
	ProductDetail.active AS product_active,
	ProductDetail.flavour,
	ProductDetail.storage_type,
	ProductDetail.cost,
	ProductDetail.weight,
	ProductDetail.id AS product_id,
	ProductDetail.flavour_id,
	ProductDetail.storage_type_id,
	Market.id AS id
FROM
	Market, Country, TransportOfferDetail, ProductDetail
WHERE
	Country.name = Market.country_name AND
	ProductDetail.id = Market.product_id AND
	TransportOfferDetail.country = Market.country_name AND
	TransportOfferDetail.storage_type_id = ProductDetail.storage_type_id;

CREATE VIEW ContractDetail AS
SELECT
	Country.name AS country,
	ProductDetail.name AS product_name,
	TransportCompany.name AS transport_company,
	User.name AS user,
	Contract.start_date AS start_date,
	Contract.expiry_date AS expiry_date,
	Contract.id AS id
FROM
	Contract, Country, ProductDetail, TransportCompany, User
WHERE
	Country.name = Contract.country_name AND
	ProductDetail.id = Contract.product_id AND
	TransportCompany.name = Contract.transport_company_name AND
	User.id = Contract.user_id
ORDER BY
	country, product_name, transport_company, user;

CREATE VIEW SalesRecordDetail AS
SELECT
	SalesRecord.date,
	ContractDetail.product_name,
	ContractDetail.country,
	ContractDetail.transport_company,
	SalesRecord.cost,
	SalesRecord.transport_price,
	SalesRecord.sale_price,
	SalesRecord.quantity,
	ContractDetail.id
FROM
	SalesRecord, ContractDetail
WHERE
	ContractDetail.id = SalesRecord.contract_id
ORDER BY
	date DESC, country, product_name;

-- DROP & CREATE FUNCTIONS

DROP FUNCTION IF EXISTS getRandBoolean;
DROP FUNCTION IF EXISTS getRandInt;
DROP FUNCTION IF EXISTS getRandDouble;
DROP FUNCTION IF EXISTS getRandDate;

delimiter $$

CREATE FUNCTION getRandBoolean (p DOUBLE) RETURNS BOOLEAN
BEGIN
	IF rand() <= p THEN
		RETURN(true);
	ELSE
		RETURN(false);
	END IF;
END$$

CREATE FUNCTION getRandInt (min INT, max INT) RETURNS INT
BEGIN
	DECLARE period INT;
	DECLARE result INT;
	SET period = max - min;
	SET result = round( min + rand() * period );
	RETURN(result);
END$$

CREATE FUNCTION getRandDouble (min DOUBLE, max DOUBLE, dp INT) RETURNS DOUBLE
BEGIN
	DECLARE period DOUBLE;
	DECLARE result DOUBLE;
	SET period = max - min;
	IF ISNULL(dp) THEN
		SET result = min + rand()*period;
	ELSE
		SET result = round(min + rand()*period, dp);
	END IF;
	RETURN(result);
END$$

CREATE FUNCTION getRandDate (earliest DATE, latest DATE) RETURNS DATE
BEGIN
	DECLARE period INT;
	DECLARE randdt INT;
	DECLARE result DATE;
	SET period = datediff(latest, earliest);
	SET randdt = round( rand() * period );
	SET result = date_add(earliest, INTERVAL randdt DAY);
	RETURN(result);
END$$

delimiter ;

-- INSERT TEST DATA SET

INSERT INTO Flavour (flavour) VALUES ('sweet');
INSERT INTO Flavour (flavour) VALUES ('salty');
INSERT INTO Flavour (flavour) VALUES ('sour');
INSERT INTO Flavour (flavour) VALUES ('smoked');

INSERT INTO StorageType (typename) VALUES ('normal temperature');
INSERT INTO StorageType (typename) VALUES ('keep fresh');
INSERT INTO StorageType (typename) VALUES ('frozen');

INSERT INTO Country (name, population, health_factor, active) VALUES ('United Kingdom', 62262000, getRandDouble(0.3, 0.7, 2), false);
INSERT INTO Country (name, population, health_factor, active) VALUES ('Germany', 80334600, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Country (name, population, health_factor, active) VALUES ('Italy', 60813326, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Country (name, population, health_factor, active) VALUES ('Spain', 47190493, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Country (name, population, health_factor, active) VALUES ('France', 63860000, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Country (name, population, health_factor, active) VALUES ('Netherlands', 16730000, getRandDouble(0.3, 0.7, 2), true);

INSERT INTO TransportCompany (name, active) VALUES ('T-Company A', getRandBoolean(0.7));
INSERT INTO TransportCompany (name, active) VALUES ('T-Company B', getRandBoolean(0.7));
INSERT INTO TransportCompany (name, active) VALUES ('T-Company C', getRandBoolean(0.7));
INSERT INTO TransportCompany (name, active) VALUES ('T-Company D', getRandBoolean(0.7));

INSERT INTO User (id, name, password, permission_type, active) VALUES (1, 'admin A', sha1('123456'), 'admin', true);
INSERT INTO User (id, name, password, permission_type, active) VALUES (2, 'admin B', sha1('123456'), 'admin', true);
INSERT INTO User (id, name, password, permission_type, active) VALUES (3, 'admin C', sha1('123456'), 'admin', false);
INSERT INTO User (id, name, password, permission_type, active) VALUES (4, 'ceo A', sha1('123456'), 'ceo', true);
INSERT INTO User (id, name, password, permission_type, active) VALUES (5, 'ceo B', sha1('123456'), 'ceo', true);
INSERT INTO User (id, name, password, permission_type, active) VALUES (6, 'ceo C', sha1('123456'), 'ceo', false);
INSERT INTO User (id, name, password, permission_type, active) VALUES (7, 'manager A', sha1('123456'), 'manager', true);
INSERT INTO User (id, name, password, permission_type, active) VALUES (8, 'manager B', sha1('123456'), 'manager', true);
INSERT INTO User (id, name, password, permission_type, active) VALUES (9, 'manager C', sha1('123456'), 'manager', false);
INSERT INTO User (id, name, password, permission_type, active) VALUES (10, 'accountant A', sha1('123456'), 'accountant', true);
INSERT INTO User (id, name, password, permission_type, active) VALUES (11, 'accountant B', sha1('123456'), 'accountant', true);
INSERT INTO User (id, name, password, permission_type, active) VALUES (12, 'accountant C', sha1('123456'), 'accountant', false);

INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (1, 1, 'SW-N', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), true, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (1, 2, 'SW-K', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), true, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (1, 3, 'SW_F', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), true, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (2, 1, 'SA-N', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), false, getRandDouble(0.3, 0.7, 2), false);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (2, 2, 'SA-K', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), false, getRandDouble(0.3, 0.7, 2), false);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (2, 3, 'SA-F', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), true, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (3, 1, 'SO-N', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), true, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (3, 2, 'SO-K', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), false, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (3, 3, 'SO-F', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), true, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (4, 1, 'SM-N', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), false, getRandDouble(0.3, 0.7, 2), false);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (4, 2, 'SM-K', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), true, getRandDouble(0.3, 0.7, 2), true);
INSERT INTO Product (flavour_id, storage_type_id, name, cost, weight, instock, health_factor, active) VALUES (4, 3, 'SM-F', getRandDouble(1, 2, 2), getRandDouble(1, 2, 2), true, getRandDouble(0.3, 0.7, 2), true);

INSERT INTO TransportOffer
	(storage_type_id, country_name, transport_company_name, price_per_kg)
SELECT
	StorageType.id AS storage_type_id,
	Country.name AS country_name,
	TransportCompany.name AS transport_company_name,
	getRandDouble(0.5, 0.75, 2) AS price_per_kg
FROM
	StorageType, Country, TransportCompany;

INSERT INTO Immigrants
	(from_country, to_country, percentage)
SELECT
	Country1.name AS from_country,
	Country2.name AS to_country,
	getRandDouble(0.5, 2, 2) AS percentage
FROM
	Country AS Country1,
	Country AS Country2
WHERE
	Country1.name NOT LIKE Country2.name;

INSERT INTO Market
	(country_name, product_id, volume, potential, minimum_price)
SELECT
	country_name,
	product_id,
	volume,
	getRandDouble(0.3 * volume, 0.6 * volume, 0) AS potential,
	getRandDouble(2 * cost, 3 * cost, 2) AS minimum_price
FROM (
	SELECT
		Country.name AS country_name,
		Product.id AS product_id,
		Product.cost AS cost,
		getRandDouble(5000, 12000, 0) AS volume
	FROM
		Country, Product
) AS T1;

INSERT INTO Contract
	(country_name, transport_company_name, product_id, user_id, start_date, expiry_date)
SELECT
	Country.name AS country_name,
	TransportCompany.name AS transport_company_name,
	Product.id AS product_id,
	User.id AS user_id,
	getRandDate('2010-01-01', '2013-12-31') AS start_date,
	getRandDate('2014-01-01', '2016-12-31') AS expiry_date
FROM
	Country, TransportCompany, Product, User
WHERE
	User.permission_type = 'manager';

INSERT INTO SalesRecord
	(contract_id, cost, sale_price, transport_price, quantity, date)
SELECT
	contract_id,
	getRandDouble(0.7 * cost, 1.3 * cost, 2) AS cost,
	getRandDouble(minimum_price, minimum_price*1.5, 2) AS sale_price,
	getRandDouble(0.7 * transport_price, 1.3 * transport_price, 2) AS transport_price,
	getRandDouble(0.5 * max_sales, max_sales, 0) AS quantity,
	getRandDate(start_date, curdate()) AS date
FROM (
	SELECT 
		Contract.id AS contract_id,
		Product.cost AS cost,
		Market.minimum_price AS minimum_price,
		( TransportOffer.price_per_kg * Product.weight ) AS transport_price,
		( Market.volume - Market.potential ) AS max_sales,
		Contract.start_date AS start_date
	FROM
		#strength level
		#1stlvl                                 #2ndlvl  #3rdlvl #4thlvl         #5thlvl
		Country, TransportCompany, StorageType, Product, Market, TransportOffer, Contract
	WHERE
		Country.name = Market.country_name AND
		Country.name = Contract.country_name AND
		TransportCompany.name = TransportOffer.transport_company_name AND
		TransportCompany.name = Contract.transport_company_name AND
		StorageType.id = Product.storage_type_id AND
		StorageType.id = TransportOffer.storage_type_id AND
		Product.id = Market.product_id AND
		Product.id = Contract.product_id
) AS T1;

COMMIT;
