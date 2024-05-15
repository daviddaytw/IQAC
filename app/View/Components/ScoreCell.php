<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ScoreCell extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?int $score
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if( is_null($this->score) ) {
            $color = 'inherit';
        } else {
            $G = round($this->score <= 50 ? $this->score * 5.1 : 255 );
            $R = round($this->score >= 50 ? (100-$this->score) * 5.1 : 255 );
            $color = "rgb($R, $G, 0)";
        }
        return view('components.score-cell', [
            'color' => $color,
        ]);
    }
}
