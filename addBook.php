<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to database
$mysqli = new mysqli("database","username", "password", "databasename");
if($mysqli->connect_errno){
  echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
?>

<!DOCTYPE html>
<html>
<!--Link to Bootstrap stylesheets and own stylesheet, etc.-->
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<!-- Prints table of all books in database -->
<body class="body">
	<div class="container">
	<br/><br/>
	    <div class="well well-sm" id="well">
	    	<table class="table">
		  	<thead>
	            <tr>
	              <th><i id="brand">BookCase Books</i></th>
	        	</tr>
	        </thead>
		    <thead>
		      <tr>
		        <th>Title</th>
		        <th>Author</th>
		        <th>Genre</th>
		        <th>Page Length</th>
		      </tr>
		    </thead>
<!-- php to insert the added book into the database 'book' table and print all books in database to table-->
<?php
if(!($stmt = $mysqli->prepare("INSERT INTO book (aid, gid, title, page_number)
VALUES (?, ?, ?, ?);"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("iisi",$_POST['author'],$_POST['genre'],$_POST['title'], $_POST['numBookPage']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
} else {
	echo "Success!\nAdded " . $stmt->affected_rows . " rows to BookCase 'Book' database.";
}
$stmt->close();

if(!($stmt2 = $mysqli->prepare("SELECT b.title, a.first_name, a.last_name, g.type, b.page_number FROM book b INNER JOIN author a ON b.aid =a.id INNER JOIN genre g ON b.gid=g.id ORDER BY b.title ASC"))){
    echo "Prepare failed: " . $stmt2->errno . " " . $stmt2->error;
}
if (!$stmt2->execute()){
	echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if (!$stmt2->bind_result($title, $name, $last, $gtype, $pages)){
	echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt2->fetch()){
	echo "<tr>\n<td>\n" . $title . "\n</td>\n<td>\n" . $name . " " . $last . "\n</td>\n<td>\n" . $gtype . "\n</td>\n<td>\n" . $pages . "\n</td>\n</tr>";
}
$stmt2->close();
?>
<!-- END php -->
		</table>
		</div>
		<!-- Button to return to Home Page -->
		<a href="http://web.engr.oregonstate.edu/~bennemar/CS340/ProjectBookcase/bookHome.php"><button type="submit" class="btn btn-default" name="backToHome">Back to Home</button></a>
		
	</div>
</body>
</html>
