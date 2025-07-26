<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TinyMCE in Laravel</title>
    <!-- Insert the blade containing the TinyMCE configuration and source script -->
    <x-head.tinymce-config/>
</head>
<body>
    <h1>TinyMCE in Laravel</h1>
    <form method="POST" action="#">
        <label for="myeditorinstance" style="display:block;margin-bottom:8px;font-weight:bold;">Nội dung</label>
        <textarea id="myeditorinstance" name="content"></textarea>
    </form>
</body>
</html>
