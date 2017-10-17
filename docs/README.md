## Recursion Schemes
Recursion schemes are extremely useful ways for working with recursive data structures, structures that may contain other values of the same data type. Examples include LinkedLists (where the tail is another LinkedList), Trees (where the branches themselves are other Trees), etc etc.

So, if you're able to write your program as a recursive structure, such as a tree, then these recursion schemes are able to help you fold down programs in useful ways.

In the examples below, we'll use a LinkedList, which is defined below. (With some help from [Phantasy-Types](https://github.com/mckayb/phantasy-types), for brevity purposes)
```php
use Phantasy\Types\sum;

$LL = sum('LinkedList', [
	'Cons' => ['head', 'tail'],
	'Nil' => []
]);

$LL->map = function (callable $f) {
	return $this->cata([
		'Cons' => function ($head, $tail) use ($f) {
			return $this->Cons($head, $f($tail));
 		},
		'Nil' => function () {
			return $this->Nil();
		}
	]);
};
$LL->isNil = function () {
	return $this->cata([
		'Cons' => function ($head, $tail) {
			return false;
		},
		'Nil' => function () {
			return true;
		}
	]);
};

// And include some quick helper functions
// so I don't have to litter everything with
// use ($LL)
function Cons ($head, $tail) use ($LL) {
  return $LL->Cons($head, $tail);
}

function Nil () use ($LL) {
  return $LL->Nil();
}
```
Note, in the above definition, that the map method for Cons, only maps over the tail, not the head, and that the map method for Nil simply returns itself.
The Cons map method tells us how to recurse into the structure, while the Nil map method tells us when to stop!

### cata (callable $f, $xs)
#### Description
Catamorphisms are used to tear down recursive structures, level by level. It is a generalization of folds on lists, to work on arbitrary data types.

In order for them to work, we need to pass in an `Algebra` as our `$f`, and our recursive data structure as our `$xs`.
An `Algebra` is simply a function from our recursive data structure to the type our data structure contains.
A catamorphism is the categorical dual of an anamorphism, if that means anything to you.

In a programming language like `Haskell`, this would simply be a function with type `f a -> a`.
#### Usage
```php
use function Phantasy\Recursion\cata;
```
#### Examples
```php
// Our recursive data structure.
$list = Cons(1, Cons(2, Cons(5, Nil())));

// Our Algebra. It takes a LinkedList of ints and returns an int.
$sum = function ($x) {
    return $x == Nil() ? 0 : $x->head + $x->tail;
};

cata($sum, $list);
// 8
```

### ana (callable $f, $x)
#### Description
Anamorphisms be used to build up a recursive structure level by level. It is a generalization of unfolds on lists, to work on arbitrary data types.

It can be useful to think of anamorphisms as a functional way to represent a while loop, if you're more familiar with imperative programming commonly found in other programming languages.

In order for them to work, we need to pass in an `Coalgebra` as our `$f`, and our initial value as our `$x`.
A `Coalgebra` is simply a function from the type of our seed values to the recursive data structure.
An anamorphism is the categorical dual of a catamorphism, if that means anything to you.

In a programming language like `Haskell`, this would simply be a function with type `a -> f a`.
#### Usage
```php
use function Phantasy\Recursion\ana;
```
#### Examples
```php
$range = function ($start, $end) {
  return ana(function ($x) use ($start, $end) {
    return $x >= $start && $x <= $end
      ? Cons($x, $x + 1)
      : Nil();
  }, $start);
};

echo $range(1, 5);
// Cons(1, Cons(2, Cons(3, Cons(4, Cons(5, Nil())))))
```

### hylo (callable $f, callable $g, $x)
#### Description
A combination of an anamorphism and a catamorphism, a Hylomorphism can be used to build up and then tear down a recursive structure.
Once again, the first parameter `$f` must be our `Algebra`, as defined in the `cata` docs. The second parameter must be a `Coalgebra` as defined in the `ana` docs. The final parameter is our initial seed value for the anamorphism.
#### Usage
```php
use function Phantasy\Recursion\hylo;
```
#### Examples
```php
$sum = function ($x) {
    return $x == Nil() ? 0 : $x->head + $x->tail;
};

$range = function ($start, $end) {
  return ana(function ($x) use ($start, $end) {
    return $x >= $start && $x <= $end
      ? Cons($x, $x + 1)
      : Nil();
  }, $start);
};

hylo($sum, $range(1, 5), 1);
// 15
```