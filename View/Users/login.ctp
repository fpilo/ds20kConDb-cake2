<script type="text/javascript"> <!--
function UnCryptMailto( s )
{
    var n = 0;
    var r = "";
    for( var i = 0; i < s.length; i++)
    {
        n = s.charCodeAt( i );
        if( n >= 8364 )
        {
            n = 128;
        }
        r += String.fromCharCode( n - 1 );
    }
    return r;
}

function linkTo_UnCryptMailto( s )
{
    location.href=UnCryptMailto( s );
}
// --> </script>

<noscript>
<h2>Attention!</h2>
<h5>Your Browser doesn't support Javascript or it is deactivated.
<br>
Please install a Browser with Javascript support or activate Javascript.</h5>
<br>
<hr>
<h3>How to activate Javascript</h3>
<ul>
<li><a href="http://activatejavascript.org/en/instructions/ie">Internet Explorer</a></li>
<li><a href="http://activatejavascript.org/en/instructions/firefox">Firefox</a></li>
<li><a href="http://activatejavascript.org/en/instructions/chrome">Chrome</a></li>
<li><a href="http://activatejavascript.org/en/instructions/safari">Safari</a></li>
<li><a href="http://activatejavascript.org/en/instructions/opera">Opera</a></li>
<li><a href="http://activatejavascript.org/en/instructions/iphone">iPhone</a></li>
</ul>
<br>
<hr>
</noscript>
<center>
	<span style="font-family:Verdana,Wide Latin; font-size:1.5em; color: #fff">
		This is the database used by the semiconductor/electronics-group from the Institute of High Energy Physics,<br>
		to store logistical and measurement data about sensors, moduls and other components.
	</span>
	<br>
</center>
<div class='login'>
	<?php	
	echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));
	echo $this->Form->input('User.username', array('placeholder'=> 'Username', 'label' => false));
	echo $this->Form->input('User.password', array('placeholder'=> 'Password', 'label' => false));
	echo $this->Form->end('Login');
	?>
</div>
<hr>
<span style="font-family:Verdana,Wide Latin; font-size:1.0em; color: #aaa">
For account requests please contact <a href="javascript:linkTo_UnCryptMailto('nbjmup;Uipnbt/CfshbvfsApfbx/bd/bu');" style="font-family:Verdana,Wide Latin; font-size:1.0em; color: #aaa">Thomas Bergauer, Thomas [dot] Bergauer [at] oeaw [dot] ac [dot] at</a>
</span> 
<!--<h3>Server closed for maintenance work.</h3>-->