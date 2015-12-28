<?php
namespace App\Http\Controllers;

use Illuminate\Session\Store;
use Slim\Slim;

/**
 * Base controller class with functions shared by all controller implementations
 * @author Basile Trujillo
 * @link https://bitbucket.org/L0gIn/slim-twig-laravel-html5-boilerplate
 */
class BaseController
{
    /**
     * @var \Slim\Slim
     */
    protected $app;

    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * @var Array
     */
    protected $settings;

    /**
     * @var Array
     */
    protected $defaultData;

    /**
     * Default controller construct
     * @param Slim  $app
     * @param Store $session
     */
    public function __construct(Slim $app, Store $session)
    {
        $this->app      = $app;
        $this->session  = $session;
        $this->settings = $app->container->get('settings');

        //Default data to pass trought twig tpl
        $this->defaultData = array(
            'settings'  => $this->settings,
            'asset.min' => $this->settings['mode'] == 'production' ? '' : '.min'
        );
    }

    /**
     * Render twig template with merged datas
     * @param $tpl
     * @param $data
     */
    protected function render($tpl, $data = array())
    {
        $datas = $data + $this->defaultData;
        $this->app->render($tpl, $datas + array(
                'flashbag'  => array(
                    'success'   => $this->session->get('success'),
                    'info'      => $this->session->get('info'),
                    'warning'   => $this->session->get('warning'),
                    'danger'    => $this->session->get('error')
                )
            ));
    }

    /**
     * Add a flash bag (temporary alert msg)
     * Key values : success, info, warning, error
     *
     * @param $key
     * @param $msg
     */
    protected function addFlashBag($key, $msg)
    {
        $this->session->flash($key, array_merge((array)$this->session->get($key), array($msg)));
    }
} 