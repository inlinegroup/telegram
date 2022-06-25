<?php

require_once "telegram.php";

$tg = new telegram('1827553766:AAFGNkybEEO2egAnL_tn79xsJVRpuR171HY');
switch($tg->input['text']){
    case '/start':
        $tg->id       = $tg->input['id'];     // optional
        $tg->msg_id   = $tg->input['msg_id']; // optional
        $tg->delete();
        $tg->text     = 'Opened inline keyboard !';
        $tg->keyboard = 
            [
                [
                    ['text'=>'! Restart bot !','callback_data'=>'/start'],
                ],
                [
                    ['text'=>'Alert 1','callback_data'=>'Alert 1'],
                    ['text'=>'Forward','callback_data'=>'Forward'],
                    ['text'=>'Alert 2','callback_data'=>'Alert 2']
                ],
                [
                    ['text'=>'Delete','callback_data'=>'Delete'],
                    ['text'=>'Message','callback_data'=>'Message'],
                    ['text'=>'Edit','callback_data'=>'Edit']
                ],
                [
                    ['text'=>'Location','callback_data'=>'Location'],
                    ['text'=>'Contact','callback_data'=>'Contact']
                ]
            ];
        $tg->send('message');
        break;
    case 'Alert 1':
        $tg->id         = $tg->input['id'];       // optional
        $tg->query_id   = $tg->input['query_id']; // optional
        $tg->show_alert = true;
        $tg->text       = 'this is a advanced alert';
        $tg->alert();
        break;
    case 'Alert 2':
        $tg->id         = $tg->input['id'];       // optional
        $tg->query_id   = $tg->input['query_id']; // optional
        $tg->show_alert = false;
        $tg->text       = 'this is a advanced alert';
        $tg->alert();
        break;
    case 'Contact':
        $tg->id    = $tg->input['id']; // optional
        $tg->phone = '09027282364';
        $tg->name  = 'inline group';
        $tg->send('contact');
        break;
    case 'Location':
        $tg->id     = $tg->input['id']; // optional
        $tg->height = 10;
        $tg->width  = 10;
        $tg->send('location');
        break;
    case 'Message':
        $tg->id       = $tg->input['id']; // optional
        $tg->text     = 'Opened menu keyboard !';
        $tg->keyboard = 
            [
                ['Forward','/start'],
                ['Delete','Message'],
                ['Location','Contact']
            ];
        $tg->send('message');
        break;
    case 'Forward':
        $tg->id     = $tg->input['id'];     // optional
        $tg->msg_id = $tg->input['msg_id']; // optional
        $tg->from   = $tg->input['id'];     // optional
        $tg->forward();
        break;
    case 'Delete':
        $tg->id     = $tg->input['id'];     // optional
        $tg->msg_id = $tg->input['msg_id']; // optional
        $tg->delete();
        break;
    case 'Edit':
        $tg->id     = $tg->input['id'];     // optional
        $tg->msg_id = $tg->input['msg_id']; // optional
        $tg->text   = "Closed panel !";
        $tg->edit('message');
        break;
}

?>