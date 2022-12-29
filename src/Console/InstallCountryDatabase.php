<?php

namespace Nnjeim\World\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Nnjeim\World\Actions\Country\InstallSingleCountryAction;

class InstallCountryDatabase extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'install:country {country_id}';
    protected $name = 'install:country {country_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';


    public function handle()
    {
		(new InstallSingleCountryAction)->install($this->argument('country_id'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['country_id', InputArgument::REQUIRED, 'the country id is required'],
        ];
    }
}
