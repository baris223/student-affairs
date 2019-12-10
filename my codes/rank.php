<?php
require_once( "sparqllib.php" );

// SPARQL End-point 
$db = sparql_connect( "http://localhost:3030/yasli/sparql" );

if( !$db ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }

// Define name space for your ontology
sparql_ns( "univ","http://www.semanticweb.org/barısyaslı/ontologies/2019/9/untitled-ontology-12#" );

//SPARQL Query 
$sparql = "SELECT       (str(?level_degree) as ?level_rank)  (COUNT(?mo_name) as ?total_modules) ?event
	    
				 
				WHERE  {	              
                               
                                 ?x a univ:module.
                                 ?x  univ:modul_name ?mo_name.
                       		 ?x univ:has_level ?ld.
				 ?ld univ:level_degree ?level_degree.
 BIND(IF(?level_rank=6, \"HIGH GRADE MODULE\",\":)\") as ?event)
                                
";
$sparql .= (isset($_GET["id"]) && !empty($_GET["id"]))?"FILTER(STR(?std_ID)=\"".$_GET["id"]."\")":"";
$sparql .=	 "}";

//echo $sparql;

$result = sparql_query( $sparql ); 
if( !$result ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields = sparql_field_array( $result );
?>

<html>
<head>
<title>Example Code</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<h3 class='h3 mb-3 mt-4 font-weight-normal text-center'>Search postgraduate or undergraduate</h3>

<div class='col-6 offset-3 text-center'><form method="GET"><input type="text" name="id" placeholder="state" /><input type="submit" value="Search" /></form></div>
<h4 class='h4 mb-3 mt-4 font-weight-normal text-center'>Number of rows: <?php echo sparql_num_rows( $result ); ?> results.</h4>
<div class='col-6 offset-3'><table class='table table-bordered table-hover'>
<thead class='thead-light'><tr>
	<?php
		foreach( $fields as $field )
		{
			print "<th>$field</th>";
		}
		print "</tr></thead><tbody>";
		while( $row = sparql_fetch_array( $result ) )
		{
			print "<tr>";
			foreach( $fields as $field )
			{
				print "<td>$row[$field]</td>";
			}
			print "</tr>";
		}
		print "";
	?>
</tbody></table></div>
</body>
</html>
 