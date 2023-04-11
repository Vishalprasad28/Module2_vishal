<?php
namespace App\UserService;

use Symfony\Component\Dotenv\Dotenv;

class EnvHandler {
  /**
   * @var string $apiKey
   *   Stores the APi key of the API service the app uses
   */
  protected string $apiKey;

  /**
   * @var string $myEmail
   *   My email id that i being used to send the mail using php mailer
   */
  protected string $myEmail;

  /**
   * @var string $emailPassword
   *   Password of my email id 
   */
  protected string $emailPassword;

  /**
   * @var dotenv $dotEnv
   */
  protected Dotenv $dotEnv;

  /**
   * Initialoizing the variables with Constructor
   */
  public function __construct() {
    $this->dotEnv = new Dotenv();
    $this->dotEnv->load(__DIR__ . '/.env');
    $this->apiKey = $_ENV['API_KEY'];
    $this->myEmail = $_ENV['EMAIL'];
    $this->emailPassword = $_ENV['EMAIL_PASSWORD'];
  }
}
