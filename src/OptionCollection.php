<?php

namespace Foamycastle\Util\Option;

use Foamycastle\Util\Option\Contract\OptionSet;
use Foamycastle\Util\Option\Contract\OptionValue;

class OptionCollection implements Contract\OptionSet
{
    /**
     * @var Option[]
     */
    protected array $options = [];
    private function __construct(array $options)
    {
        foreach ($options as $option=>$value) {
            if($value instanceof OptionValue) {
                $this->options[]=$value;
                continue;
            }
            $newOption = Option::Create($option, $value);
            $this->options[] = $newOption;
        }
    }
    public function __get (string $name) :?Option
    {
        $getOption=$this->getOptionNamed($name, $index);
        if($getOption===null){
            $newOption = Option::Create($name,null);
            $this->options[] = $newOption;
            return $newOption;
        }
        return $getOption;
    }
    public function __set (string $name, $value): void
    {
        $option=$this->getOptionNamed($name,$index);
        if($option!==null) {
            $this->options[$index] = $option->withValue($value);
        }else{
            $this->options[] = Option::Create($name,$value);
        }
    }
    public function __isset (string $name): bool
    {
        return $this->getOptionNamed($name,$index) !== null;
    }
    public function __unset (string $name): void
    {
        if($this->getOptionNamed($name,$index)!==null) {
            unset($this->options[$index]);
        }
    }
    public function __invoke(string|array $option):?OptionValue
    {
        if(is_string($option)) {
            return $this->getOptionNamed($option,$index);
        }
        foreach($option as $name=>$value) {
            $this->{$name}->withValue($value);
        }
        return null;
    }


    /**
     * @inheritDoc
     */
    public function current (): Option|false
    {
        return current($this->options);
    }

    /**
     * @inheritDoc
     */
    public function next (): void
    {
        next($this->options);
    }

    /**
     * @inheritDoc
     */
    public function key (): mixed
    {
        return current($this->options)->getKey();
    }

    /**
     * @inheritDoc
     */
    public function valid (): bool
    {
        return current($this->options) !== false;
    }

    /**
     * @inheritDoc
     */
    public function rewind (): void
    {
        reset($this->options);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists (mixed $offset): bool
    {
        return $this->getOptionNamed($offset, $index) !== null;
    }

    /**
     * @inheritDoc
     */

    public function offsetGet (mixed $offset): ?Option
    {
        return $this->getOptionNamed($offset, $index);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet (mixed $offset, mixed $value): void
    {
        $newOption = Option::Create($offset, $value);
        if(!$this->offsetExists($offset)) {
            $this->options[] = $newOption;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset (mixed $offset): void
    {
        $this->getOptionNamed($offset, $index);
        unset($this->options[$index]);
    }

    /**
     * @inheritDoc
     */
    public function serialize (): false|string|null
    {
        return json_encode(
            [
                'optionMap'=>
                array_map(function ($option) {
                    return $option->serialize();
                },$this->options)
            ]
        ) ?: '';
    }

    /**
     * @inheritDoc
     */
    public function unserialize (string $data): void
    {
        try{
            $this->options=json_decode($data)['optionMap'];
        }catch (\Exception $e){
            return;
        }
    }

    public function setOptionAsDefault (array|string $key):self
    {
        $this->getOptionNamed($key,$index)->setDefault();
        return $this;
    }

    public function unsetOptionAsDefault (array|string $key):self
    {
        $this->getOptionNamed($key,$index)->setDefault(false);
        return $this;
    }

    public function getDefault (string $key): mixed
    {
        // TODO: Implement getDefault() method.
    }

    public function hasDefault (string $key): bool
    {
        // TODO: Implement hasDefault() method.
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize (): mixed
    {
        // TODO: Implement jsonSerialize() method.
    }

    public function __serialize (): array
    {
        // TODO: Implement __serialize() method.
    }

    public function __unserialize (array $data): void
    {
        // TODO: Implement __unserialize() method.
    }
    public function getOptionNamed (string $key, ?int &$index): ?OptionValue
    {
        foreach ($this->options as $option) {
            if (strcmp($option->getKey(), $key) === 0) {
                $index = key($this->options);
                return $option;
            }
        }
        return null;
    }
    public static function Create (array $data): OptionSet
    {
        return new self($data);
    }

}