# Contributing Guide
Since this is an open source project, any contributing is welcome !!
Although contributing is encouraged, it's better to discuss what you want to contribute in the [Tickets](https://sourceforge.net/p/iqac/tickets/) first.
# Environment Setup
1. Create an Mysql user and Database
2. Download or clone the [repostiory](https://sourceforge.net/p/iqac/code/)
3. Import `db_strucutre.sql` to your MySQL.
4. edit the follownig code and save to `config.php` in the root
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

// Menu for each user role (optional to configure)
$MENUS = array(
	'JUDGE_DEFAULT' => array(
		'Contests' => '/',
		'Create Contest' => '/editContest',
		'Logout' => '/auth' 
	),
	'JUDGE_CONTEST' => array(
		'ScoreBoard' => '/',
		'Submissions' => '/submissions',
		'Questions' => '/questions',
		'Edit' => '/editContest',
		'Exit' => '/?contest=exit',
		'Logout' => '/auth'
	),
	'PARTICIPANT' => array(
		'ScoreBoard' => '/',
		'Questions' => '/questions',
		'Submissions' => '/submissions',
		'Logout' => '/auth'
	)
);
?>
```
5. Remove 3 rows below the comment `# Disable hotlinking`
6. Upload to your web server
