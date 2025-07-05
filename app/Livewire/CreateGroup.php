<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class CreateGroup extends Component
{
    public string $name = '';
    public ?string $description = '';

    public $search = '';
    public array $selectedMembers = []; 

    public $users; 
    public function mount()
    {
        if (! User::user()->isLecturer()) {
            abort(403, 'Unauthorized action.');
        }
        $this->users = User::where('id', '!=', Auth::id())->get(); 
    }


    public function createGroup()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'selectedMembers' => ['array'],
            'selectedMembers.*' => ['exists:users,id'], 
        ]);

        $group = Group::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'lecturer_id' => Auth::id(),
        ]);


        $group->members()->attach(Auth::id());

        // Tambahkan anggota yang dipilih
        if (!empty($this->selectedMembers)) {
            $group->members()->attach($this->selectedMembers);
        }

        session()->flash('message', 'Grup berhasil dibuat!');
        $this->redirect(route('chat', ['group' => $group->id]), navigate: true); 
    }


}
