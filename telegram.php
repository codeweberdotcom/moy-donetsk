<?php 
//Работа с Телеграм публикациями
//Постинг в Telegram
/*
// Пример данных
$dataTelegram = [
    'text' => $textMessage,
    'photos' => $mediaItem,
    'button_text' => 'Смотреть объявления',
    'button_url' => $link,
    'telegram_post_id' => $telegramPostId // ID поста в Телеграм
];
// Отправка поста в Телеграм
$post_id_telegram = $telegramPost->sendPost($dataTelegram);
echo "Telegram Post ID: " . $post_id_telegram;
*/

class TelegramPost
{
    protected $bot_token;
    protected $chat_id;

    public function __construct($bot_token, $chat_id)
    {
        $this->bot_token = $bot_token;
        $this->chat_id = $chat_id;
    }

    private function sendRequest($method, $params)
    {
        $url = "https://api.telegram.org/bot{$this->bot_token}/{$method}";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        if ($response['ok']) {
            return $response['result'];
        } else {
            throw new Exception('Telegram API error: ' . $response['description']);
        }
    }

    public function sendText($text)
    {
        $params = [
            'chat_id' => $this->chat_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];
        return $this->sendRequest('sendMessage', $params)['message_id'];
    }

    public function sendTextPhotos($text, $photo_urls)
    {
        if (count($photo_urls) == 1) {
            $params = [
                'chat_id' => $this->chat_id,
                'caption' => $text,
                'photo' => $photo_urls[0],
                'parse_mode' => 'HTML'
            ];
            return $this->sendRequest('sendPhoto', $params)['message_id'];
        } else {
            $media = [];
            foreach ($photo_urls as $index => $photo_url) {
                $media[] = [
                    'type' => 'photo',
                    'media' => $photo_url,
                ];
                if ($index === 0) {
                    $media[$index]['caption'] = $text;
                    $media[$index]['parse_mode'] = 'HTML';
                }
            }

            $params = [
                'chat_id' => $this->chat_id,
                'media' => json_encode($media),
            ];

            return $this->sendRequest('sendMediaGroup', $params)[0]['message_id'];
        }
    }

    public function sendTextButton($text, $button_text, $button_url)
    {
        $keyboard = [
            'inline_keyboard' => [[['text' => $button_text, 'url' => $button_url]]],
        ];

        $params = [
            'chat_id' => $this->chat_id,
            'text' => $text,
            'reply_markup' => json_encode($keyboard),
            'parse_mode' => 'HTML'
        ];

        return $this->sendRequest('sendMessage', $params)['message_id'];
    }

    public function sendTextPhotoButton($text, $photo_urls, $button_text, $button_url)
    {
        $keyboard = [
            'inline_keyboard' => [[['text' => $button_text, 'url' => $button_url]]],
        ];

        if (count($photo_urls) == 1) {
            $params = [
                'chat_id' => $this->chat_id,
                'caption' => $text,
                'photo' => $photo_urls[0],
                'reply_markup' => json_encode($keyboard),
                'parse_mode' => 'HTML'
            ];
            return $this->sendRequest('sendPhoto', $params)['message_id'];
        } else {
            $media = [];
            foreach ($photo_urls as $index => $photo_url) {
                $media[] = [
                    'type' => 'photo',
                    'media' => $photo_url,
                ];
                if ($index === 0) {
                    $media[$index]['caption'] = $text;
                    $media[$index]['parse_mode'] = 'HTML';
                }
            }

            $params = [
                'chat_id' => $this->chat_id,
                'media' => json_encode($media),
                'reply_markup' => json_encode($keyboard),
            ];

            return $this->sendRequest('sendMediaGroup', $params)[0]['message_id'];
        }
    }

    public function editMessageText($message_id, $text, $keyboard = null)
    {
        $params = [
            'chat_id' => $this->chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];

        if ($keyboard) {
            $params['reply_markup'] = json_encode($keyboard);
        }

        $this->sendRequest('editMessageText', $params);
        return $message_id;
    }

