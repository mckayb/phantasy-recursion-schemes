# Phantasy Recursion Schemes [![Build Status](https://travis-ci.org/mckayb/phantasy-recursion-schemes.svg?branch=master)](https://travis-ci.org/mckayb/phantasy-recursion-schemes) [![Coverage Status](https://coveralls.io/repos/github/mckayb/phantasy-recursion-schemes/badge.svg?branch=master)](https://coveralls.io/github/mckayb/phantasy-recursion-schemes)
Common recursion schemes implemented in PHP.

## Getting Started

### Installation
`composer require mckayb/phantasy-recursion-schemes`

### Usage
```php
// This comes from https://github.com/mckayb/phantasy-types
use function Phantasy\Types\sum;
use function Phantasy\Recursion\cata;

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
$alg = function ($x) {
	return $x->isNil() ? 0 : $x->head + $x->tail;
};
$a = $LL->Cons(3, $LL->Cons(2, $LL->Cons(1, $LL->Nil())));
echo cata($alg, $a);
// 6
```
For more information, read the [docs!](docs)

## What's Included
  * Catamorphisms, Anamorphisms, Hylomorphisms

## Contributing
Find a bug? Want to make any additions?
Just create an issue or open up a pull request.

## Want more?
For other helpers not included in this repo, check out
  * [Phantasy](https://github.com/mckayb/phantasy)
  * [Phantasy-PHP](https://github.com/mckayb/phantasy-php)
  * [Phantasy-Types](https://github.com/mckayb/phantasy-types)