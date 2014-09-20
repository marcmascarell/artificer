<?php namespace Mascame\Artificer\Plugins\ActionConfirmation;

use Mascame\Artificer\Plugin;
use Event;

class ActionConfirmationPlugin extends Plugin {

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'ActionConfirmation';
		$this->description = 'Confirm action with JS (not working yet)';
		$this->author = 'Marc Mascarell';
	}

    public function boot() {
        $this->addHooks();
    }

    public function addHooks()
    {
        Event::listen(array('bottom-scripts'), function () {
            ?>
            <script type="text/javascript">
                $(function () {
                    $('a[data-method="delete"]').click(function(event) {
                        if(!confirm('Confirm?')){
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    });
                });
            </script>
        <?php
        });
    }
}
