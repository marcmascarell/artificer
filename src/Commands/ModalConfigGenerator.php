<?php

namespace Mascame\Artificer\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class ModalConfigGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artificer:model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $fileName = $name . '.php';
        $filePath = config_path('admin/models/') . $fileName;

        if (\File::exists($filePath)) {
            $this->error('File ' . $filePath . ' already exists.');
            return;
        }

        $template = \File::get($this->getStub());

        $render = $this->renderTemplate($template, compact('name'));

        // Todo: use the artificers admin path (support path change)
        \File::put($filePath, $render);
    }

    protected function renderTemplate($template, $data) {
        foreach ($data as $key => $value) {
            $template = str_replace('{{ '. $key .' }}', $value, $template);
        }

        return $template;
    }

    protected function getStub() {
        return __DIR__ . '/../../stubs/ModelConfig.php';
    }
}
