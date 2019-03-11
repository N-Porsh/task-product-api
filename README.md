### Task description:
Write a small API that uses a database backend. 
The main data entity in your API is a "product".
* A product must have the following fields/properties: 
    * ID (integer, assigned automatically) 
    * Name (alphanumeric, at most 100 characters) 
    * Price (decimal, with 2 decimal places)  

##### Tasks: 
1. Implement API calls for creating, updating and deleting these records. 
2. Implement API calls for retrieving a list of products, and for searching a product by name.
3. Ensure that the API calls have proper error handling, and output sensible error messages.
4. Document the API: explain the proper way to use it. Assume that the reader is not familiar with the API, and will be using it as a service (having no access to the source code). 

Write the application in PHP. Use a MySQL database. Please submit:
* API source code (including a README file with deployment instructions);
* SQL schema description;
* Documentation.
---
### Installation:
##### Vagrant :
1. install [VM VirtualBox v5.1.14](https://www.virtualbox.org/wiki/Download_Old_Builds) or newer
2. install [Vagrant](https://www.vagrantup.com/downloads.html) for your OS
3. check `VagrantFile` for some configuration if needed (default host port is: **80**)
4. open terminal, navigate to application folder and type: **vagrant up**
5. wait, it should download all the dependencies, you will see the message when it finishes.
6. You are ready to test the app.

---
# Documentation:

Swagger.json used as a documentation and validation resource.

Available at: http://localhost/product-schema.json

<table>
    <tr>
        <th>METHOD</th>
        <th>Description</th>
        <th>URL</th>
        <th>URL params</th>
        <th>Data params</th>
        <th>Request example</th>
        <th>Success response</th>
        <th>Error response</th>
    </tr>
    <tr>
        <td>GET</td>
        <td>Get all products</td>
        <td>/api/v1/products</td>
        <td>-</td>
        <td>-</td>
        <td></td>
        <td>
            [
              {
                "id": "1",
                "name": "pc",
                "price": "700.99"
              },
              {
                "id": "2",
                "name": "notebook",
                "price": "800"
              }
            ]
        </td>
        <td></td>
    </tr>
	<tr>
		<td>GET</td>
		<td>Get product by {product_name}</td>
		<td>/api/v1/products/{product_name}</td>
		<td>product_name=String</td>
		<td>-</td>
		<td>-</td>
		<td>
            [
              {
                "id": "2",
                "name": "notebook",
                "price": "800"
              }
            ]
        </td>
		<td>{
              "code": 404,
              "message": "Product not found"
            }</td>
	</tr>
    <tr>
		<td>POST</td>
		<td>Add new product</td>
		<td>/api/v1/products</td>
		<td>-</td>
		<td>
		name=String(100char long)
		price=Decimal(2 digits after dot)
		</td>
		<td>{
              "name": "notebook",
              "price": 1900.99
            }</td>
		<td>
            [
              {
                "id": "2",
                "name": "notebook",
                "price": "800"
              }
            ]
        </td>
		<td>{
              "code": 404,
              "message": "Product not found"
            }</td>
	</tr>
    <tr>
		<td>PUT</td>
		<td>Update product information</td>
		<td>/api/v1/products/{product_id}</td>
		<td>product_id=Integer</td>
		<td>
		name=String(100char long)
		price=Decimal(2 digits after dot)
		</td>
		<td>{
              "name": "pc",
              "price": 1900.99
            }</td>
		<td>Code 200 {}</td>
		<td>{
              "code": 404,
              "message": "Product not found"
            }</td>
	</tr>	
    <tr>
        <td>DELETE</td>
        <td>Delete all products</td>
        <td>/api/v1/products</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>Code 200 {}</td>
        <td></td>
    </tr>
	<tr>
		<td>DELETE</td>
		<td>Delete product by product_id</td>
		<td>/api/v1/products/{product_id}</td>
		<td>product_id=Integer</td>
		<td>-</td>
		<td>-</td>
		<td>Code 200 {}</td>
		<td>{
              "code": 404,
              "message": "Product not found"
            }</td>
	</tr>	
</table>

Validation error example: 

POST http://localhost/api/v1/products

request body:

```json
{
  "non_existing": "notebook"
}
```
Response example:
```json
{
  "status": "validation_failed",
  "errors": [
    {
      "property": "name",
      "message": "The property name is required",
      "constraint": "required"
    },
    {
      "property": "price",
      "message": "The property price is required",
      "constraint": "required"
    },
    {
      "property": "",
      "message": "The property non_existing is not defined and the definition does not allow additional properties",
      "constraint": "additionalProp"
    }
  ]
}
```