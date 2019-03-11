<?php

namespace Models;

use PDO;

class Product extends Model
{

	/**
	 * get All Products
	 * @return array
	 */
	public function getProducts()
	{
		$sql = "SELECT id, name, price FROM products";
		$query = $this->db->prepare($sql);
		$query->execute();

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * get product by name
	 * @param $name
	 * @return mixed
	 */
	public function getProductByName($name)
	{
		$sql = "SELECT id, name, price FROM products WHERE name = :product_name LIMIT 1";
		$query = $this->db->prepare($sql);
		$parameters = [':product_name' => $name];
		$query->execute($parameters);

		return $query->fetch();
	}

	/**
	 * get product by name
	 * @param $id
	 * @return mixed
	 */
	public function getProductById($id)
	{
		$sql = "SELECT id, name, price FROM products WHERE id = :id LIMIT 1";
		$query = $this->db->prepare($sql);
		$parameters = [':id' => $id];
		$query->execute($parameters);

		return $query->fetch();
	}

	/**
	 * Add new product
	 * @param $name
	 * @param $price
	 */
	public function addProduct($name, $price)
	{
		$sql = "INSERT INTO products (name, price) 
                VALUES (:name, :price)";
		$query = $this->db->prepare($sql);
		$parameters = [':name' => $name, ':price' => $price];

		$query->execute($parameters);
	}

	/**
	 * Update product by product name
	 *
	 * @param $id
	 * @param $name
	 * @param $price
	 * @return bool (affected rows)
	 */
	public function updateProduct($id, $name, $price)
	{
		if ($this->isDuplicateRecord(func_get_args())) {
			return true;
		}

		$sql = "UPDATE products
                SET name = :name, price = :price
                WHERE id = :id";
		$query = $this->db->prepare($sql);
		$parameters = [':id' => $id, ':name' => $name, ':price' => $price];

		$query->execute($parameters);
		return $query->rowCount() > 0;
	}

	/**
	 * Delete product by id
	 *
	 * @param $id
	 * @return bool - affected rows
	 */
	public function deleteProduct($id)
	{
		$sql = "DELETE FROM products WHERE id = :id";
		$query = $this->db->prepare($sql);
		$parameters = array(':id' => $id);

		$query->execute($parameters);
		return $query->rowCount() > 0;
	}

	/**
	 * Delete all Products
	 */
	public function deleteAllProducts()
	{
		$query = $this->db->prepare('DELETE FROM products');
		$query->execute();
	}

	/**
	 * Checks if product with the given input data already exists in DB
	 * @param $data
	 * @return bool
	 */
	private function isDuplicateRecord($data)
	{
		$res = $this->getProductById($data[0]);

		if (!$res) {
			return false;
		}

		$res = (array) $res;
		if ($res['name'] == $data[1] && $res['price'] == $data[2]) {
			return true;
		}

		return false;
	}
}