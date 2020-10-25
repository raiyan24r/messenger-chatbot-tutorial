<?php

$hubVerifyToken = 'myBusiness_token';

if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}

$raw_input = file_get_contents('php://input'); // Receive POST request events from Messenger Platform in json format and store it in $raw_input variable
$input = json_decode($raw_input, true); // Process the json and decode it to create a multidimensional associative array
$senderId = $input['entry'][0]['messaging'][0]['sender']['id']; //Unique sender id for the user interacting with your page
$messageText = $input['entry'][0]['messaging'][0]['message']['text']; // Text Message sent by a user to the page
$postback = $input['entry'][0]['messaging'][0]['postback']['payload']; // Postback received when user clicks on a button



if (isset($messageText)) {
    $response =
      [
        'recipient' => ['id' => $senderId], // id of the user we want to send a message to
        'message' => ['text' => 'Hello, I am a chatbot'] // The message we actually want to send 
      ];
  }
  
$accessToken = "EAAKRP1f5IKcBAFVN........"; //Replace with your generated token
$requestURI = 'https://graph.facebook.com/v8.0/me/messages?access_token='; //Request URI
  
$ch = curl_init($requestURI . $accessToken); //Initiating curl with the link to send the request
curl_setopt($ch, CURLOPT_POST, 1); //Set option for transfer
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response)); //set option and parsing the value array to JSON format
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // setting option for transfer
if (isset($input)) { //Checking if there is any interaction from any user on the page
  curl_exec($ch); // Sending the request only if a event occured
}
curl_close($ch); 
