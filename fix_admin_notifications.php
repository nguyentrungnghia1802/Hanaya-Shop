<?php
// Script để sửa tất cả admin notifications sử dụng tiếng Anh cố định

$adminNotifications = [
    'OrderShippedNotification.php',
    'OrderCompletedNotification.php',
    'OrderCancelledNotification.php'
];

foreach ($adminNotifications as $file) {
    $filePath = "app/Notifications/{$file}";
    
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        
        // Sửa constructor để dùng tiếng Anh cố định
        $content = preg_replace(
            '/public function __construct\([^)]*\$locale\s*=\s*null\)(\s*{[^}]*\$this->locale\s*=\s*[^;]+;)/s',
            'public function __construct($1' . "\n" . '        // Admin notifications always use English' . "\n" . '        $this->locale = \'en\';',
            $content
        );
        
        // Sửa toMail method
        $content = str_replace(
            'app()->setLocale($this->locale);',
            '// Admin notifications always use English' . "\n" . '        app()->setLocale(\'en\');',
            $content
        );
        
        // Sửa toArray method
        $content = preg_replace(
            '/public function toArray\(([^{]+)\{(\s*return)/s',
            'public function toArray($1{' . "\n" . '        // Admin notifications always use English' . "\n" . '        app()->setLocale(\'en\');' . "\n" . '$2',
            $content
        );
        
        file_put_contents($filePath, $content);
        echo "Fixed: {$file}\n";
    }
}

echo "All admin notifications fixed!\n";
?>
