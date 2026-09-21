<?php
/**
 * Plugin Name: Paradiso Lead Manager
 * Plugin URI: https://paradisosolutions.com/
 * Description: Captures & manages demo requests, provides CRM / Newsletter API Integration, Admin lead listing, CSV export, and [paradiso_solutions] shortcode.
 * Version: 1.2.0
 * Author: Paradiso Team
 * Text Domain: paradiso-lead-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Paradiso_Lead_Manager {

    private static $instance = null;
    private $table_name;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'paradiso_leads';

        // Activation Hook
        register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );

        // Admin Menus & Actions
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
        add_action( 'admin_post_export_paradiso_leads_csv', array( $this, 'export_leads_csv' ) );
        add_action( 'wp_ajax_test_paradiso_crm_api', array( $this, 'ajax_test_crm_api_connection' ) );

        // Form Submission Actions
        add_action( 'wp_ajax_submit_paradiso_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'wp_ajax_nopriv_submit_paradiso_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'admin_post_submit_paradiso_lead_post', array( $this, 'handle_lead_submission_post' ) );
        add_action( 'admin_post_nopriv_submit_paradiso_lead_post', array( $this, 'handle_lead_submission_post' ) );

        // Contact Form 7 Auto-Capture Integration
        add_action( 'wpcf7_before_send_mail', array( $this, 'capture_cf7_lead' ) );

        // Register Shortcodes
        add_shortcode( 'paradiso_solutions', array( $this, 'shortcode_paradiso_solutions' ) );
        add_shortcode( 'paradiso_lead_form', array( $this, 'shortcode_paradiso_lead_form' ) );
    }

    /**
     * Create custom DB table wp_paradiso_leads on plugin activation
     */
    public function activate_plugin() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(50) DEFAULT '',
            company varchar(255) DEFAULT '',
            message text DEFAULT '',
            api_status varchar(50) DEFAULT 'pending',
            api_response text DEFAULT '',
            created_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) {$charset_collate};";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Register Plugin Settings in WP Options
     */
    public function register_plugin_settings() {
        register_setting( 'paradiso_api_settings_group', 'paradiso_api_enabled' );
        register_setting( 'paradiso_api_settings_group', 'paradiso_crm_api_url' );
        register_setting( 'paradiso_api_settings_group', 'paradiso_crm_api_key' );
    }

    /**
     * Register Dashboard Menu for Lead Manager & API Settings
     */
    public function register_admin_menu() {
        add_menu_page(
            __( 'Paradiso Leads', 'paradiso-lead-manager' ),
            __( 'Lead Manager', 'paradiso-lead-manager' ),
            'manage_options',
            'paradiso-leads',
            array( $this, 'render_admin_leads_page' ),
            'dashicons-clipboard',
            30
        );

        add_submenu_page(
            'paradiso-leads',
            __( 'All Leads', 'paradiso-lead-manager' ),
            __( 'All Leads', 'paradiso-lead-manager' ),
            'manage_options',
            'paradiso-leads',
            array( $this, 'render_admin_leads_page' )
        );

        add_submenu_page(
            'paradiso-leads',
            __( 'CRM API Settings', 'paradiso-lead-manager' ),
            __( 'CRM API Settings', 'paradiso-lead-manager' ),
            'manage_options',
            'paradiso-api-settings',
            array( $this, 'render_admin_api_settings_page' )
        );
    }

    /**
     * Render Admin Leads Page
     */
    public function render_admin_leads_page() {
        global $wpdb;

        // Check if viewing single lead details
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'view' && isset( $_GET['lead_id'] ) ) {
            $this->render_lead_detail_view( intval( $_GET['lead_id'] ) );
            return;
        }

        // Handle Single Delete Action
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['lead_id'] ) && isset( $_GET['_wpnonce'] ) ) {
            if ( wp_verify_nonce( $_GET['_wpnonce'], 'delete_lead_' . intval( $_GET['lead_id'] ) ) ) {
                $wpdb->delete( $this->table_name, array( 'id' => intval( $_GET['lead_id'] ) ) );
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Lead deleted successfully.', 'paradiso-lead-manager' ) . '</p></div>';
            }
        }

        // Search & Fetch Leads
        $search = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
        $query  = "SELECT * FROM {$this->table_name}";
        if ( ! empty( $search ) ) {
            $query .= $wpdb->prepare( " WHERE name LIKE %s OR email LIKE %s OR company LIKE %s OR phone LIKE %s", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%" );
        }
        $query .= " ORDER BY created_date DESC";

        $leads = $wpdb->get_results( $query );
        $export_url = admin_url( 'admin-post.php?action=export_paradiso_leads_csv&_wpnonce=' . wp_create_nonce( 'export_leads_nonce' ) );
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Demo Request Leads', 'paradiso-lead-manager' ); ?></h1>
            <a href="<?php echo esc_url( $export_url ); ?>" class="page-title-action button-primary" style="margin-left: 10px;">
                <?php esc_html_e( 'Export Leads as CSV', 'paradiso-lead-manager' ); ?>
            </a>
            <hr class="wp-header-end">

            <!-- Search Form -->
            <form method="get" style="margin-bottom:15px; margin-top:15px;">
                <input type="hidden" name="page" value="paradiso-leads">
                <p class="search-box">
                    <label class="screen-reader-text" for="post-search-input"><?php esc_html_e( 'Search Leads:', 'paradiso-lead-manager' ); ?></label>
                    <input type="search" id="post-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by name, email, company...', 'paradiso-lead-manager' ); ?>">
                    <input type="submit" id="search-submit" class="button" value="<?php esc_attr_e( 'Search Leads', 'paradiso-lead-manager' ); ?>">
                </p>
            </form>

            <table class="wp-list-table widefat fixed striped table-view-list margin-top-20">
                <thead>
                    <tr>
                        <th scope="col" style="width:50px;">ID</th>
                        <th scope="col"><?php esc_html_e( 'Name', 'paradiso-lead-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Email', 'paradiso-lead-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Phone', 'paradiso-lead-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Company', 'paradiso-lead-manager' ); ?></th>
                        <th scope="col" style="width:100px;"><?php esc_html_e( 'API Status', 'paradiso-lead-manager' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Date', 'paradiso-lead-manager' ); ?></th>
                        <th scope="col" style="width:140px;"><?php esc_html_e( 'Actions', 'paradiso-lead-manager' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( ! empty( $leads ) ) : ?>
                        <?php foreach ( $leads as $lead ) : 
                            $view_url   = admin_url( 'admin.php?page=paradiso-leads&action=view&lead_id=' . $lead->id );
                            $delete_url = wp_nonce_url( admin_url( 'admin.php?page=paradiso-leads&action=delete&lead_id=' . $lead->id ), 'delete_lead_' . $lead->id );
                            $status_bg  = ( $lead->api_status === 'sent' ) ? '#d4edda' : ( ( $lead->api_status === 'failed' ) ? '#f8d7da' : '#fff3cd' );
                            $status_fg  = ( $lead->api_status === 'sent' ) ? '#155724' : ( ( $lead->api_status === 'failed' ) ? '#721c24' : '#856404' );
                            ?>
                            <tr>
                                <td><strong>#<?php echo esc_html( $lead->id ); ?></strong></td>
                                <td><strong><a href="<?php echo esc_url( $view_url ); ?>" class="row-title"><?php echo esc_html( $lead->name ); ?></a></strong></td>
                                <td><a href="mailto:<?php echo esc_attr( $lead->email ); ?>"><?php echo esc_html( $lead->email ); ?></a></td>
                                <td><?php echo esc_html( $lead->phone ? $lead->phone : '—' ); ?></td>
                                <td><?php echo esc_html( $lead->company ? $lead->company : '—' ); ?></td>
                                <td>
                                    <span style="display:inline-block; padding:3px 8px; border-radius:3px; font-size:11px; font-weight:bold; background:<?php echo $status_bg; ?>; color:<?php echo $status_fg; ?>;">
                                        <?php echo esc_html( strtoupper( $lead->api_status ? $lead->api_status : 'PENDING' ) ); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $lead->created_date ) ) ); ?></td>
                                <td>
                                    <a href="<?php echo esc_url( $view_url ); ?>" class="button button-secondary button-small"><?php esc_html_e( 'View Details', 'paradiso-lead-manager' ); ?></a>
                                    <a href="<?php echo esc_url( $delete_url ); ?>" class="button button-small button-link-delete" style="color: #a00;" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this lead?', 'paradiso-lead-manager' ); ?>');"><?php esc_html_e( 'Delete', 'paradiso-lead-manager' ); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" style="padding: 20px; text-align: center; color: #666;">
                                <?php if ( ! empty( $search ) ) : ?>
                                    <?php printf( esc_html__( 'No leads found matching "%s".', 'paradiso-lead-manager' ), esc_html( $search ) ); ?>
                                <?php else : ?>
                                    <?php esc_html_e( 'No lead entries found yet.', 'paradiso-lead-manager' ); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Render Single Lead Detail View
     */
    private function render_lead_detail_view( $lead_id ) {
        global $wpdb;
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE id = %d", $lead_id ) );

        if ( ! $lead ) {
            echo '<div class="wrap"><h2>' . esc_html__( 'Lead Not Found', 'paradiso-lead-manager' ) . '</h2></div>';
            return;
        }

        $back_url = admin_url( 'admin.php?page=paradiso-leads' );
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php printf( esc_html__( 'Lead Details: #%d - %s', 'paradiso-lead-manager' ), $lead->id, esc_html( $lead->name ) ); ?></h1>
            <a href="<?php echo esc_url( $back_url ); ?>" class="page-title-action">&larr; <?php esc_html_e( 'Back to Leads List', 'paradiso-lead-manager' ); ?></a>
            <hr class="wp-header-end">

            <div class="postbox" style="max-width:750px; padding:20px; margin-top:20px; background:#fff; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width:180px;"><strong><?php esc_html_e( 'Lead ID:', 'paradiso-lead-manager' ); ?></strong></th>
                        <td>#<?php echo esc_html( $lead->id ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><strong><?php esc_html_e( 'Submission Date:', 'paradiso-lead-manager' ); ?></strong></th>
                        <td><?php echo esc_html( $lead->created_date ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><strong><?php esc_html_e( 'Full Name:', 'paradiso-lead-manager' ); ?></strong></th>
                        <td><strong><?php echo esc_html( $lead->name ); ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><strong><?php esc_html_e( 'Email Address:', 'paradiso-lead-manager' ); ?></strong></th>
                        <td><a href="mailto:<?php echo esc_attr( $lead->email ); ?>"><?php echo esc_html( $lead->email ); ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row"><strong><?php esc_html_e( 'Phone Number:', 'paradiso-lead-manager' ); ?></strong></th>
                        <td><?php echo esc_html( $lead->phone ? $lead->phone : 'N/A' ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><strong><?php esc_html_e( 'Company Name:', 'paradiso-lead-manager' ); ?></strong></th>
                        <td><?php echo esc_html( $lead->company ? $lead->company : 'N/A' ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><strong><?php esc_html_e( 'CRM API Status:', 'paradiso-lead-manager' ); ?></strong></th>
                        <td>
                            <strong style="text-transform:uppercase;"><?php echo esc_html( $lead->api_status ); ?></strong>
                            <?php if ( $lead->api_response ) : ?>
                                <p class="description"><?php echo esc_html( $lead->api_response ); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><strong><?php esc_html_e( 'Message / Details:', 'paradiso-lead-manager' ); ?></strong></th>
                        <td>
                            <div style="background:#f9f9f9; padding:15px; border-radius:4px; border:1px solid #e5e5e5; white-space:pre-wrap;">
                                <?php echo esc_html( $lead->message ? $lead->message : 'No message text provided.' ); ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }

    /**
     * Task 3: Render Admin CRM API Settings Subpage
     */
    public function render_admin_api_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Third-Party CRM / Newsletter API Settings', 'paradiso-lead-manager' ); ?></h1>
            <p><?php esc_html_e( 'Configure API credentials to automatically send captured demo request leads to your CRM or Newsletter service (e.g. HubSpot, Salesforce, Mailchimp, or custom Webhook API).', 'paradiso-lead-manager' ); ?></p>
            <hr>

            <form method="post" action="options.php" style="max-width:750px; background:#fff; padding:20px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <?php
                settings_fields( 'paradiso_api_settings_group' );
                do_settings_sections( 'paradiso_api_settings_group' );
                ?>

                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable CRM API Integration', 'paradiso-lead-manager' ); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="paradiso_api_enabled" value="yes" <?php checked( get_option( 'paradiso_api_enabled' ), 'yes' ); ?>>
                                <?php esc_html_e( 'Automatically post new lead submissions to Third-Party API', 'paradiso-lead-manager' ); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="paradiso_crm_api_url"><?php esc_html_e( 'API Endpoint URL / Webhook', 'paradiso-lead-manager' ); ?></label></th>
                        <td>
                            <input type="url" id="paradiso_crm_api_url" name="paradiso_crm_api_url" value="<?php echo esc_attr( get_option( 'paradiso_crm_api_url' ) ); ?>" class="regular-text" placeholder="https://api.crm.com/v1/leads or webhook URL">
                            <p class="description"><?php esc_html_e( 'Enter the HTTP POST endpoint or Webhook URL provided by your CRM.', 'paradiso-lead-manager' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="paradiso_crm_api_key"><?php esc_html_e( 'API Key / Bearer Token', 'paradiso-lead-manager' ); ?></label></th>
                        <td>
                            <input type="password" id="paradiso_crm_api_key" name="paradiso_crm_api_key" value="<?php echo esc_attr( get_option( 'paradiso_crm_api_key' ) ); ?>" class="regular-text" placeholder="api_key_xxxxxxxxxxxxx">
                            <p class="description"><?php esc_html_e( 'Optional API Key or Authorization Token.', 'paradiso-lead-manager' ); ?></p>
                        </td>
                    </tr>
                </table>

                <?php submit_button( __( 'Save API Settings', 'paradiso-lead-manager' ) ); ?>
            </form>

            <!-- Test Connection Section -->
            <div style="max-width:750px; margin-top:20px; background:#fff; padding:20px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <h3><?php esc_html_e( 'Test API Connection', 'paradiso-lead-manager' ); ?></h3>
                <p><?php esc_html_e( 'Click the button below to send a test lead payload to verify your CRM API settings.', 'paradiso-lead-manager' ); ?></p>
                <button type="button" id="btn-test-crm-api" class="button button-secondary"><?php esc_html_e( 'Send Test API Payload', 'paradiso-lead-manager' ); ?></button>
                <div id="test-api-result" style="margin-top:15px; display:none; padding:12px; border-radius:4px;"></div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#btn-test-crm-api').on('click', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var $result = $('#test-api-result');
                $btn.prop('disabled', true).text('Testing API...');
                $result.hide().removeClass('notice-success notice-error');

                $.post(ajaxurl, {
                    action: 'test_paradiso_crm_api',
                    _nonce: '<?php echo wp_create_nonce("test_api_nonce"); ?>'
                }, function(response) {
                    $btn.prop('disabled', false).text('Send Test API Payload');
                    $result.show();
                    if (response.success) {
                        $result.css({'background':'#d4edda', 'color':'#155724', 'border':'1px solid #c3e6cb'}).html('<strong>Success:</strong> ' + response.data.message);
                    } else {
                        $result.css({'background':'#f8d7da', 'color':'#721c24', 'border':'1px solid #f5c6cb'}).html('<strong>Error:</strong> ' + response.data.message);
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Ajax Test CRM API Connection Handler
     */
    public function ajax_test_crm_api_connection() {
        check_ajax_referer( 'test_api_nonce', '_nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Unauthorized access.', 'paradiso-lead-manager' ) ) );
        }

        $test_data = array(
            'name'    => 'Test User',
            'email'   => 'test.lead@paradisosolutions.com',
            'phone'   => '+1 555-0199',
            'company' => 'Paradiso Test Corp',
            'message' => 'This is a test API lead submission from Paradiso Lead Manager.',
        );

        $api_result = $this->send_lead_to_crm_api( 0, $test_data );

        if ( $api_result['success'] ) {
            wp_send_json_success( array( 'message' => $api_result['message'] ) );
        } else {
            wp_send_json_error( array( 'message' => $api_result['message'] ) );
        }
    }

    /**
     * Task 3: Send Lead Data through Third-Party CRM API
     */
    public function send_lead_to_crm_api( $lead_id, $lead_data ) {
        $api_enabled = get_option( 'paradiso_api_enabled', 'no' );
        $api_url     = get_option( 'paradiso_crm_api_url', '' );
        $api_key     = get_option( 'paradiso_crm_api_key', '' );

        if ( 'yes' !== $api_enabled || empty( $api_url ) ) {
            return array( 'success' => false, 'message' => __( 'API Sync is disabled or Endpoint URL is empty.', 'paradiso-lead-manager' ) );
        }

        $payload = array(
            'lead_id'   => $lead_id,
            'name'      => $lead_data['name'],
            'email'     => $lead_data['email'],
            'phone'     => $lead_data['phone'],
            'company'   => $lead_data['company'],
            'message'   => $lead_data['message'],
            'source'    => 'Paradiso Lead Manager Plugin',
            'timestamp' => current_time( 'mysql' ),
        );

        $headers = array(
            'Content-Type' => 'application/json',
        );
        if ( ! empty( $api_key ) ) {
            $headers['Authorization'] = 'Bearer ' . $api_key;
            $headers['X-API-Key']      = $api_key;
        }

        $response = wp_remote_post( $api_url, array(
            'method'      => 'POST',
            'timeout'     => 15,
            'headers'     => $headers,
            'body'        => wp_json_encode( $payload ),
            'data_format' => 'body',
        ) );

        global $wpdb;

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            if ( $lead_id > 0 ) {
                $wpdb->update(
                    $this->table_name,
                    array(
                        'api_status'   => 'failed',
                        'api_response' => sanitize_text_field( 'WP_Error: ' . $error_message ),
                    ),
                    array( 'id' => $lead_id )
                );
            }
            return array( 'success' => false, 'message' => 'API Connection Error: ' . $error_message );
        }

        $code = wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );

        if ( $code >= 200 && $code < 300 ) {
            if ( $lead_id > 0 ) {
                $wpdb->update(
                    $this->table_name,
                    array(
                        'api_status'   => 'sent',
                        'api_response' => sanitize_text_field( 'HTTP ' . $code . ': ' . substr( $response_body, 0, 200 ) ),
                    ),
                    array( 'id' => $lead_id )
                );
            }
            return array( 'success' => true, 'message' => __( 'Lead synced successfully to CRM API (HTTP ' . $code . ').', 'paradiso-lead-manager' ) );
        } else {
            if ( $lead_id > 0 ) {
                $wpdb->update(
                    $this->table_name,
                    array(
                        'api_status'   => 'failed',
                        'api_response' => sanitize_text_field( 'HTTP ' . $code . ': ' . substr( $response_body, 0, 200 ) ),
                    ),
                    array( 'id' => $lead_id )
                );
            }
            return array( 'success' => false, 'message' => 'CRM API Returned Error HTTP Code ' . $code );
        }
    }

    /**
     * Export Leads as CSV Handler
     */
    public function export_leads_csv() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized user', 'paradiso-lead-manager' ) );
        }

        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'export_leads_nonce' ) ) {
            wp_die( __( 'Security check failed', 'paradiso-lead-manager' ) );
        }

        global $wpdb;
        $leads = $wpdb->get_results( "SELECT * FROM {$this->table_name} ORDER BY created_date DESC", ARRAY_A );

        $filename = 'paradiso_leads_' . date( 'Y-m-d_H-i-s' ) . '.csv';

        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );

        $output = fopen( 'php://output', 'w' );
        fputcsv( $output, array( 'ID', 'Name', 'Email', 'Phone', 'Company', 'Message', 'API Status', 'Created Date' ) );

        if ( ! empty( $leads ) ) {
            foreach ( $leads as $lead ) {
                fputcsv( $output, array(
                    $lead['id'],
                    $lead['name'],
                    $lead['email'],
                    $lead['phone'],
                    $lead['company'],
                    $lead['message'],
                    $lead['api_status'],
                    $lead['created_date'],
                ) );
            }
        }

        fclose( $output );
        exit;
    }

    /**
     * Contact Form 7 Auto-Capture Integration
     */
    public function capture_cf7_lead( $contact_form ) {
        if ( ! class_exists( 'WPCF7_Submission' ) ) {
            return;
        }

        $submission = WPCF7_Submission::get_instance();
        if ( ! $submission ) {
            return;
        }

        $posted_data = $submission->get_posted_data();
        if ( empty( $posted_data ) ) {
            return;
        }

        $name    = isset( $posted_data['your-name'] ) ? $posted_data['your-name'] : ( isset( $posted_data['name'] ) ? $posted_data['name'] : '' );
        $email   = isset( $posted_data['your-email'] ) ? $posted_data['your-email'] : ( isset( $posted_data['email'] ) ? $posted_data['email'] : '' );
        $phone   = isset( $posted_data['your-phone'] ) ? $posted_data['your-phone'] : ( isset( $posted_data['phone'] ) ? $posted_data['phone'] : '' );
        $company = isset( $posted_data['your-company'] ) ? $posted_data['your-company'] : ( isset( $posted_data['company'] ) ? $posted_data['company'] : '' );
        $message = isset( $posted_data['your-message'] ) ? $posted_data['your-message'] : ( isset( $posted_data['message'] ) ? $posted_data['message'] : '' );

        if ( is_array( $name ) ) {
            $name = implode( ' ', $name );
        }
        if ( is_array( $message ) ) {
            $message = implode( "\n", $message );
        }

        if ( ! empty( $name ) || ! empty( $email ) ) {
            $this->process_lead_data( array(
                'name'    => $name,
                'email'   => $email,
                'phone'   => $phone,
                'company' => $company,
                'message' => $message,
            ) );
        }
    }

    /**
     * Process Form POST
     */
    public function handle_lead_submission_post() {
        if ( isset( $_POST['paradiso_lead_nonce'] ) && wp_verify_nonce( $_POST['paradiso_lead_nonce'], 'paradiso_lead_submit' ) ) {
            $result = $this->process_lead_data( $_POST );
            $redirect = isset( $_POST['_wp_http_referer'] ) ? esc_url_raw( $_POST['_wp_http_referer'] ) : home_url();
            $status = $result ? 'success' : 'error';
            wp_redirect( add_query_arg( 'lead_submitted', $status, $redirect ) );
            exit;
        }
    }

    /**
     * Process Form AJAX
     */
    public function handle_lead_submission() {
        check_ajax_referer( 'paradiso_lead_submit', 'nonce' );
        $result = $this->process_lead_data( $_POST );

        if ( $result ) {
            wp_send_json_success( array( 'message' => __( 'Thank you! Your demo request has been submitted successfully.', 'paradiso-lead-manager' ) ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Could not submit your request. Please try again.', 'paradiso-lead-manager' ) ) );
        }
    }

    /**
     * DB Insert & API Trigger Helper
     */
    private function process_lead_data( $data ) {
        global $wpdb;

        $name    = isset( $data['name'] ) ? sanitize_text_field( $data['name'] ) : '';
        $email   = isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '';
        $phone   = isset( $data['phone'] ) ? sanitize_text_field( $data['phone'] ) : '';
        $company = isset( $data['company'] ) ? sanitize_text_field( $data['company'] ) : '';
        $message = isset( $data['message'] ) ? sanitize_textarea_field( $data['message'] ) : '';

        if ( empty( $name ) && empty( $email ) ) {
            return false;
        }

        $inserted = $wpdb->insert(
            $this->table_name,
            array(
                'name'         => $name ?: 'N/A',
                'email'        => $email ?: 'N/A',
                'phone'        => $phone,
                'company'      => $company,
                'message'      => $message,
                'api_status'   => 'pending',
                'created_date' => current_time( 'mysql' ),
            ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );

        if ( $inserted ) {
            $lead_id = $wpdb->insert_id;
            // Task 3: Send Lead Data to Third-Party API
            $this->send_lead_to_crm_api( $lead_id, array(
                'name'    => $name,
                'email'   => $email,
                'phone'   => $phone,
                'company' => $company,
                'message' => $message,
            ) );
            return true;
        }

        return false;
    }

    /**
     * Task 2 Shortcode: [paradiso_solutions]
     */
    public function shortcode_paradiso_solutions( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => -1,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
            'category'       => '',
        ), $atts, 'paradiso_solutions' );

        $args = array(
            'post_type'      => 'solution',
            'posts_per_page' => intval( $atts['posts_per_page'] ),
            'post_status'    => 'publish',
            'orderby'        => sanitize_text_field( $atts['orderby'] ),
            'order'          => sanitize_text_field( $atts['order'] ),
        );

        if ( ! empty( $atts['category'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'solution_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $atts['category'] ),
                ),
            );
        }

        $solutions_query = new WP_Query( $args );

        ob_start();
        ?>
        <section class="blog-page-section solutions-shortcode-section">
            <div class="auto-container">
                <div class="row clearfix">
                    
                    <?php if ( $solutions_query->have_posts() ) : 
                        $anim_classes = array( 'fadeInLeft', 'fadeInUp', 'fadeInRight' );
                        $index = 0;
                        while ( $solutions_query->have_posts() ) : $solutions_query->the_post(); 
                            $anim_class = $anim_classes[ $index % 3 ];
                            $index++;

                            // Fetch ACF Fields
                            $cta_text = function_exists( 'get_field' ) ? get_field( 'solution_cta_text' ) : '';
                            if ( empty( $cta_text ) ) {
                                $cta_text = __( 'Read More', 'paradiso-lead-manager' );
                            }

                            $cta_url = function_exists( 'get_field' ) ? get_field( 'solution_cta_url' ) : '';
                            if ( empty( $cta_url ) ) {
                                $cta_url = get_permalink();
                            }
                            ?>
                            <!-- Solution Block -->
                            <div class="news-block solution-block col-lg-4 col-md-6 col-sm-12">
                                <div class="inner-box wow <?php echo esc_attr( $anim_class ); ?>" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    
                                    <!-- Solution Image / Banner -->
                                    <div class="image">
                                        <a href="<?php echo esc_url( $cta_url ); ?>">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url( get_template_directory_uri() . '/images/resource/news-1.jpg' ); ?>" alt="<?php the_title_attribute(); ?>">
                                            <?php endif; ?>
                                        </a>
                                    </div>

                                    <!-- Lower Content -->
                                    <div class="lower-content">
                                        
                                        <!-- Solution Title -->
                                        <h4><a href="<?php echo esc_url( $cta_url ); ?>"><?php the_title(); ?></a></h4>

                                        <!-- Solution Description -->
                                        <div class="text">
                                            <?php 
                                            if ( has_excerpt() ) {
                                                echo wp_kses_post( get_the_excerpt() );
                                            } else {
                                                echo wp_kses_post( wp_trim_words( get_the_content(), 18, '...' ) );
                                            }
                                            ?>
                                        </div>

                                        <!-- Feature List -->
                                        <?php if ( function_exists( 'have_rows' ) && have_rows( 'solution_features' ) ) : ?>
                                            <div class="solution-features-box" style="background:#f8f9fa; padding:15px; border-left:3px solid #0066ff; margin-bottom:20px;">
                                                <strong class="features-label" style="display:block; font-size:12px; margin-bottom:5px; text-transform:uppercase; color:#222;"><?php esc_html_e( 'Features:', 'paradiso-lead-manager' ); ?></strong>
                                                <ul class="solution-features-list" style="list-style:none; padding:0; margin:0;">
                                                    <?php while ( have_rows( 'solution_features' ) ) : the_row(); 
                                                        $feature_item = get_sub_field( 'feature_item' ) ?: get_sub_field( 'feature_name' );
                                                        if ( ! empty( $feature_item ) ) :
                                                        ?>
                                                            <li style="font-size:14px; margin-bottom:4px;"><span class="check-icon flaticon-check-mark" style="color:#28a745; margin-right:5px;"></span> <?php echo esc_html( $feature_item ); ?></li>
                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>

                                        <!-- CTA Button -->
                                        <a class="read-more cta-btn" href="<?php echo esc_url( $cta_url ); ?>">
                                            <?php echo esc_html( $cta_text ); ?>
                                            <span class="arrow flaticon-long-arrow-pointing-to-the-right"></span>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        <?php endwhile; 
                        wp_reset_postdata();
                    else : ?>
                        <div class="col-12 text-center">
                            <p><?php esc_html_e( 'No solutions found.', 'paradiso-lead-manager' ); ?></p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Demo Request Form Shortcode: [paradiso_lead_form]
     */
    public function shortcode_paradiso_lead_form( $atts ) {
        ob_start();
        
        if ( isset( $_GET['lead_submitted'] ) && $_GET['lead_submitted'] === 'success' ) {
            echo '<div class="alert alert-success" style="padding:15px; background:#d4edda; color:#155724; border-radius:4px; margin-bottom:20px;">' . esc_html__( 'Thank you! Your demo request has been submitted and synced successfully.', 'paradiso-lead-manager' ) . '</div>';
        }
        ?>
        <div class="paradiso-lead-form-wrapper" style="background:#fff; padding:30px; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.08);">
            <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="paradiso-lead-form">
                <input type="hidden" name="action" value="submit_paradiso_lead_post">
                <?php wp_nonce_field( 'paradiso_lead_submit', 'paradiso_lead_nonce' ); ?>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="lead_name" style="display:block; font-weight:600; margin-bottom:5px;"><?php esc_html_e( 'Full Name *', 'paradiso-lead-manager' ); ?></label>
                    <input type="text" id="lead_name" name="name" required class="form-control" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="lead_email" style="display:block; font-weight:600; margin-bottom:5px;"><?php esc_html_e( 'Email Address *', 'paradiso-lead-manager' ); ?></label>
                    <input type="email" id="lead_email" name="email" required class="form-control" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="lead_phone" style="display:block; font-weight:600; margin-bottom:5px;"><?php esc_html_e( 'Phone Number', 'paradiso-lead-manager' ); ?></label>
                    <input type="text" id="lead_phone" name="phone" class="form-control" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="lead_company" style="display:block; font-weight:600; margin-bottom:5px;"><?php esc_html_e( 'Company Name', 'paradiso-lead-manager' ); ?></label>
                    <input type="text" id="lead_company" name="company" class="form-control" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                </div>

                <div class="form-group" style="margin-bottom:20px;">
                    <label for="lead_message" style="display:block; font-weight:600; margin-bottom:5px;"><?php esc_html_e( 'Message / Details', 'paradiso-lead-manager' ); ?></label>
                    <textarea id="lead_message" name="message" rows="4" class="form-control" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;"></textarea>
                </div>

                <button type="submit" class="theme-btn btn-style-one" style="background:#0066ff; color:#fff; padding:12px 30px; border:none; border-radius:4px; font-weight:600; cursor:pointer;">
                    <?php esc_html_e( 'Request Demo', 'paradiso-lead-manager' ); ?>
                </button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize Plugin
Paradiso_Lead_Manager::get_instance();