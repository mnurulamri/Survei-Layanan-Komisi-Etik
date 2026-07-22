<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Survei Kepuasan Layanan Clearance Etik</h3>
    <p>Kode Pengajuan: <b><?= $pengajuan->kode_pengajuan ?></b> - <?= $pengajuan->judul ?></p>
  </div>
  <form id="formSurvei">
  <div class="box-body">
    <input type="hidden" name="id_pengajuan" value="<?= $pengajuan->id ?>">
    <?= $this->security->get_csrf_token_name()?>: <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash()?>">
    
    <?php $no=1; foreach($pertanyaan as $p): ?>
    <div class="form-group">
      <label><?= $no++.'. '.$p->pertanyaan ?></label>
      <?php if($p->tipe == 'skala'): ?>
        <div>
          <?php for($i=1; $i<=5; $i++): ?>
          <label class="radio-inline">
            <input type="radio" name="jawaban[<?= $p->id ?>]" value="<?= $i ?>" required> <?= $i ?>
          </label>
          <?php endfor; ?>
          <small class="text-muted">1=Sangat Tidak Puas, 5=Sangat Puas</small>
        </div>
      <?php elseif($p->tipe == 'text'): ?>
        <textarea name="jawaban[<?= $p->id ?>]" class="form-control" rows="3"></textarea>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="box-footer">
    <button type="submit" id="btnSimpan" class="btn btn-primary">Kirim Survei</button>
  </div>
  </form>
</div>

<script>
$('#formSurvei').submit(function(e){
    e.preventDefault();
    $('#btnSimpan').prop('disabled', true).text('Menyimpan...');

    $.ajax({
        url: '<?= site_url("survei/simpan") ?>',
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        success: function(res){
            if(res.status == 'success'){
                Swal.fire('Berhasil', 'Terima kasih atas partisipasi Anda', 'success').then(()=>{
                    window.location = '<?= site_url("dashboard") ?>';
                });
            } else {
                alert(res.msg);
                $('#btnSimpan').prop('disabled', false).text('Kirim Survei');
            }
        }
    });
});
</script>
