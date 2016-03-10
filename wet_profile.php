<?php

/*
wet_profile - Profile fields for Textpattern (http://textpattern.com/)

This sample plugin for Textpattern CMS demonstrates how to manage additional information for authors in the backend.
For educational purposes only. Not suitable for production use.

License
This plugin is distributed under the GNU General Public License Version 2 or later.

Copyright (c) Robert Wetzlmayr
http://wetzlmayr.com/
*/

class wet_profile
{
    /**
     * Initialise.
     */
    function __construct()
    {
        // Hook into the system's callbacks.
        register_callback(array(__CLASS__, 'lifecycle'), 'plugin_lifecycle.wet_profile');
        register_callback(array(__CLASS__, 'ui'), 'author_ui', 'extend_detail_form');
        register_callback(array(__CLASS__, 'save'), 'admin', 'author_save');
    }

    /**
     * Add and remove profile fields from txp_users table.
     *
     * @param $event string
     * @param $step string
     * @param $status string The lifecycle phase of this plugin.
     */
    public static function lifecyle($event, $step, $status)
    {
        switch ($status) {
            case 'enabled':
                break;
            case 'disabled':
                break;
            case 'installed':
                /*
                Amend the 'users' table to store two additional fields named `wet_profile_foo` and `wet_profile_bar`, resp.
                Uncomment the following line to really alter the database when this plugin is installed.
                Adjust data types and columns names to suit your needs.
                */
                //safe_alter('txp_users', 'ADD COLUMN wet_profile_foo VARCHAR(50) NULL AFTER nonce, ADD COLUMN wet_profile_bar VARCHAR(50) NULL AFTER wet_profile_foo');
                break;
            case 'deleted':
                /*
                Drop the two additional columns from the 'txp_users' table when the plugin is unsintalled.
                Caveat: Data in these two columns will be lost. If you want to preserve the additional data
                across install/uninstall actions you *must not* drop the additional columns.
                Uncomment the following line to really alter the database when this plugin is uninstalled.
                Adjust columns names to suit your needs.
                */
                //safe_alter('txp_users', 'DROP COLUMN wet_profile_foo, DROP COLUMN wet_profile_bar');
                break;
        }
        return;
    }

    /**
     * Paint additional fields for author.
     *
     * @param $event string
     * @param $step string
     * @param $dummy string
     * @param $rs array The author's current data
     * @return string
     */
    public static function ui($event, $step, $dummy, $rs)
    {
        extract(lAtts(array(
            'wet_profile_foo' => '',
            'wet_profile_bar' => ''
        ), $rs, 0));

        return
            inputLabel('wet_profile_foo', fInput('text', 'wet_profile_foo', $wet_profile_foo, '', '', '', INPUT_REGULAR, '', 'wet_profile_foo'), 'wet_profile_foo') . n .
            inputLabel('wet_profile_bar', fInput('text', 'wet_profile_bar', $wet_profile_bar, '', '', '', INPUT_REGULAR, '', 'wet_profile_bar'), 'wet_profile_bar') . n;
    }

    /**
     * Save additional profile fields.
     *
     * @param $event string
     * @param $step string
     */
    public static function save($event, $step)
    {
        extract(doSlash(psa(array('wet_profile_foo', 'wet_profile_bar', 'user_id'))));
        $user_id = assert_int($user_id);
        safe_update('txp_users', "
			wet_profile_foo = '$wet_profile_foo',
			wet_profile_bar = '$wet_profile_bar'",
            "user_id = $user_id"
        );
    }
}

// Get up and running.
if (txpinterface == 'admin') new wet_profile;
