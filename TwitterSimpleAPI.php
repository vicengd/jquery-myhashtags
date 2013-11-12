<?php
require_once('config.php');

$q     = $_REQUEST['q'];
$count = $_REQUEST['count'];
$lang  = $_REQUEST['lang'];
$params = 'q='.$q.'&count='.$count.'&lang='.$lang;

$settings = array(
    'oauth_token' => OAUTH_TOKEN,
    'oauth_token_secret' => OAUTH_TOKEN_SECRET,
    'consumer_key' => CONSUMER_KEY,
    'consumer_secret' => CONSUMER_SECRET,
    'parameters' => $params,
    'url' => 'https://api.twitter.com/1.1/search/tweets.json'
);

class TwitterSimpleAPI
{
    private $oauth_token;
    private $oauth_token_secret;
    private $consumer_key;
    private $consumer_secret;
    private $parameters;
    private $url;

	function __construct($settings)
	{
        $this->oauth_token        = $settings['oauth_token'];
        $this->oauth_token_secret = $settings['oauth_token_secret'];
        $this->consumer_key       = $settings['consumer_key'];
        $this->consumer_secret    = $settings['consumer_secret'];
        $this->url                = $settings['url'];

        $search = array('#', ',', ':');
        $replace = array('%23', '%20OR%20', '%3A');
        $parameters = str_replace($search, $replace, $settings['parameters']);

        $this->parameters = $parameters;

        $oauth = array(
            'oauth_consumer_key' => $this->consumer_key,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $this->oauth_token,
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        );

        $fields = explode('&', $this->parameters);

        foreach ($fields as $field)
        {
            $split = explode('=', $field);
            $oauth[$split[0]] = $split[1];
        }

        ksort($oauth);
        $keys = array();

        foreach($oauth as $key=>$value)
        {
            $keys[] = "$key=" . $value;
        }

        $base = 'GET' . "&" . rawurlencode($this->url) . '&' . rawurlencode(implode('&', $keys));

        $key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->oauth_token_secret);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base, $key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $this->oauth = $oauth;

	}

    public function getData()
    {
        $string = 'Authorization: OAuth ';
        $values = array();

        foreach($this->oauth as $key => $value)
        {
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        }

        $string .= implode(', ', $values);

        $header = array($string, 'Expect:');

        $urlcurl = $this->url.'?'.$this->parameters;

        $options = array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $urlcurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

}

$twitter = new TwitterSimpleAPI($settings);
$tweets = $twitter->getData();

echo $tweets;

?>