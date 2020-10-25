# Building A Simple Messenger Chatbot for Your Online Business
---
###### Build a messenger chatbot using basic php knowledge, use carousels with buttons to show customers products of your business on queries from customers.


If you own an online business you definitely understand how tiresome and repetitive it is to reply to the same questions from your customers. Furthermore late replies could potentially lead to losing a customer.
How would you feel if a chatbot could automatically reply to all your customer’s queries and show all available products, that too instantly. You’ll be building a chatbot just like that here .A live demo of the chatbot can be seen [here](https://www.google.com).

//IMAGE 

In this tutorial for beginners with no prior experience working with Messenger Platform  we will build a simple messenger chatbot from scratch that can automatically reply to customer’s messages,FAQs and show a catalogue of available items for sale . We will be building the chatbot from scratch using basic knowledge of [php](https://www.php.net/manual/en/intro-whatis.php) programming language.

#### Prerequisites:
* Basic knowledge about php programming language like variables, conditionals, loops, arrays etc
*  Php installed in your device
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

// IMAGE

## Step 2 : Connecting App to the Page
Now that we have successfully created our app we will connect the app to our business page.
   * From the app page find and click on *Set Up* for Messenger .You’ll be redirected to the Messenger Platform.
   *  Find the Access Tokens section and Click on *Add or Remove Pages*, select your page and click *Done* . 
   *  Once done you’ll see your page connected to the app.
   *  Now we must generate a unique token that will be used by our php script to send API requests .
   *  Click on *Generate Token*, copy the token generated and **save it** for later.

//IMAGE

## Step 3 : Setting up Webhooks
To receive messages and other events sent by Messenger users, the app should enable webhooks integration.The webhook is where the php script is, from where we'll send and receive messages. The webhooks in our case will be our php script . I’m using a free hosting service provider [000webhost]([www.000webhoost.com](https://www.000webhost.com/)) to upload my php script for this tutorial, you can use any hosting service provider with SSL certificate as **Facebook doesn’t allow http**.
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
   *  Verify Token will be 'myBusiness_token' .

//Image

   *  Subscribe to certain Webhooks events.Click on *Add Subscriptions* and select *messages* and *messages_postbacks*.

//IMAGE

The php script upto this step would look like [this](link)
And with that we’re done with creating our app, connecting it to our page and setting up webhooks. In the next steps we will get our hands dirty and start coding. 

## Step 4 : Receiving and Processing Webhooks Events
As we’ve successfully done setting everything up and subscribing to webhook events we’ll receive events from  Messenger Platform as POST requests. These events are sent when a variety of interactions or events happen including when a person sends a message. We will receive these events in our php script and process them.
The webhoo

* Open the myBusiness.php file in any editor and add the following lines of code at the end 

```php
$raw_input = file_get_contents('php://input'); // Receive POST request events from Messenger Platform in json format and store it in $raw_input variable
$input = json_decode($raw_input, true); // Process the json and decode it to create a multidimensional associative array
$senderId = $input['entry'][0]['messaging'][0]['sender']['id']; //Unique sender id for the user interacting with your page
$messageText = $input['entry'][0]['messaging'][0]['message']['text']; // Text Message sent by a user to the page
$postback = $input['entry'][0]['messaging'][0]['postback']['payload']; // Postback received when user clicks on a button
```

Variable | Use
------------ | -------------
$raw_input | Receive POST request webhook events from Messenger Platform in [JSON](https://www.json.org/json-en.html) format
$input | [Process](https://www.php.net/manual/en/function.json-decode) the JSON and decode it to create a multidimensional associative [array](https://www.php.net/manual/en/language.types.array.php) for ease of use
$senderId | [PSID](https://developers.facebook.com/docs/messenger-platform/getting-started/quick-start#what-is-a-psid-) of the user for whom the webhook event is received.With each event a sender ID unique for the user interacting with the page is received
$messageText | Text Message sent by a user to the page with the specific **$senderID**
$postback   | Postback string received when user clicks on a button

The php script upto this step would look like [this](link)



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
Php allows the script to make http requests. We’ll be using it send post request to the Send API. The variables created and their use
Variable | Use
------------ | -------------
$accessToken | The generated *Access Token*
$requestURI | [Request URI](https://developers.facebook.com/docs/messenger-platform/reference/send-api#request)
$ch| The curl connection
$messageText | Text Message sent by a user to the page with the specific **$senderID**
$response   | An array with value of the request
