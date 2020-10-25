# How to build a Messenger Chatbot for Your Online Business Using PHP
---
###### Build a messenger chatbot using basic php knowledge, use carousels with buttons to show customers products of your business on queries from customers.

//IMAGE 

If you own an online business you definitely understand how tiresome and repetitive it is to reply to the same questions from your customers. Furthermore late replies could potentially lead to losing a customer.
How would you feel if a chatbot could automatically reply to all your customer’s queries and show all available products, that too instantly. You’ll be building a chatbot just like that here .A live demo of the chatbot can be seen [here](m.me/110715410820927).



In this tutorial for beginners with no prior experience working with Messenger Platform  we will build a simple messenger chatbot from scratch that can automatically reply to customer’s messages,FAQs and show a catalogue of available items for sale . We will be building the chatbot from scratch using basic knowledge of [php](https://www.php.net/manual/en/intro-whatis.php) programming language.

#### Prerequisites:
* Basic knowledge about php programming language like variables, conditionals, loops, arrays etc
*  Any running editor of your choice.
*  A running facebook page





//IMAGE


### Steps For Creating The Chatbot :
 1. Creating a Facebook App
 2. Connecting app to the page
 3. Connecting app to webhook
 4. Receiving and Processing Webhook Events
 5. Sending different Messages
 6. Setting Up Final Chatbot
 7. Submitting App for Review

---

## Step 1 : Creating a Facebook App
   * Head on over to https://developers.facebook.com/apps/ and click on *Create App*.
   *  Select *Manage Business Integrations*  and click Continue.
   *  Any running editor of your choice.
   *  Give your app a suitable display name,contact email and select *Yourself or your own business* as App Purpose and click Create App.
  
![1](https://user-images.githubusercontent.com/65073451/97110282-927d7100-1702-11eb-9de9-372839987bce.jpg)



## Step 2 : Connecting App to the Page
Now that we have successfully created our app we will connect the app to our business page.
   * From the app page find and click on *Set Up* for Messenger .You’ll be redirected to the Messenger Platform.
   *  Find the Access Tokens section and Click on *Add or Remove Pages*, select your page and click *Done* . 


   ![3_](https://user-images.githubusercontent.com/65073451/97110413-6ca49c00-1703-11eb-97e0-e318c32e6075.png)
   *  Once done you’ll see your page connected to the app.
   *  Now we must generate a unique token that will be used by our php script to send API requests .


 ![4](https://user-images.githubusercontent.com/65073451/97110442-80500280-1703-11eb-82ef-69c526379521.png)
   *  Click on *Generate Token*, copy the token generated and save it for later.


![8](https://user-images.githubusercontent.com/65073451/97110535-0409ef00-1704-11eb-982c-037146c8bab2.png)



## Step 3 : Setting up Webhooks
To receive messages and other events sent by Messenger users, the app should enable webhooks integration.The webhook is where the php script is, from where we'll send and receive messages. The webhooks in our case will be our php script . I’m using a free hosting service provider [000webhost](https://www.000webhost.com/) to upload my php script for this tutorial, you can use any hosting service provider with SSL certificate as **Facebook doesn’t allow http**.
   * We’ll create a file in any editor and call it myBusiness_bot.php and write the following code .
```php
<?php

$hubVerifyToken = 'myBusiness_token';

if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}
```
   *  Once done we’ll save the file and upload it to our free server. 
   *  Now we’ll return to the app page and click on *Edit Callback URL* in the Webhooks section and fillup the form.
   *  Callback URL will be the public URL of our **myBusiness.php** file. 
   *  Verify Token will be 'myBusiness_token'  .

![5](https://user-images.githubusercontent.com/65073451/97110743-1c2e3e00-1705-11eb-8c46-b60bc4229fdd.PNG)

   *  Subscribe to certain Webhooks events.Click on *Add Subscriptions* and select *messages* and *messages_postbacks*.

![6](https://user-images.githubusercontent.com/65073451/97110770-43850b00-1705-11eb-8ab0-1da14a1bb7aa.PNG)

**The php script upto this step would look like [this](https://github.com/raiyan24r/messenger-chatbot-tutorial/blob/main/code-steps/file_one.php)**

And with that we’re done with creating our app, connecting it to our page and setting up webhooks. In the next steps we will get our hands dirty and start coding. 

## Step 4 : Receiving and Processing Webhooks Events
As we’ve successfully done setting everything up and subscribing to webhook events we’ll receive events from  Messenger Platform as POST requests. These events are sent when a variety of interactions or events happen including when a person sends a message. We will receive these events in our php script and process them.


* Open the myBusiness.php file in any editor and add the following lines of code at the end 

```php
$raw_input = file_get_contents('php://input'); // Receive POST request events from Messenger Platform in json format and store it in $raw_input variable
$input = json_decode($raw_input, true); // Process the json and decode it to create a multidimensional associative array
$senderId = $input['entry'][0]['messaging'][0]['sender']['id']; //Unique sender id for the user interacting with your page
$messageText = $input['entry'][0]['messaging'][0]['message']['text']; // Text Message sent by a user to the page
$postback = $input['entry'][0]['messaging'][0]['postback']['payload']; // Postback received when user clicks on a button
```
###### The variables and their use
Variable | Use
------------ | -------------
`$raw_input` | Receive POST request webhook events from Messenger Platform in [JSON](https://www.json.org/json-en.html) format
`$input` | [Process](https://www.php.net/manual/en/function.json-decode) the JSON and decode it to create a multidimensional associative [array](https://www.php.net/manual/en/language.types.array.php) for ease of use
`$senderId` | [PSID](https://developers.facebook.com/docs/messenger-platform/getting-started/quick-start#what-is-a-psid-) of the user for whom the webhook event is received.With each event a sender ID unique for the user interacting with the page is received
`$messageText` | Text Message sent by a user to the page with the specific **$senderID**
`$postback`   | Postback string received when user clicks on a button

**Your php script upto this step would look like [this](https://github.com/raiyan24r/messenger-chatbot-tutorial/blob/main/code-steps/file_two.php)**



## Step 5 : Replying to Message

//IMAGE

All messages,templates or attachments are sent using the [Send API](https://developers.facebook.com/docs/messenger-platform/reference/send-api). We’ll be sending all types of messages,templates or attachments by making POST requests using [cURL](https://www.php.net/manual/en/book.curl.php). We’ll be using php multidimensional arrays to format the structured and unstructured messages and then parse them into JSON before sending the request for our benefit.

We need the *Access Token* we generated and saved in **STEP 2** . If you’ve lost it it’s fine, go to your app dashboard following STEP 2 and generate another token and save it. Only difference is you don’t have to add your page to the app again . 


#### cURL Request Format


```php
$accessToken = "EAAHMnZB0dW1oBAIIOMdh6vENe........";
$requestURI = 'https://graph.facebook.com/v8.0/me/messages?access_token='; //Request URI

$ch = curl_init($requestURI . $accessToken); //Initiating curl with the link to send the request
curl_setopt($ch, CURLOPT_POST, 1); //Set option for transfer
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response)); //set option and parsing the value array to JSON format
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // setting option for transfer
curl_exec($ch); // Sending the request
curl_close($ch); // Closing the curl connection
```
Php allows the script to make http requests. We’ll be using it send post request to the Send API.
###### The variables created and their use
Variable | Use
------------ | -------------
`$accessToken` | The generated *Access Token*
`$requestURI` | [Request URI](https://developers.facebook.com/docs/messenger-platform/reference/send-api#request)
`$ch`| The curl connection
`$messageText` | Text Message sent by a user to the page with the specific **$senderID**
`$response`   | An array with value of the request

#### Sending Text Messages
//IMAGE

An example request for sending a simple text message in the officicial documents can be found [here](https://developers.facebook.com/docs/messenger-platform/send-messages/#sending_text).
For sending text messages an example array code format :
```php
$response =
  [

    'recipient' => ['id' => '<PSID>'], // id of the user we want to send a message to
    'message' => ['text' => 'hello, world!'] // The message to be sent

  ];

```
###### The variable/key created and their use
Variable/Key | Use
------------ | -------------
$response | Array 
recipient | An array with the PSID of the receiver
recipient. id | Receiver PSID
message | An array with the text message
message.text | The text message that will be sent to the user 

Now let’s try sending an actual message from our chatbot.Open your php file and add the following lines of code at the end .
```php
if (isset($messageText)) {
  $response =
    [
      'recipient' => ['id' => $senderId], // id of the user we want to send a message to
      'message' => ['text' => 'Hello, I am a chatbot'] // The message we actually want to send 
    ];
}


$accessToken = "EAAHMnZB0dW1oBAIIOMdh6vENeCFroWREHarblwBUW0vMMGgvMJbypuZCyZCHX62hykMdicR6MsTp4GpRai7zOMFfgoZAG4fZCAZBvcjCeZCUA8Qtu8As1gQzVEB5F8ssYjnSPUZCJ0uHxj0mniDbYHe4kB6mffaFTs5CEpR6ZBO5AVBavK1fFeyID";
$requestURI = 'https://graph.facebook.com/v8.0/me/messages?access_token='; //Request URI

$ch = curl_init($requestURI . $accessToken); //Initiating curl with the link to send the request
curl_setopt($ch, CURLOPT_POST, 1); //Set option for transfer
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response)); //set option and parsing the value array to JSON format
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // setting option for transfer


if (isset($input)) { //Checking if there is any interaction from any user on the page
  curl_exec($ch); // Sending the request only if a event occured
}
curl_close($ch); // Closing the curl connection
```
**The php script will be [this](https://github.com/raiyan24r/messenger-chatbot-tutorial/blob/main/code-steps/file_three.php)**

Save the file to the same location.Once done try sending a message to your page. You should get an automated response saying *'Hello, I am a chatbot'* . (**Important** ! : Be sure to change the access token and hub verify token)




Congratulations ! You’ve successfully set up your first chatbot that automatically replies to your messages.

###### Code Explanation

* Line[] to Line [] : We set up webhooks and process events following Step 3 and Step 4
* Line[] : Conditional statement checks if a message has been sent by the user to the page using the predefined php __function ```isset()```__
* Line[] to Line[] : If the condition is satisfied, then ```$response``` array for text message format is created 
* Line[] to Line[] : ```$response``` is parsed into JSON format and sent as a request using cURL

 Now we’ll see how to send messages with user clickable buttons.

### Sending Button Template

//IMAGE


We’ll now see how to send user clickable buttons with a text message. The detailed documentation can be found [here](https://developers.facebook.com/docs/messenger-platform/reference/templates/button). The array format for sending button template is given in the code snippet below :

```php
$response =  [
  'recipient' => ['id' => '<PSID>'],     // Sender ID of the user the message is to be sent
  'message' => [
    "attachment" => [
      "type" => "template",               // Defining attachment type
      "payload" => [
        "template_type" => "button",      // Defining the template type
        "text" => "Click on the button",  // Text message that will be sent along with the buttons

        "buttons" => [                   // array of buttons that can be a maximum of 3

          [                                  // The First Button
            "type" => "postback",             // button type: postback
            "title" => "Say Hello",             //The text that will be defined on the button
            "payload" => "button1_payload",  // Postback that will be send back as an event when a user click on this specific button
          ],
          //           [ .... ],            
          //           [ .... ],

        ],
      ],
    ],
  ]
];
```
###### Properties and their use
message.attachment.payload | Use
------------ | -------------
text | The text to be displayed along with the button
buttons | An array of maximum of 3 [buttons](https://developers.facebook.com/docs/messenger-platform/reference/buttons/postback)
text | The text to be displayed along with the button

###### Postback Button [Properties](https://developers.facebook.com/docs/messenger-platform/reference/buttons/postback#properties)
Property | Use
------------ | -------------
type | Must be postaback
title | The text that will be displayed on the button
payload | A string that will be sent back to your webhook when a user clicks on this button .This allows the script to take decisions based on the button pressed

Now we will see the button template in action. From the php file erase every line of code created after setting up webhooks. Copy and paste the following code at the end and save it to the server.

```php
if (isset($messageText)) {
  $response =  [
    'recipient' => ['id' => $senderId],
    'message' => [
      "attachment" => [
        "type" => "template",
        "payload" => [
          "template_type" => "button",
          "text" => "Click on the button",

          "buttons" => [

            [
              "type" => "postback",
              "title" => "Hello !",
              "payload" => "button1_payload",
            ],

          ],
        ],
      ],
    ]
  ];
} else if ($postback == 'button1_payload') {

  $response =
    [
      'recipient' => ['id' => $senderId],
      'message' => ['text' => 'Hello, I am a chatbot']
    ];
}
```
**Alternatively, you can rewrite the entire script from [here](https://github.com/raiyan24r/messenger-chatbot-tutorial/blob/main/code-steps/file_four.php)** .
Now try sending a text message to the page. The bot should reply with a text message attached to a button. Now click on the button and the chatbot will reply with 'Hello, I am a chatbot'. Amazing! Isn’t it ?

###### Code Explanation

* Line[] to Line [] : Script checks if a message is sent.
* Line[] : If message is sent then a `$response` array is created with button template with a single button
* Line[] to Line[] : The `$response` array sent using cURL to show the user a button 
* Line[] to Line[] : `$response` is parsed into JSON format and sent as a request using cURL

Alright then we are now going to implement one more template which will actually display the products/services offered by our business in a horizontal scrollable carousel with images.

### Sending Generic Template
The [generic template](https://developers.facebook.com/docs/messenger-platform/send-messages/template/generic) is one of my favourites because it is one of the most useful and interactive templates offered to us by the Messenger Platform. It is a structured message that contains an image,title,subtitle and even buttons all together.
We’ll be sending a list of this template as a horizontal scrollable carousel. How cool is that !?
he details and properties of the generic template with an example can be found [here](https://developers.facebook.com/docs/messenger-platform/reference/templates/generic). An example array format that we will be using to send a list of products and their details in a horizontal scrollable carousel is given below: 
```php

//IMAGE


$response = [
  'recipient' => ['id' => $senderId],
  'message' => [
    "attachment" => [
      "type" => "template",         // Attachment type will be template
      "payload" => [
        "template_type" => "generic",    // template type will be generic
        "image_aspect_ratio" => "square",     // Image attached will be square
        "elements" => [     //Array of Generic Templates. Maximum 10
          [
            "title" => "<Product 1 Title>",// The title of the Generic Template
            "image_url" => "<link to your product 1 image>", //The image URL to show
            "subtitle" => "<Product 1 Price>", // A subtitle that will be displayed
            "buttons" => [ //Array of buttons
              [
                "type" => "postback",
                "title" => "More Details",
                "payload" => "product1_payload"
              ],
            ]
          ],
          [
            "title" => "<Product 2 Title>",
            "image_url" => "<link to your product 2 image>",
            "subtitle" => "<Product 2 Price>",
            "buttons" => [
              [
                "type" => "postback",
                "title" => "More Details",
                "payload" => "product2_payload"
              ],
            ],
          ],

          //      [......]
          //      [......]

        ],
      ],
    ],
  ],
];

```

The *elements* property is an array containing a list of generic templates with images,title,subtitle and buttons. A maximum of 10 generic templates can be listed together.

Now that we’ve got the general idea of 3 different types of messages offered by the Messenger Platform we’ll use these to set up our chatbot for online business that can reply to customers automatically to show a catalog of available products.

## Step 6 : Setting Up The Final Chatbot
//IMAGE


The logical flow of the chatbot works in the following way :

1. A person sends a message or starts a conversation with the business facebook page.
2. A potential customer asks for relevant info about the products, price, image etc
3. The chatbot replies with a horizontal carousel of products that includes the product name,image and price and button to show relevant details about the product
4. Person browses the carousel and clicks on a button
5. The chatbot replies with all the details about the product


The completed code is given below, replace the entire script of the myBusiness.php file with this code:
```php
<?php

$hubVerifyToken = 'myBusiness';

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
    if (strpos(strtolower($messageText), strtolower($string)) !== false) {

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
                  "image_url" => "/7card1.jpg",
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
                  "image_url" => "/7card2.jpg",
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
                  "image_url" => "/7card3.jpg",
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
      'message' => ['text' => "GoGlow face mask ::\n🌸 Removes acne marks. 🌸 Removes dullness and dead skin from the skin. 🌸 Brightens the skin and provides natural glow.🌸 Removes white head. 🌸 Works as a gentle exfoliator. 🌸 Makes the skin soft and smooth. 🌸Removes Hyperpigmentation and dark patches.\nSize : 150gm\nShell life : 6 months after opening."]
    ];
} else if ($postback == 'product2_payload') {

  $response =
    [
      'recipient' => ['id' => $senderId],
      'message' => ['text' => "HoneyBee Face Mask\n🌻Removes acne marks. 
        🌻 Removes dullness and dead skin from the skin.
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
        🍃 Reduces Risk of Lice. 
        🍃 Prevents Dandruff. 
        🍃 Strengthens Roots. 
        🍃 Protects your scalp from being too oily."]
    ];
}

$accessToken = "EAAKRP1f5IKcBAFVNNnmfpfBZAZB3Jsg8ZCZA7VYwVt6abdYnYM8kmyq1nbvD4Nr8igZC6mAeakp8W1zUe5Wv3uckJYZC9lNv9suPgXx3shMAufAM2pn1oZAolEbBdXd1eH642TxRye40OkpWe0bqZAbUZChknDZCdktWrfKlYD0uvwbn2MHkXRlGLD";
$requestURI = 'https://graph.facebook.com/v8.0/me/messages?access_token='; //Request URI


$ch = curl_init($requestURI . $accessToken); //Initiating curl with the link to send the request
curl_setopt($ch, CURLOPT_POST, 1); //Set option for transfer
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response)); //set option and parsing the value array to JSON format
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // setting option for transfer
curl_exec($ch); // Sending the request
curl_close($ch); // Closing the curl connection

```

Alternatively, download the [file](https://github.com/raiyan24r/messenger-chatbot-tutorial/blob/main/code-steps/complete_chatbot.php) ,replace the **$accessToken** with your's.

Save the file and upload to the same location on the server. 
Now try sending a message " what are the available products? " . The chatbot should reply with a carousel of products available. Click on any button on the carousel and you’ll get respective details of the product.

//IMAGE
#### Modifying List of Products 
* The list of products in the horizontal catalogue can be modified. Based on your need you can increase (upto 10) or decrease the number of products. 
* The array in this [line](https://github.com/raiyan24r/messenger-chatbot-tutorial/blob/eaeb1937a744f1640327b8f8fddabf50535e1382/code-steps/complete_chatbot.php#L33) can be modified to add or remove more generic templates with product image, name, price etc.
* More conditional statements can be added to based on user action to create a more developed chatbot capable of doing much more. 

The sky is the limit !


###### Code Explanation
* Line [!] to Line [!] :We wrote code to setup webhooks (follow STEP 2)  and receive webhook events (follow STEP 3). 

After that we are going to setup the logic statements using if and if-else conditional statements.
* Line [!] : We write an array $query containing a list of query strings that a customer might text to trigger the chatbot. You can add your own strings in the array
* Line [!] : In the if statement we check if the **$messageText** variable matches any of the strings in the __\$query__ array 
* Line [!] to Line [!] : If the condition is satisfied then we create $response array in the generate template format with 3 elements to display 3 products and their details with image of the product. We specify the product name in title, product price as subtitle and image_url as the link of the product image to display.
* Line [!] : In the if-else statement we check if the $postback string is the same as the payload of button of product 1 in line [!]
* Line [!] : If the condition is satisfied we create a $response array in the format of Text Message with text as the relevant details of product 1
* Line [!] to Line [!] : We similarly check for which button payload the **$postback** string matches with and create a __\$response__ variable the same way
* Line [!] to Line [!] : We create a cURL connection **$ch** and send a request with the value __\$response__ array (parsing into JSON format) created in the conditional statements using the Request URI and __\$accessToken__



Now that you've successfully set up the chatbot that replies to your messages as intended.We must now submit the App for review before we can make it open to the public. 

## Step 7: Submitting your App for the facebook review

//IMAGE

You have to submit the app for review to get certain permissions.
1. First of all, collect a 1024x1024 icon of your app and upload it to **Settings > Basic > App Icon** in the app dashboard. Also complete all the required fields and scroll down to add a platform.

2. Make a screen recording of your chatbot which will be used later for permissions.
3. From the app dashboard select __App Review > Permissions and Features__
4. Search *pages_messaging* and click on the **request** button.
5. Now it’s time to Complete App verification by clicking on the option.
6. Move on  to the Platform Settings section, check the information and in the space provided, write how can the facebook community access your app in order to test it and save
7. Next, click on Requested Permissions and Feature and fill “Tell us how you're using this permission or feature” by giving how you will be using the  Pages_messaging feature.
8. Select your page
9. Give details about step by step function about your app.
10. Now upload the screen recording of the chatbot in action from earlier and click **Submit**


In maximum 5 days, you will get your review and then if your app is approved you can open the chatbot for public

//IMAGES


---
### Congratulations! You’ve set up a fully functional chatbot for your online business which will reply to customer's queries. 

:tada: :sparkles: :white_check_mark:
