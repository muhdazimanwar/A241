<?php
include('db_connect.php');

// Pagination setup
$items_per_page = 3; // Number of items per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Search setup
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Exact match search (no % wildcards)

// Get product data from the database with pagination
try {
    // If search term is provided, use exact match query
    if ($search != '') {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE name = :search LIMIT :offset, :limit");
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    } else {
        // If no search term, get all products
        $stmt = $pdo->prepare("SELECT * FROM products LIMIT :offset, :limit");
    }
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();

    // Get total number of products for pagination
    if ($search != '') {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name = :search");
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products");
    }
    $stmt->execute();
    $total_products = $stmt->fetchColumn();
    
    $total_pages = ceil($total_products / $items_per_page);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - MyEvent</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Inline styles to make the layout responsive */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .product-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: calc(33% - 20px);
            box-sizing: border-box;
            padding: 15px;
            text-align: center;
        }
        .product-item img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .product-item h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .product-item p {
            font-size: 14px;
            color: #555;
        }
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }

        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 10px 15px;
            border: 1px solid #ddd;
            margin: 0 5px;
            text-decoration: none;
            color: #007bff;
        }
        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Our Products</h2>
        
        <!-- Search Form -->
        <form method="GET" action="products.php">
            <input type="text" name="search" placeholder="Search products..." 
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Product List -->
        <div class="product-list">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <img src="images/<?= htmlspecialchars($product['picture']) ?>" 
                        alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='images/default.jpg'">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                        <p><strong>Quantity:</strong> <?= $product['quantity'] ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found matching your search.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                <a href="products.php?page=<?= $page ?>&search=<?= urlencode($search) ?>"><?= $page ?></a>
            <?php endfor; ?>
        </div>
        <p><a href="products.php">All product.</a></p>
    </div>
</body>
</html>


