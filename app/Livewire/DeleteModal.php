<?php

namespace App\Livewire;

use Flux\Flux;
use Illuminate\View\View;
use Livewire\Component;

class DeleteModal extends Component
{
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
//        dd($this->model, $this->recordIds);
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

            $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus');
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
