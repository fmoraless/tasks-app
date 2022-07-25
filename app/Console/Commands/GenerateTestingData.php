<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class GenerateTestingData extends Command
{
    use ConfirmableTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:test-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate testing data for Task API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        User::query()->delete();
        Task::query()->delete();

        /* usuario para acceder a sistema */
        $user = User::factory()->hasTasks(1)->create([
            'name' => 'Francisco Morales',
            'email' => 'admin@admin.com',
        ]);
        /* usuarios para asignar tareas */
        $users = User::factory()->count(3)->create();
        /* Tareas de prueba */
        $tasks = Task::factory()->count(15)->create();

        $this->info('User UUID:');
        $this->line($user->id);

        $this->info('Token:');
        $this->line($user->createToken('Test')->plainTextToken);

        $this->info('Tarea ID:');
        $this->line($user->tasks()->first()->id);
    }
}
