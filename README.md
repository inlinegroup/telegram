# telegram
telegram bot methods

<h1>with these methods you can: </h1>
<h3><a href='#1'>-- send message or any file</a></h3>
<h3>-- send inline or menu keyboard</h3>
<h3>-- edit or delete any message</h3>
<h3>-- check user is join in channel</h3>
<h3>-- forward message</h3>
<h3>-- upload file without link</h3>
<h3>-- send alert to user</h3>
<h3>-- very easy and safe</h3>
<br>
### Create your first bot

1. Message [`@BotFather`](https://telegram.me/BotFather) with the following text: `/newbot`

   If you don't know how to message by username, click the search field on your Telegram app and type `@BotFather`, where you should be able to initiate a conversation. Be careful not to send it to the wrong contact, because some users have similar usernames to `BotFather`.

   ![BotFather initial conversation](https://user-images.githubusercontent.com/9423417/60736229-bc2aeb80-9f45-11e9-8d35-5b53145347bc.png)

2. `@BotFather` replies with:

    ```
    Alright, a new bot. How are we going to call it? Please choose a name for your bot.
    ```

3. Type whatever name you want for your bot.

4. `@BotFather` replies with:

    ```
    Good. Now let's choose a username for your bot. It must end in `bot`. Like this, for example: TetrisBot or tetris_bot.
    ```

5. Type whatever username you want for your bot, minimum 5 characters, and must end with `bot`. For example: `telesample_bot`

6. `@BotFather` replies with:

    ```
    Done! Congratulations on your new bot. You will find it at
    telegram.me/telesample_bot. You can now add a description, about
    section and profile picture for your bot, see /help for a list of
    commands.

    Use this token to access the HTTP API:
    123456789:AAG90e14-0f8-40183D-18491dDE

    For a description of the Bot API, see this page:
    https://core.telegram.org/bots/api
    ```

7. Note down the 'token' mentioned above.
<br>
<h1>First step</h1>

Download telegram.php file and copy to your host , next create bot.php file and copy this code to file
```
<?php

require_once "telegram.php";

$tg = new telegram('PUT YOUR TOKEN BOT');

?>
```
set token in codes
<div id='1'></div>
## Send simple text message
```
$tg->id = $tg->input['id'];
$tg->text = 'Hello world !';
$tg->send('message');
```
## Send text message with inline keyboard
```
$tg->id = $tg->input['id'];
$tg->text = 'Hello world !';
$tg->keyboard = [[['text'=>'Open google','url'=>'https://google.com']],[['text'=>'Restart','url'=>'/start']]];
$tg->send('message');
```
## Send text message with menu keyboard
```
$tg->id = $tg->input['id'];
$tg->text = 'Hello world !';
$tg->keyboard = [['key 1','key 2']];
$tg->send('message');
```
## Send file message from url
```
$tg->id = $tg->input['id'];
$tg->caption = 'Hello world !';
$tg->file = 'file url';
$tg->send('file type'); // example : image, video, document, audio ,....
```
## Send file message from host
```
$tg->id = $tg->input['id'];
$tg->caption = 'Hello world !';
$tg->file = local('path of file');
$tg->send('file type'); // example : image, video, document, audio ,....
```
## Send file message from file key
```
$tg->id = $tg->input['id'];
$tg->caption = 'Hello world !';
$tg->file = $tg->input['file'];
$tg->send($tg->input['fileType']);
```
## Forward a message
```
$tg->id     = $tg->input['id'];
$tg->msg_id = $tg->input['msg_id'];
$tg->from   = $tg->input['id'];
$tg->forward();
```
## Delete a message
```
$tg->id = $tg->input['id'];
$tg->msg_id = $tg->input['msg_id'];
$tg->delete();
```
## Edit a text message
```
$tg->id     = $tg->input['id'];
$tg->msg_id = $tg->input['msg_id'];
$tg->text   = "message edited !";
$tg->edit('message');
```
## Edit caption of message
```
$tg->id     = $tg->input['id'];
$tg->msg_id = $tg->input['msg_id'];
$tg->caption   = "caption edited !";
$tg->edit('caption');
```
## Edit a file message
```
$tg->id = $tg->input['id'];
$tg->msg_id = $tg->input['msg_id'];
$tg->caption = "file edited !";
$tg->file = 'file url';
$tg->edit('file type'); // example : image, video, document, audio ,....
```
## Send a alert
```
$tg->id         = $tg->input['id'];
$tg->query_id   = $tg->input['query_id'];
$tg->show_alert = true;
$tg->text       = 'Hello world !';
$tg->alert();
```
## Send location
```
$tg->id     = $tg->input['id'];
$tg->height = 10;
$tg->width  = 10;
$tg->send('location');
```
## Send contact
```
$tg->id    = $tg->input['id'];
$tg->phone = '09027282364';
$tg->name  = 'inline group';
$tg->send('contact');
```
