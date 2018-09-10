<!DOCTYPE html>
<html lang="en">
<head>
    <title>Title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/build.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<?php $this->showViews('main'); ?>
<script>
    window.controllerInitInfo = <?=json_encode($this->getInitInfo())?>;
    window.DEBUG=<?=json_encode($this->isDebug())?>;
    window.DEBUG=<?=json_encode($this->isDebug())?>;
</script>
<script src="/js/main.bundle.js" type="text/javascript"></script>
</body>
</html>