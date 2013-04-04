<?php
/*
 *
 * Options Framework Theme - Import Default Content
 */

class OptionsFramework_Import_Default_Content {

    var $admin_page;
    var $token;

    function OptionsFramework_Import_Default_Content() {
        $this->admin_page = '';
        $this->token = 'options-import-default-content';
    }

// End Constructor

    /**
     * init()
     */
    function init() {
        if ( is_admin() ) {
			// Register the admin screen.
            add_action( 'admin_menu', array( &$this, 'register_admin_import_screen' ), 20 );
        }
    }

// End init()

    /**
     * register_admin_import_screen()
     *
     * Register the admin screen within WordPress.
     */
    function register_admin_import_screen() {

        $this->admin_page = add_theme_page( __( 'Cumico Import Default Content', 'okthemes' ), __( 'Default Content', 'okthemes' ), 'manage_options', $this->token, array( &$this, 'admin_screen' ) );

        // Admin screen logic.
        add_action( 'load-' . $this->admin_page, array( &$this, 'admin_screen_logic' ) );

        add_action( 'admin_notices', array( &$this, 'admin_notices' ), 10 );
    }

    // End register_admin_import_screen()

    /**
     * admin_screen()
     *
     * Load the admin screen.
     */
    function admin_screen() {
        ?>
        
        <div class="wrap">
        <form enctype="multipart/form-data" method="post" action="<?php echo admin_url( 'admin.php?page=' . $this->token ); ?>">
        <?php wp_nonce_field( 'OptionsFramework-default-content' ); ?>
        <input type="hidden" name="OptionsFramework-default-content" value="true" />
    
            <h2>Cumico Import Default Content</h2>
            <p>This area allows you to install the default theme content for easy management.</p>
            <p style="color:#E07B7B"><strong>Preinstall notes:</strong></p>
            <ul style="color:#E07B7B">
            	<li>Make sure you run this imports on a <strong>clean WordPress intallation</strong>, otherwise unpredictable errors may occure.</li>
                <li>Make sure you <strong>installed WooCommerce plugin</strong> before running this imports.</li>
                <li>Follow the bellow steps in the <strong>exact given order</strong>.</li>
            </ul>
            
            <h3><strong>Step 1.</strong> Import Default Content <em>(pages, posts, products)</em></h3>
            <p>Please download the <a href="<?php echo get_stylesheet_directory_uri().'/cumico_default_content.xml';?>">sample content file (right click - save as)</a> to your desktop, and then go to the <a href="<?php echo admin_url('import.php');?>">Import</a> option and choose WordPress (install the plugin if needed). Once you are there, run the importer by uploading the sample content file. This will load in all the Pages, Posts and Sample Products. </p>
            
            <h3><strong>Step 2.</strong> Import Default Settings:</h3>
            <p>If you wish to setup other default content, please check the options below. Do this <strong>after</strong> importing default content above (Step 1).</p>
    		
            <p style="color:#E07B7B">Please watch this <a href="http://www.youtube.com/watch?v=urfRA6fgty4"><strong>video tutorial</strong></a> if you experince problems.</p>
            
            <ul>
                <li><input type="checkbox" name="default_content[theme]" value="1"> (1) Import Theme Settings</li>
                <li><input type="checkbox" name="default_content[settings]" value="1"> (2) Import WooCommerce Settings</li>
                <li><input type="checkbox" name="default_content[menu]" value="1"> (3) Import Menu Settings</li>
                <li><input type="checkbox" name="default_content[widgets]" value="1"> (4) Import Sidebars and Widgets
                <input type="hidden" name="widget_positions" value="YToyMDp7czoxODoib3JwaGFuZWRfd2lkZ2V0c18yIjthOjE6e2k6MDtzOjI5OiJ3b29jb21tZXJjZV9yYW5kb21fcHJvZHVjdHMtMiI7fXM6MTg6Im9ycGhhbmVkX3dpZGdldHNfMSI7YToyOntpOjA7czoyMDoicHJvZHVjdF9jYXRlZ29yaWVzLTIiO2k6MTtzOjE0OiJwcmljZV9maWx0ZXItMiI7fXM6MTg6Im9ycGhhbmVkX3dpZGdldHNfNSI7YToxOntpOjA7czoyOToid29vY29tbWVyY2VfcmFuZG9tX3Byb2R1Y3RzLTMiO31zOjc6ImNhdGFsb2ciO2E6Mzp7aTowO3M6MjA6InByb2R1Y3RfY2F0ZWdvcmllcy0yIjtpOjE7czoxNDoicHJpY2VfZmlsdGVyLTIiO2k6MjtzOjI5OiJ3b29jb21tZXJjZV9yYW5kb21fcHJvZHVjdHMtMiI7fXM6MTk6IndwX2luYWN0aXZlX3dpZGdldHMiO2E6MTA6e2k6MDtzOjEwOiJhcmNoaXZlcy0yIjtpOjE7czo2OiJ0ZXh0LTQiO2k6MjtzOjEyOiJjYXRlZ29yaWVzLTIiO2k6MztzOjE0OiJyZWNlbnQtcG9zdHMtMiI7aTo0O3M6MTc6InJlY2VudC1jb21tZW50cy0yIjtpOjU7czoyMToic2hvcnRjb2Rlcy11bHRpbWF0ZS0yIjtpOjY7czoyMToic29jaWFsLWljb25zLXdpZGdldC0zIjtpOjc7czoxNjoiY29udGFjdC13aWRnZXQtNCI7aTo4O3M6MzE6InRlc3RpbW9uaWFsc19wb3N0X3R5cGVfd2lkZ2V0LTIiO2k6OTtzOjE0OiJwcmljZV9maWx0ZXItMiI7fXM6MTk6InByaW1hcnktd2lkZ2V0LWFyZWEiO2E6NTp7aTowO3M6Mjg6InBvcnRmb2xpb19wb3N0X3R5cGVfd2lkZ2V0LTMiO2k6MTtzOjExOiJ0YWdfY2xvdWQtMiI7aToyO3M6MTI6ImNhdGVnb3JpZXMtNCI7aTozO3M6MTA6ImFyY2hpdmVzLTMiO2k6NDtzOjE3OiJyZWNlbnQtY29tbWVudHMtMyI7fXM6MjE6InNlY29uZGFyeS13aWRnZXQtYXJlYSI7YToyOntpOjA7czoyMDoicHJvZHVjdF9jYXRlZ29yaWVzLTMiO2k6MTtzOjE0OiJwcmljZV9maWx0ZXItMyI7fXM6MjE6InBvcnRmb2xpby13aWRnZXQtYXJlYSI7YToyOntpOjA7czozMToidGVzdGltb25pYWxzX3Bvc3RfdHlwZV93aWRnZXQtNCI7aToxO3M6MTU6ImZsaWNrci13aWRnZXQtMiI7fXM6MTk6ImNvbnRhY3Qtd2lkZ2V0LWFyZWEiO2E6Mjp7aTowO3M6MTY6ImNvbnRhY3Qtd2lkZ2V0LTIiO2k6MTtzOjIxOiJzb2NpYWwtaWNvbnMtd2lkZ2V0LTIiO31zOjI0OiJmaXJzdC1mb290ZXItd2lkZ2V0LWFyZWEiO2E6Mjp7aTowO3M6NjoidGV4dC0yIjtpOjE7czoxNjoiY29udGFjdC13aWRnZXQtMyI7fXM6MjU6InNlY29uZC1mb290ZXItd2lkZ2V0LWFyZWEiO2E6MTp7aTowO3M6NjoidGV4dC0zIjt9czoyNDoidGhpcmQtZm9vdGVyLXdpZGdldC1hcmVhIjthOjE6e2k6MDtzOjY6Im1ldGEtMiI7fXM6MjU6ImZvdXJ0aC1mb290ZXItd2lkZ2V0LWFyZWEiO2E6MTp7aTowO3M6MTY6InR3aXR0ZXItd2lkZ2V0LTIiO31zOjEwOiJteS1hY2NvdW50IjthOjE6e2k6MDtzOjE5OiJ3b29jb21tZXJjZV9sb2dpbi0zIjt9czoyODoiY3VzdG9tLXBhZ2UtZm9vdGVyLXNpZGViYXItNCI7YToxOntpOjA7czoxOToiZmVhdHVyZWQtcHJvZHVjdHMtMiI7fXM6Mjg6ImN1c3RvbS1wYWdlLWZvb3Rlci1zaWRlYmFyLTMiO2E6MTp7aTowO3M6ODoib25zYWxlLTIiO31zOjI4OiJjdXN0b20tcGFnZS1mb290ZXItc2lkZWJhci0yIjthOjE6e2k6MDtzOjI5OiJ3b29jb21tZXJjZV9yYW5kb21fcHJvZHVjdHMtMyI7fXM6Mjg6ImN1c3RvbS1wYWdlLWZvb3Rlci1zaWRlYmFyLTEiO2E6MTp7aTowO3M6MTc6InJlY2VudF9wcm9kdWN0cy0yIjt9czoxOToiY3VzdG9tLXBhZ2Utc2lkZWJhciI7YTo2OntpOjA7czoyNDoicG9zdHNfZnJvbV9ibG9nX3dpZGdldC0zIjtpOjE7czoyMjoiYWRzX3Bvc3RfdHlwZV93aWRnZXQtMiI7aToyO3M6Mjg6InBvcnRmb2xpb19wb3N0X3R5cGVfd2lkZ2V0LTIiO2k6MztzOjI3OiJzcG9uc29yc19wb3N0X3R5cGVfd2lkZ2V0LTIiO2k6NDtzOjIzOiJ0ZWFtX3Bvc3RfdHlwZV93aWRnZXQtMiI7aTo1O3M6MzE6InRlc3RpbW9uaWFsc19wb3N0X3R5cGVfd2lkZ2V0LTMiO31zOjEzOiJhcnJheV92ZXJzaW9uIjtpOjM7fQ==">
                <input type="hidden" name="widget_options" value="YToyNTp7czoyNzoid29vY29tbWVyY2VfcmFuZG9tX3Byb2R1Y3RzIjthOjM6e2k6MjthOjM6e3M6NToidGl0bGUiO3M6MTU6IlJhbmRvbSBwcm9kdWN0cyI7czo2OiJudW1iZXIiO2k6MjtzOjE1OiJzaG93X3ZhcmlhdGlvbnMiO2I6MDt9aTozO2E6Mzp7czo1OiJ0aXRsZSI7czoxNToiUmFuZG9tIHByb2R1Y3RzIjtzOjY6Im51bWJlciI7aTo1O3M6MTU6InNob3dfdmFyaWF0aW9ucyI7YjowO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czoxODoicHJvZHVjdF9jYXRlZ29yaWVzIjthOjM6e2k6MjthOjY6e3M6NToidGl0bGUiO3M6MTA6IkNhdGVnb3JpZXMiO3M6Nzoib3JkZXJieSI7czo1OiJvcmRlciI7czo1OiJjb3VudCI7aToxO3M6MTI6ImhpZXJhcmNoaWNhbCI7YjoxO3M6ODoiZHJvcGRvd24iO2k6MDtzOjE4OiJzaG93X2NoaWxkcmVuX29ubHkiO2k6MDt9aTozO2E6Njp7czo1OiJ0aXRsZSI7czoxMDoiQ2F0ZWdvcmllcyI7czo3OiJvcmRlcmJ5IjtzOjU6Im9yZGVyIjtzOjU6ImNvdW50IjtpOjE7czoxMjoiaGllcmFyY2hpY2FsIjtiOjE7czo4OiJkcm9wZG93biI7aTowO3M6MTg6InNob3dfY2hpbGRyZW5fb25seSI7aTowO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czoxMjoicHJpY2VfZmlsdGVyIjthOjM6e2k6MjthOjE6e3M6NToidGl0bGUiO3M6MTI6IlByaWNlIEZpbHRlciI7fWk6MzthOjE6e3M6NToidGl0bGUiO3M6MTQ6IlByb2R1Y3QgZmlsdGVyIjt9czoxMjoiX211bHRpd2lkZ2V0IjtpOjE7fXM6ODoiYXJjaGl2ZXMiO2E6Mzp7aToyO2E6Mzp7czo1OiJ0aXRsZSI7czowOiIiO3M6NToiY291bnQiO2k6MDtzOjg6ImRyb3Bkb3duIjtpOjA7fWk6MzthOjM6e3M6NToidGl0bGUiO3M6ODoiQXJjaGl2ZXMiO3M6NToiY291bnQiO2k6MDtzOjg6ImRyb3Bkb3duIjtpOjA7fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjQ6InRleHQiO2E6NDp7aToyO2E6Mzp7czo1OiJ0aXRsZSI7czowOiIiO3M6NDoidGV4dCI7czoxNzA6IjxpbWcgc3R5bGU9Im1hcmdpbi10b3A6MTVweDsgbWFyZ2luLWJvdHRvbToyMHB4OyIgc3JjPSJodHRwOi8vaHR0cC1zb2x1dGlvbnMuY29tL3RoZW1lcy9jdW1pY29kZW1vL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDEzLzAyL2Zvb3Rlci13aWRnZXQtbG9nby5wbmciIGFsdD0iRm9vdGVyIGxvZ28iIC8+IjtzOjY6ImZpbHRlciI7YjowO31pOjM7YTozOntzOjU6InRpdGxlIjtzOjE0OiJBcmJpdHJhcnkgdGV4dCI7czo0OiJ0ZXh0IjtzOjMyMDoiVml2YW11cyBldCBtZXR1cyBuaXNpLiBOdWxsYW0gbGVjdHVzIHNlbSwgcnV0cnVtIGV1IHBvcnRhIGFjLCBpbXBlcmRpZXQgc2l0IGFtZXQgbGFjdXMuIE51bGxhbSBzY2VsZXJpc3F1ZSB0ZW1wb3Igb2RpbyBwb3J0YSBzZW1wZXI/IE1vcmJpIGVyb3MgbWk7IGxvYm9ydGlzIGVnZXQgdGluY2lkdW50IGFjLCBwb3J0YSBlZ2V0IGVzdC4gTWF1cmlzIG1hZ25hIHJpc3VzLCBzb2RhbGVzIGV1IHRlbXBvciBldSwgZmV1Z2lhdCB2ZWhpY3VsYSBhdWd1ZS4gQ3VyYWJpdHVyIHZlbCBsaWd1bGEgYXQganVzdG8gc3VzY2lwaXQgYWxpcXVldCB1dCBldSB2b2x1dHBhdC4iO3M6NjoiZmlsdGVyIjtiOjA7fWk6NDthOjM6e3M6NToidGl0bGUiO3M6MTE6Ik91ciBwcm9ncmFtIjtzOjQ6InRleHQiO3M6ODc6IlZpdmFtdXMgZXQgbWV0dXMgbmlzaS4gTnVsbGFtIGxlY3R1cyBzZW0sIHJ1dHJ1bSBldSBwb3J0YSBhYywgaW1wZXJkaWV0IHNpdCBhbWV0IGxhY3VzLiI7czo2OiJmaWx0ZXIiO2I6MDt9czoxMjoiX211bHRpd2lkZ2V0IjtpOjE7fXM6MTA6ImNhdGVnb3JpZXMiO2E6Mzp7aToyO2E6NDp7czo1OiJ0aXRsZSI7czowOiIiO3M6NToiY291bnQiO2k6MDtzOjEyOiJoaWVyYXJjaGljYWwiO2k6MDtzOjg6ImRyb3Bkb3duIjtpOjA7fWk6NDthOjQ6e3M6NToidGl0bGUiO3M6MTA6IkNhdGVnb3JpZXMiO3M6NToiY291bnQiO2k6MDtzOjEyOiJoaWVyYXJjaGljYWwiO2k6MDtzOjg6ImRyb3Bkb3duIjtpOjA7fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjEyOiJyZWNlbnQtcG9zdHMiO2E6Mjp7aToyO2E6Mjp7czo1OiJ0aXRsZSI7czowOiIiO3M6NjoibnVtYmVyIjtpOjU7fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjE1OiJyZWNlbnQtY29tbWVudHMiO2E6Mzp7aToyO2E6Mjp7czo1OiJ0aXRsZSI7czowOiIiO3M6NjoibnVtYmVyIjtpOjU7fWk6MzthOjI6e3M6NToidGl0bGUiO3M6MTU6IlJlY2VudCBjb21tZW50cyI7czo2OiJudW1iZXIiO2k6NTt9czoxMjoiX211bHRpd2lkZ2V0IjtpOjE7fXM6MTk6InNob3J0Y29kZXMtdWx0aW1hdGUiO2E6Mjp7aToyO2E6Mjp7czo1OiJ0aXRsZSI7czoxOToiU2hvcnRjb2RlcyBVbHRpbWF0ZSI7czo3OiJjb250ZW50IjtzOjU5OiJbY29udGFjdF9mb3JtIGVtYWlsX2FkZHJlc3M9ImdvZ29uZWF0YV9jcmlzdGlhbkB5YWhvby5jb20iXSI7fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjE5OiJzb2NpYWwtaWNvbnMtd2lkZ2V0IjthOjM6e2k6MjthOjE6e3M6NToidGl0bGUiO3M6MTA6IkdldCBzb2NpYWwiO31pOjM7YToxOntzOjU6InRpdGxlIjtzOjEyOiJTb2NpYWwgSWNvbnMiO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czoxNDoiY29udGFjdC13aWRnZXQiO2E6NDp7aToyO2E6NTp7czo1OiJ0aXRsZSI7czoxMDoiQ29udGFjdCB1cyI7czo3OiJhZGRyZXNzIjtzOjE3OiJOZXcgWW9yaywgTlksIFVzYSI7czo1OiJwaG9uZSI7czoyMDoiKDQwKSAtIDU1NSA1NTUgNTU1NSAiO3M6MzoiZmF4IjtzOjE5OiIoNDApIC0gNTU1IDU1NSA1NTU2IjtzOjU6ImVtYWlsIjtzOjE1OiJlbWFpbEBlbWFpbC5jb20iO31pOjM7YTo1OntzOjU6InRpdGxlIjtzOjA6IiI7czo3OiJhZGRyZXNzIjtzOjE3OiJOZXcgWW9yaywgTlksIFVzYSI7czo1OiJwaG9uZSI7czoyMDoiKDQwKSAtIDU1NSA1NTUgNTU1NSAiO3M6MzoiZmF4IjtzOjE5OiIoNDApIC0gNTU1IDU1NSA1NTU2IjtzOjU6ImVtYWlsIjtzOjE1OiJlbWFpbEBlbWFpbC5jb20iO31pOjQ7YTo1OntzOjU6InRpdGxlIjtzOjEwOiJDb250YWN0IFVzIjtzOjc6ImFkZHJlc3MiO3M6MTc6Ik5ldyBZb3JrLCBOWSwgVXNhIjtzOjU6InBob25lIjtzOjIwOiIoNDApIC0gNTU1IDU1NSA1NTU1ICI7czozOiJmYXgiO3M6MTk6Iig0MCkgLSA1NTUgNTU1IDU1NTYiO3M6NToiZW1haWwiO3M6MTU6ImVtYWlsQGVtYWlsLmNvbSI7fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjI5OiJ0ZXN0aW1vbmlhbHNfcG9zdF90eXBlX3dpZGdldCI7YTo0OntpOjI7YTozOntzOjU6InRpdGxlIjtzOjEyOiJUZXN0aW1vbmlhbHMiO3M6NjoibnVtYmVyIjtpOjI7czo4OiJjYXJvdXNlbCI7aToxO31pOjM7YTozOntzOjU6InRpdGxlIjtzOjEyOiJUZXN0aW1vbmlhbHMiO3M6NjoibnVtYmVyIjtpOjU7czo4OiJjYXJvdXNlbCI7aToxO31pOjQ7YTozOntzOjU6InRpdGxlIjtzOjEyOiJUZXN0aW1vbmlhbHMiO3M6NjoibnVtYmVyIjtpOjU7czo4OiJjYXJvdXNlbCI7aToxO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czoyNjoicG9ydGZvbGlvX3Bvc3RfdHlwZV93aWRnZXQiO2E6Mzp7aToyO2E6Mzp7czo1OiJ0aXRsZSI7czo5OiJQb3J0Zm9saW8iO3M6NjoibnVtYmVyIjtpOjU7czo4OiJjYXJvdXNlbCI7aToxO31pOjM7YTozOntzOjU6InRpdGxlIjtzOjE4OiJGcm9tIG91ciBwb3J0Zm9saW8iO3M6NjoibnVtYmVyIjtpOjU7czo4OiJjYXJvdXNlbCI7aToxO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czo5OiJ0YWdfY2xvdWQiO2E6Mjp7aToyO2E6Mjp7czo1OiJ0aXRsZSI7czo0OiJUYWdzIjtzOjg6InRheG9ub215IjtzOjg6InBvc3RfdGFnIjt9czoxMjoiX211bHRpd2lkZ2V0IjtpOjE7fXM6MTM6ImZsaWNrci13aWRnZXQiO2E6Mjp7aToyO2E6Mzp7czo1OiJ0aXRsZSI7czoxODoiRmxpY2tyIFBob3Rvc3RyZWFtIjtzOjg6InVzZXJuYW1lIjtzOjE4OiJnb2dvbmVhdGFfY3Jpc3RpYW4iO3M6OToiaW1nX2xpbWl0IjtzOjE6IjYiO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czo0OiJtZXRhIjthOjI6e2k6MjthOjE6e3M6NToidGl0bGUiO3M6NDoiTWV0YSI7fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjE0OiJ0d2l0dGVyLXdpZGdldCI7YToyOntpOjI7YToxMDp7czo1OiJ0aXRsZSI7czoxMzoiTGF0ZXN0IHR3ZWV0cyI7czo4OiJ1c2VybmFtZSI7czo5OiJjc3NsdXh1cnkiO3M6NToicG9zdHMiO3M6MToiMiI7czo4OiJpbnRlcnZhbCI7czo0OiIxODAwIjtzOjQ6ImRhdGUiO3M6NToiaiBGIFkiO3M6MTE6ImRhdGVkaXNwbGF5IjtzOjI6Im9uIjtzOjk6ImRhdGVicmVhayI7czoyOiJvbiI7czo5OiJjbGlja2FibGUiO3M6Mjoib24iO3M6MTA6ImhpZGVlcnJvcnMiO3M6Mjoib24iO3M6MTM6ImVuY29kZXNwZWNpYWwiO047fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjE3OiJ3b29jb21tZXJjZV9sb2dpbiI7YTozOntpOjM7YToyOntzOjE2OiJsb2dnZWRfb3V0X3RpdGxlIjtzOjE0OiJDdXN0b21lciBMb2dpbiI7czoxNToibG9nZ2VkX2luX3RpdGxlIjtzOjEwOiJXZWxjb21lICVzIjt9aTo0O2E6Mjp7czoxNjoibG9nZ2VkX291dF90aXRsZSI7czoxNDoiQ3VzdG9tZXIgTG9naW4iO3M6MTU6ImxvZ2dlZF9pbl90aXRsZSI7czoxMDoiV2VsY29tZSAlcyI7fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjE3OiJmZWF0dXJlZC1wcm9kdWN0cyI7YToyOntpOjI7YToyOntzOjU6InRpdGxlIjtzOjE3OiJGZWF0dXJlZCBwcm9kdWN0cyI7czo2OiJudW1iZXIiO2k6NTt9czoxMjoiX211bHRpd2lkZ2V0IjtpOjE7fXM6Njoib25zYWxlIjthOjI6e2k6MjthOjI6e3M6NToidGl0bGUiO3M6MTY6Ik9uIHNhbGUgcHJvZHVjdHMiO3M6NjoibnVtYmVyIjtpOjU7fXM6MTI6Il9tdWx0aXdpZGdldCI7aToxO31zOjE1OiJyZWNlbnRfcHJvZHVjdHMiO2E6Mjp7aToyO2E6Mzp7czo1OiJ0aXRsZSI7czoxNToiUmVjZW50IHByb2R1Y3RzIjtzOjY6Im51bWJlciI7aTo1O3M6MTU6InNob3dfdmFyaWF0aW9ucyI7aTowO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czoyMjoicG9zdHNfZnJvbV9ibG9nX3dpZGdldCI7YToyOntpOjM7YTozOntzOjU6InRpdGxlIjtzOjE1OiJQb3N0cyBmcm9tIGJsb2ciO3M6NjoibnVtYmVyIjtpOjU7czo4OiJjYXJvdXNlbCI7aToxO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czoyMDoiYWRzX3Bvc3RfdHlwZV93aWRnZXQiO2E6Mjp7aToyO2E6Mzp7czo1OiJ0aXRsZSI7czozOiJBZHMiO3M6NjoibnVtYmVyIjtpOjU7czo4OiJjYXJvdXNlbCI7aToxO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9czoyNToic3BvbnNvcnNfcG9zdF90eXBlX3dpZGdldCI7YToyOntpOjI7YTozOntzOjU6InRpdGxlIjtzOjg6IlNwb25zb3JzIjtzOjY6Im51bWJlciI7aTo1O3M6ODoiY2Fyb3VzZWwiO2k6MTt9czoxMjoiX211bHRpd2lkZ2V0IjtpOjE7fXM6MjE6InRlYW1fcG9zdF90eXBlX3dpZGdldCI7YToyOntpOjI7YTozOntzOjU6InRpdGxlIjtzOjQ6IlRlYW0iO3M6NjoibnVtYmVyIjtpOjU7czo4OiJjYXJvdXNlbCI7aToxO31zOjEyOiJfbXVsdGl3aWRnZXQiO2k6MTt9fQ==">
                </li>
            </ul>
    
            <input type="submit" class="button-primary" value="<?php _e('Import','okthemes') ?>" />
        </form>
        </div>
        
        
        <?php
    }

// End admin_screen()

