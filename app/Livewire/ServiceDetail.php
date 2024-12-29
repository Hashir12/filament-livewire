<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;

class ServiceDetail extends Component
{
    public $service;

    public function mount($id){
        $this->service = Service::findOrFail($id);
    }

    public function render()
    {
        $service = $this->service;
        return view('livewire.service-detail',compact('service'));
    }
}

