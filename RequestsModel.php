<?php

class PaymentsModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function createPayment(int $donorId, int $requestId, int $paymentMethodId, float $amount): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO payments (donor_id, request_id, payment_method_id, amount, paid_at)
            VALUES (:donor, :request, :method, :amount, NOW())
        ");
        $stmt->bindParam(':donor',  $donorId,         PDO::PARAM_INT);
        $stmt->bindParam(':request',$requestId,       PDO::PARAM_INT);
        $stmt->bindParam(':method', $paymentMethodId, PDO::PARAM_INT);
        $stmt->bindValue(':amount', number_format($amount, 2, '.', ''), PDO::PARAM_STR);
        if ($stmt->execute()) {
            return (int)$this->db->lastInsertId();
        }
        return false;
    }

    public function getPaymentsByDonorId(int $donorId): array|false {
        try {
            $stmt = $this->db->prepare("
                SELECT
                    id,
                    donor_id,
                    request_id,
                    payment_method_id,
                    amount,
                    paid_at
                FROM payments
                WHERE donor_id = :donor_id
                ORDER BY paid_at DESC
            ");
            $stmt->bindParam(':donor_id', $donorId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching payments by donor ID: " . $e->getMessage());
            return false;
        }
    }
        /**
     * Get all payments
     *
     * @return array|false Array of all payments, or false on failure
     */
    public function getAllPayments(): array|false {
        try {
            $stmt = $this->db->prepare("
                SELECT
                    id,
                    donor_id,
                    request_id,
                    payment_method_id,
                    amount,
                    paid_at
                FROM payments
                ORDER BY paid_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Error fetching all payments: " . $e->getMessage());
            return false;
        }
    }
}