    public function editMessageCaption($message_id, $caption, $keyboard = null)
    {
        $params = [
            'chat_id' => $this->chat_id,
            'message_id' => $message_id,
            'caption' => $caption,
            'parse_mode' => 'HTML'
        ];

        if ($keyboard) {
            $params['reply_markup'] = json_encode($keyboard);
        }

        $this->sendRequest('editMessageCaption', $params);
        return $message_id;
    }

    public function deleteMessage($message_id)
    {
        $params = [
            'chat_id' => $this->chat_id,
            'message_id' => $message_id
        ];

        $this->sendRequest('deleteMessage', $params);
    }

    public function sendPost($data)
    {
        $text = $data['text'] ?? null;
        $photos = $data['photos'] ?? null;
        $button_text = $data['button_text'] ?? null;
        $button_url = $data['button_url'] ?? null;
        $telegram_post_id = $data['telegram_post_id'] ?? null;

        $keyboard = null;
        if ($button_text && $button_url) {
            $keyboard = [
                'inline_keyboard' => [[['text' => $button_text, 'url' => $button_url]]],
            ];
        }

        if ($telegram_post_id) {
            if ($text && $photos) {
                // Удаляем старое сообщение
                $this->deleteMessage($telegram_post_id);
                // Отправляем новое сообщение
                if (count($photos) == 1) {
                    return $this->sendTextPhotoButton($text, $photos, $button_text, $button_url);
                } else {
                    return $this->sendTextPhotos($text, $photos);
                }
            } elseif ($text) {
                return $this->editMessageText($telegram_post_id, $text, $keyboard);
            } else {
                throw new Exception("Invalid data for updating post.");
            }
        } else {
            if ($text && $photos && $button_text && $button_url) {
                return $this->sendTextPhotoButton($text, $photos, $button_text, $button_url);
            } elseif ($text && $photos) {
                return $this->sendTextPhotos($text, $photos);
            } elseif ($text && $button_text && $button_url) {
                return $this->sendTextButton($text, $button_text, $button_url);
            } elseif ($text) {
                return $this->sendText($text);
            } else {
                throw new Exception("Invalid data for sending post.");
            }
        }
    }
}



//Работа с файлом Telegram JSON
//Хранение данных публикаций в телеграм
//// Примеры использования
/*
// Примеры использования
$store = new TelegramValueStore(); // Использует путь по умолчанию 'telegram_data.json'


$store->set('channel1', 'key1', 'value1');
echo $store->get('channel1', 'key1') . "\n";

$store->delete('channel1', 'key1');
$store->deleteChannel('channel1');

// Использование другого пути к файлу
$otherStore = new TelegramValueStore('another_data.json');
$otherStore->set('anotherKey', 'anotherValue');
echo $otherStore->get('anotherKey') . "\n"; // Output: anotherValue

*/

class TelegramValueStore {
    private $filePath;

    public function __construct($filePath = 'telegram_data.json') {
        $this->filePath = $filePath;
    }

    private function readData() {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $json = file_get_contents($this->filePath);
        return json_decode($json, true);
    }

    private function writeData($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $json);
    }

    public function set($channel, $key, $value) {
        $data = $this->readData();
        if (!isset($data[$channel])) {
            $data[$channel] = [];
        }
        $data[$channel][$key] = $value;
        $this->writeData($data);
        echo "Key $key set to value $value in channel $channel\n";
    }

    public function get($channel, $key) {
        $data = $this->readData();
        return $data[$channel][$key] ?? null;
    }

    public function delete($channel, $key) {
        $data = $this->readData();
        if (isset($data[$channel]) && isset($data[$channel][$key])) {
            unset($data[$channel][$key]);
            // If the channel has no more keys, remove the channel entry
            if (empty($data[$channel])) {
                unset($data[$channel]);
            }
            $this->writeData($data);
            echo "Key $key deleted from channel $channel\n";
        }
    }

    public function deleteChannel($channel) {
        $data = $this->readData();
        if (isset($data[$channel])) {
            unset($data[$channel]);
            $this->writeData($data);
            echo "Channel $channel deleted\n";
        }
    }
}