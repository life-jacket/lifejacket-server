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

    protected $options;
    protected $rest = [];
	public $stats;

    public function init() {
        $this->options = new Options();

		$this->rest['API'] = new REST\API();
        $this->rest['API']->init();
        $this->rest['Downloads'] = new REST\Downloads();
        $this->rest['Downloads']->init();

		if ( $this->options->get( 'require_auth' ) ) {
			$this->rest['API']->enable_authentication();
			$this->rest['Downloads']->enable_authentication();
		}

		if ( $this->options->get( 'collect_stats' ) ) {
			$this->stats = new Stats();
			$this->stats->init();
		}
    }

}