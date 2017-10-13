<?php

namespace Mascame\Artificer\Support;

use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class JavaScript
{
    private static $data = [];

    public static function sendDataToJS()
    {
        \Blade::directive('phpToJS', function () {
            return "<?php echo \Mascame\Artificer\Support\JavaScript::transform(); ?>";
        });
    }

    public static function transform()
    {
        $data = JavaScriptFacade::constructJavaScript(self::$data);

        return <<<EOT
<script type="text/javascript">
    {$data}
</script>
EOT;
    }

    /**
     * @param $data
     */
    public static function add($data)
    {
        self::$data = array_merge(self::$data, $data);
    }
}
