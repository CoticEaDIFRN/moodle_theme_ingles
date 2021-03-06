<?php
/**
 * MIT License
 *
 * Copyright 2017 IFRN
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * PHP Version 7
 *
 * @category  MoodleTheme
 * @package   Theme_Boost_isf_ingles.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2017 IFRN
 * @license   MIT https://opensource.org/licenses/MIT
 * @link      https://github.com/Coticisf_ingles/isf_ingles
 */

namespace theme_boost_isf_ingles\output;

use coding_exception;
use html_writer;
use tabobject;
use tabtree;
use custom_menu_item;
use custom_menu;
use block_contents;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use preferences_groups;
use action_menu;
use help_icon;
use single_button;
use single_select;
use paging_bar;
use url_select;
use context_course;
use pix_icon;

defined('MOODLE_INTERNAL') || die;


/**
 * Extending the core_renderer interface.
 *
 * lib/outputrenderers.php --> core_renderer
 *
 * @category  Renderer
 * @package   Theme_Boost_isf_ingles.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2017 IFRN
 * @license   MIT https://opensource.org/licenses/MIT
 * @link      https://github.com/CoticEaDIFRN/isf_ingles
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Override header
     *
     * @return string HTML of header
     */
    public function header() {
        return parent::header();
    }

    /**
     * Allow plugins to provide some content to be rendered in the navbar.
     *
     * @see message_popup_render_navbar_output in lib.php
     *
     * @return string HTML for the navbar
     */
    public function navbar_plugin_output() {

        $output = '';
        // // Early bail out conditions.
        // if (!isloggedin() || isguestuser() || user_not_fully_set_up($USER) ||
        //     get_user_preferences('auth_forcepasswordchange') ||
        //     ($CFG->sitepolicy && !$USER->policyagreed && !is_siteadmin())) {
        //     return $output;
        // }
        $output .= $this->header_messsage();
        // $output .= $this->header_help();
        $output .= $this->header_notification();
        $output .= $this->header_admin();
        $output .= $this->header_pagetitle();
        return $output;
    }


    // /**
    //  * Override to add additional class for the random login image to the body.
    //  *
    //  * @param string|array $additionalclasses Any additional classes to give the body tag,
    //  *
    //  * @return string HTML attributes to use within the body tag. This includes an ID and classes.
    //  */
    // public function body_attributes($additionalclasses = array()) {
    //     global $PAGE, $CFG;
    //     require_once($CFG->dirroot . '/theme/boost_isf_ingles/locallib.php');

    //     if (!is_array($additionalclasses)) {
    //         $additionalclasses = explode(' ', $additionalclasses);
    //     }

    //     // MODIFICATION START.
    //     // Only add classes for the login page.
    //     // if ($PAGE->bodyid == 'page-login-index') {
    //     //     $additionalclasses[] = 'loginbackgroundimage';
    //     //     // Generating a random class for displaying a random image for the login page.
    //     //     $additionalclasses[] = theme_boost_isf_ingles_get_random_loginbackgroundimage_class();
    //     // }
    //     // MODIFICATION END.

    //     return ' id="'. $this->body_id().'" class="'.$this->body_css_classes($additionalclasses).'"';
    // }

    // /**
    //  * Override to be able to use uploaded images from admin_setting as well.
    //  *
    //  * @return string The favicon URL
    //  */
    // public function favicon()
    // {
    //     global $PAGE;
    //     // MODIFICATION START.
    //     if (!empty($PAGE->theme->settings->favicon)) {
    //         return $PAGE->theme->setting_file_url('favicon', 'favicon');
    //     } else {
    //         return $this->pix_url('favicon', 'theme');
    //     }
    //     // MODIFICATION END.
    //     /* ORIGINAL START.
    //     return $this->pix_url('favicon', 'theme');
    //     ORIGINAL END. */
    // }

    public function user_menu($user = null, $withlinks = null, $usermenuclasses=null) {
        $result = parent::user_menu($user, $withlinks);
        return html_writer::div($result, $usermenuclasses);
    }

    // /**
    //  * Override to dispaly switched role information beneath the course header instead of the user menu.
    //  * We change this because the switch role function is course related and therefore it should be placed in the course context.
    //  *
    //  * Wrapper for header elements.
    //  *
    //  * @return string HTML to display the main header.
    //  */
    public function full_header() {
        // MODIFICATION START.
        global $PAGE, $USER, $COURSE;
        // MODIFICATION END.

        $html = html_writer::start_tag('header', array('id' => 'page-header', 'class' => 'row'));
        $html .= html_writer::start_div('col-xs-12 p-a-1');
        $html .= html_writer::start_div('card');
        $html .= html_writer::start_div('card-block');
        // MODIFICATION START:
        // Only display the core context header menu if the setting "showsettingsincourse" is disabled
        // or we are viewing the frontpage.
        if (get_config('theme_boost_isf_ingles', 'showsettingsincourse') == 'no' || $PAGE->pagelayout == 'frontpage') {
            $html .= html_writer::div($this->context_header_settings_menu(), 'pull-xs-right context-header-settings-menu');
        }
        // MODIFICATION END.
        /* ORIGINAL START.
        $html .= html_writer::div($this->context_header_settings_menu(), 'pull-xs-right context-header-settings-menu');
        ORIGINAL END. */
        // MODIFICATION START:
        // To get the same structure as on the Dashboard, we need to add the page heading buttons here for the profile page.
        if ($PAGE->pagelayout == 'mypublic') {
            $html .= html_writer::div($this->page_heading_button(), 'breadcrumb-button pull-xs-right');
        }
        // MODIFICATION END.
        $html .= html_writer::start_div('pull-xs-left');
        $html .= $this->context_header();
        $html .= html_writer::end_div();

	$pageheadingbutton = $this->page_heading_button();
	if (empty($PAGE->layout_options['nonavbar'])) {
            $html .= html_writer::start_div('clearfix w-100 pull-xs-left', array('id' => 'page-navbar'));
            $html .= html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav'));
            // MODIFICATION START: Add the course context menu to the course page, but not on the profile page.
            if (get_config('theme_boost_isf_ingles', 'showsettingsincourse') == 'yes' && $PAGE->pagelayout != 'mypublic') {
                $html .= html_writer::div($this->context_header_settings_menu(), 'pull-xs-right context-header-settings-menu m-l-1');
            }
            // MODIFICATION END.
            // MODIFICATION START: Instead of the settings icon, add a button to edit the profile.
            if ($PAGE->pagelayout == 'mypublic') {
                $html .= html_writer::start_div('breadcrumb-button breadcrumb-button pull-xs-right');
                $url = new moodle_url('/user/editadvanced.php', array('id' => $USER->id, 'course' => $COURSE->id, 'returnto' => 'profile'));
                $html .= $this->single_button($url, get_string('editmyprofile', 'core'));
                $html .= html_writer::end_div();
            }
            // Do not show the page heading buttons on the profile page at this place.
            // Display them only on other pages.
            if ($PAGE->pagelayout != 'mypublic') {
                $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button pull-xs-right');
            }
            // MODIFICATION END.
            $html .= html_writer::end_div();
        } else if ($pageheadingbutton) {
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button nonavbar pull-xs-right');
        }

        $html .= html_writer::tag('div', $this->course_header(), array('id' => 'course-header'));
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        $html .= html_writer::end_tag('header');

        // MODIFICATION START.
        // Only use this if setting 'showswitchedroleincourse' is active.
        if (get_config('theme_boost_isf_ingles', 'showswitchedroleincourse') === 'yes') {
            // Check if user is logged in.
            // If not, adding this section would make no sense and, even worse,
            // user_get_user_navigation_info() will throw an exception due to the missing user object.
            if (isloggedin()) {
                $opts = \user_get_user_navigation_info($USER, $this->page);
                // Role is switched.
                if (!empty($opts->metadata['asotherrole'])) {
                    // Get the role name switched to.
                    $role = $opts->metadata['rolename'];
                    // Get the URL to switch back (normal role).
                    $url = new moodle_url('/course/switchrole.php', array('id' => $COURSE->id, 'sesskey' => sesskey(), 'switchrole' => 0, 'returnurl' => $this->page->url->out_as_local_url(false)));
                    $html .= html_writer::start_tag('div', array('class' => 'switched-role-infobox alert alert-info'));
                    $html .= html_writer::start_tag('div', array());
                    $html .= get_string('switchedroleto', 'theme_boost_isf_ingles');
                    // Give this a span to be able to address via CSS.
                    $html .= html_writer::tag('span', $role, array('class' => 'switched-role'));
                    $html .= html_writer::end_tag('div');
                    // Return to normal role link.
                    $html .= html_writer::start_tag('div', array('class' => 'switched-role-back col-6'));
                    $html .= html_writer::empty_tag('img', array('src' => $this->pix_url('a/logout', 'moodle')));
                    $html .= html_writer::tag('a', get_string('switchrolereturn', 'core'), array('class' => 'switched-role-backlink', 'href' => $url));
                    $html .= html_writer::end_tag('div'); // Return to normal role link: end div.
                    $html .= html_writer::end_tag('div');
                }
            }
        }
        // MODIFICATION END.

        return $html;
    }


    // /**
    //  * Override to display course settings on every course site for permanent access
    //  *
    //  * This is an optional menu that can be added to a layout by a theme. It contains the
    //  * menu for the course administration, only on the course main page.
    //  *
    //  * @return string
    //  */
    // public function context_header_settings_menu() {
    //     $context = $this->page->context;
    //     $menu = new action_menu();

    //     $items = $this->page->navbar->get_items();
    //     $currentnode = end($items);

    //     $showcoursemenu = false;
    //     $showfrontpagemenu = false;
    //     $showusermenu = false;

    //     // MODIFICATION START.
    //     // REASON: With the original code, the course settings icon will only appear on the course main page.
    //     // Therefore the access to the course settings and related functions is not possible on other
    //     // course pages as there is no omnipresent block anymore. We want these to be accessible
    //     // on each course page.
    //     if (($context->contextlevel == CONTEXT_COURSE || $context->contextlevel == CONTEXT_MODULE) && !empty($currentnode)) {
    //         $showcoursemenu = true;
    //     }
    //     // MODIFICATION END.
    //     // @codingStandardsIgnoreStart
    //     /* ORIGINAL START.
    //     if (($context->contextlevel == CONTEXT_COURSE) &&
    //             !empty($currentnode) &&
    //             ($currentnode->type == navigation_node::TYPE_COURSE || $currentnode->type == navigation_node::TYPE_SECTION)) {
    //         $showcoursemenu = true;
    //     }
    //     ORIGINAL END. */
    //     // @codingStandardsIgnoreEnd

    //     $courseformat = course_get_format($this->page->course);
    //     // This is a single activity course format, always show the course menu on the activity main page.
    //     if ($context->contextlevel == CONTEXT_MODULE && !$courseformat->has_view_page()) {

    //         $this->page->navigation->initialise();
    //         $activenode = $this->page->navigation->find_active_node();
    //         // If the settings menu has been forced then show the menu.
    //         if ($this->page->is_settings_menu_forced()) {
    //             $showcoursemenu = true;
    //         } else if (!empty($activenode) && ($activenode->type == navigation_node::TYPE_ACTIVITY ||
    //             $activenode->type == navigation_node::TYPE_RESOURCE)) {

    //             // We only want to show the menu on the first page of the activity. This means
    //             // the breadcrumb has no additional nodes.
    //             if ($currentnode && ($currentnode->key == $activenode->key && $currentnode->type == $activenode->type)) {
    //                 $showcoursemenu = true;
    //             }
    //         }
    //     }

    //     // This is the site front page.
    //     if ($context->contextlevel == CONTEXT_COURSE && !empty($currentnode) && $currentnode->key === 'home') {
    //         $showfrontpagemenu = true;
    //     }

    //     // This is the user profile page.
    //     if ($context->contextlevel == CONTEXT_USER && !empty($currentnode) && $currentnode->key === 'myprofile') {
    //         $showusermenu = true;
    //     }

    //     if ($showfrontpagemenu) {
    //         $settingsnode = $this->page->settingsnav->find('frontpage', navigation_node::TYPE_SETTING);
    //         if ($settingsnode) {
    //             // Build an action menu based on the visible nodes from this navigation tree.
    //             $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);

    //             // We only add a list to the full settings menu if we didn't include every node in the short menu.
    //             if ($skipped) {
    //                 $text = get_string('morenavigationlinks');
    //                 $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
    //                 $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
    //                 $menu->add_secondary_action($link);
    //             }
    //         }
    //     } else if ($showcoursemenu) {
    //         $settingsnode = $this->page->settingsnav->find('courseadmin', navigation_node::TYPE_COURSE);
    //         if ($settingsnode) {
    //             // Build an action menu based on the visible nodes from this navigation tree.
    //             $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);

    //             // We only add a list to the full settings menu if we didn't include every node in the short menu.
    //             if ($skipped) {
    //                 $text = get_string('morenavigationlinks');
    //                 $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
    //                 $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
    //                 $menu->add_secondary_action($link);
    //             }
    //         }
    //     } else if ($showusermenu) {
    //         // Get the course admin node from the settings navigation.
    //         $settingsnode = $this->page->settingsnav->find('useraccount', navigation_node::TYPE_CONTAINER);
    //         if ($settingsnode) {
    //             // Build an action menu based on the visible nodes from this navigation tree.
    //             $this->build_action_menu_from_navigation($menu, $settingsnode);
    //         }
    //     }

    //     return $this->render($menu);
    // }

    // /**
    //  * Override to use theme_boost_isf_ingles login template renders the login form.
    //  *
    //  * @param \core_auth\output\login $form The renderable.
    //  *
    //  * @return string
    //  */
    // public function render_login(\core_auth\output\login $form) {
    //     global $SITE;

    //     $context = $form->export_for_template($this);

    //     // Override because rendering is not supported in template yet.
    //     $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
    //     $context->errorformatted = $this->error_text($context->error);
    //     $url = $this->get_logo_url();
    //     if ($url) {
    //         $url = $url->out(false);
    //     }
    //     $context->logourl = $url;
    //     $context->sitename = format_string($SITE->fullname, true,
    //         ['context' => context_course::instance(SITEID), "escape" => false]);
    //     // MODIFICATION START.
    //     // Only if setting "loginform" is checked, then call own login.mustache.
    //     if (get_config('theme_boost_isf_ingles', 'loginform') == 'yes') {
    //         return $this->render_from_template('theme_boost_isf_ingles/loginform', $context);
    //     } else {
    //         return $this->render_from_template('core/login', $context);
    //     }
    //     // MODIFICATION END.
    //     /* ORIGINAL START.
    //     return $this->render_from_template('core/login', $context);
    //     ORIGINAL END. */
    // }

    // /**
    //  * Take a node in the nav tree and make an action menu out of it.
    //  * The links are injected in the action menu.
    //  *
    //  * @param action_menu $menu
    //  * @param navigation_node $node
    //  * @param boolean $indent
    //  * @param boolean $onlytopleafnodes
    //  *
    //  * @return boolean nodesskipped - True if nodes were skipped in building the menu
    //  */
    // protected function build_action_menu_from_navigation(
    //     action_menu $menu,
    //     navigation_node $node,
    //     $indent = false,
    //     $onlytopleafnodes = false
    // ) {
    //     $skipped = false;
    //     // Build an action menu based on the visible nodes from this navigation tree.
    //     foreach ($node->children as $menuitem) {
    //         if ($menuitem->display) {
    //             if ($onlytopleafnodes && $menuitem->children->count()) {
    //                 $skipped = true;
    //                 continue;
    //             }
    //             if ($menuitem->action) {
    //                 if ($menuitem->action instanceof action_link) {
    //                     $link = $menuitem->action;
    //                     // Give preference to setting icon over action icon.
    //                     if (!empty($menuitem->icon)) {
    //                         $link->icon = $menuitem->icon;
    //                     }
    //                 } else {
    //                     $link = new action_link($menuitem->action, $menuitem->text, null, null, $menuitem->icon);
    //                 }
    //             } else {
    //                 if ($onlytopleafnodes) {
    //                     $skipped = true;
    //                     continue;
    //                 }
    //                 $link = new action_link(new moodle_url('#'), $menuitem->text, null, ['disabled' => true], $menuitem->icon);
    //             }
    //             if ($indent) {
    //                 $link->add_class('m-l-1');
    //             }
    //             if (!empty($menuitem->classes)) {
    //                 $link->add_class(implode(" ", $menuitem->classes));
    //             }

    //             $menu->add_secondary_action($link);
    //             $skipped = $skipped || $this->build_action_menu_from_navigation($menu, $menuitem, true);
    //         }
    //     }
    //     return $skipped;
    // }

    protected function header_pagetitle() {
        global $PAGE;
        $pagetitle = $PAGE->title;

        if($PAGE->pagelayout == 'frontpage' || $PAGE->pagelayout == 'mydashboard') {
            $pagetitle = "Salas de aula";
        } elseif ($PAGE->pagelayout == 'course' || $PAGE->pagelayout == 'incourse') {
            $pagetitle = "Sala de aula";
        } elseif ($PAGE->pagelayout == 'admin') {
            $pagetitle = "Administração";
        }

        return '<p id="navbar_pagetitle" class="hidden-sm-down">'. $pagetitle .'</p>';
    }

    protected function header_help() {
        return '<div class="popover-region collapsed popover-region-help" id="nav-help-popover-container" data-userid="2" data-region="popover-region">
        <a href="https://moodle.org/mod/forum/view.php?id=50"><div class="popover-region-toggle nav-link" data-region="popover-region-toggle" aria-role="button" aria-controls="popover-region-container-5a254db9cba625a254db9b2d7016" aria-haspopup="true" aria-label="Mostrar janela de mensagens sem as novas mensagens" tabindex="0">
                    <i class="icon fa fa-question-circle fa-fw " aria-hidden="true" title="Obter ajuda" aria-label="Obter ajuda"></i>
        </div></a></div>';
    }

    protected function header_admin() {
        if (is_siteadmin()) {
            return '<div class="popover-region collapsed popover-region-admin" id="nav-help-popover-container" data-userid="2" data-region="popover-region">
            <a href="'. (new moodle_url('/admin/search.php'))->out() .'"><div class="popover-region-toggle nav-link" data-region="popover-region-toggle" aria-role="button" aria-controls="popover-region-container-5a254db9cba625a254db9b2d7016" aria-haspopup="true" aria-label="Mostrar janela de mensagens sem as novas mensagens" tabindex="0">
                <i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Administração" aria-label="Administração"></i>
            </div></a></div>';
        }
    }

    protected function header_notification() {
        global $USER;
	// Add the notifications popover.
	$output = '';
        $enabled = \core_message\api::is_processor_enabled("popup");
        if ($enabled && isloggedin()) {
            $context = [
                'userid' => $USER->id,
                'urls' => [
                    'seeall' => (new moodle_url('/message/output/popup/notifications.php'))->out(),
                    'preferences' => (new moodle_url('/message/notificationpreferences.php', ['userid' => $USER->id]))->out(),
                ],
            ];
            $output .= $this->render_from_template('message_popup/notification_popover', $context);
        }

        return $output;
    }

    protected function header_messsage() {
        global $USER, $CFG;
        if (!empty($CFG->messaging) && isloggedin()) {
            $context = [
                'userid' => $USER->id,
                'urls' => [
                    'seeall' => (new moodle_url('/message/index.php'))->out(),
                    'writeamessage' => (new moodle_url('/message/index.php', ['contactsfirst' => 1]))->out(),
                    'preferences' => (new moodle_url('/message/edit.php', ['id' => $USER->id]))->out(),
                ],
            ];
            return $this->render_from_template('message_popup/message_popover', $context);
        }
        return '';
    }

}
