<?php

use EventEspresso\RecurringEvents\domain\Domain;

defined('EVENT_ESPRESSO_VERSION')|| exit('No direct script access allowed');



/**
 * Recurring_Events_Admin_Page_Init class
 *
 * This is the init for the Recurring_Events Addon Admin Pages.  See EE_Admin_Page_Init for method inline docs.
 *
 * @package     Event Espresso (recurring_events addon)
 * @subpackage  admin/Recurring_Events_Admin_Page_Init.core.php
 * @author      Darren Ethier
 */
class Recurring_Events_Admin_Page_Init extends EE_Admin_Page_Init
{

    /**
     * @throws DomainException
     */
    public function __construct()
    {
        do_action('AHEE_log', __FILE__, __FUNCTION__, '');
        parent::__construct();
        $this->_folder_path = Domain::adminPath();
    }



    /**
     * @return void
     */
    protected function _set_init_properties()
    {
        $this->label = Domain::adminPageLabel();
    }



    /**
     * _set_menu_map
     *
     * @return void
     */
    protected function _set_menu_map()
    {
        $this->_menu_map = new EE_Admin_Page_Sub_Menu(
            array(
                'menu_group'      => 'addons',
                'menu_order'      => 25,
                'show_on_menu'    => EE_Admin_Page_Menu_Map::BLOG_ADMIN_ONLY,
                'parent_slug'     => 'espresso_events',
                'menu_slug'       => Domain::adminPageSlug(),
                'menu_label'      => Domain::adminPageLabel(),
                'capability'      => 'administrator',
                'admin_init_page' => $this,
            )
        );
    }



}
// End of file Recurring_Events_Admin_Page_Init.core.php
// Location: /wp-content/plugins/eea-recurring-events-manager/admin/recurring_events/Recurring_Events_Admin_Page_Init.core.php
