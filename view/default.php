
<h2>Hello world!</h2>
<p>
    If you're seeing this page, that means that RFX is properly installed. Hooray!
</p>

<p>
    You provided the following arguments:
    <ul>
        <? foreach (uri_args() as $k => $v): ?>
            <li><b><?=$k?></b> = <?=htmlspecialchars($v)?></li>    
        <? endforeach ?>
    </ul>
</p>

<p><? if (auth_ok()) { ?>
    You are authenticated
<? } else { ?>
    You are not authenticated
<? } ?></p>