<?php
namespace App\Http\Controllers;

use Illuminate\Session\Store;
use Slim\Slim;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends BaseController
{
    /**
     * HomeController Constructor
     *
     * @param Slim      $app
     * @param Store     $session
     */
    public function __construct(Slim $app, Store $session)
    {
        parent::__construct($app, $session);
    }

    /**
     * Show the home page
     */
    public function indexAction()
    {
        $this->render('home.twig', array(
            'message'  => $this->session->get('message'),
            'errors'   => $this->session->get('errors')
        ));
    }
}