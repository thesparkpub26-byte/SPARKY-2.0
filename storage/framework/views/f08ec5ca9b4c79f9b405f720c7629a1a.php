<?php
    $meta = $meta ?? [];
    $siteName = 'TheSPARK';
    $title = $meta['title'] ?? 'The Spark - Official Publication';
    $description = $meta['description'] ?? 'TheSPARK is the official student publication of Camarines Sur Polytechnic Colleges. Truth knows no limits.';
    $image = $meta['image'] ?? url('/assets/Spark_Logo.png');
    $pageUrl = $meta['url'] ?? url('/');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <meta name="description" content="<?php echo e($description); ?>">
    <link rel="canonical" href="<?php echo e($pageUrl); ?>">

    <!-- Link previews (Facebook, Messenger, X, ...) -->
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">
    <meta property="og:type" content="<?php echo e($meta['type'] ?? 'website'); ?>">
    <meta property="og:title" content="<?php echo e($title); ?>">
    <meta property="og:description" content="<?php echo e($description); ?>">
    <meta property="og:url" content="<?php echo e($pageUrl); ?>">
    <meta property="og:image" content="<?php echo e($image); ?>">
    <meta name="twitter:card" content="<?php echo e(isset($meta['image']) ? 'summary_large_image' : 'summary'); ?>">
    <meta name="twitter:title" content="<?php echo e($title); ?>">
    <meta name="twitter:description" content="<?php echo e($description); ?>">
    <meta name="twitter:image" content="<?php echo e($image); ?>">
    <?php if(!empty($meta['published'])): ?>
        <meta property="article:published_time" content="<?php echo e($meta['published']); ?>">
    <?php endif; ?>
    <?php if(!empty($meta['section'])): ?>
        <meta property="article:section" content="<?php echo e($meta['section']); ?>">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/White_Spark_Logo.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body>
    <div id="app"></div>
</body>

</html>
<?php /**PATH C:\Users\emher\Desktop\Sparky\resources\views/app.blade.php ENDPATH**/ ?>