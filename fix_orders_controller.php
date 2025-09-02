<?php
// Script để sửa tất cả admin notifications trong OrdersController

$filePath = 'app/Http/Controllers/Admin/OrdersController.php';
$content = file_get_contents($filePath);

// Sửa tất cả admin notifications để không cần locale parameter
$patterns = [
    '/\$admin->notify\(new (OrderConfirmedNotification|OrderShippedNotification|OrderCompletedNotification|OrderCancelledNotification|OrderPaidNotification)\(\$order, \$currentLocale\)\);/' => '$admin->notify(new $1($order)); // Admin uses English by default',
];

foreach ($patterns as $pattern => $replacement) {
    $content = preg_replace($pattern, $replacement, $content);
}

// Cập nhật comments
$content = str_replace(
    'Get current locale from session',
    'Get current locale from session for customer notifications',
    $content
);

$content = str_replace(
    'Gửi thông báo cho admin',
    'Gửi thông báo cho admin (admin uses English by default)',
    $content
);

file_put_contents($filePath, $content);
echo "Fixed OrdersController admin notifications!\n";
?>
