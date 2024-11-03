# LifeJacket Server

This is the **Server** part of the LifeJacket project, which enables you to run a custom update server on your WordPress site. It implements an equivalent version of the .org API.

Currently, this implementation acts as a simple mirror to the original .org API. Further in the roadmap we'll start improving and replacing the API endpoints with our own implementation, but basic mirror will always be an option.

To use this tool, you will also need to install the [LifeJacket Client](https://github.com/life-jacket/lifejacket-client) client plugin on any WordPress site you want to update from your custom update server.

## Installation

Initially you'll need to download and manually install a .zip file for this plugin. You can find the latest release [here](https://github.com/life-jacket/lifejacket-server/releases).

This plugin can be activated several ways:

1. As a *regular* plugin - via the WordPress Admin UI;
2. As a *must-use* (MU) plugin - by placing it into `wp-content/mu-plugins/` directory;

Using it as a mu-plugin is advangageous as it gets activated earlier in WordPress load cycle.

## Configuration

Configuration for this plugin is currently done via constants in the `wp-config.php` file:

```php
// ===== Optional
// To enable authentication via application passwords (defaults to `true`)
define( 'LIFEJACKET_SERVER_REQUIRE_AUTH', true );
// To enable collection of usage statistics (defaults to `false`)
define( 'LIFEJACKET_SERVER_COLLECT_STATS', true );
```

Note: If you decide to require the application password authentication, you will need to generate an application password for an existing user on your WP site, and then configure your Clients to use that password.

## Usage stats

By default collection of usage stats is disabled. If you enable it via configuration (see above), a shortcode `[lifejacket_stats /]` becomes available to show top usage of the server by domain and by endpoint.

Stats are currently very rudimentary and the module is very experimental