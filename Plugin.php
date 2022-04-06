<?php namespace Winter\DriverSparkPost;

use App;
use Event;
use System\Classes\PluginBase;
use System\Models\MailSetting;

use GuzzleHttp\Client;
use Vemcogroup\SparkPostDriver\Transport\SparkPostTransport;

/**
 * Sparkpost Plugin Information File
 */
class Plugin extends PluginBase
{
    public $elevated = true;
    
    const MODE_SPARKPOST = 'sparkpost';

    public function pluginDetails()
    {
        return [
            'name'        => 'winter.driversparkpost::lang.plugin.name',
            'description' => 'winter.driversparkpost::lang.plugin.description',
            'homepage'    => 'https://github.com/wintercms/wn-driversparkpost-plugin',
            'author'      => 'Winter CMS',
            'icon'        => 'icon-leaf',
        ];
    }

    public function register()
    {
        Event::listen('mailer.beforeRegister', function ($mailManager) {
            $mailManager->extend(self::MODE_SPARKPOST, function ($config) {
                if (!isset($config['secret'])) {
                    $config = $this->app['config']->get('services.sparkpost', []);
                }

                $sparkpostOptions = $config['options'] ?? [];
                $guzzleOptions = $config['guzzle'] ?? [];
                $client = $this->app->make(Client::class, $guzzleOptions);

                return new SparkPostTransport($client, $config['secret'], $sparkpostOptions);
            });

            $settings = MailSetting::instance();
            if ($settings->send_mode === self::MODE_SPARKPOST) {
                $config = App::make('config');
                $config->set('mail.mailers.sparkpost.transport', self::MODE_SPARKPOST);
                $config->set('services.sparkpost.secret', $settings->sparkpost_secret);
            }
        });

    }

    public function boot()
    {
        MailSetting::extend(function ($model) {
            $model->bindEvent('model.beforeValidate', function () use ($model) {
                $model->rules['sparkpost_secret'] = 'required_if:send_mode,' . self::MODE_SPARKPOST;
            });
        });

        Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->getController() instanceof \System\Controllers\Settings) {
                return;
            }
            if (!$widget->model instanceof MailSetting) {
                return;
            }

            $field = $widget->getField('send_mode');
            $field->options(array_merge($field->options(), [self::MODE_SPARKPOST => 'Sparkpost']));

            $widget->addTabFields([
                'sparkpost_secret' => [
                    'tab'     => 'system::lang.mail.general',
                    'label'   => 'winter.driversparkpost::lang.sparkpost_secret',
                    'type'    => 'sensitive',
                    'commentAbove' => 'winter.driversparkpost::lang.sparkpost_secret_comment',
                    'trigger' => [
                        'action'    => 'show',
                        'field'     => 'send_mode',
                        'condition' => 'value[sparkpost]'
                    ],
                ],
            ]);
        });
    }
}
