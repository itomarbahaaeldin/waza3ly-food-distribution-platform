<?php

class PaymentOptionsModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllPaymentOptions(): array|false {
        $stmt = $this->db->prepare("SELECT id, name FROM payment_options ORDER BY id");
        return $stmt->execute() 
            ? $stmt->fetchAll(PDO::FETCH_ASSOC) 
            : false;
    }

    public function getPaymentOptionNameById(int $id): string|false {
        $stmt = $this->db->prepare("SELECT name FROM payment_options WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute() && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['name'];
        }
        return false;
    }

    public function getPaymentOptionIdByName(string $name): int|false {
        $stmt = $this->db->prepare("SELECT id FROM payment_options WHERE name = :name");
        $stmt->bindParam(':name', $name);
        if ($stmt->execute() && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return (int)$row['id'];
        }
        return false;
    }
}