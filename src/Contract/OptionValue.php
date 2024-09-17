<?php

namespace Foamycastle\Util\Option\Contract;

interface OptionValue {
    public function getKey(): string;
    public function withKey (string $key):OptionValue;
    public function getType ():string;
    public function isDefault ():bool;
    public function setDefault (bool $default=true):OptionValue;
    public function getValue():mixed;
    public function withValue (mixed $value):OptionValue;
    public function getObjectClass():string;

}