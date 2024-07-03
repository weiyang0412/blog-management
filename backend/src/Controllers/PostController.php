<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;

class PostController {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getPosts(Request $request, Response $response, $args) {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT * FROM posts";
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

    public function getPostById(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $sql = "SELECT * FROM posts WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $post = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($post) {
                $payload = json_encode($post);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $error = ["error" => "Post not found"];
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

    public function createPost(Request $request, Response $response, $args) {
        try {
            $loggedInUserId = $request->getAttribute('id');
    
            $conn = $this->db->connect();
            error_log("Content-Type: " . $request->getHeaderLine('Content-Type'));
            $bodyContent = (string)$request->getBody();
            error_log("Request Body: " . $bodyContent);
    
            $data = json_decode($bodyContent, true);
            error_log("Parsed Body: " . print_r($data, true));
            if (!isset($data['title']) || !isset($data['excerpt']) || !isset($data['body']) || !isset($data['category'])) {
                throw new Exception("Title, excerpt, body and category are required.");
            }
            $title = $data['title'];
            $excerpt = $data['excerpt'];
            $body = $data['body'];
            $category = $data['category'];
            $user_id = $data['user_id'];
    
            $sqlCheck = "SELECT * FROM posts WHERE title = :title";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bindValue(':title', $title);
            $stmtCheck->execute();
            $existingPost = $stmtCheck->fetch();
    
            if ($existingPost) {
                throw new Exception("Post with the same title already exists.");
            }
    
            $sqlInsert = "INSERT INTO posts (title, excerpt, body, category, user_id) 
                        VALUES (:title, :excerpt, :body, :category, :user_id)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bindValue(':title', $title);
            $stmtInsert->bindValue(':excerpt', $excerpt);
            $stmtInsert->bindValue(':body', $body);
            $stmtInsert->bindValue(':category', $category);
            $stmtInsert->bindValue(':user_id', $user_id);
            $stmtInsert->execute();
    
            $message = ["message" => "Post created successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = ["error" => "Error creating post: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
    
    public function updatePost(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $data = json_decode($request->getBody()->getContents(), true);
            if (!isset($data['title']) || !isset($data['excerpt']) || !isset($data['body']) || !isset($data['category'])) {
                throw new Exception("Title, excerpt, body and category are required.");
            }
            $title = $data['title'];
            $excerpt = $data['excerpt'];
            $body = $data['body'];
            $category = $data['category'];

            $sql = "UPDATE posts SET title = :title, excerpt = :excerpt, body = :body, category = :category WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':excerpt', $excerpt);
            $stmt->bindValue(':body', $body);
            $stmt->bindValue(':category', $category);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $message = ["message" => "Post updated successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = ["error" => "Error updating post: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function deletePost(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $sql = "DELETE FROM posts WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $message = ["message" => "Post deleted successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ["error" => "Error deleting post: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}