                <div style="text-align: center;margin:30px auto;">
                    <h2><?php echo $i18n['ERROR'] ?></h2>
<?php
    if ($error == 'WEBSITE_DIRECTORY_CHMOD') :
?>
                    <p><?php echo $i18n['WEBSITE_DIRECTORY_CHMOD'] ?></p>
                    <a href="<?php echo $retryUrl ?>" class="button" ><?php echo $i18n['RETRY'] ?></a>
<?php
    elseif (in_array($error, array('CONF_INC_CHMOD_0666', 'CONF_INC_CHMOD_0644'))) :
?>
                    <p><?php echo sprintf($i18n['CONF_INC_CHMOD_ERROR'], substr($error, -4)) ?></p>
                    <a href="<?php echo $retryUrl ?>" class="button" ><?php echo $i18n['RETRY'] ?></a>
<?php
    elseif ($error == 'WRITE_CONF_INC_ERROR') :
?>
                    <p><?php echo $i18n['WRITE_CONF_INC_ERROR'] ?></p>
                    <a href="index.php?action=printConfig" class="button" ><?php echo $i18n['DOWNLOAD'] ?></a>&nbsp;
                    <a href="<?php echo $retryUrl ?>" class="button"><?php echo $i18n['RETRY'] ?></a>
<?php
    elseif ($error == 'COPY_CONF_INC_ERROR') :
?>
                    <p><?php echo $i18n['COPY_CONF_INC_ERROR'] ?></p>
                    <a href="index.php?action=printConfig" class="button" ><?php echo $i18n['DOWNLOAD'] ?></a>&nbsp;
                    <a href="<?php echo $nextUrl ?>" class="button"><?php echo $i18n['NEXT'] ?></a>
<?php
    endif
?>
                </div>