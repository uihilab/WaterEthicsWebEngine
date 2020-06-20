<?php 

if ($_SESSION["connection"] == True) {
	$html = 'nothing to see here...';
} else {
	# code...
	$html  = '<h2>So Sorry, No Database Connection!</h2>';
	$html .= '<p>This implementation of the framework <b>does not have a database connection!</b></p>';
	$html .= '<p>If there <i>were</i> a database connection, then that would allow you to see how your responses to these questions compared against others. Take a look below at two features of an example results page.</p>';
	$html .= '<div id="sample-results" style="border: 4px solid black; border-radius: 15px">';
	$html .= '<h3>Sample Results</h3>';
	$html .= '<img src="../app/images/results-sample-table.svg">';
	$html .= '<img src="../app/images/results-sample-slider.svg">';
	$html .= '</div>';
}

echo json_encode($html);

?>