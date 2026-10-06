-- 12 - Transactions and hashing: reference solution

CREATE TABLE accounts (
  account_id INT UNSIGNED NOT NULL PRIMARY KEY,
  owner      VARCHAR(40) NOT NULL,
  balance    DECIMAL(10,2) NOT NULL,
  CHECK (balance >= 0)
);
INSERT INTO accounts (account_id, owner, balance) VALUES
  (1, 'Ada', 500.00), (2, 'Alan', 200.00), (3, 'Grace', 50.00);

CREATE TABLE transfers (
  transfer_id  INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  from_account INT UNSIGNED NOT NULL,
  to_account   INT UNSIGNED NOT NULL,
  amount       DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (from_account) REFERENCES accounts (account_id),
  FOREIGN KEY (to_account)   REFERENCES accounts (account_id)
);

CREATE TABLE users (
  user_id   INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username  VARCHAR(30) NOT NULL UNIQUE,
  pass_hash CHAR(64) NOT NULL
);
INSERT INTO users (username, pass_hash) VALUES ('eve', SHA2('eve-original', 256));

CREATE TABLE orders (
  order_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer VARCHAR(40) NOT NULL,
  total    DECIMAL(8,2) NOT NULL
);
CREATE TABLE order_items (
  item_id  INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product  VARCHAR(40) NOT NULL,
  price    DECIMAL(8,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders (order_id) ON DELETE CASCADE
);
INSERT INTO orders (order_id, customer, total) VALUES (1, 'Grace', 4.25);
INSERT INTO order_items (order_id, product, price) VALUES (1, 'Cable', 4.25);

CREATE TABLE secrets (
  secret_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  label     VARCHAR(40) NOT NULL UNIQUE,
  token     VARBINARY(255) NOT NULL
);

-- Task 1
START TRANSACTION;
UPDATE accounts SET balance = balance - 100.00 WHERE account_id = 1;
UPDATE accounts SET balance = balance + 100.00 WHERE account_id = 2;
COMMIT;

-- Task 2
START TRANSACTION;
INSERT INTO accounts (account_id, owner, balance) VALUES (4, 'Mistake', 999.00);
ROLLBACK;

-- Task 3
INSERT INTO users (username, pass_hash) VALUES ('ada', SHA2('correct horse', 256));

-- Task 4
START TRANSACTION;
UPDATE accounts SET balance = balance - 25.00 WHERE account_id = 3;
UPDATE accounts SET balance = balance + 25.00 WHERE account_id = 1;
INSERT INTO transfers (from_account, to_account, amount) VALUES (3, 1, 25.00);
COMMIT;

-- Task 5
START TRANSACTION;
INSERT INTO users (username, pass_hash) VALUES ('alan', SHA2('enigma', 256));
SAVEPOINT before_typo;
INSERT INTO users (username, pass_hash) VALUES ('alna', SHA2('enigma', 256));
ROLLBACK TO before_typo;
COMMIT;

-- Task 6
UPDATE users SET pass_hash = SHA2('new horse', 256)
WHERE username = 'ada' AND pass_hash = SHA2('correct horse', 256);
UPDATE users SET pass_hash = SHA2('hacked', 256)
WHERE username = 'eve' AND pass_hash = SHA2('guess', 256);

-- Task 7
START TRANSACTION;
INSERT INTO orders (customer, total) VALUES ('Ada', 70.00);
INSERT INTO order_items (order_id, product, price) VALUES
  (LAST_INSERT_ID(), 'Keyboard', 49.00),
  (LAST_INSERT_ID(), 'Mouse', 21.00);
COMMIT;

-- Task 8
INSERT INTO secrets (label, token) VALUES ('github', AES_ENCRYPT('ghp_s3cr3t', 'course-key'));

CREATE VIEW v_tokens AS
SELECT label, CAST(AES_DECRYPT(token, 'course-key') AS CHAR) AS token
FROM secrets
ORDER BY label;
