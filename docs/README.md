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
```
Note, in the above definition, that the map method for Cons, only maps over the tail, not the head, and that the map method for Nil simply returns itself.
The Cons map method tells us how to recurse into the structure, while the Nil map method tells us when to stop!

### cata (callable $f, $xs)
#### Description
Catamorphisms are used to tear down recursive structures, level by level.

In order for them to work, we need to pass in an `Algebra` as our `$f`, and our recursive data structure as our `$xs`.
An `Algebra` is simply a function from our recursive data structure to the type our data structure contains.

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
Anamorphisms be used to build up a recursive structure.

#### Usage
```php
use function Phantasy\Recursion\ana;
```
#### Examples
```php
```

### hylo (callable $f, callable $g, $x)
#### Description
A combination of an anamorphism and a catamorphism, a Hylomorphism can be used to build up and then tear down a recursive structure.
#### Usage
```php
use function Phantasy\Recursion\hylo;
```
#### Examples
```php
```