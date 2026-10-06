-- ============================================================
--  06 - Putting it together: a library schema
-- ============================================================
--
--  Everything from modules 01 to 05 in one design. Read the whole
--  description first, then create the four tables in the order given,
--  because each one references the ones before it.
--
--  A library lends books to members. There is nothing created for you
--  this time.
--
-- ============================================================

-- Table 1: authors
--   id     auto-numbered INT, primary key
--   name   VARCHAR(100), required

-- your code here


-- Table 2: books
--   id            auto-numbered INT, primary key
--   isbn          exactly 13 characters, required, no two books share one
--   title         VARCHAR(200), required
--   author_id     INT, required, foreign key to authors(id); an author
--                 who still has books cannot be deleted
--   price         DECIMAL(8,2), required, 0 when not given
--   published_on  DATE, optional

-- your code here


-- Table 3: members
--   id         auto-numbered INT, primary key
--   email      VARCHAR(255), required, unique
--   name       VARCHAR(100), required
--   joined_at  TIMESTAMP, required, the current time when not given

-- your code here


-- Table 4: loans
--   book_id      INT, foreign key to books(id)
--   member_id    INT, foreign key to members(id)
--   borrowed_on  DATE, required
--   due_on       DATE, required
--   returned_on  DATE, optional (NULL while the book is still out)
--
--   The primary key is (book_id, member_id, borrowed_on): the same member
--   may borrow the same book again on another day, but not twice on one
--   day. When a book or a member is deleted, their loans go too.
--   The overdue report runs every night: put an index named
--   `idx_loans_due_on` on due_on.

-- your code here
