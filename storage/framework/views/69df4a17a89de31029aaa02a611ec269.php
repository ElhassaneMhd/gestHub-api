<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attestation de Stage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" >
  <style>
    .contImage{
      padding: 0 0 0 60px;
    }
    .footer{

      font-size:15px;
      padding-top:300px;
    }
  </style>
</head>
<body class=' col justify-between'>
  <header class="container py-3 d-flex justify-content-center align-items-center bg-light contImage">
      <img src="data:image/svg+xml;base64,<?php echo base64_encode(file_get_contents(base_path('public/settings/companyLogo.png'))); ?>"
       alt="Company Logo" class="img-fluid " >
  </header>
  <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header mb-10">
              <h5 class="card-title text-center">Attestation de Stage</h5>
            </div>
            <div class="card-body pt-5">
              <p>Je soussigné(e), M. MOHAMED LOUDIFA, Chef division de centre informatique de la company du Système d'information, dont le siège social se situe a Rabat  
                  atteste que <?php echo e($gender??'M/Mme'); ?>. <?php echo e($firstName??'.........................'); ?> <?php echo e($lastName??'...........................'); ?> a effectué un stage au sein de notre service d'une durée du 
                  <?php echo e($startDate??'...........................'); ?> au <?php echo e($endDate??'...........................'); ?>

              </p>
              <p>
                Par sa rigueur et ses qualités professionnelles et humaines, <?php echo e($gender??'M/Mme'); ?>. <?php echo e($firstName??'...........................'); ?> <?php echo e($lastName??'...........................'); ?> a su
              trouver sa place au sein de l'équipe. Sa présence a été satisfaisante à tous points de vue.
              </p>
              <p>Cette attestation est délivrée à la applicatione du stagiaire pour servir et valoir ce que de droit.</p>
                <div class=' mt-5 w-full d-flex justify-content-start gap-3  pt-5'>
                  <p class="text-end">Fait a Rabat , le <?php echo e(date('d-m-y')); ?> </p>
                </div>           
                <div class="col-md-6">
                  <p class="text-end">Signature</p>
                </div>
            </div>
          </div>
      </div>
    </div>
    <footer  class=" text-center footer">
        <p class='m-0'>company du Système d'Information</p>
        <hr>
        <p class='m-0'>Ministère de l'Education Nationale, du Préscolaire & des Sports</p>
        <p class='m-0'>Avenue Ibn Rochd, Haut Agdal - Rabat ° Tél : 05 37 68 72 19 ° Fax : 05 37 77 18 74</p>
    </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH D:\Project\Stage_Project\gestHub-api\resources\views/attestations/attestation.blade.php ENDPATH**/ ?>