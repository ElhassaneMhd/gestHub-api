<!DOCTYPE html>
<html>

<head>
    <title><?php echo e($data['subject']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <style>
        @media screen and (max-width: 600px) {
            .content {
                width: 100% !important;
                display: block !important;
                padding: 10px !important;
            }

            .header,
            .body,
            .footer {
                padding: 20px !important;
            }
        }
    </style>
</head>

<body style="font-family: 'Poppins', Arial, sans-serif">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table class="content" width="600" border="0" cellspacing="0" cellpadding="0"
                    style="border-collapse: collapse; border: 1px solid #cccccc;">
                    <!-- Header -->
                    <tr>
                        <td class="header"
                            style="background-color: #fb923c; padding: 40px; text-align: center; color: white; font-size: 24px;">
                            <?php echo e($data['subject']); ?>

                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="body" style="padding: 40px; text-align: left; font-size: 16px; line-height: 1.6;">
                            <?php echo $data['message']; ?>

                        </td>
                    </tr>

                    <!-- Call to action Button -->
                    <tr>
                        <td style="padding: 0px 40px 0px 40px; text-align: center;">
                            <!-- CTA Button -->
                            <table cellspacing="0" cellpadding="0" style="margin: auto;">
                                <tr>
                                    <td align="center"
                                        style="background-color: green; padding: 10px 20px; border-radius: 5px;">
                                        <?php if(isset($data['pdfPath']) && $data['pdfPath']): ?>
                                            <a href="https://gesthub.netlify.app/assets/<?php echo e($data['pdfPath']); ?>"
                                                style="color: #ffffff; text-decoration: none; font-weight: bold;">Show
                                                Document</a>
                                        <?php else: ?>
                                            <a href="https://gesthub.netlify.app/login" target="_blank"
                                                style="color: #ffffff; text-decoration: none; font-weight: bold;">visiter
                                                le site</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="body" style="padding: 40px; text-align: left; font-size: 16px; line-height: 1.6;">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam corporis sint eum nemo animi
                            velit exercitationem impedit.
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td class="footer"
                            style="background-color: #6f42c1; padding: 40px; text-align: center; color: white; font-size: 14px;">
                            Copyright &copy; 2024 | GestHub
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>w
</body>

</html>
<?php /**PATH D:\Project\Stage Project\API\resources\views/mails/welcome.blade.php ENDPATH**/ ?>