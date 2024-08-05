<?php

namespace App\Controller\admin;

use App\Controller\AbstractController;
use App\Model\admin\ProductManager;
use App\Model\admin\CategorieManager;
use App\Model\admin\StockManager;

class ProductController extends AbstractController
{
    /**
     * List Product
     */
    public function index(): string
    {
        $productManager = new ProductManager();
        $items = $productManager->selectAllStockAndCategory();
        return $this->twig->render('Admin/Product/index.html.twig', ['items' => $items]);
    }

    /**
     * Show informations for a specific Product
     */
    public function show(int $id): string
    {
        $productManager = new ProductManager();
        $item = $productManager->selectOneById($id);
        return $this->twig->render('Admin/Product/show.html.twig', ['item' => $item]);
    }

    /**
     * Edit a specific Product
     */
    public function edit(int $id): ?string
    {
        $productManager = new ProductManager();
        $categoryManager = new CategorieManager();
        $stockManager = new StockManager();
        $categories = $categoryManager->selectAll();
        $item = $productManager->selectOneById($id);
        $stock = $stockManager->getStockById($id);
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = array_map('trim', $_POST);
            $stock = array_map('trim', $_POST);

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $basedir = __DIR__ . '/../../../public';
                $uploadDir = '/assets/images/uploads/';
                $uploadFile = $basedir . $uploadDir . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $item['image'] = basename($_FILES['image']['name']);
                } else {
                    $errors['image'] = 'Erreur lors du téléchargement de l\'image.';
                }
            }

            $errors = array_merge($errors, getErrorForm($item));

            if (empty($errors)) {
                $productManager->update($item);
                $stockManager->updateStock($stock);
                header('Location: /admin/product/show?id=' . $id);
                return null;
            }
        }

        return $this->twig->render('Admin/Product/edit.html.twig', [
            'item' => $item,
            'errors' => $errors,
            'categories' => $categories,
            'stock' => $stock
        ]);
    }

    /**
     * Add a new Product
     */
    public function add(): ?string
    {
        $categoryManager = new CategorieManager();
        $categories = $categoryManager->selectAll();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = array_map('trim', $_POST);

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $basedir = __DIR__ . '/../../../public';
                $uploadDir = '/assets/images/uploads/';
                $uploadFile = $basedir . $uploadDir . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $item['image'] = basename($_FILES['image']['name']);
                } else {
                    $errors['image'] = 'Erreur lors du téléchargement de l\'image.';
                }
            }


            $errors = array_merge($errors, getErrorForm($item));

            if (!empty($errors)) {
                return $this->twig->render('admin/Product/add.html.twig', [
                    'errors' => $errors,
                    'item' => $item,
                    'categories' => $categories
                ]);
            }

            $productManager = new ProductManager();

            // récupérer l'id (et la qty ?)
            $id = $productManager->insert($item);
            $qty = (int)$item['quantity'];

            // ajouter au stock ce nouveau produit
            $stockManager = new StockManager();
            $stockManager->add($id, $qty);
            //$this->loggerProduct->productCreation($item['name'], $userId);
            return $this->twig->render('admin/Product/add.html.twig', [
                'success' => true,
                'categories' => $categories
            ]);
        }

        return $this->twig->render('admin/Product/add.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Delete a specific Product
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = trim($_GET['id']);
            $categorieManager = new ProductManager();
            $categorieManager->delete((int)$id);
            header('Location:/admin/product');
        }
    }
}
function getErrorForm(array $item): array
{
    $errors = [];
    $categoryManager = new CategorieManager();
    $errors = array_merge($errors, validateProductname($item['name'] ?? ''));
    $errors = array_merge($errors, validateProductdescription($item['description'] ?? ''));
    $errors = array_merge($errors, validateProductdescriptionDetaille($item['descriptionDetail'] ?? ''));
    $errors = array_merge($errors, validateProductprice($item['price'] ?? ''));
    $errors = array_merge($errors, validateProductimage($item['image'] ?? ''));
    $errors = array_merge($errors, validateProductquantity($item['quantity'] ?? ''));
    $errors = array_merge($errors, validateProductcategory($item['category_id'] ?? '', $categoryManager));

    return $errors;
}

function validateProductname($name): array
{
    if (empty($name) || strlen($name) > 255) {
        return ['name' => "Un nom est nécessaire et il ne doit pas dépasser 255 caractères."];
    }
    return [];
}

function validateProductdescription($description): array
{
    if (empty($description)) {
        return ['description' => "Une description courte est obligatoire."];
    }
    return [];
}

function validateProductdescriptionDetaille($description): array
{
    if (empty($description)) {
        return ['description' => "Une description detaillé est obligatoire."];
    }
    return [];
}

function validateProductprice($price): array
{
    if (empty($price) || !filter_var($price, FILTER_VALIDATE_FLOAT) || $price < 0) {
        return ['price' => "Le prix doit être un nombre valide."];
    }
    return [];
}

function validateProductimage($image): array
{
    if (empty($image) || !preg_match('/\.(jpg|jpeg|png|gif)$/i', $image)) {
        return ['image' => "Le format de l\'image doit être jpg, jpeg, png ou gif."];
    }

    return [];
}

function validateProductquantity($quantity): array
{
    if (empty($quantity) || !filter_var($quantity, FILTER_VALIDATE_INT) || $quantity <= 0) {
        return ['quantity' => "La quantité doit être supérieure ou égale à 0."];
    }
    return [];
}

function validateProductcategory($category, $manager): array
{
    if (empty($category) || !$manager->selectOneById((int)$category)) {
        return ['category_id' => "categorie doit correspondre à une catégorie existante."];
    }
    return [];
}
