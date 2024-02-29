<?php
// Classe User
class User {
    // Propriétés privées
    private $id;
    private $email;
    private $password;
    private $role;

    // Constructeur de la classe User
    public function __construct( $id, $email, $password, $role) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    // Méthode pour enregistrer un utilisateur dans la base de données
    public function save($conn) {
        // Requête SQL pour l'insertion de l'utilisateur dans la table users
        $sql = "INSERT INTO users (email, password, role) VALUES ('" . $this->email . "', '" . $this->password . "', '" . $this->role . "')";

        // Exécution de la requête SQL
        if ($conn->query($sql) === TRUE) {
            echo "L'utilisateur a été enregistré avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement de l'utilisateur : " . $conn->error;
        }
    }

    // Méthode statique pour authentifier un utilisateur
    public static function login($conn, $email, $password) {
        // Requête SQL pour récupérer l'utilisateur à partir de son email
        $sql = "SELECT * FROM users WHERE email='" . $email . "'";
        $result = $conn->query($sql);

        // Vérification si un seul utilisateur a été trouvé avec l'email spécifié
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Vérification du mot de passe en utilisant password_verify
            if (password_verify($password, $row['password'])) {
                // Création et retour de l'objet User si l'authentification réussit
                return new User($row['id'], $row['email'], $row['password'], $row['role']);
            }
        }
        // Retourne null si l'authentification échoue
        return null;
    }

    public static function getAll($conn) {
        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);
        $users = array();
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Créez un nouvel objet User avec les données de chaque utilisateur
                $user = new User($row['id'], $row['email'], $row['password'], $row['role']);
                $users[] = $user;
            }
        }
    
        return $users;
    }
    
    
    public function getEmail() {
        return $this->email;
    }
    

    public function getId() {
        return $this->id;
    }
    
    public function getRole() {
        return $this->role;
    }

    public static function getById($conn, $user_id) {
        // Préparez la requête SQL pour récupérer l'utilisateur par son ID
        $sql = "SELECT * FROM user WHERE id = ?";
        
        // Préparez la requête en utilisant une déclaration préparée
        $stmt = $conn->prepare($sql);
        
        // Liez le paramètre de l'ID utilisateur à la déclaration préparée
        $stmt->bind_param("i", $user_id);
        
        // Exécutez la requête
        $stmt->execute();
        
        // Obtenez le résultat de la requête
        $result = $stmt->get_result();
        
        // Vérifiez s'il y a des lignes retournées
        if ($result->num_rows > 0) {
            // Récupérez les données de l'utilisateur à partir du résultat de la requête
            $row = $result->fetch_assoc();
            
            // Créez un nouvel objet User avec les données récupérées et retournez-le
            return new User($row['id'], $row['email'], $row['password'], $row['role']);
        } else {
            // Aucun utilisateur trouvé avec cet ID, retournez null
            return null;
        }
    }
    

}
?>
