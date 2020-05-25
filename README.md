Manufacture Shopping Cart Class
-----

#### How to use

```php

$cart = new Cart();

$p1 = $cart->add(['id' => '45', 'qty' => '2', 'price' => '100', 'name' => 'Jeans',
    'variations' => [
        'color' => 'Black',
        'dimension' => [
            'size' => 'L'
        ]
    ]
]);

$p2 = $cart->add(['id' => '45', 'qty' => '2', 'price' => '100', 'name' => 'Jeans',
    'variations' => [
        'color' => "Blue",
        'dimension' => [
            'size' => 'M'
        ]
    ]
]);

$p3 = $cart->add(['id' => '20', 'qty' => '2', 'price' => '1000', 'name' => 'TB']);
$cart->update($p3, 15);
$cart->decrease($p1);

printf("Total is: %01.2f$ ", $cart->getTotalPrice());
printf(" Total items: %d ", $cart->getTotalItems());
printf(" Jeans price: %01.2f$ ", $cart->getProductPrice($p1));

$sopping_cart = $cart->getCart();

$cart->clear();

````

#### Methods

```php
Cart::add(array $product): string;
Cart::update($rowid, $qty);
Cart::increase($rowid);
Cart::decrease($rowid);
Cart::delete($rowid);
Cart::clear();

Cart::getCart();
Cart::getTotalPrice();
Cart::getTotalItems();
Cart::getProductPrice($rowid);

```
Example <code>$productArray</code>
```php
$product = [
    'id' => '20',           // *required
    'qty' => '2',           // *required
    'price' => '1000',      // *required
    'name' => 'TV',         // *required
    'variationns'=>[        // *Optional
        'color' => "Black",
        'type'=>'LED'
        'dimension' => [
            'size' => '14 inch'
    ]
];
```
Cart session <code>$_SESSION['cart']</code>

```php
Array
(
    '00ac11c92e4769676a520fb7ca43245d' => Array
        (
            'id' => 2,
            'qty' => 1,
            'price' => 12.99,
            'name' => 'T Shirt',
            'variations' => Array
                (
                    'color' => 'blue',
                    'dimension' => Array
                        (
                            'size' => 'L'
                        )

                )

            'rowid' => '00ac11c92e4769676a520fb7ca43245d',
            'subtotal' => 12.99
        )

)

```
