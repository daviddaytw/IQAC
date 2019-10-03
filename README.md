IQAC is a instant contest platform for real-time answering. A little bit similar to Kahoot.

# Features
- Clean Design
- Interactive
- Real-Time Contest
- Multi-Judges

# How to use

The judges must create a account. One of the judges could create a contest and add other judges in.
*Judges should edit questions before contest.*

The participants does not need to create account, just sign up to contest by id about 1~5 minutes before start.

In the contest, judges need to score (and comment) the submissions of participants. The rank would be real-time update in the scoreboard.

# Contributing Guide
Since this is an open source project, any contributing is welcome !!
Although contributing is encouraged, it's better to discuss what you want to contribute in the issue first.

# Environment Setup
1. Create an Mysql user and Database.
2. Download or clone the repository.
3. Import `db_strucutre.sql` to your MySQL.
4. edit the follownig code and save to `config.php` in the root.
```php
<?
ini_set('display_errors',1);
ini_set('default_charset', 'UTF-8');

// Database Setup
// Change the following to setup for database
$db = mysqli_connect('DB HOST','DB USER','DB PASSWORD','DB NAME');
if (!$db) {
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    die("Error: Unable to connect to MySQL.");
}
?>
```
5. Upload to your web server.
