<?php

namespace App\Livewire;

use App\Models\Member;
use Livewire\Component;

class ShowTeam extends Component
{
    public function render()
    {
        $members = Member::where('status',1)->orderBy('id','desc')->get();
        return view('livewire.show-team',compact('members'));
    }
}
