<?php

namespace Foamycastle\Util\Option\Contract;

use Foamycastle\Util\Option\Option;

interface OptionSet  extends \ArrayAccess, \Iterator , \Serializable , \JsonSerializable {
    public function setOptionAsDefault (string|array $key);
    public function unsetOptionAsDefault (string|array $key);
    public function getDefault(string $key):mixed;
    public function hasDefault(string $key):bool;
    public function getOptionNamed(string $key, ?int &$index):?Option;

}