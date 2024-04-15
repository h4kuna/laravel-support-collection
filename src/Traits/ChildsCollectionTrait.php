<?php

declare(strict_types=1);

namespace Pion\Support\Collection;

use Illuminate\Support\Collection;

/**
 * Creates a property for a childs
 */
trait ChildsCollectionTrait
{
    /**
     * @var Collection|null
     */
    public $childs = null;
}
