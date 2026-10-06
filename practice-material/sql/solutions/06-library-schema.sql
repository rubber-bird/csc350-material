-- 06 - Library schema: reference solution

CREATE TABLE authors (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE books (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  isbn         CHAR(13) NOT NULL UNIQUE,
  title        VARCHAR(200) NOT NULL,
  author_id    INT NOT NULL,
  price        DECIMAL(8,2) NOT NULL DEFAULT 0,
  published_on DATE NULL,
  CONSTRAINT fk_books_author FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE RESTRICT
);

CREATE TABLE members (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  email     VARCHAR(255) NOT NULL UNIQUE,
  name      VARCHAR(100) NOT NULL,
  joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE loans (
  book_id     INT,
  member_id   INT,
  borrowed_on DATE NOT NULL,
  due_on      DATE NOT NULL,
  returned_on DATE NULL,
  PRIMARY KEY (book_id, member_id, borrowed_on),
  CONSTRAINT fk_loans_book   FOREIGN KEY (book_id)   REFERENCES books (id)   ON DELETE CASCADE,
  CONSTRAINT fk_loans_member FOREIGN KEY (member_id) REFERENCES members (id) ON DELETE CASCADE,
  INDEX idx_loans_due_on (due_on)
);
