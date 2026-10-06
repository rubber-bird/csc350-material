-- ============================================================
--  12 - Transactions and hashing
-- ============================================================
--
--  A transaction groups statements so that either all of them happen or
--  none do. Moving money is the classic case: two UPDATEs, and a crash
--  between them would make money vanish.
--
--    START TRANSACTION;
--    UPDATE accounts SET balance = balance - 100 WHERE account_id = 1;
--    UPDATE accounts SET balance = balance + 100 WHERE account_id = 2;
--    COMMIT;          -- or ROLLBACK; to undo everything since the start
--
--  Until COMMIT nothing is permanent. ROLLBACK throws it all away.
--  SAVEPOINT name / ROLLBACK TO name undo only part of a transaction.
--  LAST_INSERT_ID() is the AUTO_INCREMENT value the previous INSERT made,
--  which is how a child row finds the parent you just inserted.
--
--  Hashing turns a value into a fixed-length fingerprint that cannot be
--  reversed: SHA2('text', 256) gives 64 hex characters. To check a
--  password you hash what was typed and compare. Encryption is
--  reversible with a key: AES_ENCRYPT(value, key) / AES_DECRYPT(col, key).
--
--  HOW THE TASKS WORK: the tests look at the data left in the tables
--  after your file ran. Anything you did not COMMIT is rolled back before
--  the tests look, so a forgotten COMMIT shows up as a failing test.
--
--  You will need (click for the manual):
--    START TRANSACTION, COMMIT, ROLLBACK  https://dev.mysql.com/doc/refman/8.4/en/commit.html
--    SAVEPOINT                            https://dev.mysql.com/doc/refman/8.4/en/savepoint.html
--    LAST_INSERT_ID                       https://dev.mysql.com/doc/refman/8.4/en/information-functions.html#function_last-insert-id
--    SHA2                                 https://dev.mysql.com/doc/refman/8.4/en/encryption-functions.html#function_sha2
--    AES_ENCRYPT / AES_DECRYPT            https://dev.mysql.com/doc/refman/8.4/en/encryption-functions.html#function_aes-encrypt
--
--  The tables below are created and filled for you. Leave them alone.
--
-- ============================================================

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


-- ---------------------------------------------- EASY --------

-- Task 1: a transfer
--
-- In one transaction, move 100.00 from Ada's account (1) to Alan's (2),
-- and COMMIT it.

-- your code here


-- Task 2: a change of mind
--
-- Start a transaction, insert an account (4, 'Mistake', 999.00), then
-- ROLLBACK. The account must not exist afterwards.

-- your code here


-- Task 3: store a password hash
--
-- Insert a user 'ada' whose pass_hash is the SHA-256 hash of the text
-- 'correct horse'. Never store the password itself.

-- your code here


-- ---------------------------------------------- MEDIUM ------

-- Task 4: a transfer with a receipt
--
-- In one transaction: move 25.00 from Grace (3) to Ada (1) AND insert a
-- row into transfers recording it (from_account 3, to_account 1, amount
-- 25.00). COMMIT.

-- your code here


-- Task 5: undo part of a transaction
--
-- In one transaction: insert a user 'alan' with the SHA-256 hash of
-- 'enigma'; create a SAVEPOINT; insert a user 'alna' (a typo) with the
-- same hash; ROLLBACK TO the savepoint; COMMIT. Afterwards alan exists
-- and alna does not.

-- your code here


-- Task 6: change a password only if the old one is right
--
-- Write two UPDATEs. The first changes ada's pass_hash to the hash of
-- 'new horse', but only where the current pass_hash equals the hash of
-- 'correct horse'. The second tries the same for eve with the WRONG old
-- password 'guess', setting the hash of 'hacked'. It must change nothing.

-- your code here


-- ---------------------------------------------- HARD --------

-- Task 7: a parent and its children in one go
--
-- In one transaction: insert an order for customer 'Ada' with total
-- 70.00, then insert two order_items for that order, 'Keyboard' at 49.00
-- and 'Mouse' at 21.00, using LAST_INSERT_ID() for the order_id rather
-- than typing the number. COMMIT.

-- your code here


-- Task 8: encrypt a token and read it back
--
-- Insert a row into secrets with label 'github' and token set to
-- AES_ENCRYPT of the text 'ghp_s3cr3t' with the key 'course-key'. Then
-- create a view `v_tokens` with the columns
--   label, token
-- where token is the DECRYPTED text (wrap AES_DECRYPT in
-- CAST(... AS CHAR) so it comes back as text, not bytes). Order by label.

-- your code here
