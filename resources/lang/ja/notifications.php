<?php

return [
    'new_order_request_subject' => '新しい注文の確認依頼',
    'new_order_request_line' => '新しい注文が確認待ちです。',
    'order_code' => '注文コード',
    'view_order' => '注文を見る',
    'guest' => 'ゲスト',
    'order_waiting_confirmation' => '注文番号 #:order_id は確認待ちです。',

    // Customer notifications for new orders
    'customer_new_order_subject' => 'ご注文ありがとうございます！',
    'customer_new_order_line' => 'ご注文を受け付けました。現在処理中です。',
    'customer_order_waiting_confirmation' => 'ご注文番号 #:order_id は現在処理中です。',

    'order_confirmed_subject' => '注文が確認されました',
    'order_confirmed_line' => '注文番号 #:order_id が確認され、発送済みとしてマークされました。',
    'order_confirmed_message' => '注文番号 #:order_id が確認されました。',

    'order_cancelled_subject' => '注文がキャンセルされました',
    'order_cancelled_line'    => '注文番号 #:order_id はキャンセルされました。',
    'order_cancelled_message' => '注文番号 #:order_id はキャンセルされました。',

    'order_completed_subject'  => '注文番号 #:order_id が完了しました',
    'order_completed_greeting' => 'こんにちは :name さん,',
    'order_completed_line1'    => 'あなたの注文番号 #:order_id は正常に完了しました。',
    'order_completed_line2'    => '当店をご利用いただきありがとうございます！',
    'order_completed_line3'    => 'ご購入の商品をお楽しみください。',
    'order_completed_message'  => '注文番号 #:order_id が完了しました',
    'view_order_details'       => '注文詳細を見る',

    // Order shipped notifications
    'order_shipped_subject'    => '注文番号 #:order_id が発送されました',
    'order_shipped_line'       => 'ご注文番号 #:order_id は発送され、配送中です。',
    'order_shipped_message'    => '注文番号 #:order_id が発送されました',
    'order_shipped_thank_you'  => '当店をご利用いただきありがとうございます！',

    'payment_confirmed_subject'  => '注文番号 #:order_id の支払いが確認されました',
    'payment_confirmed_greeting' => 'こんにちは :name さん,',
    'payment_confirmed_line1'    => '注文番号 #:order_id の支払いが確認されました。',
    'payment_confirmed_line2'    => '合計: $:amount USD',
    'payment_confirmed_line3'    => '当店をご利用いただきありがとうございます！',
    'payment_confirmed_message'  => '注文番号 #:order_id の支払いが確認されました',
];
