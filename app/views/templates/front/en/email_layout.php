<?php
use KrisRo\PhpConfig\Config;
?>
<div style="box-sizing: border-box; background-color: #FAFAF9; margin: 0 !important; padding: 0.67em;"><!-- background -->

  <div style="box-sizing: border-box; width: 100%; max-width: 500px; display: block; margin: 15px auto 0; padding: 2em 1em 0; border: 1px solid #E5E5E5; background-color: #FFFFFF"><!-- wrapper -->

    <div style="text-align: center;"><!-- header -->
      <a href="/" style="text-decoration: none;">
        <img style="width:116px; padding-bottom: 10px; border-bottom: 3px solid #1d3b53;" src="cid:awork-logo-cid">
      </a>
    </div><!-- / header -->

    _TEMPLATE_BODY_PLACEHOLDER_

    <div style="text-align: center; margin: 3em 0 0; padding: 1.5em; border-top: 1px solid #E5E5E5;"><!-- footer -->
      <p style="
         margin: 0;
         font-family: Roboto, Arial, sans-serif;
         font-size: 13px;
         color: #94a3b8;
         ">

        <a href="mailto:<?php echo Config::get('mail/contact') ?>" style="color: #64748b; text-decoration: underline"><?php echo Config::get('mail/contact') ?></a> |
        <a href="tel:" style="color: #64748b; text-decoration: underline"><?php echo Config::get('phone') ?></a> |
        <a href="/" style="color: #64748b; text-decoration: underline"><?php echo Config::get('url/protocol') . '://' . Config::get('url/domain') ?></a>
      </p>
    </div><!-- / footer -->

  </div><!-- / wrapper -->

</div><!-- / background -->
