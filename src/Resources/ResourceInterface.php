<?php

namespace Hostville\Dorcas\Resources;


use Hostville\Dorcas\RequestInterface;

interface ResourceInterface extends RequestInterface
{
    /**
     * Sets the resource up to request a singular item with the provided id.
     *
     * @param string $id
     *
     * @return ResourceInterface
     */
    public function item(string $id): ResourceInterface;

    /**
     * Sets the resource up to request a collection of items.
     *
     * @return ResourceInterface
     */
    public function collection(): ResourceInterface;

    /**
     * Processes the relationships that are requested with the resource.
     * Relationships can either be passed as a series of strings; e.g.
     *      $resource->relationships('contacts', 'plods');
     * Or as an array that allows you to specify additional parameters; e.g.:
     *      $resource->relationships(['orders' => ['paginate' => [10, 0]], 'contacts', 'products']);
     *
     * NOTE: Not all relationships support pagination; also, the paginate parameter is special because it can either
     * be passed as above, or like so:
     *      $resource->relationships(['orders' => ['paginate' => ['limit' => 10, 'offset' => 0]], 'contacts', 'products']);
     * When specifying parameters for other relationships, only numeric indexes are supported.
     *
     * @param string[]|array ...$relationships
     *
     * @return ResourceInterface
     */
    public function relationships(...$relationships): ResourceInterface;
}