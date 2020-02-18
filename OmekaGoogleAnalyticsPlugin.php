<?php
/**
 * GoogleAnalyticsErr Omeka plugin.
 *
 * This plug-in allows you to paste in the JavaScript for Google Analytics and 
 * outputs it on the bottom of every public page.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package omeka
 * @subpackage GoogleAnalyticsErr
 * @author Eric Rochester (erochest@gmail.com)
 * @copyright 2011
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version 0.1
 * @link http://www.ericrochester.com/
 *
 */

define('GOOGLE_ANALYTICS_PLUGIN_VERSION', get_plugin_ini('OmekaGoogleAnalytics', 'version'));
define('GOOGLE_ANALYTICS_PLUGIN_DIR', dirname(__FILE__));
define('GOOGLE_ANALYTICS_ACCOUNT_OPTION', 'googleanalytics_account_id');

class OmekaGoogleAnalyticsPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks =array(
        'install',
        'uninstall',
        'public_head',
        'config',
        'config_form'
    );

    protected $_filters = array();

    public function hookInstall()
    {
        set_option('googleanalytics_version', GOOGLE_ANALYTICS_PLUGIN_DIR);
    }
    
    public function hookUninstall()
    {
        delete_option('googleanalytics_version');
        delete_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION);
    }
    
    public function hookPublicHead()
    {
        $accountId = get_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION);
        if (empty($accountId)) {
            return;
        }

        $jstemplate = file_get_contents(GOOGLE_ANALYTICS_PLUGIN_DIR . '/gtag.js');
        $js = str_replace("GA_MEASUREMENT_ID", $accountId, $jstemplate);
        
        echo $js;
    }
    
    public function hookConfig($args)
    {
        $post = $args['post'];
        set_option(
            GOOGLE_ANALYTICS_ACCOUNT_OPTION,
            $post[GOOGLE_ANALYTICS_ACCOUNT_OPTION]
        );
    }
    
    public function hookConfigForm()
    {
       include GOOGLE_ANALYTICS_PLUGIN_DIR . '/config_form.php';
    }
}
