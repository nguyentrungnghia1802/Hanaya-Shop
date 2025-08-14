<?php

return [
    // 支払い方法
    'payment_methods' => '支払い方法',
    'choose_payment_method' => '支払い方法を選択',
    'credit_card' => 'クレジットカード',
    'paypal' => 'PayPal',
    'cash_on_delivery' => '代金引換',
    'bank_transfer' => '銀行振込',

    // クレジットカード
    'pay_with_credit_card' => 'クレジットカードで支払う',
    'card_number' => 'カード番号',
    'card_holder_name' => 'カード名義人',
    'expiry_date' => '有効期限',
    'cvv' => 'セキュリティコード',
    'billing_address' => '請求先住所',
    'example_name' => '山田太郎',

    // PayPal
    'pay_with_paypal' => 'PayPalで支払う',
    'paypal_description' => '支払いを完了するためにPayPalにリダイレクトされます',
    'paypal_secure' => 'PayPalで安全に支払い',

    // 代金引換
    'cod_title' => '代金引換',
    'cod_description' => '商品受け取り時にお支払い',
    'cod_fee' => '代引き手数料',
    'cod_note' => '正確な現金をご用意ください',

    // 注文概要
    'order_summary' => '注文概要',
    'subtotal' => '小計',
    'shipping_fee' => '送料',
    'tax' => '税金',
    'discount' => '割引',
    'total' => '合計',
    'grand_total' => '総合計',

    // 支払い状況
    'payment_successful' => '支払い完了！',
    'payment_failed' => '支払い失敗',
    'payment_pending' => '支払い保留中',
    'payment_cancelled' => '支払いキャンセル',
    'processing_payment' => '支払い処理中...',
    'processing' => '処理中...',

    // メッセージ
    'payment_success_message' => '支払いが正常に処理されました。',
    'payment_error_message' => '支払い処理中にエラーが発生しました。もう一度お試しください。',
    'invalid_card' => '無効なカード情報',
    'payment_timeout' => '支払いタイムアウト。もう一度お試しください。',
    'paypal_redirect_message' => '支払いを完了するためにPayPalにリダイレクトされます',
    'redirecting_to_paypal' => 'PayPalにリダイレクト中...',

    // アクション
    'pay_now' => '今すぐ支払う',
    'confirm_payment' => '支払いを確認',
    'cancel_payment' => '支払いをキャンセル',
    'retry_payment' => '支払いを再試行',
    'back_to_cart' => 'カートに戻る',

    // セキュリティ
    'secure_payment' => '安全な支払い',
    'ssl_protected' => 'SSL保護',
    'payment_security_note' => 'お客様の支払い情報は暗号化され安全です',
    'cvv_validation' => 'CVVは3桁以上で入力してください',
    'card_number_validation' => 'カード番号は16桁以上で入力してください',
    'invalid_card_number' => '無効なカード番号',
    'invalid_month' => '無効な月',
    'card_expired' => 'カードの有効期限が切れています',
    'card_holder_required' => 'カード名義人を入力してください',
    'paypal_security_note' => 'PayPalでの支払いはSSL暗号化によって保護されています。あなたのアカウント情報は私たちと共有されることはありません。',
];
