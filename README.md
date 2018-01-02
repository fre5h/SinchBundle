# SinchBundle

Provides integration with **[Sinch.com](https://www.sinch.com)** SMS API.

> **Currently in development process! Things can be changed at any moment!**

![Sinch Logo](/Resources/images/sinch-logo.png)

[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/fre5h/SinchBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/fre5h/SinchBundle/)
[![Build Status](https://img.shields.io/travis/fre5h/SinchBundle/master.svg?style=flat-square)](https://travis-ci.org/fre5h/SinchBundle)
[![CodeCov](https://img.shields.io/codecov/c/github/fre5h/SinchBundle.svg?style=flat-square)](https://codecov.io/github/fre5h/SinchBundle)
[![License](https://img.shields.io/packagist/l/fresh/sinch-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/sinch-bundle)
[![Latest Stable Version](https://img.shields.io/packagist/v/fresh/sinch-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/sinch-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/fresh/sinch-bundle.svg?style=flat-square)](https://packagist.org/packages/fresh/sinch-bundle)
[![StyleCI](https://styleci.io/repos/44092074/shield?style=flat-square)](https://styleci.io/repos/44092074)
[![Gitter](https://img.shields.io/badge/gitter-join%20chat-brightgreen.svg?style=flat-square)](https://gitter.im/fre5h/SinchBundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2303fcfb-2e4b-45b3-8b37-6d1e7598acf4/small.png)](https://insight.sensiolabs.com/projects/2303fcfb-2e4b-45b3-8b37-6d1e7598acf4)
[![knpbundles.com](http://knpbundles.com/fre5h/SinchBundle/badge-short)](http://knpbundles.com/fre5h/SinchBundle)

## Requirements

* PHP 7.1 *and later*
* Symfony 4.0 *and later*

## Installation

### Create application for Sinch

Sing up in [Sinch.com](https://www.sinch.com) and [create a new app](https://www.sinch.com/dashboard/#/quickstart).

### Add dependency via Composer

```composer req fresh/sinch-bundle='dev-master'```

### Add key and secret to parameters.yml

Add the following lines to your `parameters.yml.dist` file:

```yml
parameters:
    sinch.key: EnterKeyForYourSinchApp
    sinch.secret: EnterSecretForYourSinchApp
```

During the composer update you have to enter your own key and secret for your Sinch application,
which you can find in your [Sinch dashboard](https://www.sinch.com/dashboard/#/apps).

### Update config.yml

Add the following lines to `config.yml` file:

```yml
fresh_sinch:
    key: "%sinch.key%"
    secret: "%sinch.secret%"
```

## Usage

### Example of sending SMS

```php
use Fresh\SinchBundle\Service\Sinch;

class Foo {
    public function bar(Sinch $sinch) {
        // Set the outbound number where you want to send the SMS
        $messageId = $sinch->sendSMS('+13155555552', 'Your message');
        
        // If success then the ID of sent message is returned (it is an integer value)
        echo $messageId;
    }
}
```

### Example of checking SMS status

```php
use Fresh\SinchBundle\Service\Sinch;

class Foo {
    public function bar(Sinch $sinch) {
        $messageId = 123456789; // The ID of Sinch message you get after successful SMS sending
        
        // Status is a string with one of these values: pending, successful, faulted, unknown
        $status = $sinch->getStatusOfSMS($messageId);
        
        // Other helper methods, return true of false
        $sinch->smsIsSentSuccessfully($messageId);
        $sinch->smsIsPending($messageId);
        $sinch->smsIsFaulted($messageId);
        $sinch->smsInUnknownStatus($messageId);
    }
}
```

#### Catching and processing Sinch exceptions

```php
use Fresh\SinchBundle\Exception\PaymentRequired\SinchPaymentRequiredException;
use Fresh\SinchBundle\Service\Sinch;

class Foo {
    public function bar(Sinch $sinch) {
        try {
            $messageId = $sinch->sendSMS($phoneNumber, 'Your message');
            // Some logic related to SMS processing...
        } catch (SinchPaymentRequiredException $e) {
            $logger->error('SMS was not sent. Looks like your Sinch account run out of money.');
            // Here you can, for example, send urgent emails to admin users
            // to notify that your Sinch account run out of money
        }
    }
}
```

***

## Contributing

See [CONTRIBUTING](https://github.com/fre5h/SinchBundle/blob/master/.github/CONTRIBUTING.md) file.
