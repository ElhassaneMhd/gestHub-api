<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attestation de Stage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" >
</head>
<body>
  <header class="container py-3 d-flex justify-content-between align-items-center bg-light">
    <div class="d-flex justify-content-center">
       <img src="your_company_logo.png" alt="Company Logo" class="img-fluid" style="max-width: 150px;"> 
    </div>
    <div class="col-md-6 ">
      <h1>Direction du système d'information</h1>
      <p>Av. Ibn Rochd, Rabat</p>
      <p>0537775554 - stagiaire@men.gov.ma</p>
    </div>
  </header>
  <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="card">
          <div class="">
            <h2 class="text-capitalize text-center">Attestation de Stage</h2>
          </div>
          <div class="card-body">
            <p>Je soussigné(e),Monsieur Bahae-Eddin Halim, agissant en qualité de <?php echo e("..........."); ?> de l'entreprise DSI MEN dont le siège social se situe au Av. Ibn Rochd, Rabat , 
              certifie par la présente avoir accueilli au sein de l'entreprise Monsieur <?php echo e($firstName??'.......'); ?> <?php echo e($lastName??'..............'); ?> , domicilié(e) a Rabat pour un stage destiné
               à lui faire découvrir le métier de Developpement.</p>
            <p>Je précise, par la présente, que ce stage s'est déroulé du <?php echo e($startDate??'.......'); ?> au <?php echo e($endDate??'..........'); ?>. 
              Tout au long de cette période, Monsieur <?php echo e($firstName??'............'); ?> <?php echo e($lastName??'..............'); ?>  a su répondre à ses obligations de stagiaire,
               notamment dans l'exercice des missions qui lui ont été confiées. Il s'est par ailleurs montré(e) très attentive aux conseils et à la formation qui lui 
               ont été prodigués en vue d'exercer le métier de Developpement.</p>
            <p>Cette attestation est délivrée à la applicatione du stagiaire pour servir et valoir ce que de droit.</p>
              <div class=' mt-5 w-full d-flex justify-content-start gap-3'>
                 <p class="text-end">Fait le <?php echo e(date('d-m-y')); ?></p>
                 <p class="text-end">A Rabat</p>
              </div>
                  <div class="col-md-6">
                    <p class="text-end">Monsieur Bahae-Eddin Halim</p>
                  </div>
            <div class="text-end">
              <p>Cachet de l'entreprise</p>
            </div>
          </div>
        </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\Pro\Desktop\gestSTG\BackEnd\resources\views/attestations/attestation1.blade.php ENDPATH**/ ?>