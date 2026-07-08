<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

<?= form_hidden('username', session()->get('username')) ?>
<?= form_input([
    'type'  => 'hidden',
    'name'  => 'total_harga',
    'id'    => 'total_harga',
    'value' => ''
]) ?>
<div class="col-12">
    <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'nama',
        'id'       => 'nama',
        'class'    => 'form-control',
        'value'    => session()->get('username'),
        'readonly' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'  => 'alamat',
        'id'    => 'alamat',
        'class' => 'form-control']) ?>
</div> 
<div class="col-12"> 
    <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
    <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
    <strong>select kelurahan</strong>
</div>
<div class="col-12"> 
    <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?> 
    <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
    <strong>select layanan</strong>
</div>
<div class="col-12">
    <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'ongkir',
        'id'       => 'ongkir',
        'class'    => 'form-control',
        'readonly' => true]) ?>
</div>
<div class="col-12 mt-3">
    <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
    <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="Masukkan kode voucher">
    <small class="text-muted">Tersedia: PROMO2025 (10%), PROMO2026 (15%), AKHIRTAHUN (25%)</small>
</div>
<div class="col-12">
    <?= form_submit(
        'submit',
        'Buat Pesanan',
        ['class' => 'btn btn-primary']) ?>
</div>

<?= form_close() ?> 
    </div>
    <div class="col-lg-6">
        <table class="table">
  <thead>
      <tr>
          <th scope="col">Nama</th>
          <th scope="col">Harga</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Sub Total</th>
      </tr>
  </thead>
  <tbody>
      <tr>
        <?php if (!empty($items)) : ?>
          <?php foreach ($items as $item) : ?>
              <tr>
                  <td><?= $item['name'] ?></td>
                  <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                  <td><?= $item['qty'] ?></td>
                  <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
              </tr>
          <?php endforeach; ?>
      <?php endif; ?>
<tr>
          <td colspan="2"></td>
          <td>Subtotal</td>
          <td><?= number_to_currency($total, 'IDR') ?></td>
      </tr>
      
      <tr>
          <td colspan="2"></td>
          <td class="text-danger">Diskon<br>Voucher</td>
          <td class="text-danger"><span id="text_diskon_voucher">IDR 0</span></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td>Biaya Jasa</td>
          <td><span id="text_biaya_jasa">IDR 0</span></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td class="text-success">Free Mouse</td>
          <td class="text-success"><span id="text_free_mouse">IDR 0</span></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td class="text-info">Subtotal<br>(+Jasa-<br>Voucher-Free<br>Mouse)</td>
          <td><b><span id="text_subtotal_akhir">IDR 0</span></b></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td><b>Grand Total<br>(incl. Ongkir)</b></td>
          <td><b><span id="total"><?= number_to_currency($total, 'IDR') ?></span></b></td>
      </tr>
      
  </tbody>
</table>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let subtotal = <?= $total ?>;
    
    hitungTotal();

    function hitungTotal() {
        let biaya_jasa = (subtotal <= 10000000) ? (subtotal * 0.01) : (subtotal * 0.02);
        let free_mouse = (subtotal >= 15000000) ? 150000 : 0;
        
        let kode = $('#voucher_code').val().toUpperCase();
        let persen_diskon = 0;
        let label_persen = ''; 
        
        if (kode === 'PROMO2025') {
            persen_diskon = 0.10;
            label_persen = '<br>(10%)';
        } else if (kode === 'PROMO2026') {
            persen_diskon = 0.15;
            label_persen = '<br>(15%)';
        } else if (kode === 'AKHIRTAHUN') {
            persen_diskon = 0.25;
            label_persen = '<br>(25%)';
        }
        
        let diskon_voucher = subtotal * persen_diskon;
        let subtotal_akhir = subtotal - diskon_voucher - free_mouse + biaya_jasa;
        let grand_total = subtotal_akhir + ongkir;

        $("#ongkir").val(ongkir);
        $("#total").text(`IDR ${grand_total.toLocaleString('en-US')}`);
        $("#total_harga").val(grand_total);
        
        $("#text_biaya_jasa").text(`IDR ${biaya_jasa.toLocaleString('en-US')}`);
        
        
        if (diskon_voucher > 0) {
            $("#text_diskon_voucher").html(`-IDR ${diskon_voucher.toLocaleString('en-US')} ${label_persen}`);
        } else {
            $("#text_diskon_voucher").html(`IDR 0`);
        }

        if (free_mouse > 0) {
            $("#text_free_mouse").text(`-IDR ${free_mouse.toLocaleString('en-US')}`);
        } else {
            $("#text_free_mouse").text(`IDR 0`);
        }

        
        $("#text_subtotal_akhir").text(`IDR ${subtotal_akhir.toLocaleString('en-US')}`);
    }

    
    $('#voucher_code').on('keyup', function() {
        hitungTotal(); 
    });

    $('#kelurahan').select2({
        placeholder: 'Cari daerah tujuan',
        minimumInputLength: 3, 
        ajax: {
            url: '<?= site_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
    });

    $("#kelurahan").on('change', function () {
        let id_kelurahan = $(this).val();

        $("#layanan").empty();
        ongkir = 0;
        hitungTotal(); 

        $.ajax({
            url: "<?= site_url('ajax/costs') ?>", 
            dataType: "json",
            data: {
                destination: id_kelurahan
            },
            success: function (data) { 
                data.forEach(function (item) {
                    $("#layanan").append(
                        $('<option>', {
                            value: item.cost,
                            text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                        })
                    );
                });
            }
        });
    });

    $("#layanan").on('change', function() {
        ongkir = parseInt($(this).val());
        hitungTotal();
    }); 
});
</script>
<?= $this->endSection() ?>