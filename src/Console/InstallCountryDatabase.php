<?php

namespace Nnjeim\World\Console;

use Illuminate\Console\Command;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;

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


	private array $modules = [
		'states' => [
			'class' => Models\State::class,
			'data' => [],
			'enabled' => false,
		],
		'cities' => [
			'class' => Models\City::class,
			'data' => [],
			'enabled' => false,
		]
	];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

		foreach (config('world.modules') as $module => $enabled) {
			if ($enabled && array_key_exists($module,$this->modules)) {
				$this->modules[$module]['enabled'] = true;
				$this->initModule($module);
			}
		}
    }

    public function handle()
    {
        $country = Country::find($this->argument('country_id'));

        if($country && $country->installed != 1){
            // states and cities
            if ($this->isModuleEnabled('states')) {
                $this->seedStates($country);
            }
        }
    }

	private function seedStates($country): void
	{
		// country states and cities
		$countryStates = Arr::where($this->modules['states']['data'], fn ($state) => $state['country_id'] === $country->id);
		// state schema
		$stateFields = Schema::getColumnListing(config('world.migrations.states.table_name'));

		$this->forgetFields($stateFields, ['id', 'country_id']);

		foreach (array_chunk($countryStates, 20) as $stateChunks) {

			foreach ($stateChunks as $stateArray) {

				$stateArray = array_map(fn ($field) => gettype($field) === 'string' ? trim($field) : $field, $stateArray);

				$data = Arr::only($stateArray, $stateFields);
				$data['title'] = $data['name'];
				$state = $country
					->states()
					->create($data);
				// state cities
				if ($this->isModuleEnabled('cities')) {
					$stateCities = Arr::where(
						$this->modules['cities']['data'],
						fn ($city) => $city['country_id'] === $country->id && $city['state_id'] === $stateArray['id']
					);

					$this->seedCities($country, $state, $stateCities);
				}
			}
		}
	}

	/**
	 * @param  Models\Country  $country
	 * @param  Models\State  $state
	 * @param  array  $cities
	 */
	private function seedCities(Models\Country $country, Models\State $state, array $cities): void
	{
		// state schema
		$cityFields = Schema::getColumnListing(config('world.migrations.cities.table_name'));

		$this->forgetFields($cityFields, ['id', 'country_id', 'state_id']);

		foreach (array_chunk($cities, 20) as $cityChunks) {

			foreach ($cityChunks as $cityArray) {

				$cityArray = array_map(fn ($field) => gettype($field) === 'string' ? trim($field) : $field, $cityArray);

				$data = Arr::only($cityArray, $cityFields);
				$data['title'] = $data['name'];
				$country
					->cities()
					->create(
						array_merge(
                            $data,
							['state_id' => $state->id]
						)
					);
			}
		}
	}

	/**
	 * @param  string  $module
	 * @return void
	 */
	private function initModule(string $module)
	{
		if (array_key_exists($module, $this->modules)) {
			$moduleSourcePath = __DIR__ . '/../../resources/json/' . $module . '.json';

			if (File::exists($moduleSourcePath)) {
				$this->modules[$module]['data'] = json_decode(File::get($moduleSourcePath), true);
			}
		}
	}
	
	/**
	 * @param  string  $module
	 * @return bool
	 */
	private function isModuleEnabled(string $module): bool
	{
		return $this->modules[$module]['enabled'];
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
