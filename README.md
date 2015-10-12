# SinchBundle

Provides integration with Sinch API.

**Currently in development process!!!**

## TODO

* Improve docs
* Add tests
* Add more Sinch features (check sms status, etc.)

## Requirements

* PHP 5.6 *and later*
* Symfony 2.7 *and later*
* Guzzle PHP HTTP Client *6.1.**

## Installation

### Create application for Sinch

Sing up in [Sinch.com](https://www.sinch.com) and [create a new app](https://www.sinch.com/dashboard/#/quickstart).

### Install via Composer

```php composer.phar require fresh/sinch-bundle='dev-master'```

### Register the bundle

To start using the bundle, register it in `app/AppKernel.php`:

```php
public function registerBundles()
{
    $bundles = [
        // Other bundles...
        new Fresh\SinchBundle\FreshSinchBundle(),
    ];
}
```

### Add key and secret to parameters.yml

Add the following lines to your `parameters.yml.dist` file:

```yml
parameters:
    sinch.key:    EnterKeyForYourSinchApp
    sinch.secret: EnterSecretForYourSinchApp
```

During the composer update you have to enter your own key and secret for your Sinch application, which you can find
in your Sinch dashboard.

### Update config.yml

Add the following lines to `config.yml` file:

```yml
fresh_sinch:
    key:    "%sinch.key%"
    secret: "%sinch.secret%"
```

## Using

### Example of sending SMS

```php
$sinch = $this->get('sinch');
// Set the outbound number where you want to send the SMS
$phoneNumber = 1234567890; 
$response = $sinch->sendSMS($phoneNumber, 'Your message');

// $response is an object which implements Psr\Http\Message\ResponseInterface
$response->getStatusCode();
$response->getBody()->getContents();
// and other methods
```

### Example of checking SMS status

#### Get the status of SMS

```php
$sinch = $this->get('sinch');
// You get the ID of message in successful response after sending a sms
$messageId = 123456789;
$response = $sinch->getStatusOfSMS($messageId);
// Status is a string: Successful, Unknown or smt else
```

#### Just check if SMS was sent

```php
$sinch = $this->get('sinch');
$messageId = 123456789;
if ($sinch->smsIsSent($messageId)) {
    echo 'SMS was sent';
} else {
    echo 'SMS was not sent';
}
```
