<?php
class wet_profile
{
	function __construct()
	{
		register_callback(array(__CLASS__, 'lifecycle'), 'plugin_lifecycle.wet_profile');
		register_callback(array(__CLASS__, 'ui'), 'author_ui', 'extend_detail_form');
		register_callback(array(__CLASS__, 'save'), 'admin', 'author_save');
	}

	/**
	 * Add and remove profile fields from txp_users table
	 *
	 * @param $event
	 * @param $step
	 * @param $status
	 */
	function lifecyle($event, $step, $status)
	{
		switch($status) {
			case 'enabled':
				break;
			case 'disabled':
				break;
			case 'installed':
				//safe_alter('txp_users', 'ADD COLUMN wet_profile_foo VARCHAR(50) NULL AFTER nonce, ADD COLUMN wet_profile_bar VARCHAR(50) NULL AFTER wet_profile_foo');
				break;
			case 'deleted':
				//safe_alter('txp_users', 'DROP COLUMN wet_profile_foo, DROP COLUMN wet_profile_bar');
				break;
		}
		return;
	}

	/**
	 * Paint additional fields for author
	 *
	 * @param $event
	 * @param $step
	 * @param $dummy
	 * @param $rs
	 * @return string
	 */
	function ui($event, $step, $dummy, $rs)
	{
		extract(lAtts(array(
			'wet_profile_foo' => '',
			'wet_profile_bar' => ''
		), $rs, 0));

		return
			inputLabel('wet_profile_foo', fInput('text', 'wet_profile_foo', $wet_profile_foo, '', '', '', INPUT_REGULAR, '', 'wet_profile_foo'), 'wet_profile_foo').n.
			inputLabel('wet_profile_bar', fInput('text', 'wet_profile_bar', $wet_profile_bar, '', '', '', INPUT_REGULAR, '', 'wet_profile_bar'), 'wet_profile_bar').n;
	}

	/**
	 * Save additional profile fields
	 *
	 * @param $event
	 * @param $step
	 */
	function save($event, $step)
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
if (txpinterface == 'admin') new wet_profile;
