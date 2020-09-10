<?php

namespace Tzsk\Payu\Commands;

use Illuminate\Console\Command;

class PublishComponents extends Command
{
    public $signature = 'payu:publish
        {--c|config : Publish config file}
        {--m|migration : Publish migration}
        {--t|template : Publish templates}
        {--a|all : Publish everything}';

    public $description = 'Publish payu config and/or migration';

    public function handle()
    {
        $allowed = ['config', 'migration', 'template'];

        $items = collect($allowed)
            ->mapWithKeys(fn ($value) => [$value => $this->option($value)])
            ->filter()
            ->keys()
            ->all();

        $input = array_filter($items, fn ($item) => in_array($item, $allowed));
        if (count($input) < count($items)) {
            $this->error('Invalid publishable item supplied.');

            return;
        }

        $items = empty($input) ? $allowed : $input;

        collect($items)
            ->map(
                fn ($item) => $this->call('vendor:publish', ['--tag' => "payu-{$item}"])
            );
    }
}
