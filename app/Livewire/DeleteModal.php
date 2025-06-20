<?php

namespace App\Livewire;

use App\Helpers\WithToast;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class DeleteModal extends Component
{
    use WithToast;

    public string $model = '';
    public array $recordIds = [];

    public function mount(string $model = '', array $recordIds = []): void
    {
        $this->model = $model;
        $this->recordIds = $recordIds;
    }

    #[On('bulkDelete')]
    public function loadData(string $model = '', array $recordIds = []): void
    {
        $this->model = $model;
        $this->recordIds = $recordIds;
    }

    public function delete(): void
    {
        // Dynamically resolve the model
        $modelClass = 'App\\Models\\' . $this->model;
//        dd($modelClass);

        if (class_exists($modelClass)) {
            $record = $modelClass::whereIn('id', $this->recordIds);
//            dd($record);
            foreach ($record->get() as $record) {
                $record->delete();
            }

            $this->toast($this->model . ' berhasil dihapus', 'success');
            // Emit an event to refresh any relevant parent components
            $this->dispatch('record-deleted', $this->recordIds);
        }

        Flux::modals()->close();
    }

    public function render(): View
    {
        return view('livewire.delete-modal');
    }
}
