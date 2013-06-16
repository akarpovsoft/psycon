<div id='loginBox'>
    <form action="<?php echo Yii::app()->params['http_addr']; ?>site/login" method="POST">
        <div class='headerWrapper3'></div>
        <span class='header1'>Client Login</span>
        <div class='headerWrapper4'></div>
                <div class='formField'>
                        <label>User name</label>
                        <input type="text" name='LoginForm[LoginName]'>
                </div>
                <div class='formField'>
                        <label>Password</label>
                        <div><input type="password" name='LoginForm[LoginPassword]'></div>
                </div>
        <div id='loginButton'><a href='<?php echo Yii::app()->params['ssl_addr']; ?>site/signup'>New Clients Register Here</a><input type="submit" value="Login"></div>
    </form>
    <br>
</div>