    /**
     * admin_notices()
     *
     * Display admin notices
     */
    function admin_notices() {

        if ( !isset( $_GET['page'] ) || ( $_GET['page'] != $this->token ) ) {
            return;
        }

        if ( isset( $_GET['error'] ) && $_GET['error'] == 'true' ) {
            echo '<div id="message" class="error"><p>' . __( 'There was a problem importing your settings. Please Try again.', 'okthemes' ) . '</p></div>';
       } else if ( isset( $_GET['imported'] ) && $_GET['imported'] == 'true' ) {
            echo '<div id="message" class="updated"><p>' . sprintf( __( 'Settings successfully imported. | Go to %sTheme Options%s', 'okthemes' ), '<a href="' . admin_url( 'admin.php?page=options-framework' ) . '">', '</a>' ) . '</p></div>';
        } // End IF Statement
    }

// End admin_notices()

    /**
     * admin_screen_logic()
     */
    function admin_screen_logic() {

        if ( isset( $_POST['OptionsFramework-default-content'] ) && ( $_POST['OptionsFramework-default-content'] == true ) ) {
            $this->import_def_content();
        }
    }

// End admin_screen_logic()

    /**
     * import()
     *
     * Import settings
     */
    function import_def_content() {
        

        if ( isset( $_REQUEST['OptionsFramework-default-content'] ) && 'true' == $_REQUEST['OptionsFramework-default-content'] ) {
        check_admin_referer( 'OptionsFramework-default-content' ); // Security check.

        // save all options:
        $data = $_POST;

        if(isset($data['default_content']) && is_array($data['default_content'])){
			
			if(isset($data['default_content']['theme'])&&$data['default_content']['theme']){
				
				$str_data='{"layout_width":"layout_width_960","layout_style":"full","body_background":{"color":"#ffffff","image":"","repeat":"no-repeat","position":"top left","attachment":"scroll"},"pattern_background":"pat09","page_layout":"right","responsiveness":"1","use_logo_image":"1","header_logo":"http:\/\/http-solutions.com\/themes\/cumicodemo\/wp-content\/uploads\/2012\/10\/logo1.png","logo_width":"312","logo_height":"29","display_site_tagline":false,"use_favicon":false,"favicon_logo":"","use_wp_admin_logo":false,"wp_admin_logo":"","general_link_color":"#5c5c5c","general_link_hover_color":"#e07b7b","footer_link_color":"#dedede","footer_link_hover_color":"#e07b7b","body_typography":{"size":"13px","face":"Bitter, sans-serif","style":"normal","color":"#575757"},"menu_typography":{"size":"11px","face":"Bitter, sans-serif","style":"normal","color":"#3f3f3f"},"footer_typography":{"size":"13px","face":"Bitter, sans-serif","style":"normal","color":"#999999"},"h1_typography":{"size":"30px","face":"Bitter, sans-serif","style":"normal","color":"#e07b7b"},"h2_typography":{"size":"24px","face":"Bitter, sans-serif","style":"normal","color":"#e07b7b"},"h3_typography":{"size":"18px","face":"Bitter, sans-serif","style":"normal","color":"#e07b7b"},"h4_typography":{"size":"14px","face":"Bitter, sans-serif","style":"bold","color":"#e07b7b"},"h5_typography":{"size":"12px","face":"Bitter, sans-serif","style":"bold","color":"#e07b7b"},"page_title":{"size":"30px","face":"Bitter, sans-serif","style":"normal","color":"#e07b7b"},"page_headline":{"size":"36px","face":"Bitter, sans-serif","style":"normal","color":"#dfe8ec"},"sidebar_widget_heading":{"size":"13px","face":"Bitter, sans-serif","style":"bold","color":"#e07b7b"},"sidebar_custom_widget_heading":{"size":"13px","face":"Bitter, sans-serif","style":"bold","color":"#b1c7d3"},"footer_widget_heading":{"size":"13px","face":"Bitter, sans-serif","style":"bold","color":"#ffffff"},"homepage_widget_heading":{"size":"13px","face":"Bitter, sans-serif","style":"bold","color":"#282727"},"top_header_menu":{"size":"10px","face":"Bitter, sans-serif","style":"normal","color":"#999999"},"modules_title":{"size":"18px","face":"Bitter, sans-serif","style":"normal","color":"#282727"},"product_page_title":{"size":"30px","face":"Bitter, sans-serif","style":"normal","color":"#282727"},"product_page_price":{"size":"18px","face":"Bitter, sans-serif","style":"normal","color":"#e07b7b"},"breadcrumbs_typo":{"size":"10px","face":"Bitter, sans-serif","style":"normal","color":"#999999"},"general_border_color":"#ededed","general_hover_border_color":"#cedbe1","bullet_element_color":"#00a8ff","hr_element_color":"#ededed","footer_background_color":"#383838","sidebar_background_color":"#e0e8ed","sidebar_list_background_color":"#f3f4f5","navigation_elements_background":"#dee7eb","buttons_background":"#dee7eb","form_elements_border_color":"#d9d9d9","header_search_input_background":"#e0e8ed","header_minicart_background":"#e0e8ed","header_minicart_list_background":"#f3f4f5","menu_background_color":"#e0e8ed","submenu_background_color":"#f3f4f5","submenu_hover_background_color":"#e5eaed","product_tab_border_top":"#98c0d6","homepage_layout":"without_sidebar","homepage_sidebar_select":"secondary-widget-area","headline_title":"Main headline goes here","headline_desc":"","headline_title_link":"","featured_products_title":"Featured products","featured_products_posts":"12","featured_products_orderby":"date","featured_products_order":"DESC","featured_products_carousel":"yes","featured_products_carousel_autoplay":"false","sale_products_title":"Sale products","sale_products_posts":"12","sale_products_orderby":"date","sale_products_order":"DESC","sale_products_carousel":"yes","sale_products_carousel_autoplay":"false","recent_products_title":"Recent products","recent_products_posts":"12","recent_products_orderby":"date","recent_products_order":"DESC","recent_products_carousel":"yes","recent_products_carousel_autoplay":"false","best_selling_products_title":"Best selling products","best_selling_products_posts":"12","best_selling_products_orderby":"date","best_selling_products_order":"DESC","best_selling_products_carousel":"yes","best_selling_products_carousel_autoplay":"false","products_by_id_title":"Products by id","products_ids":"","products_by_id_orderby":"date","products_by_id_order":"DESC","products_by_id_carousel":"yes","products_by_id_carousel_autoplay":"false","products_category_title":"Product categories","products_category_posts":"12","products_category_orderby":"date","products_category_order":"DESC","products_category_carousel":"yes","products_category_carousel_autoplay":"false","products_by_category_slug_title":"Products by category slug","products_by_category_slug_name":"","products_by_category_slug_posts":"12","products_by_category_slug_orderby":"date","products_by_category_slug_order":"DESC","products_by_category_slug_carousel":"yes","products_by_category_slug_carousel_autoplay":"false","ads_title":"Ads title","ads_posts":"12","ads_orderby":"date","ads_order":"DESC","ads_carousel":"yes","ads_carousel_autoplay":"false","portfolio_title":"Portfolio title","portfolio_posts":"12","portfolio_categories":"","portfolio_orderby":"date","portfolio_order":"DESC","portfolio_carousel":"yes","portfolio_carousel_autoplay":"false","testimonials_title":"Testimonials title","testimonials_posts":"12","testimonials_orderby":"date","testimonials_order":"DESC","testimonials_carousel":"yes","testimonials_carousel_autoplay":"false","sponsors_title":"Sponsors title","sponsors_posts":"12","sponsors_orderby":"date","sponsors_order":"DESC","sponsors_carousel":"yes","sponsors_carousel_autoplay":"false","team_title":"Team title","team_posts":"12","team_orderby":"date","team_order":"DESC","team_carousel":"yes","team_carousel_autoplay":"false","homepage_sorter":{"enabled":{"placebo":"placebo","headline_area":"Homepage headline","featured_products":"Featured products","sale_products":"Sale products","ads":"Ads","sponsors":"Sponsors"},"disabled":{"placebo":"placebo","best_selling_products":"Best selling products","products_by_ids":"Products by ids","recent_products":"Recent products","product_categories":"Product categories","products_by_category_slug":"Products by category slug","portfolio":"Portfolio","testimonials":"Testimonials","team":"Team"}},"slideshow_select":"flexslider","slideshow_select_categories":"Flexslider","slideshow_nr_posts":"5","flexslider_auto_animate":"false","flexslider_auto_animate_speed":"7000","flexslider_navigation":"true","flexslider_slide_effect":"fade","sequence_auto_animate":"false","sequence_auto_animate_speed":"3000","sequence_starting_frame":"true","sequence_pause_hover":"false","elastic_auto_animate":"false","elastic_auto_animate_speed":"3000","elastic_easing_speed":"800","elastic_title_speed":"1200","iview_slide_effect":"random","iview_auto_animate_speed":"500","iview_caption_speed":"500","iview_pause_time":"5000","iview_pause_hover":"false","slit_auto_animate":"false","slit_auto_animate_speed":"4000","slit_transition_speed":"800","jmpress_auto_animate":"false","jmpress_auto_animate_speed":"3500","jmpress_arrows_animation":"false","jmpress_bullet_animation":"true","product_cats":{"68":"0","59":"0","82":"0","85":"0","75":"0","52":"0"},"slideshow_plugin_shortcode":"","store_catalog_mode":false,"product_cloud_zoom":"1","mega_dropdown_ad":"http:\/\/http-solutions.com\/themes\/cumicodemo\/wp-content\/uploads\/2013\/02\/mega-ad-1.png","mega_dropdown_ad_link":"","mega_dropdown_ad_desc":"","product_per_page":"12","store_header_cart":"1","store_header_search":"1","store_breadcrumbs":"1","shop_grid_list_default":"grid","store_sale_flash":"1","store_products_price":"1","store_add_to_cart":"1","shop_layout":"without_sidebar","shop_sidebar_select":"secondary-widget-area","shop_grid_list_view":"1","shop_grid_desc":false,"catalog_layout":"with_sidebar","catalog_sidebar_select":"secondary-widget-area","catalog_grid_list_view":"1","catalog_grid_desc":false,"catalog_category_img":false,"catalog_category_desc":false,"product_sale_flash":"1","product_products_price":"1","product_products_excerpt":"1","product_products_meta":"1","product_add_to_cart":"1","product_related_products":"1","product_upsells_products":"1","product_reviews_tab":"1","product_description_tab":"1","product_attributes_tab":"1","product_crosssells_products":"1","portfolio_related_posts":"1","portfolio_related_posts_title":"Related posts","portfolio_project_details":"1","portfolio_project_details_title":"Project details","blog_inner_image":"1","rss_link":"","facebook_link":"link_goes_here","twitter_link":"link_goes_here","skype_link":"link_goes_here","vimeo_link":"link_goes_here","linkedin_link":"link_goes_here","dribble_link":"link_goes_here","forrst_link":"","flickr_link":"","google_link":"","youtube_link":"","tumblr_link":"","behance_link":"","personal_link":"","seo_meta_desc":"","seo_meta_keywords":"","sidebar_list":[{"id":"my-account","name":"My Account"},{"id":"custom-page-footer-sidebar-4","name":"Custom page footer sidebar 4"},{"id":"custom-page-footer-sidebar-3","name":"Custom page footer sidebar 3"},{"id":"custom-page-footer-sidebar-2","name":"Custom page footer sidebar 2"},{"id":"custom-page-footer-sidebar-1","name":"Custom page footer sidebar 1"},{"id":"custom-page-sidebar","name":"Custom page sidebar"}],"footer_image":"http:\/\/http-solutions.com\/themes\/cumicodemo\/wp-content\/uploads\/2013\/01\/footer-image.png","footer_image_link":"","footer_copyright":"Copyright 2012 - All rights reserved","footer_scripts":"","OptionsFramework-backup-validator":"2013-03-21 10:49:54"}';
				$datafile = json_decode($str_data,true);
				
				 $optionsframework_data = get_option( 'optionsframework' );
       			 $optionsframework_name = $optionsframework_data['id'];
				 
				 update_option( $optionsframework_name, $datafile );
					 
			}
			
			if(isset($data['default_content']['settings'])&&$data['default_content']['settings']){
				
				//Shop Page
                $shop_page = get_page_by_title('Shop');
				if($shop_page && $shop_page->ID){
					update_option('woocommerce_shop_page_id',$shop_page->ID);
				}
				
				//Cart Page
                $cart_page = get_page_by_title('Cart');
				if($cart_page && $cart_page->ID){
					update_option('woocommerce_cart_page_id',$cart_page->ID);
				}
				
				//Checkout Page
                $checkout_page = get_page_by_title('Checkout');
				if($checkout_page && $checkout_page->ID){
					update_option('woocommerce_checkout_page_id',$checkout_page->ID);
				}
				
				//Pay Page
                $pay_page = get_page_by_title('Checkout - Pay');
				if($pay_page && $pay_page->ID){
					update_option('woocommerce_pay_page_id',$pay_page->ID);
				}
				
				//My Account Page
                $myaccount_page = get_page_by_title('My Account');
				if($myaccount_page && $myaccount_page->ID){
					update_option('woocommerce_myaccount_page_id',$myaccount_page->ID);
				}
				
				//Edit Address Page
                $edit_address_page = get_page_by_title('Edit My Address');
				if($edit_address_page && $edit_address_page->ID){
					update_option('woocommerce_edit_address_page_id',$edit_address_page->ID);
				}
				
				//View Order Page
                $view_order_page = get_page_by_title('View Order');
				if($view_order_page && $view_order_page->ID){
					update_option('woocommerce_view_order_page_id',$view_order_page->ID);
				}
				
				//Change Password Page
                $change_password_page = get_page_by_title('Change Password');
				if($change_password_page && $change_password_page->ID){
					update_option('woocommerce_change_password_page_id',$change_password_page->ID);
				}
				
				//Order Received Page
                $thanks_page = get_page_by_title('Order Received');
				if($thanks_page && $thanks_page->ID){
					update_option('woocommerce_thanks_page_id',$thanks_page->ID);
				}
				
				//Logout Page
                $logout_page = get_page_by_title('Logout');
				if($logout_page && $logout_page->ID){
					update_option('woocommerce_logout_page_id',$logout_page->ID);
				}
				
				// Image sizes > 1.6
				update_option( 'shop_thumbnail_image_size', array('width' => '90', 'height' => '90', 'crop' => true) ); // Image gallery thumbs
				update_option( 'shop_single_image_size', array('width' => '360', 'height' => '360', 'crop' => true) ); // Featured product image
				update_option( 'shop_catalog_image_size', array('width' => '180', 'height' => '180', 'crop' => true) ); // Product category thumbs
				
            }

            if(isset($data['default_content']['menu'])&&$data['default_content']['menu']){
                $menus = get_terms('nav_menu');
                $save = array();
                foreach($menus as $menu){
                    if($menu->name == 'Main Navigation'){
                        $save['primary'] = $menu->term_id;
                    }else if($menu->name == 'Footer nav'){
                        $save['secondary'] = $menu->term_id;
                    }else if($menu->name == 'Customer Navigation'){
                        $save['tertiary'] = $menu->term_id;
                    }
                }
                if($save){
                    set_theme_mod( 'nav_menu_locations', array_map( 'absint', $save ) );
                }
            }
            if(isset($data['default_content']['widgets'])&&$data['default_content']['widgets']){
                $export = false;
                if($export){
                    // export widgets
                    $widget_positions = get_option('sidebars_widgets');
                    $widget_options = array();
                    foreach($widget_positions as $sidebar_name => $widgets){
                        if(is_array($widgets)){
                            foreach($widgets as $widget_name){
                                $widget_name_strip = preg_replace('#-\d+$#','',$widget_name);
                                $widget_options[$widget_name_strip] = get_option('widget_'.$widget_name_strip);
                            }
                        }
                    }
                    $a = base64_encode(serialize($widget_positions));
                    $b = base64_encode(serialize($widget_options));
                    echo "widget_positions: \n\n\n$a\n\n\n widget_options \n\n\n$b\n\n\n";exit;
                }else{
                    // import widgets
                    $widget_positions = get_option('sidebars_widgets');

                    $import_widget_positions = unserialize(base64_decode($_REQUEST['widget_positions']));
                    $import_widget_options = unserialize(base64_decode($_REQUEST['widget_options']));
                    foreach($import_widget_options as $widget_name => $widget_options){
                        $existing_options = get_option('widget_'.$widget_name,array());
                        $new_options = $existing_options + $widget_options;
                        update_option('widget_'.$widget_name,$new_options);
                    }
                    update_option('sidebars_widgets',array_merge($widget_positions,$import_widget_positions));
                }
            }
        }

        
        wp_redirect( admin_url( 'themes.php?page=' . $this->token . '&imported=true' ) );
        exit;
    } else {
// Errors: update fail
		var_dump( $optionsframework_name );
		wp_redirect( admin_url( 'themes.php?page=' . $this->token . '&error=true' ) );
		exit;
	}
}

// End import()

}

// End Class

/**
 * @uses OptionsFramework_Import_Default_Content
 */
$of_default_content = new OptionsFramework_Import_Default_Content();
$of_default_content->init();
?>