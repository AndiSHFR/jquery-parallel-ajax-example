<?php //
/*!
 * This php script servs as a dummy ajax backend.
 * The request will be delayed accoding to the requested time.
 * 
 * @copyright by Andreas Schaefer <asc@schaefer-it.net>
 * @license This function is in the public domain. Do what you want with it, no strings attached.
 */

// Define a data array we ant to return
$jsonData = [
  // Get the callers identification from the url parameters
  'ident' => isset($_GET['i']) ? $_GET['i'] : '??',
  // Get the delay from the url parameters
  'delay' => max(1, intval(isset($_GET['d']) ? $_GET['d'] : 1)),
  // Get the time the request started
  'start' => $_SERVER["REQUEST_TIME_FLOAT"]
];

// This will help to release a lock on the session file, if session handling is enabled.
// SO questions regarding php not runnin parallel requests suggested to do so.
session_write_close();

// Now sleep the number of requested seconds
sleep($jsonData['delay']);

// Add some statistics to the result
$jsonData['stop'] = microtime(true);
$jsonData['elapsed'] = $jsonData['stop'] - $jsonData['start']; 
$jsonData['started'] = date('H:i:s', $jsonData['start'] );
$jsonData['finished'] = date('H:i:s', $jsonData['stop'] );

// Set some headers to prevent caching of the result
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");            // HTTP/1.1
header("Pragma: no-cache");                                    // HTTP/1.0
// We will return JSON
header('Content-Type: application/json');
// Convert the result into a JSON string
die(json_encode($jsonData, JSON_PRETTY_PRINT));
?>