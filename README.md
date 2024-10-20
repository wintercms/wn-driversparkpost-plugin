# SparkPost Driver Plugin

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/wintercms/wn-driversparkpost-plugin/blob/main/LICENSE)

This plugin adds support for integrating SparkPost into Winter CMS.

Supports:
- Configuring & using SparkPost as a system mailer service.

## Installation

This plugin is available for installation via [Composer](http://getcomposer.org/).

```bash
composer require winter/wn-driversparkpost-plugin
```

After installing the plugin you will need to run the migrations and (if you are using a [public folder](https://wintercms.com/docs/develop/docs/setup/configuration#using-a-public-folder)) [republish your public directory](https://wintercms.com/docs/develop/docs/console/setup-maintenance#mirror-public-files).

```bash
php artisan migrate
```

## How to use this plugin

- Open an account with [SparkPost](https://www.sparkpost.com/) and setup a secret key to use this plugin.
- Enter the secret on the Mail Configuration page after choosing the Sparkpost Mail method.
