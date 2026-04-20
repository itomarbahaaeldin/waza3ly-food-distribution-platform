<?php

class PaymentMethodsModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllPaymentMethods() {
        try {
            $query = "SELECT id, name FROM payment_methods ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching payment methods: " . $e->getMessage());
            return false;
        }
    }

    public function getPaymentMethodNameById(int $id) {
        try {
            $query = "SELECT name FROM payment_methods WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['name'] : false;
        } catch (PDOException $e) {
            error_log("Error fetching payment method name by id: " . $e->getMessage());
            return false;
        }
    }

    public function getPaymentMethodIdByName(string $name) {
        try {
            $query = "SELECT id FROM payment_methods WHERE name = :name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['id'] : false;
        } catch (PDOException $e) {
            error_log("Error fetching payment method id by name: " . $e->getMessage());
            return false;
        }
    }
}