<?php

namespace Mascame\Artificer\Support;

use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class JavaScript
{
    /**
     * @var array
     */
    private static $data = [];

    /**
     * @return string
     */
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
     * Merges the given data.
     *
     * @param $data
     */
    public static function add($data)
    {
        self::$data = array_merge(self::$data, $data);
    }
}
