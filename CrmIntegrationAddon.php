<?php
/*
Plugin Name: Gravity Forms ActiveCampaing Integration
Plugin URI: http://stelssoft.com
Description: Gravity Forms AddOn which integration ActiveCampaing
Version: 1.0.0.1
Author: Ruslan Atamas
Author URI: http://vc.com/stelss1986
License: GPLv2 or later
Text Domain: crm-integration-addon
*/


//------------------------------------------
if (!class_exists("GFForms")) {
    // Gravity Forms Plugin ***is not*** active, do nothing
} else {
    // Gravity Forms Plugin is active, so continue
    GFForms::include_addon_framework();

    class CrmIntegrationAddon extends GFAddOn
    {

        /**
         * @var string
         */
        protected $_version = "1.0.0.1";

        /**
         * @var string
         */
        protected $_min_gravityforms_version = "1.7.9999";

        /**
         * @var string
         */
        protected $_slug = "crm-integration-addon";

        /**
         * @var string
         */
        protected $_path = "gravity-forms-crm/CrmIntegrationAddon.php";

        /**
         * @var string
         */
        protected $_full_path = __FILE__;

        /**
         * @var string
         */
        protected $_title = "GravityForms - ActiveCampaign Integarion";

        /**
         * @var string
         */
        protected $_short_title = "ActiveCampaign";


        public function init()
        {
            parent::init();

            add_action("gform_after_submission", [$this, 'addToCrm'], 10, 2);
        }


        public function plugin_page()
        {
            _e(
                "<p>Thank you for using this plugin. If you like it, we would like to invite you to rate it on <a href='https://wordpress.org/plugins/wp4office-gf2excel/' target='_blank'>wordpress.org</a>.</p><h2>Description</h2> <p>This Gravity Forms AddOn saves form data into a given Excel document and attaches it to notification emails.You don't need any programming skills to get native Excel documents back as the result of your Gravity Forms web form. After uploading your Excel 2007 file (.xslx, other versions are not supported) the form data is saved into one sheet (which you can define) of your document. You can then select to which notification emails thisExcel file should be attached to. Using simple Excel formulas (=A1)you can fill out complex Excel sheets with data from the web form.No further export or import of CSV data is required.</p><h2>Operating instructions</h2> <ol> <li>Create your form withGravity Forms</li> <li>Give all your fields admin field labels(under the tab 'Advanced')</li> <li>Create your notification emails</li> <li>Open the WP4O-GF2Excel form settings, upload yourExcel file, type in your sheet number to insert the form data and finally select the notifications you would like to attach the filled in Excel file.</li> <li>Submit your form and open your Excel file.Connect your actual form fields through formulas (=A1) with data of the sheet which is filled by Gravity Forms. The admin field labels will help you to associate the data with the form fields.</li><li>Open the WP4O-GF2Excel form settings again and upload the Excel file with your formulas.</li> <li>Repeat steps 5 and 6 until you are satisfied with the result.</li> <li>Be happy :-)</li> </ol><p><i>This plugin requires Gravity Forms by RocketGenius to be active.</i></p><p><i>This plugin was successfully tested on WordPress Multisite without any abnormalities.</i></p><p>This plugin is maintained by <a href='http://winball.de' target='_blank'>winball.de</a> on <a href='http://wp4office.winball2.de/gf2excel' target='_blank'>wp4office.winball2.de</a>. We welcome your pull requests,comments and suggestions for improvement. Additional <a href='http://wp4office.winball2.de/gf2excel/help' target='_blank'>help and example files</a> with descriptions are available. You can <a href='http://wp4office.winball2.de/gf2excel/demo' target='_blank'>try out a demo</a> before installing. Technical support is available under <a href='https://wordpress.org/support/plugin/wp4office-gf2excel' target='_blank'>wordpress.org.</a></p><h2>You do have problems or need individual service?</h2><p>Professional web services are our actual business. If you need help with your form or your Excel file, please feel free to <a href='http://winball.de/wp4office-gf2excel-services' target='_blank'>contact us</a>.</p>",
                'gf2excel-addon'
            );
        }


        /**
         * @param array $form
         * @return array
         */
        public function form_settings_fields($form)
        {
            return [
                [
                    "title" => "ActiveCampaign-Addon Settings",
                    "fields" => [
                        [
                            "label" => __("Active:", 'gf2excel-addon'),
                            "type" => "checkbox",
                            "name" => "active",
                            "tooltip" => __(
                                "Select the notification emails, where you want to attach the excel file",
                                'gf2excel-addon'
                            ),
                            "choices" => [
                                [
                                    'label' => __("Enabled", 'gf2excel-addon'),
                                    'name'  => 'activecampaign_enabled',
                                ]
                            ],
                        ],
                        [
                            "label" => __("Active Campaign URL:", 'gf2excel-addon'),
                            "type" => "text",
                            "name" => "activecampaign_url",
                            "tooltip" => __(
                                "Insert the path to the excel template file (must be Excel 2007 and end with .xlsx; should start with /wp-content/...)",
                                'gf2excel-addon'
                            ),
                            "class" => "medium",
                            //"validation_callback" => [$this, "validate_activecampaign_url"]
                        ],
                        [
                            "label" => __("API KEY:", 'gf2excel-addon'),
                            "type" => "text",
                            "name" => "activecampaign_api_key",
                            "tooltip" => __(
                                "Insert the sheet index of the excel sheet where you want to save the from data (excel sheet indices start with 0, please use indices which do exist)",
                                'gf2excel-addon'
                            ),
                            "class" => "medium",
                            //"validation_callback" => [$this, "validate_activecampaign_api_key"],
                        ],
                        [
                            "label" => __("List ID", 'gf2excel-addon'),
                            "type" => "text",
                            "name" => "list_id",
                            "tooltip" => __(
                                "Insert the path to the excel template file (must be Excel 2007 and end with .xlsx; should start with /wp-content/...)",
                                'gf2excel-addon'
                            ),
                            "class" => "medium",
                            //"validation_callback" => [$this, "validate_list_id"]
                        ],
                    ],
                ],
            ];
        }

        /**
         * @param array $entry
         * @param array $form
         */
        public function addToCrm($entry, $form)
        {
            if ($form['crm-integration-addon']['activecampaign_enabled'] !== '1') {
                return;
            }

            $form['crm-integration-addon']['activecampaign_url'];
            $form['crm-integration-addon']['activecampaign_api_key'];
            $listId = $form['crm-integration-addon']['list_id'];

            $data = [
                "p[{$listId}]" => $listId,
                "status[{$listId}]" => 1, // "Active" status
            ];

            foreach ($form['fields'] as $key => $value) {
                $fieldName = $value['inputName'];
                $fieldValue = $entry[$value['id']];

                if (!$this->isAvailableField($fieldName)) {
                    $fieldName = 'field[%' . str_replace(' ', '_', strtoupper($fieldName)) . '%, 0]';
                }

                $data["{$fieldName}"] = $fieldValue;
            }
        }

        private function isAvailableField($fieldName)
        {
            $availableActiveCampaingFields = [
                'email',
                'first_name',
                'last_name',
                'phone',
                'orgname',
                'tags',
            ];

            return in_array($fieldName, $availableActiveCampaingFields, true);
        }
    }

    new CrmIntegrationAddon();
}
