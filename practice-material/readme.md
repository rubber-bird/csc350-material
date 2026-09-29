<div align="center">

# CSC 350 - Practice material

</div>

Two sets of self-checking exercises. Each is a folder of files full of small functions with `// your code here` inside, plus tests that turn green as you fill them in. Do them in this order, alongside the matching [lecture notes](../lecture-notes/readme.md).

| # | Folder | Exercises | Needs | Start here |
|:---:|---|:---:|---|---|
| 1 | [javascript/](javascript/README.md) | 16 | a browser and internet | open `javascript/index.html` |
| 2 | [php/](php/README.md) | 8 | XAMPP with Apache running (see below) | open http://localhost/php-fundamentals/ |

Each folder's own README explains its exercises, how to read a failing test, and what's under the hood. Both use the same test vocabulary (`describe`, `it`, `expect`), so once you've read one set of results you can read the other.

## Running the PHP exercises in XAMPP

PHP runs on a web server, so unlike the JavaScript exercises it can't be opened as a plain file. With XAMPP installed and **Apache** started from its control panel, Apache serves whatever is inside its `htdocs` folder:

| System | `htdocs` is at |
|---|---|
| Windows | `C:\xampp\htdocs` |
| macOS | `/Applications/XAMPP/htdocs` |
| Linux | `/opt/lampp/htdocs` |

1. Drag the `php` folder from this repository into `htdocs`.
2. Rename the copy to `php-fundamentals`, the name the PHP README and its runner expect:

   ```
   C:\xampp\htdocs\php-fundamentals\              (Windows)
   /Applications/XAMPP/htdocs/php-fundamentals/   (macOS)
   ```

3. Open http://localhost/php-fundamentals/ in a browser.

Every module shows red failing tests, and your job is to make them green. Edit the files in `exercises/`, save, and refresh the page.

If you keep the folder named `php`, open http://localhost/php/ instead. Anything else you put in `htdocs` works the same way: `htdocs/club/index.php` is http://localhost/club/index.php, which is how the term project will run too.
 