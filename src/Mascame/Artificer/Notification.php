<?php namespace Mascame\Artificer;

use Session;
use Event;

class Notification {

	public static function success($value, $autohide = false) {
		Notification::add($value, 'success', $autohide);
	}

	public static function info($value, $autohide = false) {
		Notification::add($value, 'info', $autohide);
	}

	public static function warning($value, $autohide = false) {
		Notification::add($value, 'warning', $autohide);
	}

	public static function danger($value, $autohide = false) {
		Notification::add($value, 'danger', $autohide);
	}

	public static function add($value, $type = 'success', $autohide = false) {
		$notifications = Notification::getAll();

		$notifications[] = array(
			'type' => $type,
			'value'=> $value,
			'autohide'=> $autohide,
		);

		Session::flash('admin.notifications', $notifications);
	}

	public static function getAll() {
		return Session::get('admin.notifications');
	}

	public static function attach() {
		Event::listen(array('admin.notifications'), function()
		{
			return Notification::getAll();
		});

		Event::listen(array('bottom-scripts'), function()
		{
			?>
			<script>
				$(document).ready(function() {
					setTimeout(function() {
						$('.admin-notification.autohide').fadeOut();
					}, 2500 );
				})
			</script>
		<?php
		});
	}
}