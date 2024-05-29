<?php

namespace App\Livewire;

use App\Facades\Image;
use App\Models\ComparisonResult;
use App\Services\DiffService;
use Livewire\Component;

class Differ extends Component
{

    public function render()
    {
        $sourceDir = request('source');
        $item = request('item');

        $screenshots = Image::getScreenshots('screenshots/',  $sourceDir);
        $results = ComparisonResult::latestTest($sourceDir)->get();

        return view('livewire.differ',  [
            'sources' => (new DiffService())->getSources(),
            'screenshots' => $screenshots,
            'hasSource' => (bool) $sourceDir,
            'hasResults' => $results->count() > 0,
            'item' => $item,
        ]);
    }
}
