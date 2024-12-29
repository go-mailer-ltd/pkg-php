<?php

namespace GoMailer\GoMailer;

use Exception;

class Mailing
{
  protected $api_key = '';
  private $base_url = 'https://mailing.go-mailer.com';

  function __construct($api_key)
  {
    $this->api_key = $api_key;
  }

  public function send_transactional_email($template_code, $recipient_email, $data = [],  $html_markup = '', $attachments = [], $bcc = '')
  {
    if (!$template_code) {
      throw new Exception('Template code is required.');
    } elseif (!$recipient_email) {
      throw new Exception('Recipient email is required.');
    }

    $curl = curl_init();
    $body = [
      'template_code' => strtoupper($template_code),
      'recipient_email' => $recipient_email,
      'data' => $data,
      'html' => $html_markup,
      'attachments' => $attachments,
      'bcc' => $bcc
    ];

    $headers = ['Authorization: Bearer ' . $this->api_key];

    curl_setopt($curl, CURLOPT_URL, $this->base_url . '/api/v1/transactionals/dispatch');
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
