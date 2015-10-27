# SinchBundle

Provides integration with [Sinch.com](https://www.sinch.com) SMS API.

**Currently in development process!!! Things can be changed at any moment**

![Sinch Logo](/Resources/images/sinch-logo.png)

[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/fre5h/SinchBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/fre5h/SinchBundle/)
[![Build Status](https://img.shields.io/travis/fre5h/SinchBundle.svg?style=flat-square)](https://travis-ci.org/fre5h/SinchBundle)
[![CodeCov](https://img.shields.io/codecov/c/github/fre5h/SinchBundle.svg?style=flat-square)](https://codecov.io/github/fre5h/SinchBundle)
[![License](https://img.shields.io/packagist/l/fresh/sinch-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/sinch-bundle)
[![Latest Stable Version](https://img.shields.io/packagist/v/fresh/sinch-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/sinch-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/fresh/sinch-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/sinch-bundle)
[![Dependency Status](https://img.shields.io/versioneye/d/php/fresh:sinch-bundle.svg?style=flat-square)](https://www.versioneye.com/user/projects/562fcca536d0ab00190015a7)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/2303fcfb-2e4b-45b3-8b37-6d1e7598acf4.svg?style=flat-square)](https://insight.sensiolabs.com/projects/2303fcfb-2e4b-45b3-8b37-6d1e7598acf4)
[![Gitter](https://img.shields.io/badge/gitter-join%20chat-brightgreen.svg?style=flat-square)](https://gitter.im/fre5h/SinchBundle?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![knpbundles.com](http://knpbundles.com/fre5h/SinchBundle/badge-short)](http://knpbundles.com/fre5h/SinchBundle)

## TODO

* Improve docs
* Add tests
* Add more Sinch features (sign requests for production apps)

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
in your [Sinch dashboard](https://www.sinch.com/dashboard/#/apps).

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
$messageId = $sinch->sendSMS($phoneNumber, 'Your message');
// If success then the ID of sent message is returned (it is an integer value)
echo $messageId;
```

### Example of checking SMS status

#### Get the status of SMS

```php
$sinch = $this->get('sinch');
// You get the ID of message in successful response after sending a sms
$messageId = 123456789;
$status = $sinch->getStatusOfSMS($messageId);
// Status is a string: Successful, Unknown or something else
```

#### Just check if SMS was sent successfully

```php
$sinch = $this->get('sinch');
$messageId = 123456789;
if ($sinch->smsIsSentSuccessfully($messageId)) {
    echo 'SMS was sent successfully';
} else {
    echo 'SMS was not sent successfully';
}
```
