<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;

class UserController {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllUsers(Request $request, Response $response, $args) {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT * FROM users";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $payload = json_encode($result);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ["error" => "Error: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getUserById(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($user) {
                $payload = json_encode($user);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $error = ["error" => "User not found"];
                $payload = json_encode($error);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        } catch (PDOException $e) {
            $error = ["error" => "Database error: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function createUser(Request $request, Response $response, $args) {
        try {
            $conn = $this->db->connect();
    
            error_log("Content-Type: " . $request->getHeaderLine('Content-Type'));
            $bodyContent = (string)$request->getBody();
            error_log("Request Body: " . $bodyContent);
    
            $data = json_decode($bodyContent, true);
            error_log("Parsed Body: " . print_r($data, true));

            if (!isset($data['name']) || !isset($data['email']) || !isset($data['role']) || !isset($data['password'])) {
                throw new Exception("Name, email, and role are required.");
            }
    
            $name = $data['name'];
            $email = $data['email'];
            $password = $data['password'];
            $role = $data['role'];

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sqlCheck = "SELECT * FROM users WHERE email = :email";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->execute(['email' => $email]);
            $existingUser = $stmtCheck->fetch();
    
            if ($existingUser) {
                throw new Exception("The email has already been taken.");
            }

            $sqlInsert = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bindValue(':name', $name);
            $stmtInsert->bindValue(':email', $email);
            $stmtInsert->bindValue(':password', $hashedPassword);
            $stmtInsert->bindValue(':role', $role);
            $stmtInsert->execute();
    
            $message = ["message" => "User created successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = ["error" => "Error : " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function updateUser(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $data = json_decode($request->getBody()->getContents(), true);
            if (!isset($data['name']) || !isset($data['email']) || !isset($data['role'])) {
                throw new Exception("Name, email, and role are required.");
            }
            $name = $data['name'];
            $email = $data['email'];
            $role = $data['role'];
            $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':role', $role);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $message = ["message" => "User updated successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = ["error" => "Error updating user: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function deleteUser(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $message = ["message" => "User deleted successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ["error" => "Error deleting user: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function loginUser(Request $request, Response $response, $args) {
        try {
            $conn = $this->db->connect();
            $data = $request->getParsedBody();
    
            if (!isset($data['email']) || !isset($data['password'])) {
                throw new Exception("Email and password are required.");
            }
    
            $email = $data['email'];
            $password = $data['password'];
    
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception("Invalid email or password.");
            }
    
            $message = ["message" => "Login successful", "user" => $user];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = ["error" => "" . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
