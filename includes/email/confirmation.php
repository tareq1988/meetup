
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
    <tbody class="kmTextBlockOuter">
        <tr>
            <td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; ">
                <table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
                    <tbody>
                        <tr>
                            <td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">
                                <h1 style="color: #222; display: block; font-family: Helvetica, Arial; font-size: 26px; font-style: normal; font-weight: bold; line-height: 110%; letter-spacing: normal; margin: 0; margin-bottom: 9px; text-align: left"><?php echo $mail_title; ?></h1>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
    <tbody class="kmTextBlockOuter">
        <tr>
            <td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; ">
                <table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
                    <tbody>
                        <tr>
                            <td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica, Arial; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">
                                <?php echo wpautop( $mail_content ); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="kmButtonBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
    <tbody class="kmButtonBlockOuter">
        <tr>
            <td valign="top" align="center" class="kmButtonBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 9px 18px; ">
                <table border="0" cellpadding="0" cellspacing="0" width="" class="kmButtonContentContainer" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; background-color: #999; background-color:#29C14F;border-radius:5px;">
                    <tbody>
                        <tr>
                            <td align="center" valign="middle" class="kmButtonContent" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: white; font-family: Helvetica, Arial; font-size: 16px; padding: 15px; padding-top:10px;padding-bottom:10px;padding-left:15px;padding-right:15px;color:#ffffff;font-weight:bold;font-size:16px;font-family:Helvetica, Arial;">
                                <a class="kmButton " title="" href="<?php echo esc_url( $conf_link ); ?>" target="_self" style="word-wrap: break-word; font-weight: normal; letter-spacing: -0.5px; line-height: 100%; text-align: center; text-decoration: underline; color: #0000cd; font-family: Helvetica, Arial; font-size: 16px; text-decoration:initial;color:#ffffff;font-weight:bold;font-size:16px;font-family:Helvetica, Arial;padding-top:0;padding-bottom:0;">Confirm my Seat</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="kmButtonBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
    <tbody class="kmButtonBlockOuter">
        <tr>
            <td valign="top" align="center" class="kmButtonBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 9px 18px; ">
                <table border="0" cellpadding="0" cellspacing="0" width="" class="kmButtonContentContainer" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; background-color: #999; background-color:#C1292B;border-radius:5px;">
                    <tbody>
                        <tr>
                            <td align="center" valign="middle" class="kmButtonContent" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: white; font-family: Helvetica, Arial; font-size: 16px; padding: 15px; padding-top:10px;padding-bottom:10px;padding-left:15px;padding-right:15px;color:#ffffff;font-weight:bold;font-size:16px;font-family:Helvetica, Arial;">
                                <a class="kmButton " title="" href="<?php echo esc_url( $cancel_link ); ?>" target="_self" style="word-wrap: break-word; font-weight: normal; letter-spacing: -0.5px; line-height: 100%; text-align: center; text-decoration: underline; color: #0000cd; font-family: Helvetica, Arial; font-size: 16px; text-decoration:initial;color:#ffffff;font-weight:bold;font-size:16px;font-family:Helvetica, Arial;padding-top:0;padding-bottom:0;">Cancel</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
