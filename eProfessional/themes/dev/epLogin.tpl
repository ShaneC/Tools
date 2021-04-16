		<h3>Login</h3>
        <div style="Color: #900; font-weight: bold; padding-bottom: 10px;"><?php echo( $output['err'] ); ?></div>
        
        <form action="/?f=login" method="post" enctype="multipart/form-data">
        	Username:<br />
            <input type="text" name="sysLoginUsername"  />
            <br /><br />
            Password:<br />
            <input type="password" name="sysLoginPassword"  />
       		<br /><br />
            <input type="submit" name="sysLoginSubmit" value="Login!" />
        </form>