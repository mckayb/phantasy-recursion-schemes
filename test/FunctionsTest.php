<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use function Phantasy\Recursion\{
    cata,
    ana,
    hylo
};

class FunctionsTest extends TestCase
{
    public function testCata()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $list = $Cons(1, $Cons(2, $Cons(5, $Nil())));
        $sum = function ($x) use ($Nil) {
            return $x == $Nil() ? 0 : $x->head + $x->tail;
        };

        $this->assertEquals(cata($sum, $list), 8);
    }

    public function testCataCurried()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $list = $Cons(1, $Cons(2, $Cons(5, $Nil())));
        $sum = function ($x) use ($Nil) {
            return $x == $Nil() ? 0 : $x->head + $x->tail;
        };

        $cata = cata();
        $cata_ = $cata();
        $cata__ = $cata();

        $cataS = cata($sum);
        $cataS_ = $cata_($sum);
        $cataS__ = $cata__($sum);

        $this->assertEquals($cataS($list), 8);
        $this->assertEquals($cataS_($list), 8);
        $this->assertEquals($cataS__($list), 8);
    }

    public function testAna()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $head = function ($xs) {
            return $xs[0];
        };

        $tail = function ($xs) {
            return array_slice($xs, 1);
        };

        $arrToList = function ($xs) use ($Cons, $Nil, $head, $tail) {
            return count($xs) === 0 ? $Nil() : $Cons($head($xs), $tail($xs));
        };

        $this->assertEquals(ana($arrToList, [1, 2, 3, 4, 5]), $Cons(1, $Cons(2, $Cons(3, $Cons(4, $Cons(5, $Nil()))))));
    }

    public function testAnaCurried()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $head = function ($xs) {
            return $xs[0];
        };

        $tail = function ($xs) {
            return array_slice($xs, 1);
        };

        $arrToList = function ($xs) use ($Cons, $Nil, $head, $tail) {
            return count($xs) === 0 ? $Nil() : $Cons($head($xs), $tail($xs));
        };

        $ana = ana();
        $anaF = ana($arrToList);
        $anaF_ = $ana($arrToList);

        $this->assertEquals(
            $ana($arrToList, [1, 2, 3, 4, 5]),
            $Cons(1, $Cons(2, $Cons(3, $Cons(4, $Cons(5, $Nil())))))
        );
        $this->assertEquals($anaF([1, 2, 3, 4, 5]), $Cons(1, $Cons(2, $Cons(3, $Cons(4, $Cons(5, $Nil()))))));
        $this->assertEquals($anaF_([1, 2, 3, 4, 5]), $Cons(1, $Cons(2, $Cons(3, $Cons(4, $Cons(5, $Nil()))))));
    }

    public function testHylo()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $sum = function ($x) use ($Nil) {
            return $x == $Nil() ? 0 : $x->head + $x->tail;
        };

        $head = function ($xs) {
            return $xs[0];
        };

        $tail = function ($xs) {
            return array_slice($xs, 1);
        };

        $arrToList = function ($xs) use ($Cons, $Nil, $head, $tail) {
            return count($xs) === 0 ? $Nil() : $Cons($head($xs), $tail($xs));
        };

        $this->assertEquals(hylo($sum, $arrToList, [1, 2, 3, 4, 5]), 15);
    }

    public function testHyloCurried()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $sum = function ($x) use ($Nil) {
            return $x == $Nil() ? 0 : $x->head + $x->tail;
        };

        $head = function ($xs) {
            return $xs[0];
        };

        $tail = function ($xs) {
            return array_slice($xs, 1);
        };

        $arrToList = function ($xs) use ($Cons, $Nil, $head, $tail) {
            return count($xs) === 0 ? $Nil() : $Cons($head($xs), $tail($xs));
        };

        $hylo = hylo();
        $hyloSum = hylo($sum);
        $hyloSum_ = $hylo($sum);
        $hyloBoth = $hyloSum($arrToList);
        $hyloBoth_ = $hyloSum_($arrToList);
        $this->assertEquals($hyloBoth([1, 2, 3, 4, 5]), 15);
        $this->assertEquals($hyloBoth_([1, 2, 3, 4, 5]), 15);
    }
}
