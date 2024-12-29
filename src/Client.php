<?php

namespace GoMailer\GoMailer;

class Client
{
  protected $api_key = '';
  function __construct($api_key)
  {
    $this->api_key = $api_key;
  }

  function automation()
  {
    return new Automation($this->api_key);
  }

  function contacts()
  {
    return new Contact($this->api_key);
  }

  function mailing()
  {
    return new Mailing($this->api_key);
  }
}
