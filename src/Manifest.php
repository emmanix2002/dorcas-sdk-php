<?php

namespace Hostville\Dorcas;


class Manifest
{
    /**
     * @var array
     */
    private $data;

    /**
     * Manifest constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data ?: load_manifest();
    }

    /**
     * Reloads the manifest.json file.
     *
     * @return Manifest
     */
    public function reload(): Manifest
    {
        $this->data = load_manifest();
        return $this;
    }

    /**
     * Returns the loaded manifest.json content as an array.
     *
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Finds an entry in the manifest.json data. Data are separated into sections (parent).
     *
     * @param string      $parent
     * @param string      $name
     * @param string|null $entry
     *
     * @return mixed
     */
    public function get(string $parent, string $name, string $entry = null)
    {
        $key = $parent. '.' . $name;
        if (!empty($entry)) {
            $key .= '.' . $entry;
        }
        return data_get($this->data, $key, null);
    }

    /**
     * Finds a resource entry in the manifest.json data.
     *
     * @param string      $name
     * @param string|null $entry
     *
     * @return mixed
     */
    public function getResource(string $name, string $entry = null)
    {
        return $this->get('resources', $name, $entry);
    }

    /**
     * Finds a service entry in the manifest.json data.
     *
     * @param string      $name
     * @param string|null $entry
     *
     * @return mixed
     */
    public function getService(string $name, string $entry = null)
    {
        return $this->get('services', $name, $entry);
    }
}