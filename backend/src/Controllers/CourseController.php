<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use Exception;

class CourseController {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getCourses(Request $request, Response $response, $args) {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT * FROM courses";
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

    public function getCourseById(Request $request, Response $response, $args) {
        try {
            $course_code = $args['course_code'];
            $conn = $this->db->connect();
            $sql = "SELECT * FROM courses WHERE course_code = :course_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':course_code', $course_code);
            $stmt->execute();
            $course = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($course) {
                $payload = json_encode($course);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $error = ["error" => "Course not found"];
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

    public function createCourse(Request $request, Response $response, $args) {
        try {
            $conn = $this->db->connect();
            error_log("Content-Type: " . $request->getHeaderLine('Content-Type'));
            $bodyContent = (string)$request->getBody();
            error_log("Request Body: " . $bodyContent);
    
            $data = json_decode($bodyContent, true);
            error_log("Parsed Body: " . print_r($data, true));
            if (!isset($data['course_code']) || !isset($data['course_name']) || !isset($data['credit'])) {
                throw new Exception("Course code, course name, and credit are required.");
            }
            $course_code = $data['course_code'];
            $course_name = $data['course_name'];
            $credit = $data['credit'];

            $sqlCheck = "SELECT * FROM courses WHERE course_code = :course_code";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bindValue(':course_code', $course_code);
            $stmtCheck->execute();
            $existingCourse = $stmtCheck->fetch();

            if ($existingCourse) {
                throw new Exception("Course with the same course code already exists.");
            }

            $sqlInsert = "INSERT INTO courses (course_code, course_name, credit) VALUES (:course_code, :course_name, :credit)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bindValue(':course_code', $course_code);
            $stmtInsert->bindValue(':course_name', $course_name);
            $stmtInsert->bindValue(':credit', $credit);
            $stmtInsert->execute();

            $message = ["message" => "Course created successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = ["error" => "Error creating course: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function updateCourse(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $data = json_decode($request->getBody()->getContents(), true);
            if (!isset($data['course_name']) || !isset($data['credit'])) {
                throw new Exception("Course name and credit are required.");
            }
            $course_name = $data['course_name'];
            $credit = $data['credit'];

            $sql = "UPDATE courses SET course_name = :course_name, credit = :credit WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':course_name', $course_name);
            $stmt->bindValue(':credit', $credit);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $message = ["message" => "Course updated successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $error = ["error" => "Error updating course: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function deleteCourse(Request $request, Response $response, $args) {
        try {
            $id = $args['id'];
            $conn = $this->db->connect();
            $sql = "DELETE FROM courses WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $message = ["message" => "Course deleted successfully"];
            $payload = json_encode($message);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ["error" => "Error deleting course: " . $e->getMessage()];
            $payload = json_encode($error);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
