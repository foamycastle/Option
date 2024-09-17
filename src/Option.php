<?php

namespace Foamycastle\Util\Option;

use Foamycastle\Util\Option\Contract\OptionValue;
use JsonSerializable;

class Option implements \Stringable, \Serializable, JsonSerializable, OptionValue {
    protected string $key;
    protected mixed $value;
    protected bool $default;
    protected string $type;

    private function __construct(string $key, mixed $value, bool $default = false)
    {
        $this->key = $key;
        $this->value = $value;
        $this->default = $default;
        $this->type = gettype($value);
    }
    public function getKey(): string
    {
        return $this->key;
    }

    public function withKey (string $key):OptionValue
    {
        $this->key = $key;
        return $this;
    }
    public function getType ():string
    {
        return $this->type;
    }
    public function isDefault ():bool
    {
        return $this->default;
    }
    public function setDefault (bool $default=true):OptionValue{
        $this->default=$default;
        return $this;
    }
    public function __toString (): string
    {
        return json_encode($this);
    }
    public function withValue (mixed $value):OptionValue
    {
        return new self($this->key, $value, $this->default);
    }
    public function getValue():mixed
    {
        return $this->value;
    }
    public function getObjectClass():string
    {
        if($this->type==='object') {
            return get_class($this->value);
        }
        return '';
    }

    public function serialize () :string
    {
        return (string)$this;
    }

    public function unserialize (string $data): void
    {
        try{
            $deserialized = json_decode($data);
            $this->key = $deserialized->key ?? "";
            $this->value = match($deserialized->type ?? null){
                'array','object'=> unserialize($deserialized->value ?? ''),
                'string', 'integer', 'double', 'float' => $deserialized->value,
                'NULL' => 'null',
                'default' => '',
            };
            $this->type=$deserialized->type ?? '';
            $this->default = $deserialized->default === 'true';
        }catch (\Exception $e){
            return;
        }
    }

    public function __serialize (): array
    {
        return $this->jsonSerialize();
    }

    public function __unserialize (array $data): void
    {
        $this->key = $data['key'] ?? "";
        $this->value = match($data['type'] ?? null){
            'array','object'=> unserialize($data['value'] ?? ''),
            'string', 'integer', 'double', 'float' => $data['value'],
            'NULL' => 'null',
            'default' => '',
        };
        $this->type=$data['type'] ?? '';
        $this->default = $data['default'] === 'true';
    }

    public function jsonSerialize (): array
    {
        return [
            'key' => $this->key,
            'value' => match($this->type) {
                'boolean' => $this->value ? 'true' : 'false',
                'string', 'integer', 'double', 'float' => $this->value,
                'array', 'object' => serialize($this->value),
                'NULL' => 'null',
                'default' => '',
            },
            'default' => $this->default ? 'true' : 'false',
            'type' => $this->type,
        ];
    }
    public static function Create(string $key, mixed $value, bool $default = false): OptionValue
    {
        return new self($key, $value, $default);
    }
}