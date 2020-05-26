<?php

namespace Cart;
/*
 * Manufacture shopping cart class
 */
class Cart implements ShoppingCart
{
    /**
     * Cart constructor.
     */
    public function __construct()
    {
        session_set_cookie_params(7200, '/', null, false, true);
        session_start();
    }

    /**
     * @param array $product ['id' => '45', 'qty' => '2', 'price' => '100', 'name' => 'Jeans',
     *                         'variations'=>[ color=>red, dimension=>[size=>'L']]
     * @return string Return cart_item_id
     * @throws ShoppingCartException
     */
    public function add(array $product): string
    {
        /* Is item array is valid */
        if (!isset($product['id'], $product['qty'], $product['price'], $product['name'])) {
            throw new ShoppingCartException('The cart array must contain a product ID, quantity, price, and name.');
        }

        /* Generate unique cart item rowid */
        if (isset($product['variations'])) {
            $rowid = md5($product['id'] . serialize($product['variations']));

        } else {
            $rowid = md5($product['id']);
        }

        /* Make cart item & Xss filter all items */
        $product = array_map_recursive('xss_clean', $product);
        $product['rowid'] = $rowid;
        $product['qty'] = filter($product['qty'], 'int');
        $product['price'] = filter($product['price'], 'float');

        if ($this->isProductInCart($rowid)) {
            $qty = $this->getQty($rowid) + $product['qty'];
            $this->updateQty($rowid, $qty);

        } else {
            $this->setSession($rowid, $product);

        }

        $this->updateTotals($rowid);

        return $rowid;
    }

    /**
     * @param $rowid
     * @return bool
     */
    private function isProductInCart($rowid): bool
    {
        return isset($_SESSION['cart'][$rowid]);
    }

    /**
     * @param $rowid
     * @return mixed|null
     */
    private function getQty($rowid)
    {
        return $_SESSION['cart'][$rowid]['qty'] ?? null;
    }

    /**
     * @param $rowid
     * @param $qty
     */
    private function updateQty($rowid, $qty): void
    {
        $_SESSION['cart'][$rowid]['qty'] = $qty;
    }

    /**
     * @param $key
     * @param $data
     */
    private function setSession($key, $data): void
    {
        $_SESSION['cart'][$key] = $data;
    }

    /**
     * @param $rowid
     */
    private function updateTotals($rowid): void
    {
        $_SESSION['cart'][$rowid]['subtotal'] = $this->getProductPrice($rowid);
    }

    /**
     * @param $rowid
     * @return float|int|null
     */
    public function getProductPrice($rowid)
    {
        return isset($_SESSION['cart'][$rowid]) ?
            (float)$_SESSION['cart'][$rowid]['price'] * $this->getQty($rowid) : null;
    }

    public function update($rowid, $qty): void
    {
        $this->updateQty($rowid, $qty);
        $this->updateTotals($rowid);
    }

    /**
     * @param $rowid
     */
    public function increase($rowid): void
    {
        $qty = $this->getQty($rowid) + 1;

        $this->updateQty($rowid, $qty);
        $this->updateTotals($rowid);
    }

    /**
     * @param $rowid
     */
    public function decrease($rowid): void
    {
        if ($this->getQty($rowid) > 1) {
            $qty = $this->getQty($rowid) - 1;
            $this->updateQty($rowid, $qty);
            $this->updateTotals($rowid);

        } else {

            if (isset($_SESSION['cart'][$rowid])) {
                unset($_SESSION['cart'][$rowid]);
            }
            return;
        }
    }

    /**
     * @return mixed
     */
    public function getTotalPrice(): float
    {
        $totalPrice = $this->getTotals()['total_price'] ?? 0;

        return (float)$totalPrice;
    }

    /**
     * @return array|null
     */
    private function getTotals(): ?array
    {
        $item = $_SESSION['cart'] ?? null;//$this->getCart();
        $total_price = 0;
        $total_items = 0;
        if ($item) {
            foreach ($item as $k => $v) {
                $total_price += (int)$v['qty'] * (double)$v['price'];
                $total_items += (int)$v['qty'];
            }
            return ['total_price' => $total_price, 'total_items' => $total_items];
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getTotalItems(): int
    {
        $totalItems = $this->getTotals()['total_items'] ?? 0;

        return (int)$totalItems;
    }

    /**
     * @return array|mixed
     */
    public function getCart()
    {
        //$_SESSION['cart']['total'] = $this->getTotalPrice();
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Clear cart
     */
    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    /**
     * @param $rowid
     */
    public function delete($rowid): void
    {
        if (isset($_SESSION['cart'])) {

            unset($_SESSION['cart'][$rowid]);
            return;
        }
    }

}
