<?php

namespace Senna\UI\Livewire;

use Livewire\Component;

abstract class Autocomplete extends Component
{
    public $results;
    public $search;
    public $selected;
    public $showDropdown;
    public $class;
    public $name;
    public $placeholder;

    abstract public function query();

    public function mount($name = "autocomplete", $value = "", $class = null, $placeholder = null)
    {
        $this->name = $name;
        $this->search = $value;
        $this->class = $class ?? "w-full text-black border-1 focus:border-white border-white placeholder-black shadow-sm  placeholder-opacity-30 rounded-md focus:outline-none focus:border focus:ring focus:ring-white focus:ring-opacity-50 bg-transparent focus:outline-none";
        $this->showDropdown = false;
        $this->placeholder = $placeholder;
        $this->results = collect();
    }

    public function updatedSelected($id)
    {
        $this->emitSelf('valueSelected', $id);
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->results = collect();
            $this->showDropdown = false;
            return;
        }

        if ($this->query()) {
            $this->results = $this->query()->get();
        } else {
            $this->results = collect();
        }

        $this->selected = '';
        $this->showDropdown = true;
    }

    public function render()
    {
        return view('senna.ui::livewire.autocomplete');
    }
}
