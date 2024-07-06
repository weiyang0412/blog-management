<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;

class PostController
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPosts(Request $request, Response $response, $args)
    {
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

    public function getPostById(Request $request, Response $response, $args)
    {
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

    public function createPost(Request $request, Response $response, $args)
    {
        try {
            error_log("createPost method called");

            $parsedBody = $request->getParsedBody();
            $uploadedFiles = $request->getUploadedFiles();
            echo "<script>console.log('Debug Objects: " . json_encode($parsedBody) . "' );</script>";

            if (!isset($parsedBody['title']) || !isset($parsedBody['excerpt']) || !isset($parsedBody['body']) || !isset($parsedBody['category']) || !isset($uploadedFiles['thumbnail'])) {
                throw new Exception("Title, excerpt, body, category, and thumbnail are required.");
            }

            $title = $parsedBody['title'];
            $excerpt = $parsedBody['excerpt'];
            $body = $parsedBody['body'];
            $category = $parsedBody['category'];
            $user_id = $parsedBody['user_id'];
            $thumbnail = $uploadedFiles['thumbnail'];

            // Handle uploaded file
            if ($thumbnail->getError() === UPLOAD_ERR_OK) {
                $thumbnailDirectory = __DIR__ . '/../../../frontend/src/assets/thumbnails';
                if (!is_dir($thumbnailDirectory)) {
                    mkdir($thumbnailDirectory, 0777, true);
                }
                chmod($thumbnailDirectory, 0777); // Ensure directory is writable

                $thumbnailFilename = sprintf('%s_%s', uniqid(), $thumbnail->getClientFilename());
                $thumbnail->moveTo($thumbnailDirectory . DIRECTORY_SEPARATOR . $thumbnailFilename);
            } else {
                throw new Exception("Error uploading thumbnail.");
            }

            // Save the post details in the database
            $conn = $this->db->connect();
            $sql = "INSERT INTO posts (title, excerpt, body, category, user_id, thumbnail) VALUES (:title, :excerpt, :body, :category, :user_id, :thumbnail)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':excerpt', $excerpt);
            $stmt->bindValue(':body', $body);
            $stmt->bindValue(':category', $category);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':thumbnail', $thumbnailFilename);
            $stmt->execute();

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

    public function updatePost(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            
            $parsedBody = $request->getParsedBody();
            $uploadedFiles = $request->getUploadedFiles();

            error_log("Parsed body: " . print_r($parsedBody, true));
            error_log("Uploaded files: " . print_r($uploadedFiles, true));

            if (empty($parsedBody)) {
                throw new \Exception("Parsed body is empty");
            }

            if (!isset($parsedBody['title']) || !isset($parsedBody['excerpt']) || !isset($parsedBody['body']) || !isset($parsedBody['category']) || !isset($parsedBody['user_id'])) {
                throw new \Exception("Title, excerpt, body, category, and user_id are required.");
            }

            $title = $parsedBody['title'];
            $excerpt = $parsedBody['excerpt'];
            $body = $parsedBody['body'];
            $category = $parsedBody['category'];
            $user_id = $parsedBody['user_id'];
            $thumbnailFilename = $uploadedFiles['thumbnail'];

            if (isset($uploadedFiles['thumbnail'])) {
                $thumbnail = $uploadedFiles['thumbnail'];
    
                // 验证文件类型和大小
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($thumbnail->getClientMediaType(), $allowedTypes)) {
                    throw new \Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
                }
    
                $maxFileSize = 2 * 1024 * 1024; // 2MB
                if ($thumbnail->getSize() > $maxFileSize) {
                    throw new \Exception("File size exceeds the limit of 2MB.");
                }
    
                $thumbnailDirectory = __DIR__ . '/../../../frontend/src/assets/thumbnails';
                if (!is_dir($thumbnailDirectory)) {
                    mkdir($thumbnailDirectory, 0755, true);
                }
    
                $thumbnailFilename = sprintf('%s_%s', uniqid(), $thumbnail->getClientFilename());
                $thumbnail->moveTo($thumbnailDirectory . DIRECTORY_SEPARATOR . $thumbnailFilename);
            }
    
            $sql = "UPDATE posts SET title = :title, excerpt = :excerpt, body = :body, category = :category, user_id = :user_id, updated_at = NOW()";
            if ($thumbnailFilename !== null) {
                $sql .= ", thumbnail = :thumbnail";
            }
            $sql .= " WHERE id = :id";
    
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':excerpt', $excerpt);
            $stmt->bindValue(':body', $body);
            $stmt->bindValue(':category', $category);
            $stmt->bindValue(':user_id', $user_id);
            if ($thumbnailFilename !== null) {
                $stmt->bindValue(':thumbnail', $thumbnailFilename);
            }
            $stmt->bindValue(':id', $id);
            $stmt->execute();
    
            $message = ["message" => "Post updated successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            error_log("Exception: " . $e->getMessage());
            $error = ["error" => "Error updating post: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function deletePost(Request $request, Response $response, $args)
    {
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
