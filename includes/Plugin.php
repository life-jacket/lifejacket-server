<?php
namespace LifeJacket\Server;

class Plugin {
    protected static $instance = null;
    protected static $initialised = false;

    public static function get_instance( $init = true ) {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        if ( $init && ! self::$initialised ) {
            self::$instance->init();
            self::$initialised = true;
        }
        return self::$instance;
    }

    private function __construct() {}

    protected $rest = [];

    public function init() {
        // $this->rest['Plugin'] = new REST\Plugin();
        // $this->rest['Plugin']->init();
        $this->rest['API'] = new REST\API();
        $this->rest['API']->init();
        $this->rest['API']->enable_authentication();
        $this->rest['Downloads'] = new REST\Downloads();
        $this->rest['Downloads']->init();
        $this->rest['Downloads']->enable_authentication();
    }

}