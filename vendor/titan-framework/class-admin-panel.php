<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TitanFrameworkAdminPanel {

	private $defaultSettings = array(
		'name' => '', // Name of the menu item
		'title' => '', // Title displayed on the top of the admin panel
		'parent' => null, // id of parent, if blank, then this is a top level menu
		'id' => '', // Unique ID of the menu item
		'capability' => 'manage_options', // User role
		'icon' => 'dashicons-admin-generic', // Menu icon for top level menus only http://melchoyce.github.io/dashicons/
		'position' => null, // Menu position. Can be used for both top and sub level menus
		'use_form' => true, // If false, options will not be wrapped in a form
	);

	public $settings;
	public $options = array();
	public $tabs = array();
	public $owner;

	public $panelID;

	private $activeTab = null;
	private static $idsUsed = array();

	function __construct( $settings, $owner ) {
		$this->owner = $owner;

		if ( ! is_admin() ) {
			return;
		}

		$this->settings = array_merge( $this->defaultSettings, $settings );
		// $this->options = $options;

		if ( empty( $this->settings['name'] ) ) {
			return;
		}

		if ( empty( $this->settings['title'] ) ) {
			$this->settings['title'] = $this->settings['name'];
		}

		if ( empty( $this->settings['id'] ) ) {
			$prefix = '';
			if ( ! empty( $this->settings['parent'] ) ) {
				$prefix = str_replace( ' ', '-', trim( strtolower( $this->settings['parent'] ) ) ) . '-';
			}
			$this->settings['id'] = $prefix . str_replace( ' ', '-', trim( strtolower( $this->settings['name'] ) ) );
			$this->settings['id'] = str_replace( '&', '-', $this->settings['id'] );
		}

		// make sure all our IDs are unique
		$suffix = '';
		while ( in_array( $this->settings['id'] . $suffix, self::$idsUsed ) ) {
			if ( $suffix === '' ) {
				$suffix = 2;
			} else {
				$suffix++;
			}
		}
		$this->settings['id'] .= $suffix;

		// keep track of all IDs used
		self::$idsUsed[] = $this->settings['id'];

		$priority = -1;
		if ( $this->settings['parent'] ) {
			$priority = intval( $this->settings['position'] );
		}

		add_action( 'admin_menu', array( $this, 'register' ), $priority );
	}

	public function createAdminPanel( $settings ) {
		$settings['parent'] = $this->settings['id'];
		return $this->owner->createAdminPanel( $settings );
	}

	public function register() {
		// Parent menu
		if ( empty( $this->settings['parent'] ) ) {
			$this->panelID = add_menu_page( $this->settings['name'],
						   $this->settings['name'],
						   $this->settings['capability'],
						   $this->settings['id'],
						   array( $this, 'createAdminPage' ),
						   $this->settings['icon'],
						   $this->settings['position'] );
		// Sub menu
		} else {
			$this->panelID = add_submenu_page( $this->settings['parent'],
							  $this->settings['name'],
							  $this->settings['name'],
							  $this->settings['capability'],
							  $this->settings['id'],
							  array( $this, 'createAdminPage' ) );
		}

		add_action( 'load-' . $this->panelID, array( $this, 'saveOptions' ) );
	}

	public function getOptionNamespace() {
		return $this->owner->optionNamespace;
	}

	public function saveOptions() {
		if ( ! $this->verifySecurity() ) {
			return;
		}

		$message = '';

		/*
		 *  Save
		 */

		if ( $_POST['action'] == 'save' ) {
			// we are in a tab
			$activeTab = $this->getActiveTab();
			if ( ! empty( $activeTab ) ) {
				foreach ( $activeTab->options as $option ) {
					if ( empty( $option->settings['id'] ) ) {
						continue;
					}

					if ( ! empty( $_POST[$this->getOptionNamespace() . '_' . $option->settings['id']] ) ) {
						$value = $_POST[$this->getOptionNamespace() . '_' . $option->settings['id']];
					} else {
						$value = '';
					}
					// $value = $option->cleanValueForSaving( $value );
					$this->owner->setOption( $option->settings['id'], $value );
				}
			}

			foreach ( $this->options as $option ) {
				if ( empty( $option->settings['id'] ) ) {
					continue;
				}

				if ( ! empty( $_POST[$this->getOptionNamespace() . '_' . $option->settings['id']] ) ) {
					$value = $_POST[$this->getOptionNamespace() . '_' . $option->settings['id']];
				} else {
					$value = '';
				}

				$this->owner->setOption( $option->settings['id'], $value );
			}
			$this->owner->saveOptions();

			$message = 'saved';

		/*
		 * Reset
		 */

		} else if ( $_POST['action'] == 'reset' ) {
			// we are in a tab
			$activeTab = $this->getActiveTab();
			if ( ! empty( $activeTab ) ) {
				foreach ( $activeTab->options as $option ) {
					if ( empty( $option->settings['id'] ) ) {
						continue;
					}

					$this->owner->setOption( $option->settings['id'], $option->settings['default'] );
				}
			}

			foreach ( $this->options as $option ) {
				if ( empty( $option->settings['id'] ) ) {
					continue;
				}

				$this->owner->setOption( $option->settings['id'], $option->settings['default'] );
			}
			$this->owner->saveOptions();

			$message = 'reset';
		}

		/*
		 * Redirect to prevent refresh saving
		 */

		// urlencode to allow special characters in the url
		$url = wp_get_referer();
		$activeTab = $this->getActiveTab();
		$url = add_query_arg( 'page', urlencode( $this->settings['id'] ), $url );
		if ( ! empty( $activeTab ) ) {
			$url = add_query_arg( 'tab', urlencode( $activeTab->settings['id'] ), $url );
		}
		if ( ! empty( $message ) ) {
			$url = add_query_arg( 'message', $message, $url );
		}

		do_action( 'tf_admin_options_saved_' . $this->getOptionNamespace() );

		wp_redirect( $url );
	}

	private function verifySecurity() {
		if ( empty( $_POST ) || empty( $_POST['action'] ) ) {
			return false;
		}

		$screen = get_current_screen();
		if ( $screen->id != $this->panelID ) {
			return false;
		}

		if ( ! current_user_can( $this->settings['capability'] ) ) {
			return false;
		}

		if ( ! check_admin_referer( $this->settings['id'], TF . '_nonce' ) ) {
			return false;
		}

		return true;
	}

	public function getActiveTab() {
		if ( ! count( $this->tabs ) ) {
			return '';
		}
		if ( ! empty( $this->activeTab ) ) {
			return $this->activeTab;
		}

		if ( empty( $_GET['tab'] ) ) {
			$this->activeTab = $this->tabs[0];
			return $this->activeTab;
		}

		foreach ( $this->tabs as $tab ) {
			if ( $tab->settings['id'] == $_GET['tab'] ) {
				$this->activeTab = $tab;
				return $this->activeTab;
			}
		}
	}

	public function createAdminPage() {
		do_action( 'tf_admin_page_before' );
		do_action( 'tf_admin_page_before_' . $this->getOptionNamespace() );

		?>
		<div class='wrap titan-framework-panel-wrap'>
		<?php

		do_action( 'tf_admin_page_start' );
		do_action( 'tf_admin_page_start_' . $this->getOptionNamespace() );

		if ( count( $this->tabs ) ):
			?>
			<h2 class="nav-tab-wrapper">
			<?php

			do_action( 'tf_admin_page_tab_start' );
			do_action( 'tf_admin_page_tab_start_' . $this->getOptionNamespace() );

			foreach ( $this->tabs as $tab ) {
				$tab->displayTab();
			}

			do_action( 'tf_admin_page_tab_end' );
			do_action( 'tf_admin_page_tab_end_' . $this->getOptionNamespace() );

			?>
			</h2>
			<?php
		endif;

		?>
		<div class='options-container'>
		<?php

		if ( count( $this->tabs ) ):
			echo "<h2>" . $this->getActiveTab()->settings['title'] . "</h2>";
		endif;

		if ( ! count( $this->tabs ) ):
			?>
			<h2><?php echo $this->settings['title'] ?></h2>
			<?php
		endif;

		// Display notification if we did something
		if ( ! empty( $_GET['message'] ) ) {
			if ( $_GET['message'] == 'saved' ) {
				echo TitanFrameworkAdminNotification::formNotification( __( 'Settings saved.', TF_I18NDOMAIN ), $_GET['message'] );
			} else if ( $_GET['message'] == 'reset' ) {
				echo TitanFrameworkAdminNotification::formNotification( __( 'Settings reset to default.', TF_I18NDOMAIN ), $_GET['message'] );
			}
		}

		if ( $this->settings['use_form'] ):
			?>
			<form method='post'>
			<?php
		endif;

		if ( $this->settings['use_form'] ) {
			// security
			wp_nonce_field( $this->settings['id'], TF . '_nonce' );
		}

		?>
		<table class='form-table'>
			<tbody>
		<?php

		do_action( 'tf_admin_page_table_start' );
		do_action( 'tf_admin_page_table_start_' . $this->getOptionNamespace() );

		$activeTab = $this->getActiveTab();
		if ( ! empty( $activeTab ) ) {
			$activeTab->displayOptions();
		}

		foreach ( $this->options as $option ) {
			$option->display();
		}

		do_action( 'tf_admin_page_table_end' );
		do_action( 'tf_admin_page_table_end_' . $this->getOptionNamespace() );

		?>
			</tbody>
		</table>
		<?php

		if ( $this->settings['use_form'] ):
			?>
			</form>
			<?php
		endif;

		// Reset form. We use JS to trigger a reset from other buttons within the main form
		// This is used by class-option-save.php
		if ( $this->settings['use_form'] ):
			?>
			<form method='post' id='tf-reset-form'>
				<?php
				// security
				wp_nonce_field( $this->settings['id'], TF . '_nonce' );
				?>
				<input type='hidden' name='action' value='reset'/>
			</form>
			<?php
		endif;

		do_action( 'tf_admin_page_end' );
		do_action( 'tf_admin_page_end_' . $this->getOptionNamespace() );

		?>
		<div class='options-container'>
		</div>
		</div>
		</div>
		<?php

		do_action( 'tf_admin_page_after' );
		do_action( 'tf_admin_page_after_' . $this->getOptionNamespace() );
	}

	public function createTab( $settings ) {
		$obj = new TitanFrameworkAdminTab( $settings, $this );
		$this->tabs[] = $obj;
		return $obj;
	}

	public function createOption( $settings ) {
		if ( ! apply_filters( 'tf_create_option_continue_' . $this->getOptionNamespace(), true, $settings ) ) {
			return null;
		}

		$obj = TitanFrameworkOption::factory( $settings, $this );
		$this->options[] = $obj;

		do_action( 'tf_create_option_' . $this->getOptionNamespace(), $obj );

		return $obj;
	}
}