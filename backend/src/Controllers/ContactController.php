<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;

class ContactController {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function submitForm(Request $request, Response $response, $args) {
        try {
            $conn = $this->db->connect();
            $data = $request->getParsedBody();

            $name = $data['name'];
            $email = $data['email'];
            $subject = $data['subject'];
            $message = $data['message'];

            $sql = "INSERT INTO contacts (name, email, subject, message, created_at) VALUES (:name, :email, :subject, :message, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':subject', $subject);
            $stmt->bindValue(':message', $message);
            $stmt->execute();

            $message = ["message" => "Form submitted successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ["error" => "Database error: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        } catch (Exception $e) {
            $error = ["error" => "Error submitting form: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getAllForms(Request $request, Response $response, $args) {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT id, name, subject FROM contacts";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $forms = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $payload = json_encode($forms);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ["error" => "Database error: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        } catch (Exception $e) {
            $error = ["error" => "Error fetching forms: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getFormById(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $sql = "SELECT * FROM contacts WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $form = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($form) {
                $payload = json_encode($form);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $error = ["error" => "Form not found"];
                $payload = json_encode($error);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        } catch (PDOException $e) {
            $error = ["error" => "Database error: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        } catch (Exception $e) {
            $error = ["error" => "Error fetching form: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function updateForm(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $data = $request->getParsedBody();
            if (!isset($data['name']) || !isset($data['email']) || !isset($data['subject']) || !isset($data['message'])) {
                throw new Exception("Name, email, subject, and message are required.");
            }
            $name = $data['name'];
            $email = $data['email'];
            $subject = $data['subject'];
            $message = $data['message'];
            $sql = "UPDATE contacts SET name = :name, email = :email, subject = :subject, message = :message WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':subject', $subject);
            $stmt->bindValue(':message', $message);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $message = ["message" => "Form updated successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ["error" => "Database error: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        } catch (Exception $e) {
            $error = ["error" => "Error updating form: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function deleteForm(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $sql = "DELETE FROM contacts WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $message = ["message" => "Form deleted successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ["error" => "Database error: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        } catch (Exception $e) {
            $error = ["error" => "Error deleting form: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}

?>
