<?php

class PaymentMethodOptionsModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getOptionsByPaymentMethodId(int $paymentMethodId): array|false {
        $query = "
            SELECT po.payment_option_id AS id, o.name
              FROM payment_method_options po
              JOIN payment_options o ON po.payment_option_id = o.id
             WHERE po.payment_method_id = :pmid
             ORDER BY po.id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':pmid', $paymentMethodId, PDO::PARAM_INT);
        return $stmt->execute() 
            ? $stmt->fetchAll(PDO::FETCH_ASSOC) 
            : false;
    }
}