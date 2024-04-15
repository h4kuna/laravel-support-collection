<?php

declare(strict_types=1);

namespace Pion\Support\Collection;

use Illuminate\Support\Collection;

/**
 * Creates a nested collection from flat collection to child nested collection based on parent property and id property.
 *
 * All childs are stored to childs property in the object. Can be empty.
 *
 * @method array toArray()
 * @method boolean isEmpty()
 */
class NestedObjectCollection
{
    /**
     * @var static
     */
    protected $groupedCollection;

    /**
     * The builded collection
     * @var Collection
     */
    protected $collection;

    /**
     * The property to get the parent id value that is used for grouping
     * @var string
     */
    protected $parentProperty;

    /**
     * NestedObjectCollection constructor.
     *
     * Groups the items by the parent property and loops from the top of tree (null property or different value) and
     * adds childs to the object.
     *
     * @param string $parentProperty the property name to get the parent id
     * @param string $idProperty the property name to get the id value
     * @param string $rootIndexKeyOnGroup the index key for getting the root elements. When the parent property value
     * returns null, it will be empty string.
     * @param string $propertyForChildren   the property name to store the children
     */
    public function __construct(
        Collection $items,
        $parentProperty = 'parent_id',
        /**
         * The property name for stored id
         */
        protected $idProperty = 'id',
        /**
         * the index key for getting the root elements. When the parent property value
         * returns null, it will be empty string.
         */
        protected $rootIndexKeyOnGroup = '',
        protected $propertyForChildren = 'childs'
    )
    {
        // group the collection by the parent property value
        $this->groupedCollection = $items->groupBy($parentProperty);

        // store the parent property name
        $this->parentProperty = $parentProperty;

        $this->collection = $this->buildCollection();
    }

    /**
     * Passes the function into the collection
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getCollection(), $name], $arguments);
    }

    /**
     * Creates a nested collection
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Builds the collection with the nested structure
     * @return Collection
     */
    protected function buildCollection()
    {
        // create empty collection that we will add children
        $collection = new Collection();

        // add children to the collection
        $this->addChildsItems($this->rootIndexKeyOnGroup, $collection);

        return $collection;
    }

    /**
     * Checks the grouped collection if given key is in the source and ads the childs to collection
     * @return $this
     */
    protected function addChildsItems($key, Collection $collection) {

        $childs = $this->groupedCollection->get($key);

        if (null !== $childs) {
            foreach ($childs as $child) {
                // create empty collection and add to the children
                $child->{$this->propertyForChildren} = new Collection();

                // try to add children's of the child
                $this->addChildsItems($child->{$this->idProperty}, $child->{$this->propertyForChildren});

                // store to the collection
                $collection->push($child);
            }
        }

        return $this;
    }
}
