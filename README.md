  install.php		installer -- creates DB (if missing) then runs init.sql
  loginstatus.php	session helpers -- isUserLoggedIn(), getUsername(), etc
  common.php		helper functions for sanitation and validation
  config.php		DB credentials and PDO options
/data
  init.sql		SQL that builds tables and inserts sample data
/src
  DBconnect.php		central PDO connection (requires config.php)
  session.php		simple OOP class to kill / forget the session
