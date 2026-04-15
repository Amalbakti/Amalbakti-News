<?php

namespace App\Livewire\News;

use Livewire\Attributes\Validate;
use Livewire\Component;

class Subscribe extends Component
{
    #[Validate('required|email|unique:subscribers,email')]
    public $email = '';

    public function subscribe()
    {
        $this->validate();

        $subscriber = \App\Models\Subscriber::create([
            'email' => $this->email,
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $subscriber->save();

        session()->flash('subscribe-success', 'Terima kasih telah berlangganan! you\'ll receive normalizer_get_raw_decompositionizasi terbaru dari kami.');
        $this->email = '';
    }
    public function render()
    {
        return view('livewire.news.subscribe');
    }
}
