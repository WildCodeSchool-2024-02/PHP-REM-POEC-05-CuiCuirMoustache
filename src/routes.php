<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index',],
    'admin' => ['AdminController', 'index',],
    'login' => ['Auth\AuthController', 'authentification'],
    'signup' => ['Auth\AuthController', 'signup'],
    'forgot_password' => ['Auth\AuthController', 'forgotPassword'],
    'reset_password' => ['Auth\AuthController', 'resetPassword', ['token']],
    'logout' => ['Auth\AuthController', 'authLogout'],
    'account' => ['Auth\AccountController', 'account'],
    'edit_account' => ['Auth\AccountController', 'editAccount'],
    'cart' => ['CartController', 'index', ['status']],
    'cart/add' => ['CartController', 'add', ['id', 'qty']],
    'cart/update' => ['CartController', 'update', ['id', 'qty']],
    'cart/delete' => ['CartController', 'delete', ['id']],
    'cart/order' => ['CartController', 'order'],
    'cart/modify' => ['CartController', 'modify', ['id', 'qty']],
    'product' => ['ProductController', 'index'],
    'product/show' => ['ProductController', 'show', ['id']],
    'admin/stock' => ['admin\\StockController', 'index'],
    'admin/stock/update' => ['admin\\StockController', 'update', ['id', 'quantity']],
    'admin/categorie' => ['admin\\CategorieController', 'index',],
    'admin/categorie/edit' => ['admin\\CategorieController', 'edit', ['id']],
    'admin/categorie/show' => ['admin\\CategorieController', 'show', ['id']],
    'admin/categorie/add' => ['admin\\CategorieController', 'add',],
    'admin/categorie/delete' => ['admin\\CategorieController', 'delete', ['id']],
    'admin/product' => ['admin\\ProductController', 'index',],
    'admin/product/edit' => ['admin\\ProductController', 'edit', ['id']],
    'admin/product/show' => ['admin\\ProductController', 'show', ['id']],
    'admin/product/add' => ['admin\\ProductController', 'add',],
    'admin/product/delete' => ['admin\\ProductController', 'delete', ['id']],
    'admin/orderitem' => ['admin\\OrderitemController', 'index',],
    'admin/orderitem/show' => ['admin\\OrderitemController', 'show', ['id']],
    'admin/orderitem/edit' => ['admin\\OrderitemController', 'edit', ['id']],
    'admin/user' => ['admin\\UserController', 'index',],
    'admin/user/edit' => ['admin\\UserController', 'edit', ['id']],
    'admin/user/show' => ['admin\\UserController', 'show', ['id']],
    'admin/user/add' => ['admin\\UserController', 'add',],
    'admin/user/delete' => ['admin\\UserController', 'delete', ['id']],
    'admin/log' => ['admin\\LogController', 'index',],
    'about' => ['HomeController', 'indexAbout',],
    'legal' => ['HomeController', 'indexLegal',],
    'privacy' => ['HomeController', 'indexPrivacy',],
    'category' => ['CategoryController', 'index', ['id']],
];
