<?php

namespace CrudKit\Controllers;

use CrudKit\CrudKitApp;
use CrudKit\Pages\BasePage;
use CrudKit\Util\TwigUtil;
use CrudKit\Util\UrlHelper;

class BaseController {

    /**
     * @var UrlHelper
     */
    protected $url = null;

    /**
     * @var CrudKitApp
     */
    protected $app = null;

    /**
     * @param $app CrudKitApp
     */
    public function __construct ($app) {
        $this->app = $app;
    }

    public function handle () {
        if($this->url === null) {
            $this->url = new UrlHelper();
        }

        $action = $this->url->get('action');
        if($action !== null && method_exists($this, "handle_".$action)) {
            return call_user_func(array($this, "handle_", $action));
        }
        else  {
            return $this->default_page ();
        }
    }

    public function default_page () {
        $pageMap = [];
        /** @var BasePage $pageItem */
        foreach($this->app->getPages() as $pageItem) {
            $pageMap []= array(
                'id' => $pageItem->getId(),
                'name' => $pageItem->getName()
            );
        }

        $twig = new TwigUtil();
        return $twig->renderTemplateToString("layout.twig", array(
            'staticRoot' => $this->app->getStaticRoot(),
            'pageMap' => $pageMap
        ));
    }
}