## Lession2 - Internship PHP TEST

Test internship

## Usage

1. Clone the repository and navigate to the `htdocs` directory in XAMPP.
   If you don't have XAMPP, you can download it from [here](https://www.apachefriends.org/), and continue.

```bash
git clone https://github.com/thaolaptrinh/Lession2.git
```

1. Installation is super-easy via [Composer](https://getcomposer.org/):

```bash
$ composer update
```

Make sure to update your `composer.json` file.

3. Download the SQL dump [database.sql](https://github.com/thaolaptrinh/Lession2/blob/test/database.sql)

Create a database in phpMyAdmin and import the SQL dump.

After that, edit the `.env` file:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=root
DB_PASSWORD=
```

Make the necessary changes to the .env file according to your database configuration.
