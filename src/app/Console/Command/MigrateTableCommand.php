<?php

namespace Marshmallow\MultiLanguage\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Marshmallow\HelperFunctions\Facades\Str;

class MigrateTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marshmallow:translate-resource {resource_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make all data in a resource translateable';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $resource_name = $this->argument('resource_name');

        $this->resource_class = new $resource_name;
        try {
            if (!isset($this->resource_class->translatable) || empty($this->resource_class->translatable)) {
                throw new Exception('No translateable columns. We have nothing to migrate.', 1);
            }

            $this->translateable_columns = $this->resource_class->translatable;

            $this->checkTableStructureIsValid();

            $confirmed = $this->confirm(
                'Are you sure you want to proceed?'. "\n".
                ' We will change the database structure and all the data in this table.'. "\n".
                ' Please use carefully and back-up your data first.'. "\n"
            );

            if (!$confirmed) {
                exit;
            }

            $this->locale = $this->ask('What is the language of the currently available content?', config('app.locale'));

            $records = $resource_name::get();
            $this->migrateData($records);
            $this->updateTableStructure();

            $this->info(
                "\n\n The database columns and the data have been updated. Your data is now translatable."
            );

        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    protected function migrateData ($records)
    {
        $bar = $this->output->createProgressBar(count($records));

        $bar->start();

        foreach ($records as $record) {
            foreach ($this->translateable_columns as $column) {

                $value = $record->getAttributes()[$column];
                if (Str::isJson($value)) {
                    $value = json_decode($value, true);
                }

                $record->{$column} = [
                    $this->locale => $value,
                ];
            }
            $record->update();
            $bar->advance();
        }

        $bar->finish();
    }

    protected function checkTableStructureIsValid ()
    {
        /**
         * Rules
         * [x] There cannot be any kind of index on one of the columns.
         */
        $table = $this->resource_class->getTable();
        foreach ($this->translateable_columns as $column) {
            $results = DB::select(DB::raw(
                "SHOW INDEXES FROM `$table` WHERE `Column_name` = '$column';"
            ));
            if (empty($results)) {
                continue;
            }
            throw new Exception("There is an index on the column `$column`. This cannot be migrated to type JSON which is required for the translations. Remove the index or don't translate this field.", 1);
        }
    }

    protected function updateTableStructure ()
    {
        $table = $this->resource_class->getTable();
        foreach ($this->translateable_columns as $column) {
            $results = DB::select(DB::raw(
                "SHOW COLUMNS FROM `$table` WHERE `Field` = '$column';"
            ));
            if (empty($results)) {
                continue;
            }
            $database_column = $results[0];
            
            if ($database_column->Type == 'json') {
                continue;
            }

            $alter_query = "ALTER TABLE `$table` CHANGE `$column` `$column` JSON ";
            if ($database_column->Null == 'NO') {
                $alter_query .= 'NOT NULL;';
            } else {
                $alter_query .= 'NULL;';
            }

            DB::statement($alter_query);
        }
    }
}
