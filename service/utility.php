<?php 

function base_url()
{
  // Determine the protocol
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";

  // Get the host name
  $host = $_SERVER['HTTP_HOST'];

  // Get the base directory
  $baseDir = dirname($_SERVER['SCRIPT_NAME']);
  $baseDir = explode('/', $baseDir);

  // Combine to form the base URL
  $baseUrl = $protocol . $host . "/" . $baseDir[1];

  // Return the base URL
 return rtrim($baseUrl, '/'); // Remove trailing slash if necessary
}