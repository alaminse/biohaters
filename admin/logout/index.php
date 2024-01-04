<?php $id = 'admin_id';
$id_value = $_COOKIE['admin_id'];
$exp = time() - 3600;
setcookie($id, $id_value, $exp, "/");
?>
<script type="text/javascript">
    window.location.href = '../';
</script>