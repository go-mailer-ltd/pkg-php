<?php

namespace GoMailer\GoMailer;

use Exception;

class Contact
{
  protected $api_key;
  protected $base_url = 'https://users.go-mailer.com';

  function __construct($api_key)
  {
    $this->api_key = $api_key;
  }

  public function synchronize($email, $data = [])
  {
    if (!$email) {
      throw new Exception('No email specified');
    } elseif (!$data) {
      throw new Exception('No user data specified');
    };;

    $curl = curl_init();
    $body = $data;
    $body['email'] = $email;
    $headers = ['Authorization: Bearer ' . $this->api_key];

    curl_setopt($curl, CURLOPT_URL, $this->base_url . '/api/contacts');
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
      echo 'CURL ERROR: ' . curl_error($curl);
      return null;
    }

    $json_data = json_decode($response, JSON_PRETTY_PRINT);
    curl_close($curl);

    return $json_data;
  }
}
