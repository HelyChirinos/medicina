<?php

namespace Database\Seeders;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create main users (Propietarios)
        User::create([
            'name' => 'John Doe',
            'nombre'  => 'John',
            'apellido' => 'Doe',
            'cedula'  => '12345678',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'is_propietario' => '1',
        ]);

        User::create([
            'name' => 'Hely Chirinos Herrera',
            'nombre'  => 'Hely Antono',
            'apellido' => 'Chirinos Herrera',
            'cedula'  => '7222809',            
            'email' => 'helychirinos@gmail.com',
            'password' => Hash::make('hely2829'),
            'is_propietario' => '1',
        ]);

        // Create 48 dummy users
        $this->command->warn(PHP_EOL . 'Creating users ...');

        $this->withProgressBar(48, fn () => User::factory()->count(1)->create());

        $this->command->info('Users created.');
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
