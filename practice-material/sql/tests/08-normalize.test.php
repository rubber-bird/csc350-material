<?php
require_once __DIR__ . '/../spec.php';
require_once __DIR__ . '/../db.php';

describe('08 - Normalizing a flat table', function () {
    $errors = db_run_exercise('08-normalize.sql');

    // Shown only when a statement in the file failed. A file that runs
    // cleanly earns nothing by itself; the tasks below decide.
    if (count($errors) > 0) {
        it('every statement in the file runs without an SQL error', function () use ($errors) {
            throw new Exception(implode("\n", $errors));
        });
    }

    describe('customers', function () {
        it('Task 1: one row per email, with the right constraints', function () {
            expect(db_primary_key('customers'))->toEqual(['id']);
            expect(db_nullable('customers', 'email'))->toEqual(false);
            expect(db_has_index_on('customers', ['email'], true))->toEqual(true);
            expect(db_nullable('customers', 'name'))->toEqual(false);
            expect(db_nullable('customers', 'city'))->toEqual(true);
            expect(db_query('SELECT email, name, city FROM customers ORDER BY email'))->toEqual([
                ['email' => 'ada@example.org',   'name' => 'Ada Lovelace', 'city' => 'London'],
                ['email' => 'alan@example.org',  'name' => 'Alan Turing',  'city' => 'Manchester'],
                ['email' => 'grace@example.org', 'name' => 'Grace Hopper', 'city' => null],
            ]);
        });
    });

    describe('products', function () {
        it('Task 2: one row per SKU, carrying the most recent price', function () {
            expect(db_primary_key('products'))->toEqual(['id']);
            expect(db_type('products', 'sku'))->toEqual('char(8)');
            expect(db_has_index_on('products', ['sku'], true))->toEqual(true);
            expect(db_type('products', 'unit_price'))->toEqual('decimal(8,2)');
            expect(db_query('SELECT sku, name, unit_price FROM products ORDER BY sku'))->toEqual([
                ['sku' => 'SKU-0001', 'name' => 'Keyboard', 'unit_price' => '49.00'],
                ['sku' => 'SKU-0002', 'name' => 'Mouse',    'unit_price' => '21.00'],
                ['sku' => 'SKU-0003', 'name' => 'Monitor',  'unit_price' => '189.99'],
                ['sku' => 'SKU-0004', 'name' => 'Cable',    'unit_price' => '4.25'],
            ]);
        }, [
            'SELECT DISTINCT would give the Mouse twice, once per price. Keep only the row from the latest ordered_on for each SKU.',
            'INSERT INTO products (sku, name, unit_price) SELECT DISTINCT product_sku, product_name, unit_price FROM sales_flat s1 WHERE ordered_on = (SELECT MAX(ordered_on) FROM sales_flat s2 WHERE s2.product_sku = s1.product_sku);',
        ]);
    });

    describe('orders', function () {
        it('Task 3: one row per order ref, pointing at the right customer', function () {
            expect(db_primary_key('orders'))->toEqual(['id']);
            expect(db_has_index_on('orders', ['ref'], true))->toEqual(true);
            expect(db_nullable('orders', 'customer_id'))->toEqual(false);
            $fk = db_foreign_key('orders', ['customer_id']);
            expect($fk['ref_table'])->toEqual('customers');
            expect($fk['on_delete'])->toEqual('RESTRICT');
            expect(db_query('SELECT o.ref, c.email, o.ordered_on FROM orders o JOIN customers c ON c.id = o.customer_id ORDER BY o.ref'))->toEqual([
                ['ref' => 'ORD-1001', 'email' => 'ada@example.org',   'ordered_on' => '2024-01-05'],
                ['ref' => 'ORD-1002', 'email' => 'alan@example.org',  'ordered_on' => '2024-01-20'],
                ['ref' => 'ORD-1003', 'email' => 'ada@example.org',   'ordered_on' => '2024-02-01'],
                ['ref' => 'ORD-1004', 'email' => 'grace@example.org', 'ordered_on' => '2024-02-14'],
            ]);
            expect_rejected("DELETE FROM customers WHERE email = 'ada@example.org'", 'Ada still has orders');
        }, [
            'Join sales_flat to customers on email inside the INSERT ... SELECT, and use DISTINCT so each order_ref appears once.',
            'INSERT INTO orders (ref, customer_id, ordered_on) SELECT DISTINCT f.order_ref, c.id, f.ordered_on FROM sales_flat f JOIN customers c ON c.email = f.customer_email;',
        ]);
    });

    describe('order_items', function () {
        it('Task 4: the structure: composite key, two foreign keys, CHECK, generated line_total', function () {
            expect(db_primary_key('order_items'))->toEqual(['order_id', 'product_id']);
            expect(db_foreign_key('order_items', ['order_id'])['on_delete'])->toEqual('CASCADE');
            expect(db_foreign_key('order_items', ['product_id'])['ref_table'])->toEqual('products');
            expect(db_type('order_items', 'quantity'))->toEqual('int unsigned');
            expect(db_type('order_items', 'unit_price'))->toEqual('decimal(8,2)');
            expect(db_type('order_items', 'line_total'))->toEqual('decimal(10,2)');
            expect(db_generated('order_items', 'line_total'))->toEqual('STORED');
            expect_rejected("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (1, 4, 0, 4.25)", 'quantity must be above 0');
            expect_rejected("INSERT INTO order_items (order_id, product_id, quantity, unit_price, line_total) VALUES (3, 1, 1, 49.00, 49.00)", 'line_total is computed and cannot be inserted');
        }, [
            'line_total DECIMAL(10,2) AS (quantity * unit_price) STORED',
            'CHECK (quantity > 0) is its own line, like a FOREIGN KEY.',
        ]);
        it('Task 4: the data: 8 lines whose totals add up to 783.22', function () {
            expect(db_count('order_items'))->toEqual(8);
            expect(db_value('SELECT SUM(line_total) FROM order_items'))->toEqual('783.22');
            expect(db_query("SELECT p.sku, i.quantity, i.unit_price, i.line_total FROM order_items i JOIN orders o ON o.id = i.order_id JOIN products p ON p.id = i.product_id WHERE o.ref = 'ORD-1004' ORDER BY p.sku"))->toEqual([
                ['sku' => 'SKU-0001', 'quantity' => 1, 'unit_price' => '49.00',  'line_total' => '49.00'],
                ['sku' => 'SKU-0002', 'quantity' => 1, 'unit_price' => '21.00',  'line_total' => '21.00'],
                ['sku' => 'SKU-0003', 'quantity' => 2, 'unit_price' => '189.99', 'line_total' => '379.98'],
            ]);
        }, [
            'Join sales_flat to orders (on ref) and to products (on sku) to translate refs and SKUs into ids.',
            'INSERT INTO order_items (order_id, product_id, quantity, unit_price) SELECT o.id, p.id, f.quantity, f.unit_price FROM sales_flat f JOIN orders o ON o.ref = f.order_ref JOIN products p ON p.sku = f.product_sku;',
        ]);
    });

    describe('reporting', function () {
        it('Task 5: `order_totals` is a view with one row per order and the right totals', function () {
            db_require_view('order_totals');
            expect(db_query('SELECT ref, customer_name, ordered_on, total FROM order_totals ORDER BY ref'))->toEqual([
                ['ref' => 'ORD-1001', 'customer_name' => 'Ada Lovelace', 'ordered_on' => '2024-01-05', 'total' => '88.00'],
                ['ref' => 'ORD-1002', 'customer_name' => 'Alan Turing',  'ordered_on' => '2024-01-20', 'total' => '202.74'],
                ['ref' => 'ORD-1003', 'customer_name' => 'Ada Lovelace', 'ordered_on' => '2024-02-01', 'total' => '42.50'],
                ['ref' => 'ORD-1004', 'customer_name' => 'Grace Hopper', 'ordered_on' => '2024-02-14', 'total' => '449.98'],
            ]);
        }, [
            'CREATE VIEW order_totals AS SELECT ... ; a view is a saved query that behaves like a table.',
            'Join orders, customers and order_items, SUM(line_total), GROUP BY the order.',
        ]);
        it('Task 6: idx_orders_customer_ordered_on covers (customer_id, ordered_on)', function () {
            expect(db_index_columns('orders', 'idx_orders_customer_ordered_on'))->toEqual(['customer_id', 'ordered_on']);
        });
        it('Task 7: `sales_flat` is gone', function () {
            expect(db_table_exists('sales_flat'))->toEqual(false);
        });
    });
});

if (PHP_SAPI === 'cli') { spec_summary(); exit($GLOBALS['spec']['failed'] > 0 ? 1 : 0); }
