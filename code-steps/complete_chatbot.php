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


  $query = array('products', 'delivery', 'price', 'Price', 'Available', 'available', 'Hi', 'hi', 'Hello', 'hello', 'product details', 'details');

  foreach ($query as $string) {
    if (strpos($messageText, $string) !== false) {

      $response = [
        'recipient' => ['id' => $senderId],
        'message' => [
          "attachment" => [
            "type" => "template",         // Attachment type will be template
            "payload" => [
              "template_type" => "generic",    // template type will be generic
              "image_aspect_ratio" => "square",     // Image attached will be square
              "elements" => [
                [
                  "title" => "GoGlow Face Mask",
                  "image_url" => "https://www.orderbot.online/botImages/rehmansbeautyORDERS/7card1.jpg",
                  "subtitle" => "8.99$",
                  "buttons" => [
                    [
                      "type" => "postback",
                      "title" => "More Details",
                      "payload" => "product1_payload"
                    ],
                  ]
                ],
                [
                  "title" => "HoneyBee Face Pack",
                  "image_url" => "https://www.orderbot.online/botImages/rehmansbeautyORDERS/7card2.jpg",
                  "subtitle" => "9.99$",
                  "buttons" => [
                    [
                      "type" => "postback",
                      "title" => "More Details",
                      "payload" => "product2_payload"
                    ],
                  ],
                ],
                [
                  "title" => "Hairgician",
                  "image_url" => "https://www.orderbot.online/botImages/rehmansbeautyORDERS/7card3.jpg",
                  "subtitle" => "6.99$",
                  "buttons" => [
                    [
                      "type" => "postback",
                      "title" => "More Details",
                      "payload" => "product3_payload"
                    ],
                  ],
                ],



              ],
            ],
          ],
        ],
      ];

      break;
    }
  }
} else if ($postback == 'product1_payload') {

  $response =
    [
      'recipient' => ['id' => $senderId],
      'message' => ['text' => "GoGlow face mask ::\n🌸 Removes acne marks. 🌸 Removes dullness and dead skin from the skin. 🌸 Brightens the skin and provides natural glow. 🌸 Removes stubborn sun tan and uneven skin tone. 🌸 Removes dirt and impurities. 🌸 Improves skin texture and condition. 🌸 Adds moisture to the skin and does not over dry. 🌸 Removes white head. 🌸 Works as a gentle exfoliator. 🌸 Makes the skin soft and smooth. 🌸Removes Hyperpigmentation and dark patches.\nSize : 150gm\nShell life : 6 months after opening."]
    ];
} else if ($postback == 'product2_payload') {

  $response =
    [
      'recipient' => ['id' => $senderId],
      'message' => ['text' => "HoneyBee Face Mask\n🌻Removes acne marks. 
        🌻 Removes dullness and dead skin from the skin. 
        🌻 Brightens the skin and provides natural glow. 
        🌻 Removes stubborn sun tan and uneven skin tone. 
        🌻 Removes dirt and impurities. 
        🌻 Improves skin texture and condition. 
        🌻 Works as a gentle exfoliator. 
        🌻 Makes the skin soft and smooth. 
        🌻Removes Hyperpigmentation and dark patches. 
        🌻Removes stubborn sun tan from any part of your body.
        Size : 150gm
        Expiry date : 6 months after opening jar. "]
    ];
} else if ($postback == 'product3_payload') {


  $response =
    [
      'recipient' => ['id' => $senderId],
      'message' => ['text' => "Herbal Hair Oil aka Hairgician - for all hair type.\n
        🍃 Hydration to your hair.
        🍃 It will nourish your hair. 
        🍃 Prevent hair fall. 
        🍃 Improvement in hair growth.
        🍃Reduces Risk of Lice. 
        🍃Prevents Dandruff. 
        🍃Strengthens Roots. 
        🍃Protects your scalp from being too oily.
        🍃 Deep conditions hair. 
        🍃 Makes hair more shiny and adds volume. 
        🍃 Treats chemically damaged hair. 
        🍃 Protects hair from ultra violet ray that emits from the sun. 
        🍃 Hair 2x time stronger. 
        🍃 Prevents breakage"]
    ];
}

$accessToken = "EAAKRP1f5IKcBAFVN........"; //Replace with your generated token
$requestURI = 'https://graph.facebook.com/v8.0/me/messages?access_token='; //Request URI


$ch = curl_init($requestURI . $accessToken); //Initiating curl with the link to send the request
curl_setopt($ch, CURLOPT_POST, 1); //Set option for transfer
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response)); //set option and parsing the value array to JSON format
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // setting option for transfer
curl_exec($ch); // Sending the request
curl_close($ch); // Closing the curl connection
