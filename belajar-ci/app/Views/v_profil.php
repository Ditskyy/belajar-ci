<?= $this->extend('layout') ?>
<?= $this->section('pageTitle') ?>
  <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
          <li class="breadcrumb-item active">Profil</li>
        </ol>
      </nav>
    </div><?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$username  = session()->get('username') ?? 'Guest';
$role      = session()->get('role') ?? 'user';
$email     = session()->get('email') ?? 'GakAda@gmail.com';
$loginTime = session()->get('login_time') ?? date('Y-m-d H:i:s');
$status    = session()->get('status') ?? 'Tidak aktif';
?>
    <section class="section profile">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body pt-3">
              <h5 class="card-title" style="color: #012970; font-weight: 500;">Profile Information</h5>
              
              <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="color: rgba(1, 41, 112, 0.6); font-weight: 600;">Username</div>
                <div class="col-lg-9 col-md-8">
                  <?= esc($username) ?> <span class="badge bg-danger"><?= esc($role) ?></span>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="color: rgba(1, 41, 112, 0.6); font-weight: 600;">Email</div>
                <div class="col-lg-9 col-md-8 text-primary"><?= esc($email) ?></div>
              </div>

              <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="color: rgba(1, 41, 112, 0.6); font-weight: 600;">Login Time</div>
                <div class="col-lg-9 col-md-8"><?= esc($loginTime) ?></div>
              </div>

              <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="color: rgba(1, 41, 112, 0.6); font-weight: 600;">Status</div>
                <div class="col-lg-9 col-md-8">
                  <span class="badge bg-success"><?= esc($status) ?></span>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
<?= $this->endSection() ?>