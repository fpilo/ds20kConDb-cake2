<?php
	header('Content-type: text/csv');
    //header('Content-length: ' . $file['DbFile']['size']); // some people reported problems with this line (see the comments), commenting out this line helped in those cases
	header('Content-Disposition: attachment; filename="test.csv"');
    echo $data;
	exit;
?>