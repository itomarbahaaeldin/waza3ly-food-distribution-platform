<?php

class PaymentOptionValuesModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function createPaymentOptionValue(int $paymentId, int $optionId, string $value): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO payment_option_values (payment_id, payment_option_id, value)
            VALUES (:pid, :optid, :val)
        ");
        $stmt->bindParam(':pid',   $paymentId, PDO::PARAM_INT);
        $stmt->bindParam(':optid', $optionId,  PDO::PARAM_INT);
        $stmt->bindParam(':val',   $value,     PDO::PARAM_STR);
        if ($stmt->execute()) {
            return (int)$this->db->lastInsertId();
        }
        return false;
    }

    public function getOptionValuesByPaymentId(int $paymentId): array|false {
        try {
            $stmt = $this->db->prepare("
                SELECT
                    id,
                    payment_id,
                    payment_option_id,
                    value
                FROM payment_option_values
                WHERE payment_id = :payment_id
                ORDER BY id
            ");
            $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching option values by payment ID: " . $e->getMessage());
            return false;
        }
    }
}