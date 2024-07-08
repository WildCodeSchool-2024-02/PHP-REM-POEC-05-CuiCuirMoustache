<?php

namespace App\Model;

use PDO;

class AuthModel extends AbstractManager
{
    public const TABLE = 'users'; 

    /**
     * Méthode pour vérifier les informations de connexion d'un utilisateur.
     *
     * @param string $username Nom d'utilisateur
     * @param string $password Mot de passe
     * @return bool Retourne true si l'authentification est réussie, sinon false
     */
    public function authenticate(string $username, string $password): bool
    {
        // Prépare la requête SQL pour vérifier l'authentification
        $query = "SELECT id FROM " . self::TABLE . " WHERE username = :username AND password = :password";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'username' => $username,
            'password' => md5($password) // Exemple: utilisation de md5 pour le hashage du mot de passe
        ]);

        // Récupère le résultat de la requête
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifie si un utilisateur correspondant a été trouvé
        if ($user) {
            return true;
        }

        return false;
    }

}
