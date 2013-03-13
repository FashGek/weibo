<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title>New Theme</title>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>header</h1>
	</div>
	<div class="container">
		<?php echo $content; ?>
	</div>
</body>
</html>