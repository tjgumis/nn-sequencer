<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\DataStorage;

use Paneric\NNOptimizer\DataStorage\Traits\EquationLMDataStorageTrait;
use Paneric\NNOptimizer\DataStorage\Traits\NetworkDataStorageTrait;
use Paneric\NNOptimizer\DataStorage\Traits\SettingsDataStorageTrait;

class DataStorage
{
    use EquationLMDataStorageTrait;
    use NetworkDataStorageTrait;
    use SettingsDataStorageTrait;
}
