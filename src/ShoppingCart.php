<?php

namespace Cart;

interface ShoppingCart
{
    public function add(array $product);
    public function update($rowid, $qty);
    public function delete($rowid);
    public function clear();
}
