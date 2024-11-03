<?php
namespace LifeJacket\Server;

class Stats {
	public function init() {
		add_shortcode( 'lifejacket_stats', [ $this, 'shortcode'] );
		$this->dbdelta();
	}

	public function shortcode( $args =[], $content = '' ) {
		$output = '';
		$output .= '<div class="lifejacket-stats">';
		$output .= '<h2>' . __( 'LifeJacket Server statistics', 'lifejacket-server' ) . '</h2>';
		$output .= '<h3>' . __( 'Top domains', 'lifejacket-server' ) . '</h3>';

		$data = $this->get_dimension( 'domain', 5 );
		$headings = [
			__( 'Domain', 'lifejacket-server' ),
			__( 'Count', 'lifejacket-server' ),
		];
		$output .= $this->render_table( $data, $headings );

		$output .= '<h3>' . __( 'Top endpoints', 'lifejacket-server' ) . '</h3>';

		$data = $this->get_dimension( 'endpoint', 5 );
		$headings = [
			__( 'Endpoint', 'lifejacket-server' ),
			__( 'Count', 'lifejacket-server' ),
		];
		$output .= $this->render_table( $data, $headings );

		$output .= '</div>';
		return $output;
	}

	public function log( $domain, $endpoint ) {
		global $wpdb;
		$wpdb->insert(
			$this->get_table_name(),
			[
				'domain' => $domain,
				'endpoint' => $endpoint,
			]
		);
	}


	protected function dbdelta() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		
		$table_name = $this->get_table_name();

		$sql = "CREATE TABLE " . $table_name . " (
		id int(11) NOT NULL AUTO_INCREMENT,
		domain tinytext NOT NULL,
		endpoint tinytext NOT NULL,
	    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id)
		) $charset_collate;";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		dbDelta($sql);
	}

	protected function get_dimension( $dimension, $limit = 5 ) {
		global $wpdb;
		$table_name = $this->get_table_name();
		$q = "SELECT %i as `dimension`, COUNT(*) as `count` FROM %i GROUP BY %i ORDER BY Count(*) DESC LIMIT %d";
		$q = $wpdb->prepare( $q, $dimension, $table_name, $dimension, $limit );
		$data = $wpdb->get_results( $q, ARRAY_A );
		return $data;
	}

	protected function render_table( $data, $headings = false ) {
		$output = '';

		$output .= '<table border="1">';
		if ( $headings ) {
			$output .= '<tr>';
			foreach ( $headings as $heading ) {
				$output .= "<th>{$heading}</th>";
			}
			$output .= '</tr>';
		}
		foreach ( $data as $row ) {
			$output .= "<tr><td>{$row['dimension']}</td><td>{$row['count']}</td></tr>";
		}
		$output .= '</table>';
		return $output;
	}

	protected function get_table_name() {
		global $wpdb;
		return $wpdb->base_prefix . 'lifejacket_stats';
	}
}