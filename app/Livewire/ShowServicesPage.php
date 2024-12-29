<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;

class ShowServicesPage extends Component
{
    public function render()
    {
        $data['services'] = Service::orderBy('title', 'ASC')->get();

        return view('livewire.show-services-page',compact('data'));
    }
}
