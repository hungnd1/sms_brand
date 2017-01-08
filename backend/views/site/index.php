<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;

$this->title = 'SMS-BranchName';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to SMS-BranchName!</h1>
        <p class="lead">You have login successfully to Application..</p>
    </div>

</div>

<?php if(isset($message)){  ?>
    <?php
    Modal::begin([
        'header' => '<h4>Thông báo</h4>',
        'id' => 'myModal',
        'size' => ''
    ]);

    echo $this->render('_popup', [
        'message' => $message
    ]);
    Modal::end();
    ?>
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var message = '<?= $message != '' ? $message : 0 ?>';
            if(message != 0){
                $('#myModal').modal('show');
            }
        });
        function closeP(){
            $('#myModal').modal('toggle');
        }
    </script>
<?php  } ?>
