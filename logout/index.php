<?php $id = 'student_id';
$id_value = $_COOKIE['student_id'];
$exp = time() - 3600;
setcookie($id, $id_value, $exp, "/");
?>
<script type="text/javascript">
    window.location.href = 'http://localhost/biohaters/login/';
</script>