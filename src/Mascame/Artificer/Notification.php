<?php namespace Mascame\Artificer;

use Event;
use Mascame\Notify\Notify;

class Notification extends Notify {

	public static $key = 'admin.notifications';

	public static function attach()
	{
		Event::listen(array('admin.notifications'), function () {
			return Notification::getAll();
		});

		Event::listen(array('bottom-scripts'), function () {
			?>
			<script>
				$(document).ready(function () {
					setTimeout(function () {
						$('.admin-notification.autohide').fadeOut();
					}, 2500);
				})
			</script>
		<?php
		});
	}
}