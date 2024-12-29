<?php

namespace GoMailer\GoMailer;

use Exception;

class Automation
{
  protected $api_key = '';
  protected $base_url = 'https://automata.go-mailer.com';

  function __construct($api_key)
  {
    $this->api_key = $api_key;
  }

  public function trigger_event($event_code, $contact_email, $context)
  {
    if (!$event_code) {
      throw new Exception('Event code is required.');
    } elseif (!$contact_email) {
      throw new Exception('Contact email is required.');
    } elseif (empty($context)) {
      throw new Exception('Please provide a $context - relevant data to this specific event and contact.');
    }

    $curl = curl_init();
    $body = ['event_code' => $event_code, 'contact_email' => $contact_email, 'context' => $context];
    $headers = ['Authorization: Bearer ' . $this->api_key];

    curl_setopt($curl, CURLOPT_URL, $this->base_url . '/api/v1/events/trigger');
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
