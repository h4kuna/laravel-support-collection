<?php

declare(strict_types=1);

namespace Pion\Support\Collection;

use Illuminate\Support\Collection;

/**
 * Collection that will support adding values with grouped index (collection indexed by groupKey)
 *
 * @method Collection|null get($key, $default = null) in most use-cases returns a collection
 */
class GroupedCollection extends Collection
{
    /**
     * Adds the grouped collection
     *
     * @param string $groupKey
     * @param string|null $key
     * @return $this
     */
    public function addToGroup($groupKey, mixed $value, $key = null)
    {
        // get the group, if the group is not created
        // null will be returned
        $group = $this->get($groupKey);

        // create new empty group
        if (null === $group) {
            $group = new Collection();
            $this->put($groupKey, $group);
        }

        // add the value to the collection
        $group->put($key, $value);

        return $this;
    }
}
