<?php

namespace Foamycastle\Util\Option\Contract;

use Foamycastle\Util\Option\Option;

interface OptionSet  extends \ArrayAccess, \Iterator , \Serializable , \JsonSerializable {
    public function setOptionAsDefault (string|array $key):OptionSet;
    public function unsetOptionAsDefault (string|array $key):OptionSet;
    public function getOptionNamed(string $key, ?int &$index):?OptionValue;
    public static function Create(array $data):OptionSet;

}