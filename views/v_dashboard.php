<?php if($p->status == 'LOLOS' && !$this->Survei_model->cek_sudah_isi($p->id)): ?>
    <a href="<?= site_url('survei/form/'.$p->id) ?>" class="btn btn-success btn-xs">
        <i class="fa fa-star"></i> Isi Survei
    </a>
<?php elseif($p->status == 'LOLOS'): ?>
    <span class="label label-info">Sudah Isi Survei</span>
<?php endif; ?>
