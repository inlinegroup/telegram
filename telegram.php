<?php
class telegram{
    public $data = [];
    public $input = []; 
    public $bot_dl_url, $bot_url, $token;
    public $one_time_keyboard = false;
    public $resize_keyboard = true;
    public $show_alert = false;
    public $keyboard;
    public $query_id;
    public $phone;
    public $name;
    public $msg_id;
    public $height;
    public $width;
    public $from;
    public $text;
    public $caption;
    public $file;
    public $id;
    
    public function __construct($token){
        $this->token = $token;
        $this->bot_url = "https://api.telegram.org/bot$token";
        $this->bot_dl_url = "https://api.telegram.org/file/bot$token";
        
        $this->data = json_decode(file_get_contents("php://input"), true); 
        if(isset($this->data["message"])){
            $this->input['id']       = $this->data["message"]["chat"]["id"];
            $this->input['msg_id']   = $this->data["message"]["message_id"];
            if(isset($this->data["message"]["chat"]["username"])){
               $this->input['username']  = $this->data["message"]["chat"]["username"];
            }else{ $this->input['username'] = ""; }
            if(isset($this->data["message"]["chat"]["first_name"])){
               $this->input['name']  = $this->data["message"]["chat"]["first_name"];
           }else{ $this->input['name'] = ""; }
           if(isset($this->data["message"]["forward_from_chat"])){
               $this->input['for_id'] = $this->data["message"]["forward_from_chat"]["id"];
           }else{ $this->input['for_id'] = false; }
           if(isset($this->data["message"]["text"])){
               $this->input['text']  = $this->data["message"]["text"];
           }else{ $this->input['text'] = ""; }
           if(isset($this->data["message"]["caption"])){
                $this->input['caption']  = $this->data["message"]["caption"];
            }else{ $this->input['caption']
            if(isset($this->data["message"]["photo"])){
                $this->input['file']     = $this->data["message"]["photo"][count($this->data["message"]["photo"])-1]["file_id"];
                $this->input['fileType'] = "photo";
            }if(isset($this->data["message"]["document"])){       
                $this->input['file']     = $this->data["message"]["document"]["file_id"];       
                $this->input['fileType'] = "document";     
            }if(isset($this->data["message"]["video"])){       
                $this->input['file']     = $this->data["message"]["video"]["file_id"];       
                $this->input['fileType'] = "video";    
            }if(isset($this->data["message"]["audio"])){       
                $this->input['file']     = $this->data["message"]["audio"]["file_id"];       
                $this->input['fileType'] = "audio";     
            }if(isset($this->data["message"]["voice"])){       
                $this->input['file']     = $this->data["message"]["voice"]["file_id"];       
                $this->input['fileType'] = "voice";
            }
        }elseif(isset($this->data["callback_query"])){
            $this->input['text']     = $this->data["callback_query"]["data"]
            $this->query_id = $this->input['query_id'] = $this->data["callback_query"]["id"];
            $this->input['id']       = $this->data["callback_query"]["message"]["chat"]["id"];
            $this->input['msg_id']   = $this->data['callback_query']['message']['message_id'];
            $this->input['query']    = $this->data['callback_query']['message']['text'];
            $this->input['name']     = $this->data['callback_query']['from']['first_name'];
        }
        
        $this->id     = $this->input['id'];
        $this->from   = $this->input['id']
        $this->msg_id = $this->input['msg_id'];
        
        $url = "$this->bot_url/getFile";
        $result = json_decode($this->connect($url, ['file_id' => $this->file]));  
        if(!$result->ok){ $this->input['url'] = false; }
        else{ $this->input['url'] = "$this->bot_dl_url/".$result_array->result->file_path; }
        
    }
    
    public function local($path,$title){
        $path = realpath($path);
        $file = new CURLFILE($path);
        $file->setPostFilename($title);
        return $file;
    }
    
    public function send($type){
        $url = "$this->bot_url/send".ucfirst($type);
        $post_params = 
            [
                'chat_id'    => $this->id,
                'disable_web_page_preview' => true,
                'parse_mode' => 'HTML',
            ];
        switch($type){
            case 'message':
                $post_params['text']      = $this->text;
                break;
            case 'location':
                $post_params['latitude']  = $this->height;
                $post_params['longitude'] = $this->width;
                break;
            case 'contact':
                $post_params['phone_number'] = $this->phone;
                $post_params['first_name']   = $this->name; 
                break;
            default:
                $post_params[$type]     = $type;
                $post_params['caption'] = $this->caption;
                break;
        }
        if(isset($this->keyboard)){
            if(isset($this->keyboard[0][0]['text'])){ $post_params['reply_markup'] = json_encode(['inline_keyboard' => $this->keyboard]); }
            else{ $post_params['reply_markup'] = json_encode(['keyboard' => $this->keyboard ,'resize_keyboard' => $this->resize_keyboard ,'one_time_keyboard' => $this->one_time_keyboard]); }
        }
        return $this->connect($url, $post_params);
    }
    
    public function edit($type){
        $post_params = 
            [ 
                'chat_id'    => $this->id , 
                'message_id' => $this->msg_id ,
            ];
        switch($type){
            case 'message':
                $url = "$this->bot_url/editMessageText";
                $post_params['text'] = $this->text;
                break;
            case 'caption':
                $url = "$this->bot_url/editMessageCaption";
                $post_params['caption'] = $this->caption;
                break;
            default:
                $url = "$this->bot_url/editMessageMedia";
                $new_media = 
                    [ 
                        'type'    => $type,
                        'media'   => $this->file,
                        'caption' => $this->caption,
                    ];
                $post_params['media'] = json_encode($new_media);
        }
        
        return $this->connect($url, $post_params);
    }
    
    public function forward(){
        $url = "$this->bot_url/forwardMessage";
        $post_params = 
            [ 
                'chat_id' => $this->id , 
                'message_id' => $this->msg_id,
                'from_chat_id' => $this->from
            ];
        return $this->connect($url, $post_params);
    }
    
    public function is_join($channel){
        $tch = json_decode(connect("$this->bot_url/getChatMember?chat_id=$channel&user_id=$this->id"));
        if(!isset($tch->result)){ return 'left'; }
        return $tch->result->status;
    }
    
    public function delete(){
        $url = "$this->bot_url/deleteMessage";
        $post_params = 
            [
                'chat_id' => $this->id , 
                'message_id' => $this->msg_id
            ];
        return $this->connect($url, $post_params);
    }
    
    public function alert(){
        $url = "$this->bot_url/answerCallbackQuery";
        $post_params = 
            [
                'callback_query_id' => $this->query_id , 
                'text'              => $this->text ,
                'show_alert'        => $this->show_alert
            ];
        return $this->connect($url, $post_params);
    }
    
    public function connect($url, $post_params){
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_POSTFIELDS, $post_params);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($cu);
        curl_close($cu);
        return $result;
    }
}

?>
